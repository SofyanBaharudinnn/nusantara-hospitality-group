<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DimTime;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OccupancyController extends Controller
{
    public function index(Request $request)
    {
        $year    = $request->input('year', 2024);
        $month   = $request->input('month', null);
        $hotelId = $request->input('hotel', null);

        // ── Data per Properti ──
        $hotels = Hotel::all()->map(function ($hotel) {
            $totalKamar = Room::where('hotel_key', $hotel->hotel_key)->count();
            $terisi     = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->count();
            $rate       = $totalKamar > 0
                ? round(($terisi / $totalKamar) * 100, 1) : 0;
            $revenue    = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->sum('room_revenue');
            $avgRevPer  = $terisi > 0 ? ($revenue / $terisi) : 0;

            return [
                'id'      => $hotel->hotel_key,
                'nama'    => $hotel->hotel_name,
                'kamar'   => $terisi . '/' . $totalKamar,
                'rate'    => $rate,
                'adr'     => 'Rp ' . number_format($avgRevPer, 0, ',', '.'),
                'revpar'  => 'Rp ' . number_format($avgRevPer * ($rate / 100), 0, ',', '.'),
                'revenue' => 'Rp ' . number_format($revenue, 0, ',', '.'),
            ];
        });

        // ── Chart Bulanan per Properti ──
        $chartData = Hotel::all()->map(function ($hotel) use ($year) {
            $totalKamar = Room::where('hotel_key', $hotel->hotel_key)->count();
            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $keys   = DimTime::keysForMonth($year, $m);
                $booked = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->whereIn('date_key', $keys)
                            ->count();
                $data[] = $totalKamar > 0
                    ? round(($booked / $totalKamar) * 100, 1) : 0;
            }
            return ['nama' => $hotel->hotel_name, 'data' => $data];
        });

        // ── Tipe Kamar ──
        $roomTypes = Booking::join('dim_room', 'fact_reservation.room_key', '=', 'dim_room.room_key')
                        ->select('dim_room.room_type', DB::raw('count(*) as total'))
                        ->where('fact_reservation.is_cancelled', 'No')
                        ->groupBy('dim_room.room_type')
                        ->pluck('total', 'room_type');

        // ── Booking Terbaru ──
        $query = Booking::with(['hotel', 'room', 'customer', 'channel'])
                        ->latest('reservation_key');
        if ($hotelId) $query->where('hotel_key', $hotelId);
        $bookings = $query->limit(15)->get();

        $hotelList = Hotel::all();

        return view('admin.occupancy', compact(
            'hotels', 'chartData', 'roomTypes',
            'bookings', 'hotelList', 'year', 'month', 'hotelId'
        ));
    }
}