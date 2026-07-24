<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class TempatSehatController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending'); // Default pending
        
        $query = Mitra::with('user')->where('tipe_mitra', 'pengelola_kesehatan');
        
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
        
        // Count untuk statistik
        $countPending = Mitra::where('tipe_mitra', 'pengelola_kesehatan')->where('status', 'pending')->count();
        $countApproved = Mitra::where('tipe_mitra', 'pengelola_kesehatan')->where('status', 'approved')->count();
        $countRejected = Mitra::where('tipe_mitra', 'pengelola_kesehatan')->where('status', 'rejected')->count();
        $countAll = Mitra::where('tipe_mitra', 'pengelola_kesehatan')->count();
        
        return view('BACKEND.Tempat Sehat.index', compact('mitras', 'status', 'countPending', 'countApproved', 'countRejected', 'countAll'));
    }

    public function verify($id)
    {
        \Log::info('TempatSehatController::verify called for ID: ' . $id);
        $mitra = Mitra::findOrFail($id);
        $mitra->update(['status' => 'approved']);
        $mitra->user->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Pengelola kesehatan berhasil diverifikasi.');
    }

    public function show($id)
    {
        $mitra = Mitra::findOrFail($id);
        return view('BACKEND.Tempat Sehat.detail', compact('mitra'));
    }

    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();

        return redirect()->back()->with('success', 'Pengelola kesehatan berhasil dihapus.');
    }
}

