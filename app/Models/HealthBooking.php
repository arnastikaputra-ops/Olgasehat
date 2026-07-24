<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HealthBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_booking',
        'user_id',
        'clinic_id',
        'doctor_id',
        'service_id',
        'tanggal',
        'jam',
        'durasi',
        'nama_pasien',
        'nomor_telepon',
        'email',
        'tanggal_lahir',
        'jenis_kelamin',
        'keluhan',
        'riwayat_penyakit',
        'alergi',
        'status',
        'metode_pembayaran',
        'virtual_account',
        'bank_code',
        'total_harga',
        'komisi_tipe',
        'komisi_nilai',
        'komisi_platform',
        'pendapatan_mitra',
        'status_pembayaran',
        'catatan',
        'catatan_dokter',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_lahir' => 'date',
        'total_harga' => 'decimal:2',
        'komisi_nilai' => 'decimal:2',
        'komisi_platform' => 'decimal:2',
        'pendapatan_mitra' => 'decimal:2',
    ];

    // Auto generate kode booking
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->kode_booking)) {
                $date = now()->format('Ymd');
                $random = Str::upper(Str::random(6));
                $booking->kode_booking = 'BK-' . $date . '-' . $random;
            }
        });
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Clinic
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // Relasi ke Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Relasi ke HealthService
    public function service()
    {
        return $this->belongsTo(HealthService::class, 'service_id');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'no_show' => 'secondary',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Accessor untuk status text Indonesia
    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'no_show' => 'Tidak Datang',
        ];

        return $texts[$this->status] ?? $this->status;
    }
}

