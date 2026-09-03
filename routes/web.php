<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GaleriController; 
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ActivityTypeController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Frontend\HealthFrontendController;

// ======================================================
// PUBLIC ROUTES (No Authentication Required)
// ======================================================
Route::get('/', function() {
    $programs = \App\Models\Program::orderBy('created_at', 'desc')->get();
    $homeBanners = \App\Models\Galeri::where('kategori', 'home_banner')
                                      ->orderBy('urutan', 'asc')
                                      ->orderBy('created_at', 'desc')
                                      ->limit(4)
                                      ->get();
    $lapanganBanners = \App\Models\Galeri::where('kategori', 'lapangan_banner')
                                         ->orderBy('urutan', 'asc')
                                         ->orderBy('created_at', 'desc')
                                         ->limit(4)
                                         ->get();
    $kesehatanBanners = \App\Models\Galeri::where('kategori', 'kesehatan_banner')
                                           ->orderBy('urutan', 'asc')
                                           ->orderBy('created_at', 'desc')
                                           ->limit(4)
                                           ->get();
    
    // Jika user sudah login, tambahkan activities
    $activities = null;
    if (Auth::check() && Auth::user()->role === 'user') {
        $activities = \App\Models\Activity::where('status', 'approved')
                                          ->orderBy('created_at', 'desc')
                                          ->limit(3)
                                          ->get();
    }
    
    return view('FRONTEND.home', compact('programs', 'homeBanners', 'lapanganBanners', 'kesehatanBanners', 'activities'));
});

Route::get('/tentang', [AboutUsController::class, 'showPublic'])->name('tentang');
Route::get('/blog-news', [BeritaController::class, 'index'])->name('frontend.blog-news');
Route::get('/blog-news/{id}', [BeritaController::class, 'show'])->name('frontend.blog-news-detail');
Route::get('/membership-detail/{id?}', function($id = null) {
    if ($id) {
        $activity = \App\Models\Activity::where('status', 'approved')->find($id);
        if ($activity) {
            return redirect()->route('community.detail', $id);
        }
    }
    return redirect()->route('community', ['type' => 'klub']);
});
Route::get('/community', [App\Http\Controllers\ActivityController::class, 'index'])->name('community');
Route::get('/community-detail/{id}', [App\Http\Controllers\ActivityController::class, 'showDetail'])->name('community.detail');
Route::get('/confirm', fn() => view('FRONTEND.confirm'));
Route::get('/daftar-pemilik', fn() => view('FRONTEND.daftar_pemilik'));
Route::get('/daftar-pemilik-detail', fn() => view('FRONTEND.daftar_pemilik_detail'));
Route::get('/login-pemilik', fn() => view('FRONTEND.login_pemilik'));
Route::get('/payment', fn() => view('FRONTEND.payment'));
Route::get('/success', fn() => view('FRONTEND.success'));
Route::get('/venue', [App\Http\Controllers\VenueFrontendController::class, 'index'])->name('frontend.venue');
Route::get('/healthy', [HealthFrontendController::class, 'index'])->name('frontend.healthy');
Route::get('/venue-detail/{id}', [App\Http\Controllers\VenueFrontendController::class, 'show'])->name('frontend.venue.detail');
Route::get('/venue-detail/{id}/slots', [App\Http\Controllers\VenueFrontendController::class, 'getSlots'])->name('frontend.venue.slots');
Route::get('/venue/search', [App\Http\Controllers\VenueFrontendController::class, 'search'])->name('frontend.venue.search');
Route::get('/venue/categories', [App\Http\Controllers\VenueFrontendController::class, 'getCategories'])->name('frontend.venue.categories');
Route::get('/venue/filter', [App\Http\Controllers\VenueFrontendController::class, 'filter'])->name('frontend.venue.filter');
Route::get('/klinik', fn() => view('FRONTEND.klinik'));
Route::get('/service-detail/{service}', [HealthFrontendController::class, 'serviceDetail'])->name('frontend.service.detail');
Route::get('/venue-management', fn() => view('FRONTEND.venue_management'))->name('venue.management');
Route::get('/health-management', fn() => view('FRONTEND.health_management'))->name('health.management');
Route::get('/daftar-mitra-kesehatan', fn() => redirect('/health-management'))->name('daftar.mitra.kesehatan');

// ======================================================
// AUTHENTICATION ROUTES
// ======================================================
Route::controller(LoginController::class)->group(function () {
    // Login & Logout
    Route::get('/login', 'login')->name('login');
    Route::post('/loginproses', 'loginproses')->name('loginproses');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/userlogout', 'userLogout')->name('user.logout');

    // User Registration
    Route::get('/loginuser', fn() => view('user.loginuser'));
    Route::get('/registeruser', 'registerUserForm')->name('registeruser.form');
    Route::post('/registeruser', 'registerUser')->name('registeruser.submit');

    // Pemilik Lapangan Registration
    Route::get('/registerpemilik', 'registerPemilikForm')->name('registerpemilik.form');
    Route::post('/registerpemilik', 'registerPemilik')->name('registerpemilik.submit');
});

// ======================================================
// USER ROUTES (Authenticated + Role: User)
// ======================================================
Route::get('/daftaruser', [LoginController::class, 'registerUserForm'])->name('user.register.form');
Route::post('/daftaruser', [LoginController::class, 'registerUser'])->name('user.register.submit');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboarduser', [LoginController::class, 'editProfile'])->name('user.editprofile');
    Route::post('/editprofile', [LoginController::class, 'updateProfile'])->name('user.updateprofile');
});

Route::middleware('auth')->group(function () {
    Route::get('/buat-aktivitas', fn() => view('user.user_buat_aktivitas'))->name('activities.create');
    Route::post('/buat-aktivitas', [App\Http\Controllers\ActivityController::class, 'storeFromUser'])->name('activities.store.user');
    Route::post('/community-detail/{id}/join', [App\Http\Controllers\ActivityController::class, 'joinEvent'])->name('user.community.join');
    Route::get('/riwayatpayment', [LoginController::class, 'riwayatPayment'])->name('user.riwayatpayment');
    Route::get('/riwayatkontrol', [LoginController::class, 'riwayatKontrol'])->name('user.riwayatkontrol');
    Route::get('/riwayatmembership', [LoginController::class, 'riwayatMembership'])->name('user.riwayatmembership');
    Route::post('/book-slots', [App\Http\Controllers\VenueFrontendController::class, 'bookSlots'])->name('user.book-slots');
    Route::post('/health-booking/store', [App\Http\Controllers\Frontend\HealthFrontendController::class, 'storeBooking'])->name('health.booking.store');
    Route::post('/riwayatkontrol/{id}/reschedule', [LoginController::class, 'rescheduleBooking'])->name('user.riwayatkontrol.reschedule');
    Route::post('/user/review', [ReviewController::class, 'storeFromUser'])->name('user.review.store');
});
Route::get('/api/contact-us', function() {
    return response()->json(\App\Models\ContactUs::all());
})->name('api.contact-us');
Route::view('/registeremail', 'user.registeremail')->name('user.registeremail');
Route::view('/loginemail', 'user.loginemail')->name('user.loginemail');
Route::view('/resetpassword', 'user.resetpassword')->name('user.resetpassword');
Route::view('/edit-profile-user', 'user.editprofile_user')->name('user.profile.edit');

Route::get('/riwayat-komunitas', [App\Http\Controllers\ActivityController::class, 'riwayatKomunitas'])->name('user.riwayat-komunitas');
Route::get('/riwayat-komunitas/{id}/edit', [App\Http\Controllers\ActivityController::class, 'editUserActivity'])->name('user.aktivitas.edit');
Route::put('/riwayat-komunitas/{id}', [App\Http\Controllers\ActivityController::class, 'updateUserActivity'])->name('user.aktivitas.update');
Route::post('/riwayat-komunitas/participant/{id}/approve', [App\Http\Controllers\ActivityController::class, 'approveParticipant'])->name('user.participant.approve');
Route::post('/riwayat-komunitas/participant/{id}/reject', [App\Http\Controllers\ActivityController::class, 'rejectParticipant'])->name('user.participant.reject');
// ======================================================
// PEMILIK / MITRA ROUTES
// ======================================================
Route::get('/loginpengelolavenue', fn() => view('pemiliklapangan.loginpengelolavenue'));
Route::get('/regispengelola', fn() => view('pemiliklapangan.regispengelola'));
Route::get('/isidata', [App\Http\Controllers\MitraController::class, 'create'])->name('mitra.create');
Route::post('/isidata', [App\Http\Controllers\MitraController::class, 'store'])->name('mitra.store');

Route::middleware(['auth', 'role:pemiliklapangan'])->group(function () {
    Route::get('/pemiliklapangan/dashboard', function() {
        $user = Auth::user();
        $venues = \App\Models\Pendaftaran::where('user_id', $user->id)
            ->with(['lapangans'])
            ->get();
        
        $activities = \App\Models\Activity::where('pemilik_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $memberships = $activities->where('jenis', 'membership');
        $communities = $activities->where('jenis', '!=', 'membership');

        $totalVenues = $venues->count();
        $totalLapangan = $venues->sum(fn($v) => $v->lapangans->count());
        $venuesWithHours = $venues->filter(fn($v) => !empty($v->jam_buka) && !empty($v->jam_tutup))->count();
        $venuesWithMembership = $venues->filter(fn($v) => $v->is_membership_discount || $memberships->where('pendaftaran_id', $v->id)->count() > 0)->count();

        return view('pemiliklapangan.Dashboard.dashboard', compact(
            'venues', 'activities', 'memberships', 'communities', 
            'totalVenues', 'totalLapangan', 'venuesWithHours', 'venuesWithMembership'
        ));
    })->name('pemilik.dashboard');
    Route::get('/pemiliklapangan/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('pemilik.analytics');
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('pemilik.analytics.short');
    Route::post('/pemiliklapangan/analytics/send-email', [App\Http\Controllers\AnalyticsController::class, 'sendEmail'])->name('pemilik.analytics.send_email');
    Route::get('/pemiliklapangan/analytics/export-csv', [App\Http\Controllers\AnalyticsController::class, 'exportCsv'])->name('pemilik.analytics.export_csv');
    Route::get('/pemiliklapangan/komunitas', fn() => view('pemiliklapangan.pemilik_buat_komunitas'))->name('pemilik.komunitas');
    Route::post('/pemiliklapangan/komunitas', [App\Http\Controllers\ActivityController::class, 'storeFromPemilik'])->name('activities.store.pemilik');
    Route::get('/pemiliklapangan/membership', function() {
        $user = Auth::user();
        $venues = \App\Models\Pendaftaran::where('user_id', $user->id)->get();
        $myMemberships = \App\Models\Activity::where('pemilik_id', $user->id)
            ->where('jenis', 'membership')
            ->with('pendaftaran')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pemiliklapangan.pemilik_buat_membership', compact('venues', 'myMemberships'));
    })->name('pemilik.membership');
    Route::delete('/pemiliklapangan/membership/{id}', [App\Http\Controllers\ActivityController::class, 'destroy'])->name('pemilik.membership.delete');
    Route::post('/pemiliklapangan/membership/participant/{id}/approve', [App\Http\Controllers\ActivityController::class, 'approveParticipant'])->name('pemilik.membership.participant.approve');
    Route::post('/pemiliklapangan/membership/participant/{id}/reject', [App\Http\Controllers\ActivityController::class, 'rejectParticipant'])->name('pemilik.membership.participant.reject');
    Route::get('/pemiliklapangan/event', fn() => view('pemiliklapangan.pemilik_buat_event'))->name('pemilik.event');
    Route::post('/pemiliklapangan/event', [App\Http\Controllers\ActivityController::class, 'storeFromPemilik'])->name('activities.store.pemilik.event');
    // Route untuk proses pendaftaran venue
    Route::get('/informasi', [PendaftaranController::class, 'informasi'])->name('informasi');
    Route::post('/insertinform', [PendaftaranController::class, 'insertinform'])->name('insertinform');

    // Route detail - GET untuk menampilkan form, POST untuk menyimpan
    Route::get('/detail/{id?}', [PendaftaranController::class, 'detail'])->name('detail');
    Route::post('/insertdetail', [PendaftaranController::class, 'insertdetail'])->name('insertdetail');

    Route::get('/syarat', [PendaftaranController::class, 'syarat'])->name('syarat');
    Route::get('/end', [PendaftaranController::class, 'end'])->name('end');
    Route::get('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal', [PendaftaranController::class, 'showLapanganSchedule'])->name('fasilitas.lapangan.jadwal');

    // Route untuk halaman fasilitas
    Route::get('/fasilitas', [PendaftaranController::class, 'venue'])->name('fasilitas');
    Route::get('/fasilitas/venue/{id}', [PendaftaranController::class, 'showVenue'])->name('fasilitas.detail');
    Route::get('/fasilitas/venue/{id}/edit', [PendaftaranController::class, 'editVenue'])->name('fasilitas.edit');
    Route::post('/fasilitas/venue/{id}/update', [PendaftaranController::class, 'updateVenue'])->name('fasilitas.update');
    Route::post('/fasilitas/venue/{id}/lapangan', [PendaftaranController::class, 'storeLapangan'])->name('fasilitas.lapangan.store');
    Route::get('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal', [PendaftaranController::class, 'showLapanganSchedule'])->name('fasilitas.lapangan.jadwal');
    Route::get('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal/api', [PendaftaranController::class, 'getSlotsByDate'])->name('fasilitas.lapangan.jadwal.api');
    Route::post('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal', [PendaftaranController::class, 'storeLapanganSlot'])->name('fasilitas.lapangan.jadwal.store');
    Route::post('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal/bulk', [PendaftaranController::class, 'storeBulkLapanganSlots'])->name('fasilitas.lapangan.jadwal.bulk');
    Route::get('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal/{slot}/edit', [PendaftaranController::class, 'editLapanganSlot'])->name('fasilitas.lapangan.jadwal.edit');
    Route::put('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal/{slot}', [PendaftaranController::class, 'updateLapanganSlot'])->name('fasilitas.lapangan.jadwal.update');
    Route::delete('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal/{slot}', [PendaftaranController::class, 'deleteLapanganSlot'])->name('fasilitas.lapangan.jadwal.delete');
    Route::delete('/fasilitas/venue/{venue}/lapangan/{lapangan}/jadwal-delete-by-date', [PendaftaranController::class, 'deleteSlotsByDate'])->name('fasilitas.lapangan.jadwal.delete_by_date');
    Route::get('/papan', [PendaftaranController::class, 'papan'])->name('papan');
    Route::get('/papan/venue/{id}', [PendaftaranController::class, 'papanVenueDetail'])->name('papan.venue.detail');
    Route::get('/pemiliklapangan/promo', [PendaftaranController::class, 'promo'])->name('pemilik.promo');
    Route::post('/pemiliklapangan/promo', [PendaftaranController::class, 'storePromo'])->name('pemilik.promo.store');
    
    Route::prefix('keuangan')->group(function () {
        Route::get('/fasilitas', function() {
            $user = Auth::user();
            $venue = \App\Models\Pendaftaran::where('user_id', $user->id)->first();
            
            $bookings = collect();
            $totalTransaksiBulanIni = 0;
            $totalPendapatanBulanIni = 0;
            $totalKomisiBulanIni = 0;
            $totalTransaksiHariIni = 0;
            $totalPendapatanHariIni = 0;

            if ($venue) {
                $bookings = \App\Models\VenueBooking::where('pendaftaran_id', $venue->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $bulanIni = now()->month;
                $tahunIni = now()->year;
                $hariIni = now()->toDateString();

                $bookingsBulanIni = $bookings->filter(fn($b) => $b->created_at->month == $bulanIni && $b->created_at->year == $tahunIni);
                $totalTransaksiBulanIni = $bookingsBulanIni->count();
                $totalPendapatanBulanIni = $bookingsBulanIni->sum('pendapatan_mitra');
                $totalKomisiBulanIni = $bookingsBulanIni->sum('komisi_platform');

                $bookingsHariIni = $bookings->filter(fn($b) => $b->created_at->toDateString() == $hariIni);
                $totalTransaksiHariIni = $bookingsHariIni->count();
                $totalPendapatanHariIni = $bookingsHariIni->sum('pendapatan_mitra');
            }

            return view('pemiliklapangan.Keuangan.fasilitas', compact(
                'venue', 'bookings', 'totalTransaksiBulanIni', 'totalPendapatanBulanIni',
                'totalKomisiBulanIni', 'totalTransaksiHariIni', 'totalPendapatanHariIni'
            ));
        })->name('keuangan.fasilitas');
        Route::get('/komunitas', fn() => view('pemiliklapangan.Keuangan.komunitas'))->name('keuangan.komunitas');
        Route::get('/membership', function() {
            $user = Auth::user();
            $membershipActivities = \App\Models\Activity::where('pemilik_id', $user->id)
                ->where(function($q) {
                    $q->where('jenis', 'membership')
                      ->orWhereHas('activityType', fn($at) => $at->where('name', 'klub'));
                })
                ->get();
            
            $activityIds = $membershipActivities->pluck('id');
            $participants = \App\Models\ActivityParticipant::whereIn('activity_id', $activityIds)
                ->with(['activity', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalPendapatan = $participants->where('status', 'approved')->sum(function($p) {
                return $p->activity->harga ?? 0;
            });

            return view('pemiliklapangan.Keuangan.membership', compact('membershipActivities', 'participants', 'totalPendapatan'));
        })->name('keuangan.membership');
        Route::get('/event', fn() => view('pemiliklapangan.Keuangan.event'))->name('keuangan.event');
    });

    Route::get('/pemiliklapangan/pengaturan', [App\Http\Controllers\MitraController::class, 'pengaturan'])->name('pemilik.pengaturan');
    Route::post('/pemiliklapangan/pengaturan/update', [App\Http\Controllers\MitraController::class, 'updatePengaturan'])->name('pemilik.pengaturan.update');
});

// ======================================================
// ======================================================
// PENGELOLA KESEHATAN ROUTES
// ======================================================
Route::get('/loginpengelolakesehatan', fn() => view('pemilikkesehatan.loginpengelolakesehatan'));
Route::get('/regispengelolakesehatan', fn() => view('pemilikkesehatan.regispengelolakesehatan'));
Route::get('/isidatakesehatan', [App\Http\Controllers\PengelolaKesehatanController::class, 'create'])->name('pengelolakesehatan.create');
Route::post('/isidatakesehatan', [App\Http\Controllers\PengelolaKesehatanController::class, 'store'])->name('pengelolakesehatan.store');

// Dashboard & Manajemen Kesehatan (Authenticated)
Route::middleware(['auth', 'role:pengelolakesehatan'])->group(function () {
    Route::prefix('pengelolakesehatan')->name('pengelola.')->group(function () {
        // Dashboard
        Route::get('/', [App\Http\Controllers\Health\HealthManagerController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [App\Http\Controllers\Health\HealthManagerController::class, 'dashboard'])->name('dashboard.alt');
        
        // Analytics
        Route::get('/analytics', [App\Http\Controllers\Health\HealthAnalyticsController::class, 'index'])->name('analytics');
        Route::post('/analytics/send-email', [App\Http\Controllers\Health\HealthAnalyticsController::class, 'sendEmail'])->name('analytics.send_email');
        Route::get('/analytics/export-csv', [App\Http\Controllers\Health\HealthAnalyticsController::class, 'exportCsv'])->name('analytics.export_csv');
        
        // Pengaturan
        Route::get('/pengaturan', [App\Http\Controllers\PengelolaKesehatanController::class, 'pengaturan'])->name('pengaturan');
        Route::post('/pengaturan/update', [App\Http\Controllers\PengelolaKesehatanController::class, 'updatePengaturan'])->name('pengaturan.update');
        
        // Klinik
        Route::get('/klinik', [App\Http\Controllers\Health\HealthManagerController::class, 'clinics'])->name('clinics');
        Route::get('/klinik/create', [App\Http\Controllers\Health\HealthManagerController::class, 'createClinic'])->name('clinics.create');
        Route::post('/klinik', [App\Http\Controllers\Health\HealthManagerController::class, 'storeClinic'])->name('clinics.store');
        Route::get('/klinik/{id}', [App\Http\Controllers\Health\HealthManagerController::class, 'showClinic'])->name('clinics.show');
        Route::get('/klinik/{id}/edit', [App\Http\Controllers\Health\HealthManagerController::class, 'editClinic'])->name('clinics.edit');
        Route::put('/klinik/{id}', [App\Http\Controllers\Health\HealthManagerController::class, 'updateClinic'])->name('clinics.update');
        
        // Dokter
        Route::get('/dokter', [App\Http\Controllers\Health\HealthManagerDoctorController::class, 'index'])->name('doctors.index');
        Route::get('/dokter/create', [App\Http\Controllers\Health\HealthManagerDoctorController::class, 'create'])->name('doctors.create');
        Route::post('/dokter', [App\Http\Controllers\Health\HealthManagerDoctorController::class, 'store'])->name('doctors.store');
        Route::get('/dokter/{id}/edit', [App\Http\Controllers\Health\HealthManagerDoctorController::class, 'edit'])->name('doctors.edit');
        Route::put('/dokter/{id}', [App\Http\Controllers\Health\HealthManagerDoctorController::class, 'update'])->name('doctors.update');
        Route::delete('/dokter/{id}', [App\Http\Controllers\Health\HealthManagerDoctorController::class, 'destroy'])->name('doctors.destroy');
        
        // Jadwal Dokter
        Route::get('/jadwal-dokter', [App\Http\Controllers\Health\HealthManagerScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/jadwal-dokter/create', [App\Http\Controllers\Health\HealthManagerScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/jadwal-dokter', [App\Http\Controllers\Health\HealthManagerScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/jadwal-dokter/{id}/edit', [App\Http\Controllers\Health\HealthManagerScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/jadwal-dokter/{id}', [App\Http\Controllers\Health\HealthManagerScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/jadwal-dokter/{id}', [App\Http\Controllers\Health\HealthManagerScheduleController::class, 'destroy'])->name('schedules.destroy');
        
        // Layanan
        Route::get('/layanan', [App\Http\Controllers\Health\HealthManagerServiceController::class, 'index'])->name('services.index');
        Route::get('/layanan/create', [App\Http\Controllers\Health\HealthManagerServiceController::class, 'create'])->name('services.create');
        Route::post('/layanan', [App\Http\Controllers\Health\HealthManagerServiceController::class, 'store'])->name('services.store');
        Route::get('/layanan/{id}/edit', [App\Http\Controllers\Health\HealthManagerServiceController::class, 'edit'])->name('services.edit');
        Route::put('/layanan/{id}', [App\Http\Controllers\Health\HealthManagerServiceController::class, 'update'])->name('services.update');
        Route::delete('/layanan/{id}', [App\Http\Controllers\Health\HealthManagerServiceController::class, 'destroy'])->name('services.destroy');
        
        // Booking
        Route::get('/booking', [App\Http\Controllers\Health\HealthManagerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/booking/{id}', [App\Http\Controllers\Health\HealthManagerBookingController::class, 'show'])->name('bookings.show');
        Route::post('/booking/{id}/update-status', [App\Http\Controllers\Health\HealthManagerBookingController::class, 'updateStatus'])->name('bookings.update-status');

        // Community & Membership
        Route::get('/pengelolakesehatan/komunitas', fn() => view('pemilikkesehatan.pemilikkesehatan_buat_komunitas'))->name('pengelola.komunitas');
        Route::post('/pengelolakesehatan/komunitas', [App\Http\Controllers\ActivityController::class, 'storeFromPengelola'])->name('activities.store.pengelola');
        Route::get('/pengelolakesehatan/membership', function() {
            $user = Auth::user();
            $clinics = \App\Models\Clinic::where('user_id', $user->id)->get();
            $myMemberships = \App\Models\Activity::where('pemilik_id', $user->id)
                ->where('jenis', 'membership')
                ->with('clinic')
                ->orderBy('created_at', 'desc')
                ->get();
            return view('pemilikkesehatan.pemilikkesehatan_buat_membership', compact('clinics', 'myMemberships'));
        })->name('pengelola.membership');
        Route::delete('/pengelolakesehatan/membership/{id}', [App\Http\Controllers\ActivityController::class, 'destroy'])->name('pengelola.membership.delete');
    });
});

// ======================================================
// SUPERADMIN / BACKOFFICE ROUTES (Authenticated + Role: Superadmin)
// ======================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    Route::middleware(['role:superadmin'])->group(function () {
        // Dashboard Admin & Keuangan
        Route::get('/admin', [AdminController::class, 'admin'])->name('admin');
        Route::get('/admin/keuangan', [AdminController::class, 'keuangan'])->name('admin.keuangan');

        // PENGATURAN REKENING & PAYMENT SETTINGS (SUPERADMIN)
        Route::get('/admin/payment-settings', [App\Http\Controllers\Admin\PaymentSettingController::class, 'index'])->name('admin.payment-settings.index');
        Route::post('/admin/payment-settings', [App\Http\Controllers\Admin\PaymentSettingController::class, 'store'])->name('admin.payment-settings.store');
        Route::post('/admin/payment-settings/membership-discount', [App\Http\Controllers\Admin\PaymentSettingController::class, 'updateMembershipDiscount'])->name('admin.payment-settings.update-discount');
        Route::put('/admin/payment-settings/{id}', [App\Http\Controllers\Admin\PaymentSettingController::class, 'update'])->name('admin.payment-settings.update');
        Route::patch('/admin/payment-settings/{id}/toggle', [App\Http\Controllers\Admin\PaymentSettingController::class, 'toggleStatus'])->name('admin.payment-settings.toggle');
        Route::delete('/admin/payment-settings/{id}', [App\Http\Controllers\Admin\PaymentSettingController::class, 'destroy'])->name('admin.payment-settings.destroy');

        // GALERI
        Route::get('/galeri', [GaleriController::class, 'galeri'])->name('galeri');
        Route::get('/galeri/home-banner', [GaleriController::class, 'homeBanner'])->name('galeri.home-banner');
        Route::get('/galeri/lapangan-banner', [GaleriController::class, 'lapanganBanner'])->name('galeri.lapangan-banner');
        Route::get('/galeri/kesehatan-banner', [GaleriController::class, 'kesehatanBanner'])->name('galeri.kesehatan-banner');
        Route::get('/galeri/venue-banner', [GaleriController::class, 'venueBanner'])->name('galeri.venue-banner');
        Route::get('/tambahgaleri/{kategori}', [GaleriController::class, 'tambahgaleri'])->name('tambahgaleri.kategori');
        Route::get('/tambahgaleri', [GaleriController::class, 'tambahgaleri'])->name('tambahgaleri');
        Route::post('/insertgaleri', [GaleriController::class, 'insertgaleri'])->name('insertgaleri');
        Route::get('/tampilkangaleri/{id}', [GaleriController::class, 'tampilkangaleri'])->name('tampilkangaleri');
        Route::post('/updategaleri/{id}', [GaleriController::class, 'updategaleri'])->name('updategaleri');
        Route::get('/deletegaleri/{id}', [GaleriController::class, 'deletegaleri'])->name('deletegaleri');

        // NEWS
        Route::get('/newss', [BeritaController::class, 'newss'])->name('newss');
        Route::get('/tambahdata', [BeritaController::class, 'tambahdata'])->name('tambahdata');
        Route::post('/insertdata', [BeritaController::class, 'insertdata'])->name('insertdata');
        Route::get('/tampilkandata/{id}', [BeritaController::class, 'tampilkandata'])->name('tampilkandata');
        Route::post('/updatedata/{id}', [BeritaController::class, 'updatedata'])->name('updatedata');
        Route::get('/deletenews/{id}', [BeritaController::class, 'deletenews'])->name('deletenews');
        Route::get('/exportpdf', [BeritaController::class, 'exportpdf'])->name('exportpdf');

        // REVIEW
        Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
        Route::get('/tambah_review', [ReviewController::class, 'create'])->name('review.create');
        Route::post('/simpan_review', [ReviewController::class, 'store'])->name('review.store');
        Route::get('/edit_review/{id}', [ReviewController::class, 'edit'])->name('review.edit');
        Route::put('/update_review/{id}', [ReviewController::class, 'update'])->name('review.update');
        Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

        // CATEGORY
        Route::get('/category', [CategoryController::class, 'category'])->name('category.index');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

        // PROGRAM
        Route::get('/programs', [ProgramController::class, 'programs'])->name('programs');
        Route::get('/tambahprogram', [ProgramController::class, 'tambahprogram'])->name('tambahprogram');
        Route::post('/insertprogram', [ProgramController::class, 'insertprogram'])->name('insertprogram');
        Route::get('/tampilkanprogram/{id}', [ProgramController::class, 'tampilkanprogram'])->name('tampilkanprogram');
        Route::post('/updateprogram/{id}', [ProgramController::class, 'updateprogram'])->name('updateprogram');
        Route::get('/deleteprogram/{id}', [ProgramController::class, 'deleteprogram'])->name('deleteprogram');

        // ACCOUNT
        Route::get('/akun', [LoginController::class, 'akun'])->name('akun');
        Route::get('/add', [LoginController::class, 'add'])->name('add');
        Route::post('/insertacc', [LoginController::class, 'insertacc'])->name('insertacc');
        Route::get('/tampilkanacc/{id}', [LoginController::class, 'tampilkanacc'])->name('tampilkanacc');
        Route::post('/updateacc/{id}', [LoginController::class, 'updateacc'])->name('updateacc');
        Route::delete('/deleteacc/{id}', [LoginController::class, 'deleteacc'])->name('deleteacc');

        // VERIFIKASI MITRA
        Route::get('/verifikasi-mitra', [App\Http\Controllers\MitraController::class, 'index'])->name('mitra.index');
        Route::match(['GET', 'POST', 'PUT'], '/verifikasi-mitra/{id}/verify', [App\Http\Controllers\MitraController::class, 'verify'])->name('mitra.verify');
        Route::match(['GET', 'POST', 'PUT'], '/verifikasi-mitra/{id}', [App\Http\Controllers\MitraController::class, 'verify'])->name('mitra.verify.legacy');
        Route::get('/datapemiliklapangan', [App\Http\Controllers\MitraController::class, 'index'])->name('mitra.datapemiliklapangan');
        Route::get('/verifikasi-mitra/{id}', [App\Http\Controllers\MitraController::class, 'show'])->name('mitra.show');
        Route::delete('/verifikasi-mitra/{id}', [App\Http\Controllers\MitraController::class, 'destroy'])->name('mitra.destroy');

        // MANAJEMEN USER (ADMIN)
        Route::get('/users', [AdminController::class, 'listUsers'])->name('admin.users.list');
        Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

        // MANAJEMEN VENUE (ADMIN)
        Route::get('/data-venue', [App\Http\Controllers\AdminController::class, 'listVenue'])->name('admin.venue.list');
        Route::get('/data-venue/{id}', [App\Http\Controllers\AdminController::class, 'showVenue'])->name('admin.venue.show');
        Route::get('/data-venue/{id}/edit', [App\Http\Controllers\AdminController::class, 'editVenue'])->name('admin.venue.edit');
        Route::put('/data-venue/{id}', [App\Http\Controllers\AdminController::class, 'updateVenue'])->name('admin.venue.update');
        Route::put('/data-venue/{id}/verify', [App\Http\Controllers\AdminController::class, 'verifyVenue'])->name('admin.venue.verify');
        Route::put('/data-venue/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectVenue'])->name('admin.venue.reject');
        Route::delete('/data-venue/{id}', [App\Http\Controllers\AdminController::class, 'deleteVenue'])->name('admin.venue.delete');

        // LAYANAN KESEHATAN - Kategori Klinik
        Route::prefix('health')->name('health.')->group(function () {
            // Kategori Klinik
            Route::resource('categories', App\Http\Controllers\Health\ClinicCategoryController::class);
            
            // Klinik
            Route::get('clinics', [App\Http\Controllers\Health\ClinicController::class, 'index'])->name('clinics.index');
            Route::get('clinics/create', [App\Http\Controllers\Health\ClinicController::class, 'create'])->name('clinics.create');
            Route::post('clinics', [App\Http\Controllers\Health\ClinicController::class, 'store'])->name('clinics.store');
            Route::get('clinics/{id}', [App\Http\Controllers\Health\ClinicController::class, 'show'])->name('clinics.show');
            Route::get('clinics/{id}/edit', [App\Http\Controllers\Health\ClinicController::class, 'edit'])->name('clinics.edit');
            Route::put('clinics/{id}', [App\Http\Controllers\Health\ClinicController::class, 'update'])->name('clinics.update');
            Route::match(['GET', 'POST', 'PUT'], 'clinics/{id}/approve', [App\Http\Controllers\Health\ClinicController::class, 'approve'])->name('clinics.approve');
            Route::post('clinics/{id}/reject', [App\Http\Controllers\Health\ClinicController::class, 'reject'])->name('clinics.reject');
            Route::delete('clinics/{id}', [App\Http\Controllers\Health\ClinicController::class, 'destroy'])->name('clinics.destroy');
            
            // Dokter
            Route::get('doctors', [App\Http\Controllers\Health\DoctorController::class, 'index'])->name('doctors.index');
            Route::get('doctors/create', [App\Http\Controllers\Health\DoctorController::class, 'create'])->name('doctors.create');
            Route::post('doctors', [App\Http\Controllers\Health\DoctorController::class, 'store'])->name('doctors.store');
            Route::get('doctors/{id}', [App\Http\Controllers\Health\DoctorController::class, 'show'])->name('doctors.show');
            Route::get('doctors/{id}/edit', [App\Http\Controllers\Health\DoctorController::class, 'edit'])->name('doctors.edit');
            Route::put('doctors/{id}', [App\Http\Controllers\Health\DoctorController::class, 'update'])->name('doctors.update');
            Route::delete('doctors/{id}', [App\Http\Controllers\Health\DoctorController::class, 'destroy'])->name('doctors.destroy');
            
            // Jadwal Dokter
            Route::get('doctor-schedules', [App\Http\Controllers\Health\DoctorScheduleController::class, 'index'])->name('doctor-schedules.index');
            Route::get('doctor-schedules/create', [App\Http\Controllers\Health\DoctorScheduleController::class, 'create'])->name('doctor-schedules.create');
            Route::post('doctor-schedules', [App\Http\Controllers\Health\DoctorScheduleController::class, 'store'])->name('doctor-schedules.store');
            Route::get('doctor-schedules/{id}/edit', [App\Http\Controllers\Health\DoctorScheduleController::class, 'edit'])->name('doctor-schedules.edit');
            Route::put('doctor-schedules/{id}', [App\Http\Controllers\Health\DoctorScheduleController::class, 'update'])->name('doctor-schedules.update');
            Route::delete('doctor-schedules/{id}', [App\Http\Controllers\Health\DoctorScheduleController::class, 'destroy'])->name('doctor-schedules.destroy');
            
            // Layanan Kesehatan
            Route::get('services', [App\Http\Controllers\Health\HealthServiceController::class, 'index'])->name('services.index');
            Route::get('services/create', [App\Http\Controllers\Health\HealthServiceController::class, 'create'])->name('services.create');
            Route::post('services', [App\Http\Controllers\Health\HealthServiceController::class, 'store'])->name('services.store');
            Route::get('services/{id}/edit', [App\Http\Controllers\Health\HealthServiceController::class, 'edit'])->name('services.edit');
            Route::put('services/{id}', [App\Http\Controllers\Health\HealthServiceController::class, 'update'])->name('services.update');
            Route::delete('services/{id}', [App\Http\Controllers\Health\HealthServiceController::class, 'destroy'])->name('services.destroy');
            
            // Booking Kesehatan
            Route::get('bookings', [App\Http\Controllers\Health\HealthBookingController::class, 'index'])->name('bookings.index');
            Route::get('bookings/{id}', [App\Http\Controllers\Health\HealthBookingController::class, 'show'])->name('bookings.show');
            Route::post('bookings/{id}/update-status', [App\Http\Controllers\Health\HealthBookingController::class, 'updateStatus'])->name('bookings.update-status');
            Route::delete('bookings/{id}', [App\Http\Controllers\Health\HealthBookingController::class, 'destroy'])->name('bookings.destroy');
        });

        // TEMPAT SEHAT (Pengelola Kesehatan)
        Route::get('/tempat-sehat', [App\Http\Controllers\TempatSehatController::class, 'index'])->name('tempat-sehat.index');
        Route::get('/tempat-sehat/{id}', [App\Http\Controllers\TempatSehatController::class, 'show'])->name('tempat-sehat.show');
        Route::match(['GET', 'POST', 'PUT'], '/tempat-sehat/{id}/verify', [App\Http\Controllers\TempatSehatController::class, 'verify'])->name('tempat-sehat.verify');
        Route::delete('/tempat-sehat/{id}', [App\Http\Controllers\TempatSehatController::class, 'destroy'])->name('tempat-sehat.destroy');


        // ACTIVITY TYPES
        Route::resource('activity-types', ActivityTypeController::class);
        Route::get('activity-types-daftar', [App\Http\Controllers\ActivityController::class, 'daftar'])->name('activity-types.daftar');
        
        // VERIFIKASI PEMBAYARAN EVENT (Harus sebelum activities/{id} agar tidak conflict)
        Route::get('activities/verifikasi-pembayaran', [App\Http\Controllers\ActivityController::class, 'verifikasiPembayaran'])->name('activities.verifikasi-pembayaran');
        Route::put('activities/pembayaran/{id}/approve', [App\Http\Controllers\ActivityController::class, 'approvePembayaran'])->name('activities.approve-pembayaran');
        Route::put('activities/pembayaran/{id}/reject', [App\Http\Controllers\ActivityController::class, 'rejectPembayaran'])->name('activities.reject-pembayaran');

        // ACTIVITIES (Verifikasi)
        Route::get('activities/{id}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');
        Route::put('activities/{id}/approve', [App\Http\Controllers\ActivityController::class, 'approve'])->name('activities.approve');
        Route::put('activities/{id}/reject', [App\Http\Controllers\ActivityController::class, 'reject'])->name('activities.reject');
        Route::delete('activities/{id}', [App\Http\Controllers\ActivityController::class, 'destroy'])->name('activities.destroy');

        // EXTRA - SOCIAL MEDIA
        Route::get('/social-media', [SocialMediaController::class, 'index'])->name('social-media.index');
        Route::get('/social-media/create', [SocialMediaController::class, 'create'])->name('social-media.create');
        Route::post('/social-media', [SocialMediaController::class, 'store'])->name('social-media.store');
        Route::get('/social-media/{id}/edit', [SocialMediaController::class, 'edit'])->name('social-media.edit');
        Route::put('/social-media/{id}', [SocialMediaController::class, 'update'])->name('social-media.update');
        Route::delete('/social-media/{id}', [SocialMediaController::class, 'destroy'])->name('social-media.destroy');

        // EXTRA - CONTACT US
        Route::get('/contact', [ContactUsController::class, 'index'])->name('contact-us.index');
        Route::get('/contact/create', [ContactUsController::class, 'create'])->name('contact-us.create');
        Route::post('/contact', [ContactUsController::class, 'store'])->name('contact-us.store');
        Route::get('/contact/{id}/edit', [ContactUsController::class, 'edit'])->name('contact-us.edit');
        Route::put('/contact/{id}', [ContactUsController::class, 'update'])->name('contact-us.update');
        Route::delete('/contact/{id}', [ContactUsController::class, 'destroy'])->name('contact-us.destroy');

        // EXTRA - ABOUT US
        Route::get('/about', [AboutUsController::class, 'index'])->name('about-us.index');
        Route::get('/about/create', [AboutUsController::class, 'create'])->name('about-us.create');
        Route::post('/about', [AboutUsController::class, 'store'])->name('about-us.store');
        Route::get('/about/{id}/edit', [AboutUsController::class, 'edit'])->name('about-us.edit');
        Route::put('/about/{id}', [AboutUsController::class, 'update'])->name('about-us.update');
        Route::delete('/about/{id}', [AboutUsController::class, 'destroy'])->name('about-us.destroy');

        // Community & Membership
        Route::get('/admin/komunitas', fn() => view('BACKEND.admin_buat_komunitas'))->name('admin.komunitas');
        Route::post('/admin/komunitas', [App\Http\Controllers\ActivityController::class, 'storeFromAdmin'])->name('activities.store.admin');
        Route::get('/admin/membership', fn() => view('BACKEND.admin_buat_membership'))->name('admin.membership');

        // Booking ACC & Commission Approval Routes
        Route::post('/owner/booking/{id}/approve', [App\Http\Controllers\BookingApprovalController::class, 'approveVenueBooking'])->name('booking.approve');
        Route::post('/owner/booking/{id}/reject', [App\Http\Controllers\BookingApprovalController::class, 'rejectVenueBooking'])->name('booking.reject');
        Route::post('/admin/venue/{id}/update-commission', [App\Http\Controllers\BookingApprovalController::class, 'updateVenueCommission'])->name('admin.venue.update-commission');

    });
});

