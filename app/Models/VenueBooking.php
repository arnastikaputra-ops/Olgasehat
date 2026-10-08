<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VenueBooking extends Model
{
    use HasFactory;

    protected $table = 'venue_bookings';

    protected $fillable = [
        'kode_booking',
        'user_id',
        'pendaftaran_id',
        'slot_ids',
        'nama_pemesan',
        'nomor_telepon',
        'email',
        'subtotal',
        'komisi_tipe',
        'komisi_nilai',
        'komisi_platform',
        'pendapatan_mitra',
        'total_harga',
        'metode_pembayaran',
        'bank_code',
        'virtual_account',
        'status_pembayaran',
        'catatan',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'slot_ids' => 'array',
        'subtotal' => 'decimal:2',
        'komisi_nilai' => 'decimal:2',
        'komisi_platform' => 'decimal:2',
        'pendapatan_mitra' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->kode_booking)) {
                $date = now()->format('Ymd');
                $random = Str::upper(Str::random(6));
                $booking->kode_booking = 'OLG-' . $date . '-' . $random;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function venue()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function getBuktiPembayaranUrlAttribute()
    {
        return image_url($this->bukti_pembayaran, null);
    }
}
