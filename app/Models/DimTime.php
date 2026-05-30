<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimTime extends Model
{
    protected $table      = 'dim_time';
    protected $primaryKey = 'date_key';
    public    $timestamps = false;

    protected $fillable = [
        'date', 'year', 'quarter', 'month', 'month_name',
        'day', 'day_of_week', 'is_weekend',
    ];

    public function reservations()
    {
        return $this->hasMany(Booking::class, 'date_key', 'date_key');
    }

    /**
     * Cari date_key berdasarkan tahun dan bulan.
     * Mengembalikan array of date_keys untuk bulan tersebut.
     */
    public static function keysForMonth(int $year, int $month): array
    {
        return self::where('year', $year)
            ->where('month', $month)
            ->pluck('date_key')
            ->toArray();
    }

    /**
     * Cari date_keys dalam rentang tanggal.
     */
    public static function keysInRange(string $from, string $to): array
    {
        return self::whereBetween('date', [$from, $to])
            ->pluck('date_key')
            ->toArray();
    }
}
