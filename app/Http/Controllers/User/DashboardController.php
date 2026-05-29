<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today      = today();
        $totalKamar = Hotel::sum('kapasitas_total');

        // ── Stat 1: Okupansi Hari Ini ──
        $terisi = Booking::whereIn('status', ['confirmed', 'completed'])
                    ->whereDate('tgl_checkin', '<=', $today)
                    ->whereDate('tgl_checkout', '>=', $today)
                    ->count();

        $occupancy = $totalKamar > 0 ? round(($terisi / $totalKamar) * 100, 1) : 0;

        // ── Stat 2: Revenue MTD ──
        $revenue = Booking::whereIn('status', ['confirmed', 'completed'])
                    ->whereYear('tgl_checkin', now()->year)
                    ->whereMonth('tgl_checkin', now()->month)
                    ->sum('total_bayar');

        // ── Stat 3: Total Booking Bulan Ini ──
        $totalBookingsMtd = Booking::whereIn('status', ['confirmed', 'completed', 'pending'])
                    ->whereYear('tgl_checkin', now()->year)
                    ->whereMonth('tgl_checkin', now()->month)
                    ->count();

        // ── Stat 4: Avg Rating Bulan Ini ──
        $avgRating = Booking::whereIn('status', ['confirmed', 'completed'])
                    ->whereNotNull('rating')
                    ->whereYear('tgl_checkin', now()->year)
                    ->avg('rating');

        $summary = [
            'occupancy_today'    => $occupancy,
            'active_guests'      => $terisi,
            'revenue_mtd'        => $revenue,
            'total_bookings_mtd' => $totalBookingsMtd,
            'avg_rating'         => $avgRating ? round($avgRating, 1) : '-',
        ];

        // ── Chart: 7 Hari Terakhir ──
        $weeklyOccupancy = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date   = now()->copy()->subDays($i);
            $booked = Booking::whereIn('status', ['confirmed', 'completed'])
                        ->whereDate('tgl_checkin', '<=', $date)
                        ->whereDate('tgl_checkout', '>=', $date)
                        ->count();
            $rate = $totalKamar > 0 ? round(($booked / $totalKamar) * 100, 1) : 0;
            $weeklyOccupancy['labels'][] = $date->isoFormat('ddd, D MMM');
            $weeklyOccupancy['data'][]   = $rate;
        }

        // ── Chart: Distribusi Status Booking (bulan ini) ──
        $statusCounts = Booking::whereYear('tgl_checkin', now()->year)
            ->whereMonth('tgl_checkin', now()->month)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $bookingStatusDist = [
            'confirmed' => $statusCounts['confirmed'] ?? 0,
            'completed' => $statusCounts['completed'] ?? 0,
            'pending'   => $statusCounts['pending']   ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0,
        ];

        // ── Tabel: 5 Reservasi Terbaru ──
        $recentBookings = Booking::with(['hotel', 'customer', 'room'])
                            ->whereIn('status', ['confirmed', 'completed', 'pending'])
                            ->latest('tgl_checkin')
                            ->take(5)
                            ->get();

        // ── Top 3 Hotel by Ocupancy ──
        $topHotels = Hotel::where('is_active', true)->get()->map(function ($hotel) use ($today) {
            $terisi = Booking::where('hotel_id', $hotel->id)
                        ->whereIn('status', ['confirmed', 'completed'])
                        ->whereDate('tgl_checkin', '<=', $today)
                        ->whereDate('tgl_checkout', '>=', $today)
                        ->count();
            $rate = $hotel->kapasitas_total > 0
                    ? round(($terisi / $hotel->kapasitas_total) * 100, 1) : 0;
            return [
                'nama'     => $hotel->nama,
                'kota'     => $hotel->kota,
                'bintang'  => $hotel->bintang,
                'occupied' => $terisi,
                'total'    => $hotel->kapasitas_total,
                'rate'     => $rate,
            ];
        })->sortByDesc('rate')->take(3)->values();

        return view('user.dashboard', compact(
            'summary', 'weeklyOccupancy',
            'bookingStatusDist', 'recentBookings', 'topHotels'
        ));
    }
}