<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        // ── KPI ──
        $totalGuests  = Customer::count();

        // Repeat guests = tamu yang punya lebih dari 1 reservasi
        $repeatGuests = DB::table('fact_reservation')
                            ->select('guest_key')
                            ->groupBy('guest_key')
                            ->havingRaw('count(*) > 1')
                            ->get()->count();

        $repeatRate   = $totalGuests > 0
                         ? round(($repeatGuests / $totalGuests) * 100, 1) : 0;

        $avgClv       = DB::table('fact_reservation')
                            ->where('is_cancelled', 'No')
                            ->selectRaw('guest_key, SUM(room_revenue) as clv')
                            ->groupBy('guest_key')
                            ->get()
                            ->avg('clv') ?? 0;

        $avgRating    = null; // tidak ada di DW

        // ── Segmentasi ──
        $segmentasi = Customer::select('segment', DB::raw('count(*) as total'))
                        ->groupBy('segment')
                        ->pluck('total', 'segment');

        // ── Negara Asal ──
        $negaraAsal = Customer::select('nationality', DB::raw('count(*) as total'))
                        ->groupBy('nationality')
                        ->orderByDesc('total')
                        ->limit(7)
                        ->pluck('total', 'nationality');

        // ── Preferensi Kamar ──
        $prefKamar = Booking::join('dim_room', 'fact_reservation.room_key', '=', 'dim_room.room_key')
                        ->select('dim_room.room_type', DB::raw('count(*) as total'))
                        ->where('fact_reservation.is_cancelled', 'No')
                        ->groupBy('dim_room.room_type')
                        ->pluck('total', 'room_type');

        // ── Top Customers ──
        $topCustomers = DB::table('fact_reservation')
            ->join('dim_guest', 'fact_reservation.guest_key', '=', 'dim_guest.guest_key')
            ->join('dim_hotel', 'fact_reservation.hotel_key', '=', 'dim_hotel.hotel_key')
            ->where('fact_reservation.is_cancelled', 'No')
            ->select(
                'dim_guest.guest_key',
                'dim_guest.guest_name',
                'dim_guest.segment',
                DB::raw('COUNT(fact_reservation.reservation_key) as visits'),
                DB::raw('SUM(fact_reservation.room_revenue) as total_spending'),
                DB::raw('MAX(dim_hotel.hotel_name) as fav_hotel')
            )
            ->groupBy('dim_guest.guest_key', 'dim_guest.guest_name', 'dim_guest.segment')
            ->orderByDesc('total_spending')
            ->limit(10)
            ->get()
            ->map(fn($c) => [
                'nama'     => $c->guest_name,
                'segmen'   => ucfirst($c->segment),
                'visits'   => $c->visits,
                'property' => $c->fav_hotel ?? '-',
                'clv'      => 'Rp ' . number_format($c->total_spending, 0, ',', '.'),
                'last'     => '-',
                'tier'     => 'silver',
            ]);

        return view('admin.customers', compact(
            'totalGuests', 'repeatRate', 'avgClv', 'avgRating',
            'segmentasi', 'negaraAsal', 'prefKamar', 'topCustomers'
        ));
    }
}