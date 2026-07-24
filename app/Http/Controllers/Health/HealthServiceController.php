<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\HealthService;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HealthService::with(['clinic', 'doctor']);

        // Filter berdasarkan klinik
        if ($request->has('clinic_id') && $request->clinic_id) {
            $query->where('clinic_id', $request->clinic_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', '%' . $search . '%')
                  ->orWhere('kategori', 'LIKE', '%' . $search . '%');
            });
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(15);
        $clinics = Clinic::where('status', 'approved')->get();

        return view('BACKEND.Health.HealthService.index', compact('services', 'clinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clinics = Clinic::where('user_id', Auth::id())->get();
        $doctors = Doctor::whereIn('clinic_id', $clinics->pluck('id'))->where('aktif', true)->get();
        return view('BACKEND.Health.HealthService.create', compact('clinics', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        HealthService::create($request->all());

        return redirect()->route('health.services.index')
            ->with('success', 'Layanan kesehatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = HealthService::findOrFail($id);
        $clinics = Clinic::where('user_id', Auth::id())->get();
        $doctors = Doctor::whereIn('clinic_id', $clinics->pluck('id'))->where('aktif', true)->get();
        return view('BACKEND.Health.HealthService.edit', compact('service', 'clinics', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
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

        $service = HealthService::findOrFail($id);
        $service->update($request->all());

        return redirect()->route('health.services.index')
            ->with('success', 'Layanan kesehatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $service = HealthService::findOrFail($id);
        $service->delete();

        return redirect()->route('health.services.index')
            ->with('success', 'Layanan kesehatan berhasil dihapus.');
    }
}

