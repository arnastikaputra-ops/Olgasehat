<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthService extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori',
        'tipe_harga',
        'harga',
        'durasi',
        'clinic_id',
        'doctor_id',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'harga' => 'decimal:2',
    ];

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

    // Relasi ke HealthBookings
    public function bookings()
    {
        return $this->hasMany(HealthBooking::class);
    }
}

