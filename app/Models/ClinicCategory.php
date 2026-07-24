<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'icon',
        'slug',
        'deskripsi',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relasi ke Clinics
    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }
}

