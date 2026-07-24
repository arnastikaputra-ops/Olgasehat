<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthManagerDoctorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $doctors = Doctor::whereIn('clinic_id', $clinicIds)
            ->with(['clinic'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $clinics = Clinic::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        
        return view('pemilikkesehatan.Doctor.index', compact('doctors', 'clinics'));
    }

    public function create()
    {
        $user = Auth::user();
        $clinics = Clinic::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        
        return view('pemilikkesehatan.Doctor.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
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

        // Pastikan klinik milik user
        $clinic = Clinic::where('user_id', $user->id)
            ->where('id', $request->clinic_id)
            ->firstOrFail();

        $data = $request->all();
        $data['status'] = 'approved';
        $data['aktif'] = true;

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotodokter'), $imageName);
            $data['foto'] = $imageName;
        }

        Doctor::create($data);

        return redirect()->route('pengelola.doctors.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $doctor = Doctor::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        $clinics = Clinic::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        
        return view('pemilikkesehatan.Doctor.edit', compact('doctor', 'clinics'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $doctor = Doctor::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        
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

        // Pastikan klinik milik user
        $clinic = Clinic::where('user_id', $user->id)
            ->where('id', $request->clinic_id)
            ->firstOrFail();

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($doctor->foto && file_exists(public_path('fotodokter/' . $doctor->foto))) {
                unlink(public_path('fotodokter/' . $doctor->foto));
            }
            $image = $request->file('foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('fotodokter'), $imageName);
            $data['foto'] = $imageName;
        }

        $doctor->update($data);

        return redirect()->route('pengelola.doctors.index')
            ->with('success', 'Dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $doctor = Doctor::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        
        if ($doctor->foto && file_exists(public_path('fotodokter/' . $doctor->foto))) {
            unlink(public_path('fotodokter/' . $doctor->foto));
        }
        
        $doctor->delete();

        return redirect()->route('pengelola.doctors.index')
            ->with('success', 'Dokter berhasil dihapus.');
    }
}

