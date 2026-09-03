<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\DoctorSchedule;
use App\Models\HealthService;
use App\Models\HealthBooking;
use Illuminate\Http\Request;

class HealthFrontendController extends Controller
{
    public function index(Request $request)
    {
        $queryText = $request->input('q', '');
        $jenisLayanan = $request->input('jenis_layanan', '');
        $tanggal = $request->input('tanggal', '');
        $jam = $request->input('jam', '');

        $clinicsQuery = Clinic::with([
                'services' => function ($query) {
                    $query->where('aktif', true)->orderBy('created_at', 'asc');
                },
                'doctors.schedules',
            ])
            ->where('status', 'approved');

        // Filter search query (nama, kota, alamat, layanan)
        if (!empty($queryText)) {
            $clinicsQuery->where(function($q) use ($queryText) {
                $q->where('nama', 'like', "%{$queryText}%")
                  ->orWhere('kota', 'like', "%{$queryText}%")
                  ->orWhere('alamat', 'like', "%{$queryText}%")
                  ->orWhereHas('services', function($sq) use ($queryText) {
                      $sq->where('nama', 'like', "%{$queryText}%")
                        ->orWhere('deskripsi', 'like', "%{$queryText}%");
                  });
            });
        }

        // Filter jenis layanan
        if (!empty($jenisLayanan) && $jenisLayanan !== 'all') {
            $clinicsQuery->whereHas('services', function($sq) use ($jenisLayanan) {
                $sq->where('nama', 'like', "%{$jenisLayanan}%");
            });
        }

        // Filter berdasarkan tanggal (hari operasional / jadwal dokter)
        if (!empty($tanggal)) {
            $daysMap = [
                0 => 'minggu',
                1 => 'senin',
                2 => 'selasa',
                3 => 'rabu',
                4 => 'kamis',
                5 => 'jumat',
                6 => 'sabtu',
            ];
            $dayIndex = \Carbon\Carbon::parse($tanggal)->dayOfWeek;
            $dayOfWeek = $daysMap[$dayIndex] ?? 'senin';

            $clinicsQuery->where(function($cq) use ($dayOfWeek) {
                $cq->whereJsonContains('hari_operasional', $dayOfWeek)
                  ->orWhereHas('doctors.schedules', function($sq) use ($dayOfWeek) {
                      $sq->where('hari', 'like', "%{$dayOfWeek}%")->where('aktif', true);
                  });
            });
        }

        // Filter jam operasional / jadwal
        if (!empty($jam) && $jam !== 'all') {
            $clinicsQuery->where(function($cq) use ($jam) {
                $cq->where(function($q) use ($jam) {
                    $q->where('jam_buka', '<=', $jam)
                      ->where('jam_tutup', '>', $jam);
                })
                ->orWhereHas('doctors.schedules', function($sq) use ($jam) {
                    $sq->where('jam_mulai', '<=', $jam)
                      ->where('jam_selesai', '>', $jam)
                      ->where('aktif', true);
                });
            });
        }

        $featuredClinics = $clinicsQuery->orderBy('created_at', 'desc')->get();

        $serviceCategories = HealthService::where('aktif', true)
            ->distinct()
            ->pluck('nama')
            ->filter()
            ->sort()
            ->values();

        return view('FRONTEND.healthy', [
            'featuredClinics' => $featuredClinics,
            'serviceCategories' => $serviceCategories,
        ]);
    }

    public function serviceDetail($serviceId)
    {
        $service = HealthService::with(['clinic', 'doctor'])
            ->where('aktif', true)
            ->findOrFail($serviceId);

        $clinic = $service->clinic()
            ->with([
                'services' => function ($query) use ($service) {
                    $query->where('aktif', true)->orderBy('created_at', 'asc');
                },
                'doctors' => function ($query) {
                    $query->where('aktif', true);
                },
                'galleries',
            ])
            ->first();

        if (!$clinic || $clinic->status !== 'approved') {
            abort(404);
        }

        $schedules = DoctorSchedule::with('doctor')
            ->where('clinic_id', $clinic->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        $timeSlots = $schedules->groupBy('hari')->map(function ($items) {
            return $items->map(function ($schedule) {
                return [
                    'label' => substr($schedule->jam_mulai, 0, 5),
                    'doctor' => optional($schedule->doctor)->nama_lengkap
                        ?? optional($schedule->doctor)->nama
                        ?? 'Tim Dokter',
                ];
            });
        });

        $servingDoctors = $clinic->doctors->where('aktif', true)->take(6);
        $paymentSettings = \App\Models\PaymentSetting::where('is_active', true)->orderBy('category')->orderBy('bank_name')->get();

        $clinicReviews = \App\Models\Review::where(function($q) use ($clinic) {
            $q->where('tipe_target', 'klinik')->orWhere('tipe_target', 'clinic');
        })->where('target_id', $clinic->id)->latest()->get();

        $avgRating = $clinicReviews->count() > 0 ? round($clinicReviews->avg('rate'), 1) : 5.0;
        $totalReviews = $clinicReviews->count();

        return view('FRONTEND.service_detail', [
            'service' => $service,
            'clinic' => $clinic,
            'schedules' => $schedules,
            'timeSlots' => $timeSlots,
            'servingDoctors' => $servingDoctors,
            'paymentSettings' => $paymentSettings,
            'clinicReviews' => $clinicReviews,
            'avgRating' => $avgRating,
            'totalReviews' => $totalReviews,
        ]);
    }

    public function storeBooking(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk melakukan pemesanan.'
            ], 401);
        }

        try {
            $request->validate([
                'clinic_id' => 'required|exists:clinics,id',
                'doctor_id' => 'required|exists:doctors,id',
                'service_id' => 'required|exists:health_services,id',
                'tanggal' => 'required|date|after_or_equal:today',
                'jam' => 'required',
                'metode_pembayaran' => 'nullable|string',
                'bank_code' => 'nullable|string',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            ], [
                'bukti_pembayaran.required' => 'Wajib mengunggah foto bukti pembayaran untuk menyelesaikan janji layanan.',
                'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa berkas foto/gambar.',
            ]);

            $user = auth()->user();
            $service = HealthService::findOrFail($request->service_id);
            $clinic = Clinic::find($request->clinic_id);
            $bankCode = $request->input('bank_code', 'BCA');

            // Check if user has an active approved membership
            $isMember = \App\Models\ActivityParticipant::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereHas('activity', function($q) {
                    $q->where('jenis', 'membership')
                      ->orWhereHas('activityType', function($at) {
                          $at->whereIn('name', ['klub', 'membership']);
                      });
                })
                ->exists();

            $subtotal = (float) ($service->harga ?? 0);
            $defaultPlatformDiscount = (float) \App\Services\AppSetting::get('membership_discount_percent', 10);
            $clinicDiscountPercent = ($clinic && isset($clinic->is_membership_discount) && $clinic->is_membership_discount)
                ? (float) ($clinic->membership_discount_percent ?? $defaultPlatformDiscount)
                : 0;

            $diskonMember = ($isMember && $clinicDiscountPercent > 0) ? round($subtotal * ($clinicDiscountPercent / 100)) : 0;
            $totalHarga = max(0, $subtotal - $diskonMember);

            $komisiTipe = $clinic->komisi_tipe ?? 'none';
            $komisiNilai = (float) ($clinic->komisi_nilai ?? 0);
            $komisiPlatform = 0;

            if ($komisiTipe === 'percentage' && $komisiNilai > 0) {
                $komisiPlatform = ($totalHarga * $komisiNilai) / 100;
            } elseif ($komisiTipe === 'fixed' && $komisiNilai > 0) {
                $komisiPlatform = min($komisiNilai, $totalHarga);
            }

            $pendapatanMitra = max(0, $totalHarga - $komisiPlatform);

            $paymentSetting = \App\Models\PaymentSetting::where('bank_code', strtoupper($bankCode))->where('is_active', true)->first();
            $virtualAccount = $paymentSetting ? $paymentSetting->account_number : ('88008' . mt_rand(10000000, 99999999));

            // Handle upload bukti pembayaran
            $buktiPembayaranName = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $buktiPembayaranName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('bukti_pembayaran'), $buktiPembayaranName);
            }

            $booking = HealthBooking::create([
                'user_id' => $user->id,
                'clinic_id' => $request->clinic_id,
                'doctor_id' => $request->doctor_id,
                'service_id' => $request->service_id,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'nama_pasien' => $request->input('nama_pasien', $user->name),
                'nomor_telepon' => $request->input('nomor_telepon', $user->phone ?? '08123456789'),
                'total_harga' => $totalHarga,
                'komisi_tipe' => $komisiTipe,
                'komisi_nilai' => $komisiNilai,
                'komisi_platform' => $komisiPlatform,
                'pendapatan_mitra' => $pendapatanMitra,
                'metode_pembayaran' => $request->input('metode_pembayaran', 'virtualAccount'),
                'bank_code' => $bankCode,
                'virtual_account' => $virtualAccount,
                'status' => 'pending',
                'status_pembayaran' => 'pending_acc',
                'bukti_pembayaran' => $buktiPembayaranName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan jadwal pemeriksaan berhasil diproses & dikonfirmasi!',
                'booking' => $booking
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}


