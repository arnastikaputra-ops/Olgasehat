<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LapanganSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'lapangan_id',
        'user_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'harga',
        'harga_awal',
        'status',
        'is_promo',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_promo' => 'boolean',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk filter jadwal yang masih berlaku (belum lewat)
     */
    public function scopeValid($query)
    {
        $today = now()->startOfDay();
        
        return $query->where(function($q) use ($today) {
            // Tanggal lebih besar dari hari ini
            $q->whereDate('tanggal', '>', $today)
              // Atau tanggal sama dengan hari ini, tapi jam selesai belum lewat
              ->orWhere(function($subQ) use ($today) {
                  $subQ->whereDate('tanggal', '=', $today)
                       ->whereTime('jam_selesai', '>=', now()->format('H:i:s'));
              });
        });
    }

    /**
     * Scope untuk filter jadwal yang sudah lewat
     */
    public function scopeExpired($query)
    {
        $today = now()->startOfDay();
        $now = now();
        
        return $query->where(function($q) use ($today, $now) {
            // Tanggal lebih kecil dari hari ini
            $q->whereDate('tanggal', '<', $today)
              // Atau tanggal sama dengan hari ini, tapi jam selesai sudah lewat
              ->orWhere(function($subQ) use ($today, $now) {
                  $subQ->whereDate('tanggal', '=', $today)
                       ->whereTime('jam_selesai', '<', $now->format('H:i:s'));
              });
        });
    }
}

