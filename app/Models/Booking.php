<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_booking', 'hotel_id', 'room_id',
        'customer_id', 'channel_id',
        'tgl_checkin', 'tgl_checkout',
        'jml_malam', 'jml_tamu',
        'harga_per_malam', 'total_bayar', 'diskon',
        'status', 'rating', 'catatan',
    ];

    protected $casts = [
        'tgl_checkin'     => 'date',
        'tgl_checkout'    => 'date',
        'harga_per_malam' => 'decimal:2',
        'total_bayar'     => 'decimal:2',
        'diskon'          => 'decimal:2',
    ];

    public function hotel()    { return $this->belongsTo(Hotel::class); }
    public function room()     { return $this->belongsTo(Room::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function channel()  { return $this->belongsTo(Channel::class); }

    public static function generateKode(): string
    {
        do {
            $kode = 'BK-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (self::where('kode_booking', $kode)->exists());

        return $kode;
    }
}