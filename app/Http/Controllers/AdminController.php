<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\VenueBooking;
use App\Models\HealthBooking;
use App\Models\Clinic;
use App\Models\Mitra;
use App\Models\Activity;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function admin(){
        return view('BACKEND.Layout.admin');
    }

    /**
     * Halaman Laporan Keuangan Superadmin (Venue & Kesehatan & Platform Commission)
     */
    public function keuangan(Request $request)
    {
        $type = $request->get('type', 'all');

        $venueBookings = VenueBooking::with(['venue', 'user'])->orderBy('created_at', 'desc')->get();
        $healthBookings = HealthBooking::with(['clinic', 'user', 'service', 'doctor'])->orderBy('created_at', 'desc')->get();

        $totalOmsetVenue = $venueBookings->sum('total_harga');
        $totalKomisiVenue = $venueBookings->sum('komisi_platform');
        $totalPendapatanMitraVenue = $venueBookings->sum('pendapatan_mitra');

        $totalOmsetHealth = $healthBookings->sum('harga');
        $totalKomisiHealth = $healthBookings->sum('komisi_platform');
        $totalPendapatanMitraHealth = $healthBookings->sum('pendapatan_mitra');

        $totalOmsetKeseluruhan = $totalOmsetVenue + $totalOmsetHealth;
        $totalKomisiPlatform = $totalKomisiVenue + $totalKomisiHealth;

        return view('BACKEND.Keuangan.index', compact(
            'venueBookings',
            'healthBookings',
            'totalOmsetVenue',
            'totalKomisiVenue',
            'totalPendapatanMitraVenue',
            'totalOmsetHealth',
            'totalKomisiHealth',
            'totalPendapatanMitraHealth',
            'totalOmsetKeseluruhan',
            'totalKomisiPlatform',
            'type'
        ));
    }

    public function dashboard(){
        $userCount = User::where('role', 'user')->count();
        $venueCount = Pendaftaran::where('syarat_disetujui', true)->count();
        $healthCount = Clinic::where('status', 'approved')->count();
        
        $pendingMitraCount = Mitra::where('status', 'pending')->count();
        $pendingVenueCount = Pendaftaran::where('syarat_disetujui', false)->count();
        $pendingHealthCount = Clinic::where('status', 'pending')->count();
        $pendingActivityCount = Activity::where('status', 'pending')->count();
        $totalPendingCount = $pendingMitraCount + $pendingVenueCount + $pendingHealthCount + $pendingActivityCount;

        $totalVenueBookings = VenueBooking::count();
        $totalHealthBookings = HealthBooking::count();
        $totalBookings = $totalVenueBookings + $totalHealthBookings;

        $totalKomisiPlatform = VenueBooking::sum('komisi_platform') + HealthBooking::sum('komisi_platform');

        return view('BACKEND.Dashboard.dashboard', compact(
            'userCount',
            'venueCount',
            'healthCount',
            'totalPendingCount',
            'totalBookings',
            'totalKomisiPlatform'
        ));
    }

    // Show pending Pemilik Lapangan for verification
    public function verifikasiMitra()
    {
        $pendingUsers = User::where('role', 'pemiliklapangan')
                            ->where('status', 'pending')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('BACKEND.Verifikasi.verifikasi_mitra', compact('pendingUsers'));
    }

    // Approve Pemilik Lapangan
    public function approveMitra($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'pemiliklapangan' && $user->status === 'pending') {
            $user->update(['status' => 'approved']);
            return redirect()->back()->with('success', 'Mitra Pemilik Lapangan berhasil disetujui.');
        }

        return redirect()->back()->with('error', 'Gagal menyetujui mitra.');
    }

    // Reject Pemilik Lapangan
    public function rejectMitra($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'pemiliklapangan' && $user->status === 'pending') {
            $user->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Mitra Pemilik Lapangan berhasil ditolak.');
        }

        return redirect()->back()->with('error', 'Gagal menolak mitra.');
    }

    /**
     * Menampilkan daftar semua venue untuk admin
     */
    public function listVenue(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Pendaftaran::with(['user', 'lapangans']);

        // Filter berdasarkan status
        if ($status !== 'all') {
            $query->where('syarat_disetujui', $status === 'approved' ? true : false);
        }

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('namavenue', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%")
                  ->orWhere('provinsi', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $venues = $query->orderBy('created_at', 'desc')->paginate(15);

        // Hitung statistik
        $countPending = Pendaftaran::where('syarat_disetujui', false)->count();
        $countApproved = Pendaftaran::where('syarat_disetujui', true)->count();
        $countAll = Pendaftaran::count();

        return view('BACKEND.Venue.datavenue', compact('venues', 'status', 'countPending', 'countApproved', 'countAll'));
    }

    /**
     * Menampilkan detail venue untuk admin
     */
    public function showVenue($id)
    {
        $venue = Pendaftaran::with(['user', 'lapangans', 'galleries'])->findOrFail($id);
        
        // Fasilitas sudah di-cast sebagai array di model
        $fasilitas = $venue->fasilitas;
        if (!is_array($fasilitas)) {
            if (empty($fasilitas)) {
                $fasilitas = [];
            } elseif (is_string($fasilitas)) {
                $decoded = json_decode($fasilitas, true);
                $fasilitas = is_array($decoded) ? $decoded : [];
            } else {
                $fasilitas = [];
            }
        }

        return view('BACKEND.Venue.detailvenue', compact('venue', 'fasilitas'));
    }

    /**
     * Menampilkan form edit venue untuk admin
     */
    public function editVenue($id)
    {
        $venue = Pendaftaran::with('galleries')->findOrFail($id);
        
        // Fasilitas sudah di-cast sebagai array di model
        $fasilitas = $venue->fasilitas;
        if (!is_array($fasilitas)) {
            if (empty($fasilitas)) {
                $fasilitas = [];
            } elseif (is_string($fasilitas)) {
                $decoded = json_decode($fasilitas, true);
                $fasilitas = is_array($decoded) ? $decoded : [];
            } else {
                $fasilitas = [];
            }
        }

        return view('BACKEND.Venue.editvenue', compact('venue', 'fasilitas'));
    }

    /**
     * Update venue dari admin
     */
    public function updateVenue(Request $request, $id)
    {
        $venue = Pendaftaran::findOrFail($id);

        $validated = $request->validate([
            'namavenue' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'email_venue' => 'nullable|email|max:255',
            'video_review' => 'nullable|url|max:500',
            'detail' => 'nullable|string',
            'aturan' => 'nullable|string',
            'lokasi' => 'nullable|url|max:500',
            'syarat_disetujui' => 'nullable|boolean',
            'komisi_tipe' => 'nullable|in:none,percentage,fixed',
            'komisi_nilai' => 'nullable|numeric|min:0',
            'membership_komisi_tipe' => 'nullable|in:none,percentage,fixed',
            'membership_komisi_nilai' => 'nullable|numeric|min:0',
        ]);

        $venue->update($validated);

        return redirect()->route('admin.venue.list')
            ->with('success', 'Venue berhasil diupdate.');
    }

    /**
     * Hapus venue
     */
    public function deleteVenue($id)
    {
        $venue = Pendaftaran::findOrFail($id);
        
        // Hapus lapangan dan slot terkait
        foreach ($venue->lapangans as $lapangan) {
            $lapangan->slots()->delete();
            $lapangan->delete();
        }

        // Hapus gallery terkait
        $venue->galleries()->delete();

        $venue->delete();

        return redirect()->route('admin.venue.list')
            ->with('success', 'Venue berhasil dihapus.');
    }

    /**
     * Verifikasi venue (setujui venue)
     */
    public function verifyVenue(Request $request, $id)
    {
        $venue = Pendaftaran::findOrFail($id);

        $data = ['syarat_disetujui' => true];
        if ($request->has('komisi_tipe')) {
            $data['komisi_tipe'] = $request->input('komisi_tipe', 'none');
            $data['komisi_nilai'] = $request->input('komisi_nilai', 0);
        }
        if ($request->has('membership_komisi_tipe')) {
            $data['membership_komisi_tipe'] = $request->input('membership_komisi_tipe', 'percentage');
            $data['membership_komisi_nilai'] = $request->input('membership_komisi_nilai', 0);
        }

        $venue->update($data);

        return redirect()->route('admin.venue.list')
            ->with('success', 'Venue berhasil diverifikasi dan sudah muncul di frontend.');
    }

    /**
     * Tolak venue (ubah status menjadi tidak disetujui)
     */
    public function rejectVenue($id)
    {
        $venue = Pendaftaran::findOrFail($id);
        $venue->update(['syarat_disetujui' => false]);

        return redirect()->route('admin.venue.list')
            ->with('success', 'Venue berhasil ditolak dan tidak muncul di frontend.');
    }

    /**
     * Menampilkan daftar user dengan role 'user'
     */
    public function listUsers(Request $request)
    {
        $search = $request->get('search', '');

        $query = User::where('role', 'user');

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('BACKEND.User.list_users', compact('users', 'search'));
    }

    /**
     * Menampilkan detail user
     */
    public function showUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return view('BACKEND.User.show_user', compact('user'));
    }

    /**
     * Hapus user
     */
    public function deleteUser($id)
    {
        try {
            $user = User::where('role', 'user')->findOrFail($id);

            // Prevent deleting the currently logged in user
            if ($user->id === Auth::id()) {
                return redirect()->route('admin.users.list')
                    ->with('error', 'Tidak dapat menghapus akun yang sedang digunakan!');
            }

            $user->delete();
            return redirect()->route('admin.users.list')
                ->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.list')
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
