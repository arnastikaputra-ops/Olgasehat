<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'foto',
        'urutan',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function getFotoUrlAttribute()
    {
        return image_url($this->foto, asset('assets/olgasehat-icon.png'));
    }
}

