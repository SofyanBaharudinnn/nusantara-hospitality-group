<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class TrendController extends Controller
{
    public function index()
    {
        $totalKamar = Hotel::sum('kapasitas_total');

        // ── Okupansi per Bulan ──
        $occupancyByMonth = [];
        $revenueByMonth   = [];

        for ($m = 1; $m <= 12; $m++) {
            $start = \Carbon\Carbon::create(now()->year, $m, 1)->startOfMonth();
            $end   = $start->copy()->endOfMonth();

            $booked = Booking::whereIn('status', ['confirmed','completed'])
                        ->whereDate('tgl_checkin', '<=', $end)
                        ->whereDate('tgl_checkout', '>=', $start)
                        ->count();

            $revenue = Booking::whereIn('status', ['confirmed','completed'])
                        ->whereYear('tgl_checkin', now()->year)
                        ->whereMonth('tgl_checkin', $m)
                        ->sum('total_bayar');

            $occupancyByMonth[] = $totalKamar > 0
                ? round(($booked / $totalKamar) * 100, 1) : 0;
            $revenueByMonth[]   = round($revenue / 1000000, 1);
        }

        // ── Kuartal ──
        $quarters = [];
        foreach ([[1,3],[4,6],[7,9],[10,12]] as $i => [$from, $to]) {
            $bookings = Booking::whereIn('status', ['confirmed','completed'])
                            ->whereYear('tgl_checkin', now()->year)
                            ->whereMonth('tgl_checkin', '>=', $from)
                            ->whereMonth('tgl_checkin', '<=', $to);

            $revenue  = $bookings->sum('total_bayar');
            $count    = $bookings->count();

            $start = \Carbon\Carbon::create(now()->year, $from, 1)->startOfMonth();
            $end   = \Carbon\Carbon::create(now()->year, $to, 1)->endOfMonth();
            $booked = Booking::whereIn('status', ['confirmed','completed'])
                        ->whereDate('tgl_checkin', '<=', $end)
                        ->whereDate('tgl_checkout', '>=', $start)
                        ->count();
            $occ = $totalKamar > 0 ? round(($booked / $totalKamar) * 100, 1) : 0;

            $seasons = ['Low / Shoulder', 'Shoulder / Peak', 'Peak', 'Peak / Shoulder'];

            $quarters[] = [
                'label'   => 'Q' . ($i + 1) . ' ' . now()->year,
                'season'  => $seasons[$i],
                'occ'     => $occ . '%',
                'booking' => number_format($count, 0, ',', '.'),
                'revenue' => 'Rp ' . number_format($revenue / 1000000, 1, ',', '.') . ' M',
                'up'      => true,
            ];
        }

        // ── Weekend vs Weekday ──
        $weekend  = [];
        $weekday  = [];
        for ($m = 1; $m <= 12; $m++) {
            // SQLite: strftime('%w') returns 0=Sunday, 6=Saturday
            $wend = Booking::whereIn('status', ['confirmed','completed'])
                        ->whereYear('tgl_checkin', now()->year)
                        ->whereMonth('tgl_checkin', $m)
                        ->whereRaw("strftime('%w', tgl_checkin) IN ('0', '6')")
                        ->count();

            $wday = Booking::whereIn('status', ['confirmed','completed'])
                        ->whereYear('tgl_checkin', now()->year)
                        ->whereMonth('tgl_checkin', $m)
                        ->whereRaw("strftime('%w', tgl_checkin) NOT IN ('0', '6')")
                        ->count();

            $weekend[]  = $wend;
            $weekday[]  = $wday;
        }

        return view('admin.trends', compact(
            'occupancyByMonth', 'revenueByMonth',
            'quarters', 'weekend', 'weekday'
        ));
    }
}