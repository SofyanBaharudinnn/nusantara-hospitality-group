<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DimTime;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class OccupancyController extends Controller
{
    public function index()
    {
        $totalKamar  = Room::count();

        // ── Overall Stats ──
        $totalTerisi = Booking::where('is_cancelled', 'No')->count();
        $overallRate = $totalKamar > 0
            ? round(($totalTerisi / $totalKamar) * 100, 1) : 0;

        $overallStats = [
            'total_kamar'  => $totalKamar,
            'total_terisi' => $totalTerisi,
            'total_kosong' => max(0, $totalKamar - $totalTerisi),
            'rate'         => $overallRate,
        ];

        // ── Per Properti ──
        $properties = Hotel::all()->map(function ($hotel) {
            $totalKamarHotel = Room::where('hotel_key', $hotel->hotel_key)->count();
            $terisi = Booking::where('hotel_key', $hotel->hotel_key)
                        ->where('is_cancelled', 'No')
                        ->count();
            $rate = $totalKamarHotel > 0
                    ? round(($terisi / $totalKamarHotel) * 100, 1) : 0;

            // Breakdown tipe kamar per hotel
            $roomTypes = Room::where('hotel_key', $hotel->hotel_key)
                ->select('room_type', DB::raw('count(*) as total_kamar'))
                ->groupBy('room_type')
                ->get()
                ->map(fn($r) => [
                    'tipe'     => ucfirst($r->room_type),
                    'total'    => $r->total_kamar,
                    'tersedia' => $r->total_kamar, // DW tidak punya is_available
                    'terisi'   => 0,
                ]);

            // Revenue bulan ini per hotel
            $keysThisMonth = DimTime::keysForMonth(2024, 12);
            $revenueMtd = Booking::where('hotel_key', $hotel->hotel_key)
                ->where('is_cancelled', 'No')
                ->whereIn('date_key', $keysThisMonth)
                ->sum('room_revenue');

            return [
                'name'        => $hotel->hotel_name,
                'kota'        => $hotel->city,
                'bintang'     => $hotel->star_rating,
                'tipe'        => $hotel->hotel_type,
                'occupied'    => $terisi,
                'total'       => $totalKamarHotel,
                'rate'        => $rate,
                'revenue_mtd' => $revenueMtd,
                'room_types'  => $roomTypes,
            ];
        })->toArray();

        // ── Chart: 7 Bulan Terakhir (DW tidak punya data harian eksplisit) ──
        $trend30 = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date  = \Carbon\Carbon::create(2024, 12, 1)->subMonths($i);
            $keys  = DimTime::keysForMonth($date->year, $date->month);
            $booked = Booking::where('is_cancelled', 'No')
                        ->whereIn('date_key', $keys)
                        ->count();
            $rate = $totalKamar > 0 ? round(($booked / $totalKamar) * 100, 1) : 0;
            $trend30['labels'][] = $date->format('M Y');
            $trend30['data'][]   = $rate;
        }

        return view('user.occupancy', compact('properties', 'overallStats', 'trend30'));
    }
}