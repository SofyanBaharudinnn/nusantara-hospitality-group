<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\DimTime;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $year  = 2024;
        $month = 12;
        $today = '2024-12-15';
        $keysThisMonth = DimTime::keysForMonth($year, $month);

        // ── Stat Cards ──
        $totalBookings  = Booking::count();
        $totalRevenue   = Booking::where('is_cancelled', 'No')->sum('room_revenue');
        $totalCustomers = Customer::count();
        $avgRating      = null;

        $totalKamar   = DB::table('dim_room')->count();

        // Okupansi hari ini: Reservasi aktif pada tanggal $today
        $terisi = DB::table('fact_reservation')
            ->join('dim_time', 'fact_reservation.date_key', '=', 'dim_time.date_key')
            ->where('is_cancelled', 'No')
            ->whereDate('dim_time.date', '<=', $today)
            ->whereRaw("DATE_ADD(dim_time.date, INTERVAL fact_reservation.nights DAY) >= ?", [$today])
            ->count();
            
        $occupancyRate = $totalKamar > 0 ? round(($terisi / $totalKamar) * 100, 1) : 0;

        // ── Chart Okupansi 12 bulan (Tahun 2024) ──
        $occupancyMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $keys   = DimTime::keysForMonth($year, $m);
            $booked = Booking::where('is_cancelled', 'No')
                        ->whereIn('date_key', $keys)
                        ->count();
            $occupancyMonthly[] = $totalKamar > 0
                ? round(($booked / $totalKamar) * 100, 1) : 0;
        }

        // ── Chart Channel ──
        $channelData = Booking::select('channel_key', DB::raw('count(*) as total'))
                        ->with('channel')
                        ->groupBy('channel_key')
                        ->get()
                        ->map(fn($b) => [
                            'nama'  => $b->channel->channel_name ?? 'Unknown',
                            'total' => $b->total,
                        ]);

        // ── Revenue per Properti ──
        $revenueByHotel = Hotel::all()->map(function ($hotel) {
            $revenue = Booking::where('hotel_key', $hotel->hotel_key)
                        ->where('is_cancelled', 'No')
                        ->sum('room_revenue');
            return [
                'nama'    => $hotel->hotel_name,
                'revenue' => round($revenue / 1000000, 1),
            ];
        });

        // ── Reservasi Terbaru (Bulan Ini) ──
        $recentBookings = Booking::with(['hotel', 'room', 'customer', 'channel', 'dimTime'])
                            ->whereIn('date_key', $keysThisMonth)
                            ->latest('reservation_key')
                            ->limit(8)
                            ->get();

        $displayDate  = Carbon::parse($today)->isoFormat('dddd, D MMMM YYYY');
        $displayMonth = Carbon::create($year, $month, 1)->isoFormat('MMMM YYYY');

        return view('admin.dashboard', compact(
            'totalBookings', 'totalRevenue', 'totalCustomers',
            'avgRating', 'occupancyRate', 'terisi', 'totalKamar',
            'occupancyMonthly', 'channelData', 'revenueByHotel',
            'recentBookings', 'displayDate', 'displayMonth', 'year'
        ));
    }
}