<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Hotel;

class ReportController extends Controller
{
    public function index()
    {
        $reports = [
            ['icon'=>'🏨','title'=>'Laporan Okupansi Bulanan',   'description'=>'Ringkasan tingkat hunian semua properti per bulan','period'=>now()->format('M Y'),    'status'=>'ready'],
            ['icon'=>'💰','title'=>'Laporan Revenue',            'description'=>'Total pendapatan breakdown per properti & channel', 'period'=>'Q' . now()->quarter . ' ' . now()->year, 'status'=>'ready'],
            ['icon'=>'👥','title'=>'Laporan Customer Behavior',  'description'=>'Analisis segmentasi tamu, repeat rate, dan CLV',   'period'=>now()->format('M Y'),    'status'=>'ready'],
            ['icon'=>'📈','title'=>'Laporan Seasonal Trend',     'description'=>'Tren musiman dan perbandingan YoY',                'period'=>'Tahunan ' . now()->year,'status'=>'ready'],
        ];

        // Ringkasan per hotel
        $hotelSummary = Hotel::where('is_active', true)->get()->map(function ($hotel) {
            $bookings  = Booking::where('hotel_id', $hotel->id)
                            ->whereIn('status', ['confirmed','completed']);
            $revenue   = $bookings->sum('total_bayar');
            $tamu      = $bookings->count();
            $today     = today();
            $terisi    = Booking::where('hotel_id', $hotel->id)
                            ->whereIn('status', ['confirmed','completed'])
                            ->whereDate('tgl_checkin', '<=', $today)
                            ->whereDate('tgl_checkout', '>=', $today)
                            ->count();
            $rate      = $hotel->kapasitas_total > 0
                         ? round(($terisi / $hotel->kapasitas_total) * 100, 1) : 0;

            return [
                'nama'    => $hotel->nama,
                'occ'     => $rate . '%',
                'tamu'    => number_format($tamu, 0, ',', '.'),
                'revenue' => 'Rp ' . number_format($revenue / 1000000, 1, ',', '.') . ' M',
            ];
        });

        // Total
        $totalRevenue = Booking::whereIn('status', ['confirmed','completed'])->sum('total_bayar');
        $totalTamu    = Booking::whereIn('status', ['confirmed','completed'])->count();

        // Revenue per kuartal
        $revenueKuartal = [];
        foreach (range(1, 4) as $q) {
            $from = ($q - 1) * 3 + 1;
            $to   = $q * 3;
            $rev  = Hotel::where('is_active', true)->get()->map(function ($hotel) use ($from, $to) {
                return round(
                    Booking::where('hotel_id', $hotel->id)
                        ->whereIn('status', ['confirmed','completed'])
                        ->whereYear('tgl_checkin', now()->year)
                        ->whereMonth('tgl_checkin', '>=', $from)
                        ->whereMonth('tgl_checkin', '<=', $to)
                        ->sum('total_bayar') / 1000000,
                    1
                );
            })->toArray();
            $revenueKuartal[] = $rev;
        }

        $hotelNames = Hotel::where('is_active', true)->pluck('nama')->toArray();

        return view('admin.reports', compact(
            'reports', 'hotelSummary', 'totalRevenue',
            'totalTamu', 'revenueKuartal', 'hotelNames'
        ));
    }
}