<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DimTime;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class TrendController extends Controller
{
    public function index()
    {
        $totalKamar = DB::table('dim_room')->count();
        $year       = 2024;

        // ── Okupansi per Bulan ──
        $occupancyByMonth = [];
        $revenueByMonth   = [];

        for ($m = 1; $m <= 12; $m++) {
            $keys   = DimTime::keysForMonth($year, $m);
            $booked = Booking::where('is_cancelled', 'No')
                        ->whereIn('date_key', $keys)
                        ->count();
            $revenue = Booking::where('is_cancelled', 'No')
                        ->whereIn('date_key', $keys)
                        ->sum('room_revenue');

            $occupancyByMonth[] = $totalKamar > 0
                ? round(($booked / $totalKamar) * 100, 1) : 0;
            $revenueByMonth[]   = round($revenue / 1000000, 1);
        }

        // ── Kuartal ──
        $quarters = [];
        foreach ([[1,3],[4,6],[7,9],[10,12]] as $i => [$from, $to]) {
            $keys = DimTime::where('year', $year)
                        ->whereBetween('month', [$from, $to])
                        ->pluck('date_key')
                        ->toArray();

            $revenue = Booking::where('is_cancelled', 'No')
                            ->whereIn('date_key', $keys)
                            ->sum('room_revenue');
            $count   = Booking::whereIn('date_key', $keys)->count();
            $booked  = Booking::where('is_cancelled', 'No')
                            ->whereIn('date_key', $keys)
                            ->count();
            $occ     = $totalKamar > 0 ? round(($booked / $totalKamar) * 100, 1) : 0;

            $seasons = ['Low / Shoulder', 'Shoulder / Peak', 'Peak', 'Peak / Shoulder'];

            $quarters[] = [
                'label'   => 'Q' . ($i + 1) . ' ' . $year,
                'season'  => $seasons[$i],
                'occ'     => $occ . '%',
                'booking' => number_format($count, 0, ',', '.'),
                'revenue' => 'Rp ' . number_format($revenue / 1000000, 1, ',', '.') . ' M',
                'up'      => true,
            ];
        }

        // ── Weekend vs Weekday ──
        $weekend = [];
        $weekday = [];
        for ($m = 1; $m <= 12; $m++) {
            $wendKeys = DimTime::where('year', $year)
                            ->where('month', $m)
                            ->where('is_weekend', 'Yes')
                            ->pluck('date_key')->toArray();
            $wdayKeys = DimTime::where('year', $year)
                            ->where('month', $m)
                            ->where('is_weekend', 'No')
                            ->pluck('date_key')->toArray();

            $weekend[] = Booking::where('is_cancelled', 'No')
                            ->whereIn('date_key', $wendKeys)
                            ->count();
            $weekday[] = Booking::where('is_cancelled', 'No')
                            ->whereIn('date_key', $wdayKeys)
                            ->count();
        }

        return view('admin.trends', compact(
            'occupancyByMonth', 'revenueByMonth',
            'quarters', 'weekend', 'weekday'
        ));
    }
}