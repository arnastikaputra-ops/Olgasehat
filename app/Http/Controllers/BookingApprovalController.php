<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VenueBooking;
use App\Models\HealthBooking;
use App\Models\Pendaftaran;
use App\Models\Clinic;
use App\Models\LapanganSlot;
use Illuminate\Support\Facades\Auth;

class BookingApprovalController extends Controller
{
    /**
     * Approve Venue Booking by Venue Owner / Admin
     */
    public function approveVenueBooking(Request $request, $id)
    {
        $booking = VenueBooking::with('venue')->findOrFail($id);

        // Recalculate platform commission based on current venue commission settings
        $venue = $booking->venue;
        $komisiTipe = $venue->komisi_tipe ?? 'none';
        $komisiNilai = (float) ($venue->komisi_nilai ?? 0);
        $totalHarga = (float) $booking->total_harga;
        $komisiPlatform = 0;

        if ($komisiTipe === 'percentage' && $komisiNilai > 0) {
            $komisiPlatform = ($totalHarga * $komisiNilai) / 100;
        } elseif ($komisiTipe === 'fixed' && $komisiNilai > 0) {
            $komisiPlatform = min($komisiNilai, $totalHarga);
        }

        $pendapatanMitra = max(0, $totalHarga - $komisiPlatform);

        $booking->update([
            'komisi_tipe' => $komisiTipe,
            'komisi_nilai' => $komisiNilai,
            'komisi_platform' => $komisiPlatform,
            'pendapatan_mitra' => $pendapatanMitra,
            'status_pembayaran' => 'paid',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi booking berhasil disetujui (ACC)!',
                'booking' => $booking
            ]);
        }

        return redirect()->back()->with('success', 'Transaksi booking berhasil disetujui (ACC)! Pembayaran dikonfirmasi.');
    }

    /**
     * Reject Venue Booking by Venue Owner / Admin
     */
    public function rejectVenueBooking(Request $request, $id)
    {
        $booking = VenueBooking::findOrFail($id);

        $booking->update([
            'status_pembayaran' => 'rejected',
        ]);

        // Release slots back to available
        if (!empty($booking->slot_ids) && is_array($booking->slot_ids)) {
            LapanganSlot::whereIn('id', $booking->slot_ids)->update([
                'status' => 'available',
                'user_id' => null
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi booking telah ditolak.',
                'booking' => $booking
            ]);
        }

        return redirect()->back()->with('success', 'Transaksi booking telah ditolak dan slot jadwal dikembalikan.');
    }

    /**
     * Update Commission Settings for a Venue (Back Office Admin)
     */
    public function updateVenueCommission(Request $request, $id)
    {
        $request->validate([
            'komisi_tipe' => 'required|in:none,percentage,fixed',
            'komisi_nilai' => 'nullable|numeric|min:0',
        ]);

        $venue = Pendaftaran::findOrFail($id);
        $venue->komisi_tipe = $request->komisi_tipe;
        $venue->komisi_nilai = $request->input('komisi_nilai', 0);
        $venue->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan komisi platform venue berhasil diperbarui!',
                'venue' => $venue
            ]);
        }

        return redirect()->back()->with('success', 'Pengaturan komisi platform venue berhasil diperbarui!');
    }
}
