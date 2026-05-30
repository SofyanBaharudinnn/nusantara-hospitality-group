<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table      = 'dim_guest';
    protected $primaryKey = 'guest_key';
    public    $timestamps = false;

    protected $fillable = [
        'guest_id', 'guest_name', 'nationality', 'segment', 'city',
    ];

    // Alias helpers untuk kompatibilitas view lama
    public function getNamaAttribute(): string    { return $this->guest_name ?? ''; }
    public function getNegaraAttribute(): string  { return $this->nationality ?? ''; }
    public function getSegmenAttribute(): string  { return $this->segment ?? ''; }
    public function getKotaAsalAttribute(): string { return $this->city ?? ''; }

    // Kolom lama yang tidak ada di DW — kembalikan nilai default
    public function getTierAttribute(): string        { return 'silver'; }
    public function getTotalKunjunganAttribute(): int { return $this->reservations()->count(); }
    public function getTotalSpendingAttribute()       { return $this->reservations()->sum('room_revenue'); }

    public function reservations()
    {
        return $this->hasMany(Booking::class, 'guest_key', 'guest_key');
    }

    public function bookings()
    {
        return $this->reservations();
    }
}