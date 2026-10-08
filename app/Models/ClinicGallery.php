<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'foto',
        'urutan',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function getFotoUrlAttribute()
    {
        return image_url($this->foto, asset('assets/olgasehat-icon.png'));
    }
}

