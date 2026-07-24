<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Clinic::with(['user']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tipe
        if ($request->has('tipe') && $request->tipe) {
            $query->where('tipe', $request->tipe);
        }


        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', '%' . $search . '%')
                  ->orWhere('alamat', 'LIKE', '%' . $search . '%')
                  ->orWhere('kota', 'LIKE', '%' . $search . '%');
            });
        }

        // Get counts for statistics (before pagination)
        $countPending = Clinic::where('status', 'pending')->count();
        $countApproved = Clinic::where('status', 'approved')->count();
        $countRejected = Clinic::where('status', 'rejected')->count();
        
        $clinics = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('BACKEND.Health.Clinic.index', compact('clinics', 'countPending', 'countApproved', 'countRejected'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('BACKEND.Health.Clinic.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:klinik,layanan',
            'deskripsi' => 'nullable|string',
            'motto' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'hari_operasional' => 'nullable|array',
            'jam_buka' => 'nullable',
            'jam_tutup' => 'nullable',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->nama);
        $data['status'] = 'pending';

        if ($request->has('fasilitas')) {
            $fasilitas = array_filter(array_map('trim', $request->fasilitas));
            $data['fasilitas'] = array_values($fasilitas);
        }

        // Handle upload logo
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '_logo_' . $image->getClientOriginalName();
            $image->move(public_path('fotoklinik'), $imageName);
            $data['logo'] = $imageName;
        }

        // Handle upload foto utama
        if ($request->hasFile('foto_utama')) {
            $image = $request->file('foto_utama');
            $imageName = time() . '_utama_' . $image->getClientOriginalName();
            $image->move(public_path('fotoklinik'), $imageName);
            $data['foto_utama'] = $imageName;
        }

        Clinic::create($data);

        return redirect()->route('health.clinics.index')
            ->with('success', 'Klinik berhasil ditambahkan dan menunggu verifikasi.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $clinic = Clinic::with(['user', 'doctors', 'services', 'bookings'])->findOrFail($id);
        return view('BACKEND.Health.Clinic.show', compact('clinic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $clinic = Clinic::findOrFail($id);
        return view('BACKEND.Health.Clinic.edit', compact('clinic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:klinik,layanan',
            'deskripsi' => 'nullable|string',
            'motto' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'hari_operasional' => 'nullable|array',
            'jam_buka' => 'nullable',
            'jam_tutup' => 'nullable',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'komisi_tipe' => 'nullable|in:none,percentage,fixed',
            'komisi_nilai' => 'nullable|numeric|min:0',
        ]);

        $clinic = Clinic::findOrFail($id);
        $data = $request->all();
        $data['slug'] = Str::slug($request->nama);

        if ($request->has('fasilitas')) {
            $fasilitas = array_filter(array_map('trim', $request->fasilitas));
            $data['fasilitas'] = array_values($fasilitas);
        }

        // Handle upload logo
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($clinic->logo && file_exists(public_path('fotoklinik/' . $clinic->logo))) {
                unlink(public_path('fotoklinik/' . $clinic->logo));
            }
            $image = $request->file('logo');
            $imageName = time() . '_logo_' . $image->getClientOriginalName();
            $image->move(public_path('fotoklinik'), $imageName);
            $data['logo'] = $imageName;
        }

        // Handle upload foto utama
        if ($request->hasFile('foto_utama')) {
            // Hapus foto lama jika ada
            if ($clinic->foto_utama && file_exists(public_path('fotoklinik/' . $clinic->foto_utama))) {
                unlink(public_path('fotoklinik/' . $clinic->foto_utama));
            }
            $image = $request->file('foto_utama');
            $imageName = time() . '_utama_' . $image->getClientOriginalName();
            $image->move(public_path('fotoklinik'), $imageName);
            $data['foto_utama'] = $imageName;
        }

        $clinic->update($data);

        return redirect()->route('health.clinics.index')
            ->with('success', 'Klinik berhasil diperbarui.');
    }

    /**
     * Approve clinic
     */
    public function approve(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);
        
        // If already approved, reset to pending
        if ($clinic->status == 'approved') {
            $clinic->status = 'pending';
            $clinic->verified_at = null;
            $clinic->save();
            return redirect()->back()->with('success', 'Status klinik diubah menjadi pending.');
        }
        
        $clinic->status = 'approved';
        $clinic->verified_at = now();
        $clinic->alasan_reject = null; // Clear rejection reason if any
        if ($request->has('komisi_tipe')) {
            $clinic->komisi_tipe = $request->input('komisi_tipe', 'none');
            $clinic->komisi_nilai = $request->input('komisi_nilai', 0);
        }
        $clinic->save();

        return redirect()->back()->with('success', 'Klinik berhasil disetujui.');
    }

    /**
     * Reject clinic
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:500',
        ]);

        $clinic = Clinic::findOrFail($id);
        $clinic->status = 'rejected';
        $clinic->alasan_reject = $request->alasan_reject;
        $clinic->save();

        return redirect()->back()->with('success', 'Klinik ditolak.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);
        
        // Hapus foto jika ada
        if ($clinic->logo && file_exists(public_path('fotoklinik/' . $clinic->logo))) {
            unlink(public_path('fotoklinik/' . $clinic->logo));
        }
        if ($clinic->foto_utama && file_exists(public_path('fotoklinik/' . $clinic->foto_utama))) {
            unlink(public_path('fotoklinik/' . $clinic->foto_utama));
        }
        
        $clinic->delete();

        return redirect()->route('health.clinics.index')
            ->with('success', 'Klinik berhasil dihapus.');
    }
}

