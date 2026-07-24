<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\HealthService;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthManagerServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $services = HealthService::whereIn('clinic_id', $clinicIds)
            ->with(['clinic', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pemilikkesehatan.Service.index', compact('services'));
    }

    public function create()
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $clinics = Clinic::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        
        $doctors = Doctor::whereIn('clinic_id', $clinicIds)
            ->where('aktif', true)
            ->get();
        
        return view('pemilikkesehatan.Service.create', compact('clinics', 'doctors'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|max:255',
            'tipe_harga' => 'required|in:gratis,berbayar',
            'harga' => 'nullable|numeric|min:0|required_if:tipe_harga,berbayar',
            'durasi' => 'nullable|integer|min:15|max:480',
            'clinic_id' => 'required|exists:clinics,id',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        // Pastikan klinik milik user
        $clinic = Clinic::where('user_id', $user->id)
            ->where('id', $request->clinic_id)
            ->firstOrFail();

        HealthService::create($request->all());

        return redirect()->route('pengelola.services.index')
            ->with('success', 'Layanan kesehatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $service = HealthService::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        $clinics = Clinic::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        $doctors = Doctor::whereIn('clinic_id', $clinicIds)
            ->where('aktif', true)
            ->get();
        
        return view('pemilikkesehatan.Service.edit', compact('service', 'clinics', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $service = HealthService::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string|max:255',
            'tipe_harga' => 'required|in:gratis,berbayar',
            'harga' => 'nullable|numeric|min:0|required_if:tipe_harga,berbayar',
            'durasi' => 'nullable|integer|min:15|max:480',
            'clinic_id' => 'required|exists:clinics,id',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $service->update($request->all());

        return redirect()->route('pengelola.services.index')
            ->with('success', 'Layanan kesehatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $service = HealthService::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        $service->delete();

        return redirect()->route('pengelola.services.index')
            ->with('success', 'Layanan kesehatan berhasil dihapus.');
    }
}

