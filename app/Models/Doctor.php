<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'gelar',
        'spesialisasi',
        'deskripsi',
        'foto',
        'nomor_str',
        'pendidikan',
        'pengalaman',
        'clinic_id',
        'status',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relasi ke Clinic
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // Relasi ke DoctorSchedules
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    // Relasi ke HealthServices
    public function services()
    {
        return $this->hasMany(HealthService::class);
    }

    // Relasi ke HealthBookings
    public function bookings()
    {
        return $this->hasMany(HealthBooking::class);
    }

    // Accessor untuk nama lengkap dengan gelar
    public function getNamaLengkapAttribute()
    {
        return ($this->gelar ? $this->gelar . ' ' : '') . $this->nama;
    }
}

