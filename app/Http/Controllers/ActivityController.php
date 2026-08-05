<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\ActivityParticipant;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Store aktivitas dari user biasa
     */
    public function storeFromUser(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'biaya' => 'required|in:gratis,berbayar',
            'harga' => 'nullable|integer|min:0|required_if:biaya,berbayar',
            'deskripsi' => 'required|string',
            'link' => 'nullable|string|max:500',
            'jenis' => 'required|in:komunitas,membership,event',
        ]);

        // Tentukan activity_type_id berdasarkan jenis
        $activityType = ActivityType::where('name', $request->jenis === 'komunitas' ? 'open-class' : ($request->jenis === 'membership' ? 'klub' : 'event'))->first();

        $data = new Activity();
        $data->nama = $request->nama;
        $data->kategori = $request->kategori;
        $data->lokasi = $request->lokasi;
        $data->biaya_bergabung = $request->biaya;
        $data->harga = $request->biaya === 'berbayar' ? $request->harga : null;
        $data->deskripsi = $request->deskripsi;
        $data->link_kontak = $request->link;
        $data->link_kontak_2 = $request->link_kontak_2;
        $data->jenis = $request->jenis;
        $data->status = 'pending'; // Status pending untuk verifikasi
        $data->user_id = Auth::id();
        $data->activity_type_id = $activityType ? $activityType->id : null;

        // Handle upload banner
        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotoaktivitas'), $imageName);
            $data->banner = $imageName;
        }

        $data->save();

        return redirect()->back()->with('success', 'Aktivitas berhasil dibuat dan sedang menunggu verifikasi admin.');
    }

    /**
     * Store aktivitas dari pemilik lapangan
     */
    public function storeFromPemilik(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'biaya_bergabung' => 'required|in:gratis,berbayar',
            'harga' => 'nullable|integer|min:0|required_if:biaya_bergabung,berbayar',
            'deskripsi' => 'required|string',
            'link_kontak' => 'nullable|string|max:500',
            'link_kontak_2' => 'nullable|string|max:500',
            'tipe_aktivitas' => 'nullable|in:komunitas,event,membership',
            'jenis' => 'nullable|in:komunitas,event,membership',
        ]);

        $jenis = $request->tipe_aktivitas ?? $request->jenis ?? 'komunitas';

        $activityTypeName = $jenis === 'event'
            ? 'event'
            : ($jenis === 'membership' ? 'klub' : 'open-class');

        $activityType = ActivityType::where('name', $activityTypeName)->first();

        if (!$activityType) {
            $activityType = ActivityType::firstOrCreate([
                'name' => $activityTypeName,
            ], [
                'title' => $jenis === 'membership' ? 'Membership' : ($jenis === 'event' ? 'Event Olahraga' : 'Komunitas'),
                'icon' => $jenis === 'membership' ? 'fas fa-users' : ($jenis === 'event' ? 'fas fa-calendar-alt' : 'fas fa-chalkboard-teacher'),
            ]);
        }
        $data = new Activity();
        $data->nama = $request->nama;
        $data->kategori = $request->kategori;
        $data->lokasi = $request->lokasi;
        $data->biaya_bergabung = $request->biaya_bergabung;
        $data->harga = $request->biaya_bergabung === 'berbayar' ? $request->harga : null;
        $data->deskripsi = $request->deskripsi;
        $data->link_kontak = $request->link_kontak;
        $data->link_kontak_2 = $request->link_kontak_2;
        $data->jenis = $jenis;
        $data->status = 'pending'; // Status pending untuk verifikasi
        $data->pemilik_id = Auth::id();
        $data->activity_type_id = $activityType ? $activityType->id : null;

        // Handle upload banner
        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotoaktivitas'), $imageName);
            $data->banner = $imageName;
        }

        $data->save();

        $message = $jenis === 'event' 
            ? 'Event berhasil dibuat dan sedang menunggu verifikasi admin.'
            : ($jenis === 'membership' ? 'Paket Membership berhasil dibuat dan sedang menunggu verifikasi admin.' : 'Komunitas berhasil dibuat dan sedang menunggu verifikasi admin.');

        return redirect()->back()->with('success', $message);
    }

    /**
     * Store aktivitas dari Super Admin
     */
    public function storeFromAdmin(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'biaya' => 'required|in:gratis,berbayar',
            'harga' => 'nullable|integer|min:0|required_if:biaya,berbayar',
            'deskripsi' => 'required|string',
            'link' => 'nullable|string|max:500',
            'link_kontak_2' => 'nullable|string|max:500',
            'jenis' => 'required|in:komunitas,membership,event',
        ]);

        $activityTypeName = $request->jenis === 'komunitas' ? 'open-class' : ($request->jenis === 'membership' ? 'klub' : 'event');
        $activityType = ActivityType::where('name', $activityTypeName)->first();

        if (!$activityType) {
            $activityType = ActivityType::create([
                'name' => $activityTypeName,
                'title' => $request->jenis === 'membership' ? 'Membership' : ($request->jenis === 'event' ? 'Event Olahraga' : 'Komunitas'),
                'icon' => $request->jenis === 'membership' ? 'fas fa-users' : ($request->jenis === 'event' ? 'fas fa-calendar-alt' : 'fas fa-chalkboard-teacher'),
            ]);
        }

        $data = new Activity();
        $data->nama = $request->nama;
        $data->kategori = $request->kategori;
        $data->lokasi = $request->lokasi;
        $data->biaya_bergabung = $request->biaya;
        $data->harga = $request->biaya === 'berbayar' ? $request->harga : null;
        $data->deskripsi = $request->deskripsi;
        $data->link_kontak = $request->link;
        $data->link_kontak_2 = $request->link_kontak_2;
        $data->jenis = $request->jenis;
        $data->status = 'approved'; // Super Admin auto-approved
        $data->verified_at = now();
        $data->user_id = Auth::id();
        $data->activity_type_id = $activityType ? $activityType->id : null;

        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotoaktivitas'), $imageName);
            $data->banner = $imageName;
        }

        $data->save();

        $jenisTitle = ucfirst($request->jenis);
        return redirect()->back()->with('success', "{$jenisTitle} berhasil dibuat dan langsung terpublikasi.");
    }

    /**
     * Store aktivitas dari Pengelola Kesehatan
     */
    public function storeFromPengelola(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'biaya' => 'required|in:gratis,berbayar',
            'harga' => 'nullable|integer|min:0|required_if:biaya,berbayar',
            'deskripsi' => 'required|string',
            'link' => 'nullable|string|max:500',
            'link_kontak_2' => 'nullable|string|max:500',
            'jenis' => 'required|in:komunitas,membership,event',
        ]);

        $activityTypeName = $request->jenis === 'komunitas' ? 'open-class' : ($request->jenis === 'membership' ? 'klub' : 'event');
        $activityType = ActivityType::where('name', $activityTypeName)->first();

        if (!$activityType) {
            $activityType = ActivityType::create([
                'name' => $activityTypeName,
                'title' => $request->jenis === 'membership' ? 'Membership' : ($request->jenis === 'event' ? 'Event Olahraga' : 'Komunitas'),
                'icon' => $request->jenis === 'membership' ? 'fas fa-users' : ($request->jenis === 'event' ? 'fas fa-calendar-alt' : 'fas fa-chalkboard-teacher'),
            ]);
        }

        $data = new Activity();
        $data->nama = $request->nama;
        $data->kategori = $request->kategori;
        $data->lokasi = $request->lokasi;
        $data->biaya_bergabung = $request->biaya;
        $data->harga = $request->biaya === 'berbayar' ? $request->harga : null;
        $data->deskripsi = $request->deskripsi;
        $data->link_kontak = $request->link;
        $data->link_kontak_2 = $request->link_kontak_2;
        $data->jenis = $request->jenis;
        $data->status = 'pending'; // Pending verification by super admin
        $data->pemilik_id = Auth::id();
        $data->activity_type_id = $activityType ? $activityType->id : null;

        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotoaktivitas'), $imageName);
            $data->banner = $imageName;
        }

        $data->save();

        $jenisTitle = ucfirst($request->jenis);
        return redirect()->back()->with('success', "{$jenisTitle} berhasil dibuat dan sedang menunggu verifikasi admin.");
    }

    /**
     * User bergabung ke event/aktivitas
     */
    public function joinEvent(Request $request, $id)
    {
        $activity = Activity::where('status', 'approved')->findOrFail($id);

        // Cek apakah user sudah bergabung
        $existingParticipant = ActivityParticipant::where('activity_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingParticipant) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar pada event ini.');
        }

        $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'bukti_pembayaran' => $activity->biaya_bergabung === 'berbayar' ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $participant = new ActivityParticipant();
        $participant->activity_id = $activity->id;
        $participant->user_id = Auth::id();
        $participant->nama_peserta = $request->nama_peserta;
        $participant->status = $activity->biaya_bergabung === 'berbayar' ? 'pending' : 'approved'; // Jika gratis langsung approved

        // Handle upload bukti pembayaran jika event berbayar
        if ($request->hasFile('bukti_pembayaran')) {
            $image = $request->file('bukti_pembayaran');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('bukti_pembayaran'), $imageName);
            $participant->bukti_pembayaran = $imageName;
        }

        if ($request->has('bank_code') && $request->bank_code) {
            $participant->catatan = 'Metode Pembayaran: ' . strtoupper($request->bank_code);
        }

        $participant->save();

        $message = $activity->biaya_bergabung === 'berbayar'
            ? 'Pendaftaran berhasil! Bukti pembayaran sedang diverifikasi oleh admin.'
            : 'Anda berhasil bergabung dengan event ini!';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Daftar aktivitas untuk verifikasi di super admin
     */
    public function daftar(Request $request)
    {
        $status = $request->get('status', 'pending'); // Default pending
        
        $query = Activity::with(['user', 'pemilik', 'activityType']);
        
        // Filter berdasarkan status
        if ($status == 'pending') {
            $query->where('status', 'pending');
        } elseif ($status == 'approved') {
            $query->where('status', 'approved');
        } elseif ($status == 'rejected') {
            $query->where('status', 'rejected');
        } elseif ($status == 'all') {
            // Tampilkan semua
        } else {
            $query->where('status', 'pending');
        }
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', '%' . $search . '%')
                  ->orWhere('kategori', 'LIKE', '%' . $search . '%')
                  ->orWhere('lokasi', 'LIKE', '%' . $search . '%');
            });
        }
        
        $activities = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Count untuk statistik
        $countPending = Activity::where('status', 'pending')->count();
        $countApproved = Activity::where('status', 'approved')->count();
        $countRejected = Activity::where('status', 'rejected')->count();
        $countAll = Activity::count();
        
        return view('backend.activity_types.daftar', compact('activities', 'status', 'countPending', 'countApproved', 'countRejected', 'countAll'));
    }

    /**
     * Approve aktivitas
     */
    public function approve($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->status = 'approved';
        $activity->verified_at = now();
        $activity->save();

        return redirect()->route('activity-types.daftar')->with('success', 'Aktivitas berhasil disetujui dan akan muncul di halaman community.');
    }

    /**
     * Reject aktivitas
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:500',
        ]);

        $activity = Activity::findOrFail($id);
        $activity->status = 'rejected';
        $activity->alasan_reject = $request->alasan_reject;
        $activity->save();

        return redirect()->route('activity-types.daftar')->with('success', 'Aktivitas ditolak.');
    }

    /**
     * Show detail aktivitas untuk verifikasi
     */
    public function show($id)
    {
        $activity = Activity::with(['user', 'pemilik', 'activityType'])->findOrFail($id);
        return view('backend.activity_types.detail', compact('activity'));
    }

    /**
     * Hapus aktivitas
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($activity->gambar && Storage::exists('public/' . $activity->gambar)) {
            Storage::delete('public/' . $activity->gambar);
        }
        
        $activity->delete();

        return redirect()->route('activity-types.daftar', ['status' => request('status', 'all')])->with('success', 'Aktivitas berhasil dihapus.');
    }

    /**
     * Display approved activities for frontend community page (unified for guest and user)
     */
    public function index(Request $request)
    {
        $query = Activity::where('status', 'approved')
            ->with(['user', 'pemilik', 'activityType']);

        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $type = $request->type;
            $query->where(function($q) use ($type) {
                if ($type === 'klub' || $type === 'membership') {
                    $q->where('jenis', 'membership')
                      ->orWhereHas('activityType', function($aq) {
                          $aq->whereIn('name', ['klub', 'membership']);
                      });
                } elseif ($type === 'open-class' || $type === 'komunitas') {
                    $q->where('jenis', 'komunitas')
                      ->orWhereHas('activityType', function($aq) {
                          $aq->whereIn('name', ['open-class', 'komunitas']);
                      });
                } else {
                    $q->where('jenis', $type)
                      ->orWhereHas('activityType', function($aq) use ($type) {
                          $aq->where('name', $type);
                      });
                }
            });
        }

        // Search by location or category
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('lokasi', 'LIKE', '%' . $search . '%')
                  ->orWhere('kategori', 'LIKE', '%' . $search . '%')
                  ->orWhere('nama', 'LIKE', '%' . $search . '%');
            });
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(12);
        $activityTypes = ActivityType::all();

        // Gunakan view yang sama untuk guest dan user
        return view('FRONTEND.community', compact('activities', 'activityTypes'));
    }

    /**
     * Display approved activities for logged-in user community page
     * @deprecated Use index() instead - redirect to main route
     */
    public function indexUser(Request $request)
    {
        // Redirect ke route utama
        return redirect()->route('community');
    }

    /**
     * Show activity detail (unified for guest and user)
     */
    public function showDetail($id)
    {
        $activity = Activity::where('status', 'approved')
            ->with(['user', 'pemilik', 'activityType', 'participants'])
            ->findOrFail($id);

        // Cek apakah user sudah bergabung
        $isJoined = false;
        $participant = null;
        if (Auth::check()) {
            $participant = ActivityParticipant::where('activity_id', $id)
                ->where('user_id', Auth::id())
                ->first();
            $isJoined = $participant !== null;
        }

        // Get related activities (same category, excluding current)
        $relatedActivities = Activity::where('status', 'approved')
            ->where('id', '!=', $id)
            ->where('kategori', $activity->kategori)
            ->with(['user', 'pemilik', 'activityType'])
            ->limit(4)
            ->get();

        $paymentSettings = PaymentSetting::where('is_active', true)->get();

        // Gunakan view yang sama untuk guest dan user
        return view('FRONTEND.community_detail', compact('activity', 'relatedActivities', 'isJoined', 'participant', 'paymentSettings'));
    }

    /**
     * Show activity detail for logged-in user
     * @deprecated Use showDetail() instead - redirect to main route
     */
    public function showUser($id)
    {
        // Redirect ke route utama
        return redirect()->route('community.detail', $id);
    }

    /**
     * Menampilkan riwayat aktivitas yang dibuat oleh user yang login
     * DAN aktivitas yang diikuti (bergabung) oleh user
     */
    public function riwayatKomunitas(Request $request)
    {
        $user = Auth::user();
        
        // Ambil aktivitas yang dibuat oleh user yang login
        $activitiesCreated = Activity::where('user_id', $user->id)
            ->with(['activityType', 'participants.user'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status) {
            $activitiesCreated->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $activitiesCreated->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', '%' . $search . '%')
                  ->orWhere('kategori', 'LIKE', '%' . $search . '%')
                  ->orWhere('lokasi', 'LIKE', '%' . $search . '%');
            });
        }

        $activities = $activitiesCreated->paginate(10);

        // Ambil aktivitas yang diikuti user (bergabung sebagai peserta)
        $activitiesJoined = ActivityParticipant::where('user_id', $user->id)
            ->with(['activity.activityType'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.riwayatkomunitas', compact('activities', 'activitiesJoined'));
    }

    /**
     * Menampilkan form edit aktivitas untuk user
     */
    public function editUserActivity($id)
    {
        $activity = Activity::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.edit_aktivitas', compact('activity'));
    }

    /**
     * Update aktivitas yang dibuat oleh user
     */
    public function updateUserActivity(Request $request, $id)
    {
        $activity = Activity::where('user_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'biaya' => 'required|in:gratis,berbayar',
            'harga' => 'nullable|integer|min:0|required_if:biaya,berbayar',
            'deskripsi' => 'required|string',
            'link' => 'nullable|string|max:500',
            'link_kontak_2' => 'nullable|string|max:500',
            'jenis' => 'required|in:komunitas,membership,event',
        ]);

        // Update data
        $activity->nama = $request->nama;
        $activity->kategori = $request->kategori;
        $activity->lokasi = $request->lokasi;
        $activity->biaya_bergabung = $request->biaya;
        $activity->harga = $request->biaya === 'berbayar' ? $request->harga : null;
        $activity->deskripsi = $request->deskripsi;
        $activity->link_kontak = $request->link;
        $activity->link_kontak_2 = $request->link_kontak_2;
        $activity->jenis = $request->jenis;
        
        // Jika status sudah approved, set kembali ke pending untuk verifikasi ulang
        if ($activity->status === 'approved') {
            $activity->status = 'pending';
        }

        // Handle upload banner baru jika ada
        if ($request->hasFile('banner')) {
            // Hapus banner lama jika ada
            if ($activity->banner && file_exists(public_path('fotoaktivitas/' . $activity->banner))) {
                unlink(public_path('fotoaktivitas/' . $activity->banner));
            }

            $image = $request->file('banner');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotoaktivitas'), $imageName);
            $activity->banner = $imageName;
        }

        $activity->save();

        return redirect()->route('user.riwayat-komunitas')
            ->with('success', 'Aktivitas berhasil diperbarui dan sedang menunggu verifikasi ulang admin.');
    }


    /**
     * Daftar peserta event yang perlu diverifikasi pembayarannya (untuk admin)
     */
    public function verifikasiPembayaran(Request $request)
    {
        $status = $request->get('status', 'pending'); // Default pending
        
        $query = ActivityParticipant::with(['activity.activityType', 'user'])
            ->whereHas('activity', function($q) {
                $q->where('status', 'approved'); // Hanya event yang sudah disetujui
            });

        // Filter berdasarkan status
        if ($status == 'pending') {
            $query->where('status', 'pending');
        } elseif ($status == 'approved') {
            $query->where('status', 'approved');
        } elseif ($status == 'rejected') {
            $query->where('status', 'rejected');
        } elseif ($status == 'all') {
            // Tampilkan semua
        } else {
            $query->where('status', 'pending');
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_peserta', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('activity', function($q) use ($search) {
                      $q->where('nama', 'LIKE', '%' . $search . '%')
                        ->orWhere('kategori', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $participants = $query->orderBy('created_at', 'desc')->paginate(15);

        // Count untuk statistik
        $countPending = ActivityParticipant::where('status', 'pending')
            ->whereHas('activity', function($q) {
                $q->where('status', 'approved');
            })
            ->count();
        $countApproved = ActivityParticipant::where('status', 'approved')
            ->whereHas('activity', function($q) {
                $q->where('status', 'approved');
            })
            ->count();
        $countRejected = ActivityParticipant::where('status', 'rejected')
            ->whereHas('activity', function($q) {
                $q->where('status', 'approved');
            })
            ->count();
        $countAll = ActivityParticipant::whereHas('activity', function($q) {
                $q->where('status', 'approved');
            })
            ->count();

        return view('backend.activity_types.verifikasi_pembayaran', compact('participants', 'status', 'countPending', 'countApproved', 'countRejected', 'countAll'));
    }

    /**
     * Approve pembayaran peserta event
     */
    public function approvePembayaran($id)
    {
        $participant = ActivityParticipant::with('activity')->findOrFail($id);
        
        // Pastikan event sudah approved
        if ($participant->activity->status !== 'approved') {
            return redirect()->back()->with('error', 'Event belum disetujui.');
        }

        $participant->status = 'approved';
        $participant->save();

        return redirect()->route('activities.verifikasi-pembayaran')->with('success', 'Pembayaran berhasil disetujui.');
    }

    /**
     * Reject pembayaran peserta event
     */
    public function rejectPembayaran(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:500',
        ]);

        $participant = ActivityParticipant::with('activity')->findOrFail($id);
        
        // Pastikan event sudah approved
        if ($participant->activity->status !== 'approved') {
            return redirect()->back()->with('error', 'Event belum disetujui.');
        }

        $participant->status = 'rejected';
        $participant->catatan = $request->alasan_reject;
        $participant->save();

        return redirect()->route('activities.verifikasi-pembayaran')->with('success', 'Pembayaran ditolak.');
    }

    /**
     * Approve peserta event oleh pembuat event
     */
    public function approveParticipant($id)
    {
        $participant = ActivityParticipant::with('activity')->findOrFail($id);
        $user = Auth::user();
        
        // Pastikan user adalah pembuat event
        if ($participant->activity->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui peserta ini.');
        }

        // Pastikan event sudah approved
        if ($participant->activity->status !== 'approved') {
            return redirect()->back()->with('error', 'Event belum disetujui.');
        }

        $participant->status = 'approved';
        $participant->save();

        return redirect()->route('user.riwayat-komunitas')->with('success', 'Peserta berhasil disetujui.');
    }

    /**
     * Reject peserta event oleh pembuat event
     */
    public function rejectParticipant(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'nullable|string|max:500',
        ]);

        $participant = ActivityParticipant::with('activity')->findOrFail($id);
        $user = Auth::user();
        
        // Pastikan user adalah pembuat event
        if ($participant->activity->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menolak peserta ini.');
        }

        // Pastikan event sudah approved
        if ($participant->activity->status !== 'approved') {
            return redirect()->back()->with('error', 'Event belum disetujui.');
        }

        $participant->status = 'rejected';
        if ($request->alasan_reject) {
            $participant->catatan = $request->alasan_reject;
        }
        $participant->save();

        return redirect()->route('user.riwayat-komunitas')->with('success', 'Peserta ditolak.');
    }
}
