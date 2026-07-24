<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lapangan;

class Pendaftaran extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'kategori' => 'array',
        'fasilitas' => 'array',
        'jam_operasional' => 'array',
    ];

    public function galleries()
    {
        return $this->hasMany(VenueGallery::class)->orderBy('urutan');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'user_id', 'user_id');
    }

    public function lapangans()
    {
        return $this->hasMany(Lapangan::class);
    }

    public function bookings()
    {
        return $this->hasMany(VenueBooking::class, 'pendaftaran_id');
    }
}
