<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DoctorSchedule::with(['doctor', 'clinic']);

        // Filter berdasarkan dokter
        if ($request->has('doctor_id') && $request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter berdasarkan klinik
        if ($request->has('clinic_id') && $request->clinic_id) {
            $query->where('clinic_id', $request->clinic_id);
        }

        $schedules = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(20);
        $clinics = Clinic::where('user_id', Auth::id())->get();
        $doctors = Doctor::whereIn('clinic_id', $clinics->pluck('id'))->where('aktif', true)->get();

        return view('BACKEND.Health.DoctorSchedule.index', compact('schedules', 'doctors', 'clinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clinics = Clinic::where('user_id', Auth::id())->get();
        $doctors = Doctor::whereIn('clinic_id', $clinics->pluck('id'))->where('aktif', true)->get();
        return view('BACKEND.Health.DoctorSchedule.create', compact('doctors', 'clinics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'durasi_konsultasi' => 'nullable|integer|min:15|max:120',
            'kuota_per_hari' => 'nullable|integer|min:1|max:100',
        ]);

        // Cek apakah sudah ada jadwal di hari & waktu yang sama
        $existing = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('clinic_id', $request->clinic_id)
            ->where('hari', $request->hari)
            ->where('jam_mulai', $request->jam_mulai)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal sudah ada di hari dan waktu yang sama.');
        }

        DoctorSchedule::create($request->all());

        return redirect()->route('health.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $clinics = Clinic::where('user_id', Auth::id())->get();
        $doctors = Doctor::whereIn('clinic_id', $clinics->pluck('id'))->where('aktif', true)->get();
        return view('BACKEND.Health.DoctorSchedule.edit', compact('schedule', 'doctors', 'clinics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'durasi_konsultasi' => 'nullable|integer|min:15|max:120',
            'kuota_per_hari' => 'nullable|integer|min:1|max:100',
        ]);

        $schedule = DoctorSchedule::findOrFail($id);

        // Cek apakah sudah ada jadwal di hari & waktu yang sama (kecuali jadwal ini)
        $existing = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('clinic_id', $request->clinic_id)
            ->where('hari', $request->hari)
            ->where('jam_mulai', $request->jam_mulai)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal sudah ada di hari dan waktu yang sama.');
        }

        $schedule->update($request->all());

        return redirect()->route('health.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('health.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}

