<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class PengelolaKesehatanController extends Controller
{
    public function create()
    {
        return view('pemilikkesehatan.isidatakesehatan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_anda' => 'required|string|max:255',
            'nama_bisnis' => 'required|string|max:255',
            'kontak_bisnis' => 'required|string|max:20',
            'email_bisnis' => 'required|email|unique:mitras,email_bisnis',
            'tipe_venue' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user with role pengelolakesehatan
        $user = \App\Models\User::create([
            'name' => $request->nama_anda,
            'email' => $request->email_bisnis,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'pengelolakesehatan',
            'status' => 'pending',
        ]);

        Mitra::create([
            'user_id' => $user->id,
            'nama_anda' => $request->nama_anda,
            'nama_bisnis' => $request->nama_bisnis,
            'kontak_bisnis' => $request->kontak_bisnis,
            'email_bisnis' => $request->email_bisnis,
            'tipe_venue' => $request->tipe_venue,
            'tipe_mitra' => 'pengelola_kesehatan',
            'status' => 'pending',
        ]);

        return redirect('/')->with('success', 'Data berhasil dikirim untuk verifikasi. Silakan tunggu persetujuan dari Super Admin.');

    }

    public function pengaturan()
    {
        $user = auth()->user();
        $mitra = Mitra::where('user_id', optional($user)->id)->first();

        return view('pemilikkesehatan.Pengaturan.index', compact('user', 'mitra'));
    }

    public function updatePengaturan(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $mitra = Mitra::where('user_id', $user->id)->first();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'kontak_bisnis' => 'nullable|string|max:20',
            'nama_bisnis' => 'nullable|string|max:255',
            'password_lama' => 'nullable|string',
            'password_baru' => 'nullable|string|min:8',
        ]);

        // Update profile image if uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/profile_images');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $user->image = 'uploads/profile_images/' . $filename;
        }

        // Update user fields
        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        // Update password if filled
        if ($request->filled('password_lama') && $request->filled('password_baru')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->password_lama, $user->password)) {
                return redirect()->back()->withErrors(['password_lama' => 'Password lama yang Anda masukkan tidak sesuai.']);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password_baru);
        }

        $user->save();

        // Update mitra fields
        if ($mitra) {
            $mitraData = [];
            if ($request->filled('nama_bisnis')) {
                $mitraData['nama_bisnis'] = $request->nama_bisnis;
            }
            if ($request->filled('kontak_bisnis')) {
                $mitraData['kontak_bisnis'] = $request->kontak_bisnis;
            }
            if ($request->filled('name')) {
                $mitraData['nama_anda'] = $request->name;
            }
            if ($request->filled('email')) {
                $mitraData['email_bisnis'] = $request->email;
            }
            if (!empty($mitraData)) {
                $mitra->update($mitraData);
            }
        }

        return redirect()->back()->with('success', 'Pengaturan dan foto profil berhasil diperbarui.');
    }
}

