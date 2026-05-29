<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'email', 'telepon', 'segmen',
        'negara', 'kota_asal', 'tgl_lahir',
        'tier', 'total_kunjungan', 'total_spending',
    ];

    protected $casts = [
        'tgl_lahir'      => 'date',
        'total_spending' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function updateStats(): void
    {
        $this->update([
            'total_kunjungan' => $this->bookings()
                ->whereIn('status', ['confirmed', 'completed'])->count(),
            'total_spending'  => $this->bookings()
                ->whereIn('status', ['confirmed', 'completed'])->sum('total_bayar'),
        ]);
    }
}