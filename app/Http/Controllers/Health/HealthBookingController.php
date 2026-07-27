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
        $user = Auth::user();
        $query = HealthBooking::with(['user', 'clinic', 'doctor', 'service']);

        // Stats counts
        $countPending = HealthBooking::whereIn('status', ['pending', 'pending_acc'])->orWhere('status_pembayaran', 'pending_acc')->count();
        $countConfirmed = HealthBooking::whereIn('status', ['confirmed', 'approved'])->count();
        $countCompleted = HealthBooking::where('status', 'completed')->count();
        $countTotal = HealthBooking::count();

        // Filter status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where(function($q) {
                    $q->whereIn('status', ['pending', 'pending_acc'])->orWhere('status_pembayaran', 'pending_acc');
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter klinik
        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }

        // Filter dokter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_booking', 'LIKE', '%' . $search . '%')
                  ->orWhere('nama_pasien', 'LIKE', '%' . $search . '%')
                  ->orWhere('nomor_telepon', 'LIKE', '%' . $search . '%');
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        if ($user && $user->role === 'admin') {
            $clinics = Clinic::all();
            $doctors = Doctor::where('aktif', true)->get();
        } else {
            $clinics = Clinic::where('user_id', Auth::id())->get();
            $doctors = Doctor::whereIn('clinic_id', $clinics->pluck('id'))->where('aktif', true)->get();
        }

        return view('BACKEND.Health.HealthBooking.index', compact(
            'bookings', 'clinics', 'doctors', 
            'countPending', 'countConfirmed', 'countCompleted', 'countTotal'
        ));
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
     * Update booking status (Verifikasi ACC / Tolak / Selesai oleh Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'alasan_reject' => 'nullable|string',
            'catatan_dokter' => 'nullable|string',
        ]);

        $booking = HealthBooking::findOrFail($id);
        $status = $request->status;

        if (in_array($status, ['confirmed', 'approved'])) {
            $booking->status = 'confirmed';
            $booking->status_pembayaran = 'paid';
        } elseif (in_array($status, ['rejected', 'cancelled', 'ditolak'])) {
            $booking->status = 'cancelled';
            $booking->status_pembayaran = 'rejected';
            if ($request->filled('alasan_reject')) {
                $booking->alasan_reject = $request->alasan_reject;
            }
        } elseif ($status === 'completed') {
            $booking->status = 'completed';
            $booking->status_pembayaran = 'paid';
        } else {
            $booking->status = $status;
        }

        if ($request->filled('catatan_dokter')) {
            $booking->catatan_dokter = $request->catatan_dokter;
        }

        $booking->save();

        return redirect()->back()->with('success', 'Status booking janji klinik berhasil diperbarui!');
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

