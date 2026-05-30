<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $table      = 'dim_booking_channel';
    protected $primaryKey = 'channel_key';
    public    $timestamps = false;

    protected $fillable = [
        'channel_name', 'channel_type',
    ];

    // Alias helpers untuk kompatibilitas view lama
    public function getNamaAttribute(): string { return $this->channel_name ?? ''; }
    public function getTipeAttribute(): string { return $this->channel_type ?? ''; }

    public function reservations()
    {
        return $this->hasMany(Booking::class, 'channel_key', 'channel_key');
    }

    public function bookings()
    {
        return $this->reservations();
    }
}