<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;

class OccupancyController extends Controller
{
    public function index()
    {
        $today      = today();
        $totalKamar = Hotel::sum('kapasitas_total');

        // ── Overall Stats ──
        $totalTerisi = Booking::whereIn('status', ['confirmed', 'completed'])
                        ->whereDate('tgl_checkin', '<=', $today)
                        ->whereDate('tgl_checkout', '>=', $today)
                        ->count();

        $overallRate = $totalKamar > 0
            ? round(($totalTerisi / $totalKamar) * 100, 1) : 0;

        $overallStats = [
            'total_kamar'  => $totalKamar,
            'total_terisi' => $totalTerisi,
            'total_kosong' => $totalKamar - $totalTerisi,
            'rate'         => $overallRate,
        ];

        // ── Per Properti ──
        $properties = Hotel::where('is_active', true)->get()->map(function ($hotel) use ($today) {
            $terisi = Booking::where('hotel_id', $hotel->id)
                        ->whereIn('status', ['confirmed', 'completed'])
                        ->whereDate('tgl_checkin', '<=', $today)
                        ->whereDate('tgl_checkout', '>=', $today)
                        ->count();

            $rate = $hotel->kapasitas_total > 0
                    ? round(($terisi / $hotel->kapasitas_total) * 100, 1) : 0;

            // Breakdown tipe kamar
            $roomTypes = Room::where('hotel_id', $hotel->id)
                ->selectRaw('tipe, count(*) as total_kamar, sum(case when is_available = 1 then 1 else 0 end) as tersedia')
                ->groupBy('tipe')
                ->get()
                ->map(fn($r) => [
                    'tipe'      => ucfirst($r->tipe),
                    'total'     => $r->total_kamar,
                    'tersedia'  => $r->tersedia,
                    'terisi'    => $r->total_kamar - $r->tersedia,
                ]);

            // Revenue bulan ini per hotel
            $revenueMtd = Booking::where('hotel_id', $hotel->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereYear('tgl_checkin', now()->year)
                ->whereMonth('tgl_checkin', now()->month)
                ->sum('total_bayar');

            return [
                'name'       => $hotel->nama,
                'kota'       => $hotel->kota,
                'bintang'    => $hotel->bintang,
                'tipe'       => $hotel->tipe,
                'occupied'   => $terisi,
                'total'      => $hotel->kapasitas_total,
                'rate'       => $rate,
                'revenue_mtd'=> $revenueMtd,
                'room_types' => $roomTypes,
            ];
        })->toArray();

        // ── Chart: 30 Hari Terakhir (global occupancy) ──
        $trend30 = ['labels' => [], 'data' => []];
        for ($i = 29; $i >= 0; $i--) {
            $date   = now()->copy()->subDays($i);
            $booked = Booking::whereIn('status', ['confirmed', 'completed'])
                        ->whereDate('tgl_checkin', '<=', $date)
                        ->whereDate('tgl_checkout', '>=', $date)
                        ->count();
            $rate = $totalKamar > 0 ? round(($booked / $totalKamar) * 100, 1) : 0;

            // Only label every 5 days to avoid crowding
            $trend30['labels'][] = ($i % 5 === 0) ? $date->format('d M') : '';
            $trend30['data'][]   = $rate;
        }

        return view('user.occupancy', compact('properties', 'overallStats', 'trend30'));
    }
}