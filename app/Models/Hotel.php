<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table      = 'dim_hotel';
    protected $primaryKey = 'hotel_key';
    public    $timestamps = false;

    protected $fillable = [
        'hotel_id', 'hotel_name', 'city', 'star_rating', 'hotel_type',
    ];

    // Alias helpers untuk kompatibilitas view lama
    public function getNamaAttribute(): string  { return $this->hotel_name ?? ''; }
    public function getKotaAttribute(): string  { return $this->city ?? ''; }
    public function getBintangAttribute(): int  { return $this->star_rating ?? 0; }
    public function getTipeAttribute(): string  { return $this->hotel_type ?? ''; }

    // Kapasitas total = jumlah kamar yang terdaftar di dim_room
    public function getKapasitasTotalAttribute(): int
    {
        return $this->rooms()->count();
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_key', 'hotel_key');
    }

    public function reservations()
    {
        return $this->hasMany(Booking::class, 'hotel_key', 'hotel_key');
    }

    // Alias agar controller lama bisa pakai ->bookings()
    public function bookings()
    {
        return $this->reservations();
    }

    public function occupancyRate(): float
    {
        $total    = $this->kapasitas_total;
        $occupied = $this->reservations()
            ->where('is_cancelled', 'No')
            ->count();

        return $total > 0 ? round(($occupied / $total) * 100, 1) : 0;
    }
}