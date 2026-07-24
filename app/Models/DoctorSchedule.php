<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'durasi_konsultasi',
        'kuota_per_hari',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relasi ke Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Relasi ke Clinic
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // Accessor untuk format hari Indonesia
    public function getHariIndonesiaAttribute()
    {
        $hari = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu',
        ];

        return $hari[$this->hari] ?? $this->hari;
    }
}

