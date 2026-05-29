<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'tipe', 'kota', 'provinsi',
        'bintang', 'kapasitas_total', 'alamat',
        'telepon', 'email', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function occupancyRate(): float
    {
        $total    = $this->kapasitas_total;
        $occupied = $this->bookings()
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereDate('tgl_checkin', '<=', today())
            ->whereDate('tgl_checkout', '>=', today())
            ->count();

        return $total > 0 ? round(($occupied / $total) * 100, 1) : 0;
    }
}