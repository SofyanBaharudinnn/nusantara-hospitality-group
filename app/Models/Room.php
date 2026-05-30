<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table      = 'dim_room';
    protected $primaryKey = 'room_key';
    public    $timestamps = false;

    protected $fillable = [
        'room_id', 'hotel_key', 'room_type', 'capacity', 'base_rate',
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
    ];

    // Alias helpers untuk kompatibilitas view lama
    public function getTipeAttribute(): string  { return $this->room_type ?? ''; }
    public function getHargaDasarAttribute()    { return $this->base_rate ?? 0; }
    public function getKapasitasAttribute(): int { return $this->capacity ?? 0; }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_key', 'hotel_key');
    }

    public function reservations()
    {
        return $this->hasMany(Booking::class, 'room_key', 'room_key');
    }

    public function bookings()
    {
        return $this->reservations();
    }
}