<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tipe_target',
        'target_id',
        'nama',
        'ulasan',
        'rate',
        'company',
        'foto'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'target_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'target_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'target_id');
    }

    public function getFotoUrlAttribute()
    {
        return image_url($this->foto, asset('assets/olgasehat-icon.png'));
    }
}
