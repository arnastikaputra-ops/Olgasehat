<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tipe',
        'slug',
        'deskripsi',
        'motto',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'nomor_telepon',
        'email',
        'website',
        'hari_operasional',
        'jam_buka',
        'jam_tutup',
        'logo',
        'foto_utama',
        'galeri',
        'clinic_category_id',
        'layanan_tersedia',
        'fasilitas',
        'status',
        'alasan_reject',
        'verified_at',
        'user_id',
        'komisi_tipe',
        'komisi_nilai',
        'membership_komisi_tipe',
        'membership_komisi_nilai',
        'is_membership_discount',
        'membership_discount_percent',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik_rekening',
    ];

    protected $casts = [
        'hari_operasional' => 'array',
        'galeri' => 'array',
        'layanan_tersedia' => 'array',
        'fasilitas' => 'array',
        'verified_at' => 'datetime',
        'is_membership_discount' => 'boolean',
        'membership_discount_percent' => 'float',
        'membership_komisi_nilai' => 'float',
    ];

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($clinic) {
            if (empty($clinic->slug)) {
                $clinic->slug = Str::slug($clinic->nama);
            }
        });
    }

    // Relasi ke User (pemilik/admin klinik)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke ClinicCategory
    public function category()
    {
        return $this->belongsTo(ClinicCategory::class, 'clinic_category_id');
    }

    // Relasi ke Doctors
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    // Relasi ke HealthServices
    public function services()
    {
        return $this->hasMany(HealthService::class);
    }

    // Relasi ke HealthBookings
    public function bookings()
    {
        return $this->hasMany(HealthBooking::class);
    }

    // Relasi ke ClinicGalleries
    public function galleries()
    {
        return $this->hasMany(ClinicGallery::class)->orderBy('urutan');
    }
}

