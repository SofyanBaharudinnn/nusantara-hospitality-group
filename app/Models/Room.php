<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id', 'nomor_kamar', 'tipe',
        'kapasitas', 'harga_dasar', 'lantai',
        'fasilitas', 'is_available',
    ];

    protected $casts = [
        'harga_dasar'  => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}