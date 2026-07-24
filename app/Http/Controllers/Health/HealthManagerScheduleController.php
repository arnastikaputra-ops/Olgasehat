<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthManagerScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $schedules = DoctorSchedule::whereIn('clinic_id', $clinicIds)
            ->with(['doctor', 'clinic'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
        
        $doctors = Doctor::whereIn('clinic_id', $clinicIds)
            ->where('aktif', true)
            ->get();
        
        return view('pemilikkesehatan.Schedule.index', compact('schedules', 'doctors'));
    }

    public function create()
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $doctors = Doctor::whereIn('clinic_id', $clinicIds)
            ->where('aktif', true)
            ->get();
        
        $clinics = Clinic::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        
        return view('pemilikkesehatan.Schedule.create', compact('doctors', 'clinics'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'hari' => 'required|array',
            'hari.*' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'durasi_konsultasi' => 'required|integer|min:15|max:120',
        ]);

        // Pastikan dokter dan klinik milik user
        $doctor = Doctor::whereIn('clinic_id', $clinicIds)
            ->where('id', $request->doctor_id)
            ->firstOrFail();

        $clinic = Clinic::where('user_id', $user->id)
            ->where('id', $request->clinic_id)
            ->firstOrFail();

        $created = 0;
        $errors = [];

        // Generate time slots based on duration
        $startTime = strtotime($request->jam_mulai);
        $endTime = strtotime($request->jam_selesai);
        $durationMinutes = $request->durasi_konsultasi;
        $slots = [];

        while ($startTime < $endTime) {
            $slots[] = date('H:i', $startTime);
            $startTime = strtotime("+$durationMinutes minutes", $startTime);
        }

        // Loop untuk setiap hari yang dipilih
        foreach ($request->hari as $hari) {
            foreach ($slots as $slotTime) {
                // Cek duplikasi
                $existing = DoctorSchedule::where('doctor_id', $request->doctor_id)
                    ->where('clinic_id', $request->clinic_id)
                    ->where('hari', $hari)
                    ->where('jam_mulai', $slotTime)
                    ->first();

                if ($existing) {
                    $errors[] = "Jadwal untuk hari " . ucfirst($hari) . " jam " . $slotTime . " sudah ada.";
                    continue;
                }

                $slotEndTime = date('H:i', strtotime("+$durationMinutes minutes", strtotime($slotTime)));

                DoctorSchedule::create([
                    'doctor_id' => $request->doctor_id,
                    'clinic_id' => $request->clinic_id,
                    'hari' => $hari,
                    'jam_mulai' => $slotTime,
                    'jam_selesai' => $slotEndTime,
                    'durasi_konsultasi' => $request->durasi_konsultasi,
                ]);
                $created++;
            }
        }

        if ($created > 0) {
            $message = $created . ' slot jadwal dokter berhasil ditambahkan.';
            if (!empty($errors)) {
                $message .= ' ' . implode(' ', $errors);
            }
            return redirect()->route('pengelola.schedules.index')
                ->with('success', $message);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $errors));
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $schedule = DoctorSchedule::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        $doctors = Doctor::whereIn('clinic_id', $clinicIds)
            ->where('aktif', true)
            ->get();
        $clinics = Clinic::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        
        return view('pemilikkesehatan.Schedule.edit', compact('schedule', 'doctors', 'clinics'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $schedule = DoctorSchedule::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'hari' => 'required|array',
            'hari.*' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'durasi_konsultasi' => 'nullable|integer|min:15|max:120',
            'kuota_per_hari' => 'nullable|integer|min:1|max:100',
        ]);

        // Jika hanya 1 hari dipilih, update schedule yang ada
        if (count($request->hari) == 1) {
            $hari = $request->hari[0];
            
            // Cek duplikasi
            $existing = DoctorSchedule::where('doctor_id', $request->doctor_id)
                ->where('clinic_id', $request->clinic_id)
                ->where('hari', $hari)
                ->where('jam_mulai', $request->jam_mulai)
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jadwal sudah ada di hari dan waktu yang sama.');
            }

            $schedule->update([
                'doctor_id' => $request->doctor_id,
                'clinic_id' => $request->clinic_id,
                'hari' => $hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'durasi_konsultasi' => $request->durasi_konsultasi ?? 30,
                'kuota_per_hari' => $request->kuota_per_hari ?? 20,
            ]);

            return redirect()->route('pengelola.schedules.index')
                ->with('success', 'Jadwal dokter berhasil diperbarui.');
        } else {
            // Jika multiple hari, hapus yang lama dan buat yang baru
            $oldHari = $schedule->hari;
            $schedule->delete();

            $created = 0;
            $errors = [];

            foreach ($request->hari as $hari) {
                // Cek duplikasi
                $existing = DoctorSchedule::where('doctor_id', $request->doctor_id)
                    ->where('clinic_id', $request->clinic_id)
                    ->where('hari', $hari)
                    ->where('jam_mulai', $request->jam_mulai)
                    ->first();

                if ($existing) {
                    $errors[] = "Jadwal untuk hari " . ucfirst($hari) . " sudah ada di waktu yang sama.";
                    continue;
                }

                DoctorSchedule::create([
                    'doctor_id' => $request->doctor_id,
                    'clinic_id' => $request->clinic_id,
                    'hari' => $hari,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'durasi_konsultasi' => $request->durasi_konsultasi ?? 30,
                    'kuota_per_hari' => $request->kuota_per_hari ?? 20,
                ]);
                $created++;
            }

            if ($created > 0) {
                $message = $created . ' jadwal dokter berhasil diperbarui.';
                if (!empty($errors)) {
                    $message .= ' ' . implode(' ', $errors);
                }
                return redirect()->route('pengelola.schedules.index')
                    ->with('success', $message);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', implode(' ', $errors));
            }
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $schedule = DoctorSchedule::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        $schedule->delete();

        return redirect()->route('pengelola.schedules.index')
            ->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}

