<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function create()
    {
        return view('pemiliklapangan.isidata');
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

        // Create user with role pemiliklapangan
        $user = \App\Models\User::create([
            'name' => $request->nama_anda,
            'email' => $request->email_bisnis,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'pemiliklapangan',
            'status' => 'pending',
        ]);

        Mitra::create([
            'user_id' => $user->id,
            'nama_anda' => $request->nama_anda,
            'nama_bisnis' => $request->nama_bisnis,
            'kontak_bisnis' => $request->kontak_bisnis,
            'email_bisnis' => $request->email_bisnis,
            'tipe_venue' => $request->tipe_venue,
            'status' => 'pending',
        ]);

        return redirect('/')->with('success', 'Data berhasil dikirim untuk verifikasi.');

    }

    public function pengaturan()
    {
        $user = auth()->user();
        $mitra = Mitra::where('user_id', optional($user)->id)->first();

        return view('pemiliklapangan.Pengaturan.index', compact('user', 'mitra'));
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

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending'); // Default pending
        
        $query = Mitra::with('user')->where(function($q) {
            $q->whereNull('tipe_mitra')->orWhere('tipe_mitra', '!=', 'pengelola_kesehatan');
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
                $q->where('nama_bisnis', 'LIKE', '%' . $search . '%')
                  ->orWhere('email_bisnis', 'LIKE', '%' . $search . '%')
                  ->orWhere('tipe_venue', 'LIKE', '%' . $search . '%')
                  ->orWhere('nama_anda', 'LIKE', '%' . $search . '%');
            });
        }
        
        $mitras = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Count untuk statistik Pemilik Lapangan
        $venueMitraQuery = fn() => Mitra::where(function($q) {
            $q->whereNull('tipe_mitra')->orWhere('tipe_mitra', '!=', 'pengelola_kesehatan');
        });

        $countPending = $venueMitraQuery()->where('status', 'pending')->count();
        $countApproved = $venueMitraQuery()->where('status', 'approved')->count();
        $countRejected = $venueMitraQuery()->where('status', 'rejected')->count();
        $countAll = $venueMitraQuery()->count();
        
        return view('BACKEND.Verifikasi Mitra.datapemiliklapangan', compact('mitras', 'status', 'countPending', 'countApproved', 'countRejected', 'countAll'));
    }

    public function verify(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);

        $data = ['status' => 'approved'];
        if ($request->has('komisi_tipe')) {
            $data['komisi_tipe'] = $request->input('komisi_tipe', 'none');
            $data['komisi_nilai'] = $request->input('komisi_nilai', 0);
        }

        $mitra->update($data);
        if ($mitra->user) {
            $mitra->user->update(['status' => 'approved']);
            
            // Juga update venue (Pendaftaran) jika ada
            \App\Models\Pendaftaran::where('user_id', $mitra->user_id)->update([
                'syarat_disetujui' => true,
                'komisi_tipe' => $data['komisi_tipe'] ?? 'none',
                'komisi_nilai' => $data['komisi_nilai'] ?? 0,
            ]);
        }

        return redirect()->back()->with('success', 'Mitra berhasil diverifikasi.');
    }

    public function show($id)
    {
        $mitra = Mitra::findOrFail($id);
        return view('BACKEND.Verifikasi Mitra.detail', compact('mitra'));
    }

    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();

        return redirect()->back()->with('success', 'Mitra berhasil dihapus.');
    }
}
