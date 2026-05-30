<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table      = 'fact_reservation';
    protected $primaryKey = 'reservation_key';
    public    $timestamps = false;         // DW tidak punya created_at/updated_at
    public    $incrementing = false;       // PK bukan auto-increment di DW

    protected $fillable = [
        'date_key', 'guest_key', 'hotel_key', 'room_key', 'channel_key',
        'nights', 'rooms_booked', 'room_revenue', 'is_cancelled',
    ];

    protected $casts = [
        'room_revenue' => 'decimal:2',
    ];

    // ── Relasi ke dimensi ──
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_key', 'hotel_key');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_key', 'room_key');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'guest_key', 'guest_key');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_key', 'channel_key');
    }

    public function dimTime()
    {
        return $this->belongsTo(DimTime::class, 'date_key', 'date_key');
    }

    // ── Alias accessors untuk kompatibilitas view lama ──

    /** Status: 'No' = active, 'Yes' = cancelled */
    public function getStatusAttribute(): string
    {
        return $this->is_cancelled === 'Yes' ? 'cancelled' : 'confirmed';
    }

    public function getTotalBayarAttribute() { return $this->room_revenue ?? 0; }
    public function getJmlMalamAttribute()   { return $this->nights ?? 0; }
    public function getJmlTamuAttribute()    { return $this->rooms_booked ?? 1; }
    public function getRatingAttribute()     { return null; }
    public function getCatatanAttribute()    { return null; }
    public function getDiskonAttribute()     { return 0; }
    public function getKodeBookingAttribute(): string { return 'RSV-' . $this->reservation_key; }

    // Tanggal check-in dari dim_time (diload saat dibutuhkan)
    public function getTglCheckinAttribute()
    {
        return $this->dimTime ? \Carbon\Carbon::parse($this->dimTime->date) : null;
    }

    public function getTglCheckoutAttribute()
    {
        $checkin = $this->getTglCheckinAttribute();
        if (!$checkin) return null;
        return $checkin->copy()->addDays($this->nights ?? 0);
    }

    // Helper scope: reservasi aktif (tidak dibatalkan)
    public function scopeActive($query)
    {
        return $query->where('is_cancelled', 'No');
    }

    // Helper scope: reservasi dibatalkan
    public function scopeCancelled($query)
    {
        return $query->where('is_cancelled', 'Yes');
    }

    // Emulasi whereIn('status', [...]) — digunakan di controller lama
    public function scopeWhereInStatus($query, array $statuses)
    {
        if (in_array('cancelled', $statuses) && !in_array('confirmed', $statuses)) {
            return $query->where('is_cancelled', 'Yes');
        }
        if (!in_array('cancelled', $statuses)) {
            return $query->where('is_cancelled', 'No');
        }
        return $query; // semua status
    }

    public static function generateKode(): string
    {
        $max = self::max('reservation_key') ?? 0;
        return 'RSV-' . str_pad($max + 1, 5, '0', STR_PAD_LEFT);
    }
}