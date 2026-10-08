<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LapanganSlot;
use App\Models\HealthBooking;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update google_id and avatar if missing
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $user->avatar ?? $googleUser->getAvatar(),
                ]);
            } else {
                // Register new user with Google details
                $user = User::create([
                    'name'      => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Pengguna Google'),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'password'  => Hash::make(Str::random(24)),
                    'role'      => 'user',
                    'status'    => 'approved',
                ]);
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect('/')->with('success', 'Berhasil masuk dengan akun Google!');
        } catch (\Exception $e) {
            return redirect('/loginuser')->withErrors([
                'email' => 'Gagal login dengan Google: ' . $e->getMessage()
            ]);
        }
    }

    public function login()
    {
        return view('Backend.login');
    }

    public function loginproses(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // penting untuk keamanan

            $user = Auth::user();

            // Redirect based on role
            if ($user->role === 'superadmin') {
                return redirect('/dashboard');
            } elseif ($user->role === 'user') {
                return redirect('/dashboarduser');
            } elseif ($user->role === 'pemiliklapangan') {
                if ($user->status === 'approved') {
                    return redirect('/pemiliklapangan/dashboard');
                } else {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akun Anda belum disetujui oleh Super Admin.',
                    ]);
                }
            } elseif ($user->role === 'pengelolakesehatan') {
                if ($user->status === 'approved') {
                    return redirect('/pengelolakesehatan/dashboard');
                } else {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akun Anda belum disetujui oleh Super Admin.',
                    ]);
                }
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Unauthorized role.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ]);
    }

    // Registration form for User role
    public function registerUserForm()
    {
        return view('user.daftaruser');
    }

    // Handle User registration
    public function registerUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'     => 'user',
        ]);

       return redirect('/loginemail')->with('success', 'Akun anda berhasil terdaftar.');
    }

    // Registration form for Pemilik Lapangan role
    public function registerPemilikForm()
    {
        return view('pemiliklapangan.regispengelola');
    }

    // Handle Pemilik Lapangan registration
    public function registerPemilik(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'     => 'pemiliklapangan',
            'status'   => 'pending',
        ]);

        return redirect('/loginpengelolavenue')->with('success', 'Registrasi berhasil. Akun Anda sedang dalam proses verifikasi oleh Super Admin. Silakan login setelah disetujui.');
    }

    public function logout(){
        $user = Auth::user();
        $role = $user ? $user->role : null;
        Auth::logout();
        if ($role === 'pemiliklapangan' || $role === 'pengelolakesehatan') {
            return redirect('/')->with('success', 'Anda Berhasil Log Out');
        } else {
            return redirect('login');
        }
    }

    public function userLogout(){
        Auth::logout();
        return redirect('/')->with('success', 'Akun anda telah logout');
    }

    // Show edit profile form for user role
    public function editProfile()
    {
        $user = Auth::user();
        
        // Dynamic counts for user dashboard
        $bookingCount = LapanganSlot::where('user_id', $user->id)->where('status', 'booked')->count();
        $healthBookingCount = HealthBooking::where('user_id', $user->id)->count();
        
        $communityCount = ActivityParticipant::where('user_id', $user->id)
            ->whereHas('activity', function($q) {
                $q->where('jenis', 'komunitas');
            })
            ->where('status', 'approved')
            ->count();
            
        $membershipCount = ActivityParticipant::where('user_id', $user->id)
            ->whereHas('activity', function($q) {
                $q->where('jenis', 'membership')
                  ->orWhereHas('activityType', function($at) {
                      $at->where('name', 'klub');
                  });
            })
            ->where('status', 'approved')
            ->count();

        return view('user.dashboarduser', compact('user', 'bookingCount', 'healthBookingCount', 'communityCount', 'membershipCount'));
    }

    public function riwayatPayment()
    {
        $user = Auth::user();
        $venueBookings = \App\Models\VenueBooking::with('venue')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $healthBookings = \App\Models\HealthBooking::with(['clinic', 'doctor', 'service'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.riwayatpayment', compact('user', 'venueBookings', 'healthBookings'));
    }

    public function riwayatKontrol(Request $request)
    {
        $user = Auth::user();
        $query = HealthBooking::where('user_id', $user->id)
            ->with(['clinic', 'doctor', 'service']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('kode_booking', 'like', "%{$q}%")
                    ->orWhere('nama_pasien', 'like', "%{$q}%")
                    ->orWhereHas('clinic', fn($c) => $c->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('doctor', fn($d) => $d->where('nama', 'like', "%{$q}%")->orWhere('nama_lengkap', 'like', "%{$q}%"))
                    ->orWhereHas('service', fn($s) => $s->where('nama', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('kategori')) {
            $kat = $request->kategori;
            $query->whereHas('service', fn($s) => $s->where('kategori', 'like', "%{$kat}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $healthBookings = $query->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->get();

        return view('user.riwayatkontrol', compact('user', 'healthBookings'));
    }

    public function rescheduleBooking(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required',
        ]);

        $user = Auth::user();
        $booking = HealthBooking::where('user_id', $user->id)->findOrFail($id);

        $booking->update([
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'status' => 'pending', // Reset status so clinic needs to reconfirm
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal kontrol berhasil diperbarui! Menunggu konfirmasi ulang dari pihak klinik.'
        ]);
    }

    public function riwayatMembership()
    {
        $user = Auth::user();
        $memberships = ActivityParticipant::where('user_id', $user->id)
            ->whereHas('activity', function($q) {
                $q->where('jenis', 'membership')
                  ->orWhereHas('activityType', function($at) {
                      $at->where('name', 'klub');
                  });
            })
            ->with(['activity.user', 'activity.pemilik'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.riwayatmembership', compact('user', 'memberships'));
    }

    // Handle profile update for user role
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|max:255',
            'username' => 'nullable|max:255',
            'birth_month' => 'nullable|max:20',
            'birth_year' => 'nullable|digits:4',
            'birth_day' => 'nullable|digits_between:1,2',
            'phone' => 'nullable|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $user->image; // Keep existing image if no new one uploaded

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image && file_exists(public_path('storage/' . $user->image))) {
                unlink(public_path('storage/' . $user->image));
            }

            // Store new image
            $imagePath = $request->file('image')->store('profile_images', 'public');
        }

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'] ?? $user->username,
            'birth_month' => $validated['birth_month'] ?? $user->birth_month,
            'birth_year' => $validated['birth_year'] ?? $user->birth_year,
            'birth_day' => $validated['birth_day'] ?? $user->birth_day,
            'phone' => $validated['phone'] ?? $user->phone,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function akun(Request $request)
    {
        if ($request->has('search')) {
            $data = User::where(function($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                      ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                      ->orWhere('role', 'LIKE', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        } else {
            $data = User::orderBy('created_at', 'desc')->paginate(5);
        }

        return view('Backend.Account.akun', compact('data'));
    }

    public function add()
    {
        return view('Backend.Account.add');
    }

    // Simpan user baru
    public function insertacc(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:superadmin,pemiliklapangan,pengelolakesehatan,user',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'status'   => 'approved',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('akun')->with('success', 'User berhasil ditambahkan!');
    }

    public function tampilkanacc($id)
    {
        $data = User::findOrFail($id);
        return view('Backend.Account.edit', compact('data'));
    }

    // Update user
    public function updateacc(Request $request, $id)
    {
        $data = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'required|in:superadmin,pemiliklapangan,pengelolakesehatan,user',
            'password' => 'nullable|min:6',
        ]);

        $updateData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $data->update($updateData);

        return redirect()->route('akun')->with('update', 'User berhasil diperbarui!');
    }

    // Delete user
    public function deleteacc($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting the currently logged in user
            if ($user->id === Auth::id()) {
                return redirect()->route('akun')->with('error', 'Tidak dapat menghapus akun yang sedang digunakan!');
            }

            $user->delete();
            return redirect()->route('akun')->with('delete', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('akun')->with('error', 'Gagal menghapus user!');
        }
    }

    public function dashboard()
    {
        return app(AdminController::class)->dashboard();
    }
}
