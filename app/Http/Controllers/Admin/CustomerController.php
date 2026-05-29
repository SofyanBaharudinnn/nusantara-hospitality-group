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
        $totalGuests   = Customer::count();
        $repeatGuests  = Customer::where('total_kunjungan', '>', 1)->count();
        $repeatRate    = $totalGuests > 0
                         ? round(($repeatGuests / $totalGuests) * 100, 1) : 0;
        $avgClv        = Customer::avg('total_spending') ?? 0;
        $avgRating     = Booking::whereNotNull('rating')->avg('rating') ?? 0;

        // ── Segmentasi ──
        $segmentasi = Customer::select('segmen', DB::raw('count(*) as total'))
                        ->groupBy('segmen')
                        ->pluck('total', 'segmen');

        // ── Negara Asal ──
        $negaraAsal = Customer::select('negara', DB::raw('count(*) as total'))
                        ->groupBy('negara')
                        ->orderByDesc('total')
                        ->limit(7)
                        ->pluck('total', 'negara');

        // ── Preferensi Kamar ──
        $prefKamar = Booking::join('rooms','bookings.room_id','=','rooms.id')
                        ->select('rooms.tipe', DB::raw('count(*) as total'))
                        ->whereIn('bookings.status', ['confirmed','completed'])
                        ->groupBy('rooms.tipe')
                        ->pluck('total', 'tipe');

        // ── Top Customers ──
        $topCustomers = Customer::with(['bookings.hotel'])
                            ->orderByDesc('total_spending')
                            ->limit(10)
                            ->get()
                            ->map(function ($c) {
                                $favHotel = $c->bookings()
                                    ->select('hotel_id', DB::raw('count(*) as cnt'))
                                    ->groupBy('hotel_id')
                                    ->orderByDesc('cnt')
                                    ->with('hotel')
                                    ->first();

                                return [
                                    'nama'       => $c->nama,
                                    'segmen'     => ucfirst($c->segmen),
                                    'visits'     => $c->total_kunjungan,
                                    'property'   => $favHotel?->hotel?->nama ?? '-',
                                    'clv'        => 'Rp ' . number_format($c->total_spending, 0, ',', '.'),
                                    'last'       => $c->bookings()->latest('tgl_checkin')->value('tgl_checkin')
                                                    ? \Carbon\Carbon::parse($c->bookings()->latest('tgl_checkin')->value('tgl_checkin'))->format('d M Y')
                                                    : '-',
                                    'tier'       => ucfirst($c->tier),
                                ];
                            });

        return view('admin.customers', compact(
            'totalGuests', 'repeatRate', 'avgClv', 'avgRating',
            'segmentasi', 'negaraAsal', 'prefKamar', 'topCustomers'
        ));
    }
}