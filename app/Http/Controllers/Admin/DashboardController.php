<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ──
        $totalBookings   = Booking::count();
        $totalRevenue    = Booking::whereIn('status', ['confirmed','completed'])->sum('total_bayar');
        $totalCustomers  = Customer::count();
        $avgRating       = Booking::whereNotNull('rating')->avg('rating');

        // Okupansi hari ini
        $today           = today();
        $totalKamar      = Hotel::sum('kapasitas_total');
        $terisi          = Booking::whereIn('status', ['confirmed','completed'])
                            ->whereDate('tgl_checkin', '<=', $today)
                            ->whereDate('tgl_checkout', '>=', $today)
                            ->count();
        $occupancyRate   = $totalKamar > 0
                            ? round(($terisi / $totalKamar) * 100, 1) : 0;

        // ── Chart Okupansi 12 bulan ──
        $occupancyMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $start = now()->startOfYear()->addMonths($m - 1);
            $end   = $start->copy()->endOfMonth();

            $booked = Booking::whereIn('status', ['confirmed','completed'])
                        ->whereDate('tgl_checkin', '<=', $end)
                        ->whereDate('tgl_checkout', '>=', $start)
                        ->count();

            $occupancyMonthly[] = $totalKamar > 0
                ? round(($booked / $totalKamar) * 100, 1) : 0;
        }

        // ── Chart Channel ──
        $channelData = Booking::select('channel_id', DB::raw('count(*) as total'))
                        ->with('channel')
                        ->groupBy('channel_id')
                        ->get()
                        ->map(fn($b) => [
                            'nama'  => $b->channel->nama ?? 'Unknown',
                            'total' => $b->total,
                        ]);

        // ── Revenue per Properti ──
        $revenueByHotel = Hotel::withSum(
            ['bookings' => fn($q) => $q->whereIn('status', ['confirmed','completed'])],
            'total_bayar'
        )->get()->map(fn($h) => [
            'nama'    => $h->nama,
            'revenue' => round($h->bookings_sum_total_bayar / 1000000, 1),
        ]);

        // ── Reservasi Terbaru ──
        $recentBookings = Booking::with(['hotel','room','customer','channel'])
                            ->latest()
                            ->limit(8)
                            ->get();

        return view('admin.dashboard', compact(
            'totalBookings', 'totalRevenue', 'totalCustomers',
            'avgRating', 'occupancyRate', 'terisi', 'totalKamar',
            'occupancyMonthly', 'channelData', 'revenueByHotel',
            'recentBookings'
        ));
    }
}