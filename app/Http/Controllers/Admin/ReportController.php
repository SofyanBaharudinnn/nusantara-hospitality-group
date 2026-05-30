<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\DimTime;
use App\Models\Hotel;
use App\Models\Room;

class ReportController extends Controller
{
    public function index()
    {
        $reports = [
            ['icon'=>'🏨','title'=>'Laporan Okupansi Bulanan',   'description'=>'Ringkasan tingkat hunian semua properti per bulan','period'=>'Dec 2024',    'status'=>'ready'],
            ['icon'=>'💰','title'=>'Laporan Revenue',            'description'=>'Total pendapatan breakdown per properti & channel', 'period'=>'Q4 2024', 'status'=>'ready'],
            ['icon'=>'👥','title'=>'Laporan Customer Behavior',  'description'=>'Analisis segmentasi tamu, repeat rate, dan CLV',   'period'=>'Dec 2024',    'status'=>'ready'],
            ['icon'=>'📈','title'=>'Laporan Seasonal Trend',     'description'=>'Tren musiman dan perbandingan YoY',                'period'=>'Tahunan 2024','status'=>'ready'],
        ];

        // Ringkasan per hotel
        $hotelSummary = Hotel::all()->map(function ($hotel) {
            $totalKamar = Room::where('hotel_key', $hotel->hotel_key)->count();
            $revenue    = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->sum('room_revenue');
            $tamu       = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->count();
            $terisi     = $tamu; // di DW semua reservasi aktif = terisi
            $rate       = $totalKamar > 0
                         ? round(($terisi / $totalKamar) * 100, 1) : 0;

            return [
                'nama'    => $hotel->hotel_name,
                'occ'     => $rate . '%',
                'tamu'    => number_format($tamu, 0, ',', '.'),
                'revenue' => 'Rp ' . number_format($revenue / 1000000, 1, ',', '.') . ' M',
            ];
        });

        // Total
        $totalRevenue = Booking::where('is_cancelled', 'No')->sum('room_revenue');
        $totalTamu    = Booking::where('is_cancelled', 'No')->count();

        // Revenue per kuartal per hotel
        $revenueKuartal = [];
        foreach (range(1, 4) as $q) {
            $from = ($q - 1) * 3 + 1;
            $to   = $q * 3;
            $rev  = Hotel::all()->map(function ($hotel) use ($from, $to) {
                $keys = DimTime::where('year', 2024)
                            ->whereBetween('month', [$from, $to])
                            ->pluck('date_key')->toArray();
                return round(
                    Booking::where('hotel_key', $hotel->hotel_key)
                        ->where('is_cancelled', 'No')
                        ->whereIn('date_key', $keys)
                        ->sum('room_revenue') / 1000000,
                    1
                );
            })->toArray();
            $revenueKuartal[] = $rev;
        }

        $hotelNames = Hotel::all()->pluck('hotel_name')->toArray();

        return view('admin.reports', compact(
            'reports', 'hotelSummary', 'totalRevenue',
            'totalTamu', 'revenueKuartal', 'hotelNames'
        ));
    }
}