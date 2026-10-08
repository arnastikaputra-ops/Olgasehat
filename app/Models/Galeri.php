<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getFotoUrlAttribute()
    {
        return image_url($this->foto, asset('assets/olgasehat-icon.png'));
    }
}
