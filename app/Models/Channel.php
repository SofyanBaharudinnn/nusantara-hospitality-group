<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'tipe', 'platform',
        'komisi_pct', 'is_online', 'is_active',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}