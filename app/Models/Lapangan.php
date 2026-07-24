<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LapanganSlot;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'nama',
    ];

    public function venue()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function slots()
    {
        return $this->hasMany(LapanganSlot::class);
    }
}

