<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\HealthBooking;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthManagerBookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $query = HealthBooking::whereIn('clinic_id', $clinicIds)
            ->with(['user', 'clinic', 'doctor', 'service']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan klinik
        if ($request->has('clinic_id') && $request->clinic_id) {
            $query->where('clinic_id', $request->clinic_id);
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

        $bookings = $query->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->paginate(20);
        
        $clinics = Clinic::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        return view('pemilikkesehatan.Booking.index', compact('bookings', 'clinics'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $booking = HealthBooking::whereIn('clinic_id', $clinicIds)
            ->with(['user', 'clinic', 'doctor', 'service'])
            ->findOrFail($id);
        
        return view('pemilikkesehatan.Booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $clinicIds = Clinic::where('user_id', $user->id)->pluck('id');
        
        $booking = HealthBooking::whereIn('clinic_id', $clinicIds)->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled,no_show',
            'catatan_dokter' => 'nullable|string',
        ]);

        $booking->status = $request->status;
        if ($request->catatan_dokter) {
            $booking->catatan_dokter = $request->catatan_dokter;
        }
        $booking->save();

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui.');
    }
}

