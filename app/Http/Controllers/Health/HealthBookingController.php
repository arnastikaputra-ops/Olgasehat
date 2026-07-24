<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\HealthBooking;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HealthBooking::with(['user', 'clinic', 'doctor', 'service']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan klinik
        if ($request->has('clinic_id') && $request->clinic_id) {
            $query->where('clinic_id', $request->clinic_id);
        }

        // Filter berdasarkan dokter
        if ($request->has('doctor_id') && $request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal') && $request->tanggal) {
            $query->where('tanggal', $request->tanggal);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_booking', 'LIKE', '%' . $search . '%')
                  ->orWhere('nama_pasien', 'LIKE', '%' . $search . '%')
                  ->orWhere('nomor_telepon', 'LIKE', '%' . $search . '%');
            });
        }

        $bookings = $query->orderBy('tanggal', 'desc')->orderBy('jam', 'desc')->paginate(20);
        $clinics = Clinic::where('user_id', Auth::id())->get();
        $doctors = Doctor::whereIn('clinic_id', $clinics->pluck('id'))->where('aktif', true)->get();

        return view('BACKEND.Health.HealthBooking.index', compact('bookings', 'clinics', 'doctors'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $booking = HealthBooking::with(['user', 'clinic', 'doctor', 'service'])->findOrFail($id);
        return view('BACKEND.Health.HealthBooking.show', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled,no_show',
            'catatan_dokter' => 'nullable|string',
        ]);

        $booking = HealthBooking::findOrFail($id);
        $booking->status = $request->status;
        if ($request->catatan_dokter) {
            $booking->catatan_dokter = $request->catatan_dokter;
        }
        $booking->save();

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booking = HealthBooking::findOrFail($id);
        $booking->delete();

        return redirect()->route('health.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}

