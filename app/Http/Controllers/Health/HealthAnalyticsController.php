<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\HealthBooking;
use App\Models\User;
use App\Mail\AnalyticsReportMail;

class HealthAnalyticsController extends Controller
{
    /**
     * Tampilkan halaman analitik pengelola kesehatan
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil semua klinik milik pengelola kesehatan yang login
        $clinics = Clinic::where('user_id', $user->id)->get();
        $clinicIds = $clinics->pluck('id');
        
        // Ambil dokter yang bertugas di klinik-klinik tersebut
        $doctors = Doctor::whereIn('clinic_id', $clinicIds)->get();
        
        // Mapping klinik ke dokter untuk dropdown dinamis jika diperlukan
        $clinicDoctors = [];
        foreach ($clinics as $c) {
            $clinicDoctors[$c->id] = $doctors->where('clinic_id', $c->id)->values()->toArray();
        }

        // Set default filter tanggal (awal bulan ini sampai akhir bulan ini agar janji mendatang ikut terhitung)
        $mulaiDari = $request->input('mulai_dari', Carbon::now()->startOfMonth()->toDateString());
        $sampaiDengan = $request->input('sampai_dengan', Carbon::now()->endOfMonth()->toDateString());
        
        $clinicFilter = $request->input('clinic_id', 'all');
        $doctorFilter = $request->input('doctor_id', 'all');

        // Query booking kesehatan (mencakup tanggal pemeriksaan maupun tanggal transaksi dibuat)
        $bookingQuery = HealthBooking::where(function($q) use ($mulaiDari, $sampaiDengan) {
            $q->whereBetween('tanggal', [$mulaiDari, $sampaiDengan])
              ->orWhereBetween('created_at', [
                  Carbon::parse($mulaiDari)->startOfDay(),
                  Carbon::parse($sampaiDengan)->endOfDay()
              ]);
        });

        if ($doctorFilter !== 'all') {
            $bookingQuery->where('doctor_id', $doctorFilter);
        } elseif ($clinicFilter !== 'all') {
            $bookingQuery->where('clinic_id', $clinicFilter);
        } else {
            $bookingQuery->whereIn('clinic_id', $clinicIds);
        }

        $bookings = $bookingQuery->get();

        // Cari transaksi (Non-Cancelled) untuk revenue dan total booking
        $activeBookings = $bookings->where('status', '!=', 'cancelled');
        $totalRevenue = $activeBookings->sum('total_harga');
        $totalTransactions = $bookings->count();

        // Hitung total Pasien / Pengguna Unik yang melakukan booking di klinik ini
        $uniquePatients = $bookings->pluck('user_id')->filter()->unique();
        $totalUsers = $uniquePatients->count();
        if ($totalUsers === 0) {
            $totalUsers = User::where('role', 'user')
                ->whereBetween('created_at', [
                    Carbon::parse($mulaiDari)->startOfDay(),
                    Carbon::parse($sampaiDengan)->endOfDay()
                ])->count();
        }

        $newUsers = User::whereIn('id', $uniquePatients)->get();

        // Data harian untuk grafik Chart.js
        $chartData = $this->prepareChartData($mulaiDari, $sampaiDengan, $bookings, $newUsers);

        return view('pemilikkesehatan.Analytics.index', compact(
            'clinics',
            'doctors',
            'clinicDoctors',
            'mulaiDari',
            'sampaiDengan',
            'clinicFilter',
            'doctorFilter',
            'totalRevenue',
            'totalTransactions',
            'totalUsers',
            'chartData'
        ));
    }

    /**
     * Mempersiapkan data format harian untuk diagram Chart.js
     */
    private function prepareChartData($startDate, $endDate, $bookings, $newUsers)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $labels = [];
        $revenue = [];
        $transactions = [];
        $users = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateString = $date->toDateString();
            $labels[] = $date->translatedFormat('d M Y');

            $dayBookings = $bookings->filter(function ($b) use ($dateString) {
                $bDate = $b->tanggal instanceof Carbon ? $b->tanggal->toDateString() : date('Y-m-d', strtotime($b->tanggal));
                $cDate = $b->created_at instanceof Carbon ? $b->created_at->toDateString() : date('Y-m-d', strtotime($b->created_at));
                return $bDate === $dateString || $cDate === $dateString;
            });

            $activeDayBookings = $dayBookings->where('status', '!=', 'cancelled');
            $revenue[] = (int) $activeDayBookings->sum('total_harga');
            $transactions[] = $dayBookings->count();

            // Pasien harian = Pasien unik yang melakukan booking pada tanggal tersebut
            $dayUniquePatients = $dayBookings->pluck('user_id')->filter()->unique()->count();
            if ($dayUniquePatients === 0 && isset($newUsers)) {
                $dayUniquePatients = $newUsers->filter(function ($u) use ($dateString) {
                    return $u->created_at->toDateString() === $dateString;
                })->count();
            }

            $users[] = $dayUniquePatients;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'transactions' => $transactions,
            'users' => $users
        ];
    }

    /**
     * Kirim ringkasan laporan analitik via email
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'clinic_id' => 'required',
            'doctor_id' => 'required',
            'mulai_dari' => 'required|date',
            'sampai_dengan' => 'required|date|after_or_equal:mulai_dari',
        ]);

        $user = Auth::user();
        $emailTujuan = $request->email;

        $clinicName = 'Semua Klinik';
        if ($request->clinic_id !== 'all') {
            $c = Clinic::where('user_id', $user->id)->find($request->clinic_id);
            if ($c) $clinicName = $c->nama;
        }

        $doctorName = 'Semua Dokter';
        if ($request->doctor_id !== 'all') {
            $d = Doctor::find($request->doctor_id);
            if ($d) $doctorName = $d->nama_lengkap ?? $d->nama;
        }

        $clinics = Clinic::where('user_id', $user->id)->get();
        $clinicIds = $clinics->pluck('id');

        $bookingQuery = HealthBooking::whereBetween('tanggal', [$request->mulai_dari, $request->sampai_dengan]);

        if ($request->doctor_id !== 'all') {
            $bookingQuery->where('doctor_id', $request->doctor_id);
        } elseif ($request->clinic_id !== 'all') {
            $bookingQuery->where('clinic_id', $request->clinic_id);
        } else {
            $bookingQuery->whereIn('clinic_id', $clinicIds);
        }

        $bookings = $bookingQuery->get();
        $confirmedBookings = $bookings->whereIn('status', ['confirmed', 'completed']);
        $totalRevenue = $confirmedBookings->sum('total_harga');
        $totalTransactions = $bookings->count();
        
        $totalUsers = User::where('role', 'user')
            ->whereBetween('created_at', [
                Carbon::parse($request->mulai_dari)->startOfDay(),
                Carbon::parse($request->sampai_dengan)->endOfDay()
            ])->count();

        $dataLaporan = [
            'owner_name' => $user->name,
            'venue' => $clinicName,
            'lapangan' => $doctorName,
            'mulai_dari' => Carbon::parse($request->mulai_dari)->translatedFormat('d F Y'),
            'sampai_dengan' => Carbon::parse($request->sampai_dengan)->translatedFormat('d F Y'),
            'total_revenue' => $totalRevenue,
            'total_transactions' => $totalTransactions,
            'total_users' => $totalUsers
        ];

        try {
            Mail::to($emailTujuan)->send(new AnalyticsReportMail($dataLaporan));
            return response()->json([
                'success' => true,
                'message' => 'Laporan analitik kesehatan berhasil dikirim ke ' . $emailTujuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage() . '. Harap periksa konfigurasi mail server di berkas .env Anda.'
            ], 500);
        }
    }

    /**
     * Ekspor data analitik harian ke berkas CSV
     */
    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        
        $mulaiDari = $request->input('mulai_dari', Carbon::now()->startOfMonth()->toDateString());
        $sampaiDengan = $request->input('sampai_dengan', Carbon::now()->toDateString());
        
        $clinicFilter = $request->input('clinic_id', 'all');
        $doctorFilter = $request->input('doctor_id', 'all');

        $clinics = Clinic::where('user_id', $user->id)->get();
        $clinicIds = $clinics->pluck('id');

        $bookingQuery = HealthBooking::whereBetween('tanggal', [$mulaiDari, $sampaiDengan]);

        if ($doctorFilter !== 'all') {
            $bookingQuery->where('doctor_id', $doctorFilter);
        } elseif ($clinicFilter !== 'all') {
            $bookingQuery->where('clinic_id', $clinicFilter);
        } else {
            $bookingQuery->whereIn('clinic_id', $clinicIds);
        }

        $bookings = $bookingQuery->get();
        
        $newUsers = User::where('role', 'user')
            ->whereBetween('created_at', [
                Carbon::parse($mulaiDari)->startOfDay(),
                Carbon::parse($sampaiDengan)->endOfDay()
            ])->get();

        $chartData = $this->prepareChartData($mulaiDari, $sampaiDengan, $bookings, $newUsers);

        $filename = "laporan_analitik_kesehatan_" . $mulaiDari . "_to_" . $sampaiDengan . ".csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Tanggal', 'Pendapatan Klinik (IDR)', 'Jumlah Janji / Booking', 'User Baru');

        $callback = function() use($chartData, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            for ($i = 0; $i < count($chartData['labels']); $i++) {
                fputcsv($file, array(
                    $chartData['labels'][$i],
                    $chartData['revenue'][$i],
                    $chartData['transactions'][$i],
                    $chartData['users'][$i]
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
