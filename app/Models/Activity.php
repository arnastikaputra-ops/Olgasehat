<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori',
        'lokasi',
        'biaya_bergabung',
        'harga',
        'deskripsi',
        'link_kontak',
        'banner',
        'status',
        'jenis',
        'user_id',
        'pemilik_id',
        'activity_type_id',
        'alasan_reject',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // Relasi ke User (yang membuat sebagai user biasa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke User (yang membuat sebagai pemilik lapangan)
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    // Relasi ke ActivityType
    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    // Relasi ke Participants (user yang bergabung)
    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }
}
