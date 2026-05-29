<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OccupancyController extends Controller
{
    public function index(Request $request)
    {
        $today     = today();
        $year      = $request->input('year',  now()->year);
        $month     = $request->input('month', null);
        $hotelId   = $request->input('hotel', null);

        // ── Data per Properti ──
        $hotels = Hotel::where('is_active', true)->get()->map(function ($hotel) use ($today) {
            $terisi = Booking::where('hotel_id', $hotel->id)
                        ->whereIn('status', ['confirmed','completed'])
                        ->whereDate('tgl_checkin', '<=', $today)
                        ->whereDate('tgl_checkout', '>=', $today)
                        ->count();

            $rate = $hotel->kapasitas_total > 0
                ? round(($terisi / $hotel->kapasitas_total) * 100, 1) : 0;

            $revenue = Booking::where('hotel_id', $hotel->id)
                        ->whereIn('status', ['confirmed','completed'])
                        ->sum('total_bayar');

            $adr = Booking::where('hotel_id', $hotel->id)
                    ->whereIn('status', ['confirmed','completed'])
                    ->avg('harga_per_malam') ?? 0;

            return [
                'id'        => $hotel->id,
                'nama'      => $hotel->nama,
                'kamar'     => $terisi . '/' . $hotel->kapasitas_total,
                'rate'      => $rate,
                'adr'       => 'Rp ' . number_format($adr, 0, ',', '.'),
                'revpar'    => 'Rp ' . number_format($adr * ($rate / 100), 0, ',', '.'),
                'revenue'   => 'Rp ' . number_format($revenue, 0, ',', '.'),
            ];
        });

        // ── Chart Bulanan per Properti ──
        $chartData = Hotel::where('is_active', true)->get()->map(function ($hotel) use ($year) {
            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $start = \Carbon\Carbon::create($year, $m, 1)->startOfMonth();
                $end   = $start->copy()->endOfMonth();

                $booked = Booking::where('hotel_id', $hotel->id)
                            ->whereIn('status', ['confirmed','completed'])
                            ->whereDate('tgl_checkin', '<=', $end)
                            ->whereDate('tgl_checkout', '>=', $start)
                            ->count();

                $data[] = $hotel->kapasitas_total > 0
                    ? round(($booked / $hotel->kapasitas_total) * 100, 1) : 0;
            }
            return ['nama' => $hotel->nama, 'data' => $data];
        });

        // ── Tipe Kamar ──
        $roomTypes = Booking::join('rooms','bookings.room_id','=','rooms.id')
                        ->select('rooms.tipe', DB::raw('count(*) as total'))
                        ->whereIn('bookings.status', ['confirmed','completed'])
                        ->groupBy('rooms.tipe')
                        ->pluck('total','tipe');

        // ── Booking Terbaru ──
        $query = Booking::with(['hotel','room','customer','channel'])->latest();
        if ($hotelId) $query->where('hotel_id', $hotelId);
        $bookings = $query->limit(15)->get();

        $hotelList = Hotel::where('is_active', true)->get();

        return view('admin.occupancy', compact(
            'hotels', 'chartData', 'roomTypes',
            'bookings', 'hotelList', 'year', 'month', 'hotelId'
        ));
    }
}