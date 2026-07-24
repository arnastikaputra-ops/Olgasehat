<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\Pendaftaran;
use App\Models\Lapangan;
use App\Models\LapanganSlot;
use App\Models\User;
use App\Mail\AnalyticsReportMail;

class AnalyticsController extends Controller
{
    /**
     * Tampilkan halaman analitik utama dengan data visualisasi
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil semua venue milik user yang login
        $venues = Pendaftaran::where('user_id', $user->id)->get();
        $venueIds = $venues->pluck('id');
        
        // Ambil lapangan yang berhubungan dengan venue-venue tersebut
        $lapangans = Lapangan::whereIn('pendaftaran_id', $venueIds)->get();
        
        // Buat mapping venue ke lapangan untuk memudahkan dropdown dinamis di frontend
        $venueLapangans = [];
        foreach ($venues as $v) {
            $venueLapangans[$v->id] = $lapangans->where('pendaftaran_id', $v->id)->values()->toArray();
        }

        // Set default filter tanggal (awal bulan ini sampai hari ini)
        $mulaiDari = $request->input('mulai_dari', Carbon::now()->startOfMonth()->toDateString());
        $sampaiDengan = $request->input('sampai_dengan', Carbon::now()->toDateString());
        
        $venueFilter = $request->input('venue_id', 'all');
        $lapanganFilter = $request->input('lapangan_id', 'all');

        // Query untuk mencari slot yang dipesan (booked)
        $slotsQuery = LapanganSlot::whereBetween('tanggal', [$mulaiDari, $sampaiDengan]);

        // Filter berdasarkan venue / lapangan
        if ($lapanganFilter !== 'all') {
            $slotsQuery->where('lapangan_id', $lapanganFilter);
        } elseif ($venueFilter !== 'all') {
            $slotsQuery->whereIn('lapangan_id', function ($query) use ($venueFilter) {
                $query->select('id')
                      ->from('lapangans')
                      ->where('pendaftaran_id', $venueFilter);
            });
        } else {
            // Default hanya slot milik venue pemilik ini
            $slotsQuery->whereIn('lapangan_id', function ($query) use ($venueIds) {
                $query->select('id')
                      ->from('lapangans')
                      ->whereIn('pendaftaran_id', $venueIds);
            });
        }

        // Ambil semua slot berdasarkan filter
        $slots = $slotsQuery->get();

        // Cari transaksi sukses (status = booked)
        $bookedSlots = $slots->where('status', 'booked');
        $totalRevenue = $bookedSlots->sum('harga');
        $totalTransactions = $bookedSlots->count();

        // Ambil data pertumbuhan pengguna baru (role = user) global/fasilitas
        // Karena tidak ada relasi langsung transaksi ke user, kita tampilkan user terdaftar baru di platform
        $usersQuery = User::where('role', 'user')
            ->whereBetween('created_at', [
                Carbon::parse($mulaiDari)->startOfDay(),
                Carbon::parse($sampaiDengan)->endOfDay()
            ]);
        
        $totalUsers = $usersQuery->count();
        $newUsers = $usersQuery->get();

        // Proses data harian untuk grafik Chart.js
        $chartData = $this->prepareChartData($mulaiDari, $sampaiDengan, $bookedSlots, $newUsers);

        return view('pemiliklapangan.Analytics.index', compact(
            'venues',
            'lapangans',
            'venueLapangans',
            'mulaiDari',
            'sampaiDengan',
            'venueFilter',
            'lapanganFilter',
            'totalRevenue',
            'totalTransactions',
            'totalUsers',
            'chartData'
        ));
    }

    /**
     * Mempersiapkan data format harian untuk diagram Chart.js
     */
    private function prepareChartData($startDate, $endDate, $bookedSlots, $newUsers)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $labels = [];
        $revenue = [];
        $transactions = [];
        $users = [];

        // Loop setiap harian dalam rentang tanggal
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateString = $date->toDateString();
            $labels[] = $date->translatedFormat('d M Y'); // Format tanggal lokal yang ramah

            // Filter data slot booked pada tanggal ini
            $daySlots = $bookedSlots->filter(function ($slot) use ($dateString) {
                $slotDate = $slot->tanggal instanceof Carbon ? $slot->tanggal->toDateString() : date('Y-m-d', strtotime($slot->tanggal));
                return $slotDate === $dateString;
            });

            $revenue[] = (int) $daySlots->sum('harga');
            $transactions[] = $daySlots->count();

            // Filter user terdaftar pada tanggal ini
            $dayUsersCount = $newUsers->filter(function ($u) use ($dateString) {
                return $u->created_at->toDateString() === $dateString;
            })->count();

            $users[] = $dayUsersCount;
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
            'venue_id' => 'required',
            'lapangan_id' => 'required',
            'mulai_dari' => 'required|date',
            'sampai_dengan' => 'required|date|after_or_equal:mulai_dari',
        ]);

        $user = Auth::user();
        $emailTujuan = $request->email;

        // Ambil nama venue/lapangan untuk filter teks laporan
        $venueName = 'Semua Venue';
        if ($request->venue_id !== 'all') {
            $v = Pendaftaran::where('user_id', $user->id)->find($request->venue_id);
            if ($v) $venueName = $v->namavenue;
        }

        $lapanganName = 'Semua Lapangan';
        if ($request->lapangan_id !== 'all') {
            $l = Lapangan::find($request->lapangan_id);
            if ($l) $lapanganName = $l->nama;
        }

        // Ambil data dengan filter yang sama
        $venues = Pendaftaran::where('user_id', $user->id)->get();
        $venueIds = $venues->pluck('id');

        $slotsQuery = LapanganSlot::whereBetween('tanggal', [$request->mulai_dari, $request->sampai_dengan]);

        if ($request->lapangan_id !== 'all') {
            $slotsQuery->where('lapangan_id', $request->lapangan_id);
        } elseif ($request->venue_id !== 'all') {
            $slotsQuery->whereIn('lapangan_id', function ($query) use ($request) {
                $query->select('id')->from('lapangans')->where('pendaftaran_id', $request->venue_id);
            });
        } else {
            $slotsQuery->whereIn('lapangan_id', function ($query) use ($venueIds) {
                $query->select('id')->from('lapangans')->whereIn('pendaftaran_id', $venueIds);
            });
        }

        $bookedSlots = $slotsQuery->where('status', 'booked')->get();
        $totalRevenue = $bookedSlots->sum('harga');
        $totalTransactions = $bookedSlots->count();
        
        $totalUsers = User::where('role', 'user')
            ->whereBetween('created_at', [
                Carbon::parse($request->mulai_dari)->startOfDay(),
                Carbon::parse($request->sampai_dengan)->endOfDay()
            ])->count();

        $dataLaporan = [
            'owner_name' => $user->name,
            'venue' => $venueName,
            'lapangan' => $lapanganName,
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
                'message' => 'Laporan analitik berhasil dikirim ke ' . $emailTujuan
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
        
        $venueFilter = $request->input('venue_id', 'all');
        $lapanganFilter = $request->input('lapangan_id', 'all');

        // Filter data sama seperti di index
        $venues = Pendaftaran::where('user_id', $user->id)->get();
        $venueIds = $venues->pluck('id');

        $slotsQuery = LapanganSlot::whereBetween('tanggal', [$mulaiDari, $sampaiDengan]);

        if ($lapanganFilter !== 'all') {
            $slotsQuery->where('lapangan_id', $lapanganFilter);
        } elseif ($venueFilter !== 'all') {
            $slotsQuery->whereIn('lapangan_id', function ($query) use ($venueFilter) {
                $query->select('id')->from('lapangans')->where('pendaftaran_id', $venueFilter);
            });
        } else {
            $slotsQuery->whereIn('lapangan_id', function ($query) use ($venueIds) {
                $query->select('id')->from('lapangans')->whereIn('pendaftaran_id', $venueIds);
            });
        }

        $bookedSlots = $slotsQuery->where('status', 'booked')->get();
        
        $newUsers = User::where('role', 'user')
            ->whereBetween('created_at', [
                Carbon::parse($mulaiDari)->startOfDay(),
                Carbon::parse($sampaiDengan)->endOfDay()
            ])->get();

        $chartData = $this->prepareChartData($mulaiDari, $sampaiDengan, $bookedSlots, $newUsers);

        // Membuat berkas CSV
        $filename = "laporan_analitik_" . $mulaiDari . "_to_" . $sampaiDengan . ".csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Tanggal', 'Pendapatan (IDR)', 'Jumlah Transaksi', 'User Baru');

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
