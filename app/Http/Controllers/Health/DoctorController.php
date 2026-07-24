<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Doctor::with(['clinic']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan klinik
        if ($request->has('clinic_id') && $request->clinic_id) {
            $query->where('clinic_id', $request->clinic_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', '%' . $search . '%')
                  ->orWhere('spesialisasi', 'LIKE', '%' . $search . '%');
            });
        }

        $doctors = $query->orderBy('created_at', 'desc')->paginate(15);
        $clinics = Clinic::where('user_id', Auth::id())->get();

        return view('BACKEND.Health.Doctor.index', compact('doctors', 'clinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clinics = Clinic::where('user_id', Auth::id())->get();
        return view('BACKEND.Health.Doctor.create', compact('clinics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:50',
            'spesialisasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nomor_str' => 'nullable|string|max:255',
            'pendidikan' => 'nullable|string|max:255',
            'pengalaman' => 'nullable|string',
            'clinic_id' => 'required|exists:clinics,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = 'pending';

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotodokter'), $imageName);
            $data['foto'] = $imageName;
        }

        Doctor::create($data);

        return redirect()->route('health.doctors.index')
            ->with('success', 'Dokter berhasil ditambahkan dan menunggu verifikasi.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $doctor = Doctor::with(['clinic', 'schedules', 'services', 'bookings'])->findOrFail($id);
        return view('BACKEND.Health.Doctor.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $clinics = Clinic::where('user_id', Auth::id())->get();
        return view('BACKEND.Health.Doctor.edit', compact('doctor', 'clinics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:50',
            'spesialisasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nomor_str' => 'nullable|string|max:255',
            'pendidikan' => 'nullable|string|max:255',
            'pengalaman' => 'nullable|string',
            'clinic_id' => 'required|exists:clinics,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $doctor = Doctor::findOrFail($id);
        $data = $request->all();

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($doctor->foto && file_exists(public_path('fotodokter/' . $doctor->foto))) {
                unlink(public_path('fotodokter/' . $doctor->foto));
            }
            $image = $request->file('foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotodokter'), $imageName);
            $data['foto'] = $imageName;
        }

        $doctor->update($data);

        return redirect()->route('health.doctors.index')
            ->with('success', 'Dokter berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        
        // Hapus foto jika ada
        if ($doctor->foto && file_exists(public_path('fotodokter/' . $doctor->foto))) {
            unlink(public_path('fotodokter/' . $doctor->foto));
        }
        
        $doctor->delete();

        return redirect()->route('health.doctors.index')
            ->with('success', 'Dokter berhasil dihapus.');
    }
}

