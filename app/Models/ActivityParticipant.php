<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'user_id',
        'nama_peserta',
        'bukti_pembayaran',
        'status',
        'catatan',
    ];

    // Relasi ke Activity
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
