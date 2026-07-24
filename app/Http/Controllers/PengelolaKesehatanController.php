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
        $user = auth()->user();
        $mitra = Mitra::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'kontak_bisnis' => 'required|string|max:20',
            'nama_bisnis' => 'required|string|max:255',
        ]);

        $mitra->update([
            'kontak_bisnis' => $request->kontak_bisnis,
            'nama_bisnis' => $request->nama_bisnis,
        ]);

        return redirect()->back()->with('success', 'Data bisnis berhasil diperbarui.');
    }
}

