<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DimTime;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKamar = Room::count();
        
        // Karena data DW sampai akhir 2024, kita gunakan default 'sekarang' di akhir tahun 2024
        // agar dashboard terlihat kaya data (tidak kosong)
        $year  = 2024;
        $month = 12;
        $today = '2024-12-15';

        // ── Stat 1: Okupansi (Reservasi aktif pada tanggal $today) ──
        $terisi = DB::table('fact_reservation')
            ->join('dim_time', 'fact_reservation.date_key', '=', 'dim_time.date_key')
            ->where('is_cancelled', 'No')
            ->whereDate('dim_time.date', '<=', $today)
            ->whereRaw("DATE_ADD(dim_time.date, INTERVAL fact_reservation.nights DAY) >= ?", [$today])
            ->count();
            
        $occupancy = $totalKamar > 0 ? round(($terisi / $totalKamar) * 100, 1) : 0;

        // ── Stat 2: Revenue Bulan Ini (MTD) ──
        $keysThisMonth = DimTime::keysForMonth($year, $month);
        $revenue = Booking::where('is_cancelled', 'No')
                    ->whereIn('date_key', $keysThisMonth)
                    ->sum('room_revenue');

        // ── Stat 3: Total Booking Bulan Ini ──
        $totalBookingsMtd = Booking::whereIn('date_key', $keysThisMonth)->count();

        // ── Stat 4: Avg Rating (tidak ada di DW) ──
        $avgRating = '-';

        $summary = [
            'occupancy_today'    => $occupancy,
            'active_guests'      => $terisi,
            'revenue_mtd'        => $revenue,
            'total_bookings_mtd' => $totalBookingsMtd,
            'avg_rating'         => $avgRating,
            'display_month'      => Carbon::create($year, $month, 1)->isoFormat('MMMM YYYY'),
            'display_date'       => Carbon::parse($today)->isoFormat('dddd, D MMMM YYYY'),
        ];

        // ── Chart: 7 Bulan Terakhir (DW tidak punya data harian berurutan yang bagus) ──
        $weeklyOccupancy = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date  = Carbon::create($year, $month, 1)->subMonths($i);
            $keys  = DimTime::keysForMonth($date->year, $date->month);
            $booked = Booking::where('is_cancelled', 'No')
                        ->whereIn('date_key', $keys)
                        ->count();
            $rate = $totalKamar > 0 ? round(($booked / $totalKamar) * 100, 1) : 0;
            $weeklyOccupancy['labels'][] = $date->isoFormat('MMM YY');
            $weeklyOccupancy['data'][]   = $rate;
        }

        // ── Chart: Distribusi Status (bulan ini saja) ──
        $cancelled  = Booking::whereIn('date_key', $keysThisMonth)->where('is_cancelled', 'Yes')->count();
        $confirmed  = Booking::whereIn('date_key', $keysThisMonth)->where('is_cancelled', 'No')->count();

        $bookingStatusDist = [
            'confirmed' => $confirmed,
            'completed' => 0,
            'pending'   => 0,
            'cancelled' => $cancelled,
        ];

        // ── Tabel: 5 Reservasi Terbaru (dari bulan ini) ──
        $recentBookings = Booking::with(['hotel', 'customer', 'room', 'dimTime'])
                            ->where('is_cancelled', 'No')
                            ->whereIn('date_key', $keysThisMonth)
                            ->latest('reservation_key')
                            ->take(5)
                            ->get();

        // ── Top 3 Hotel by Occupancy (pada $today) ──
        $topHotels = Hotel::all()->map(function ($hotel) use ($today) {
            $totalKamarHotel = Room::where('hotel_key', $hotel->hotel_key)->count();
            
            $terisiHotel = DB::table('fact_reservation')
                ->join('dim_time', 'fact_reservation.date_key', '=', 'dim_time.date_key')
                ->where('hotel_key', $hotel->hotel_key)
                ->where('is_cancelled', 'No')
                ->whereDate('dim_time.date', '<=', $today)
                ->whereRaw("DATE_ADD(dim_time.date, INTERVAL fact_reservation.nights DAY) >= ?", [$today])
                ->count();
                
            $rate = $totalKamarHotel > 0 ? round(($terisiHotel / $totalKamarHotel) * 100, 1) : 0;
            return [
                'nama'     => $hotel->hotel_name,
                'kota'     => $hotel->city,
                'bintang'  => $hotel->star_rating,
                'occupied' => $terisiHotel,
                'total'    => $totalKamarHotel,
                'rate'     => $rate,
            ];
        })->sortByDesc('rate')->take(3)->values();

        return view('user.dashboard', compact(
            'summary', 'weeklyOccupancy',
            'bookingStatusDist', 'recentBookings', 'topHotels'
        ));
    }
}