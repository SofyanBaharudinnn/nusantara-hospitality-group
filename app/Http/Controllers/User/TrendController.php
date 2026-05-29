<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use Carbon\Carbon;

class TrendController extends Controller
{
    public function index()
    {
        $totalKamar = Hotel::sum('kapasitas_total');
        $year       = now()->year;
        $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];

        $occupancyData   = [];
        $bookingCountData = [];
        $revenueData      = [];
        $monthTable       = [];

        for ($m = 1; $m <= 12; $m++) {
            $start = Carbon::create($year, $m, 1)->startOfMonth();
            $end   = $start->copy()->endOfMonth();

            $booked = Booking::whereIn('status', ['confirmed', 'completed'])
                        ->whereDate('tgl_checkin', '<=', $end)
                        ->whereDate('tgl_checkout', '>=', $start)
                        ->count();

            $bookingCount = Booking::whereIn('status', ['confirmed', 'completed', 'pending'])
                        ->whereYear('tgl_checkin', $year)
                        ->whereMonth('tgl_checkin', $m)
                        ->count();

            $revenue = Booking::whereIn('status', ['confirmed', 'completed'])
                        ->whereYear('tgl_checkin', $year)
                        ->whereMonth('tgl_checkin', $m)
                        ->sum('total_bayar');

            $occ = $totalKamar > 0 ? round(($booked / $totalKamar) * 100, 1) : 0;

            $occupancyData[]    = $occ;
            $bookingCountData[]  = $bookingCount;
            $revenueData[]       = round($revenue / 1000000, 1);

            // Kategori musim
            $season = match(true) {
                in_array($m, [1, 2])       => ['label' => 'Low Season',      'color' => '#f87171'],
                in_array($m, [3, 5, 9])    => ['label' => 'Shoulder Season', 'color' => '#fbbf24'],
                default                    => ['label' => 'Peak Season',      'color' => '#4ade80'],
            };

            $monthTable[] = [
                'month'    => $monthNames[$m - 1],
                'bookings' => $bookingCount,
                'occ'      => $occ,
                'revenue'  => $revenue,
                'season'   => $season,
            ];
        }

        $monthlyData = [
            'labels' => $monthNames,
            'data'   => $occupancyData,
        ];

        // ── Seasons Summary ──
        $seasons = [
            ['name' => 'Low Season',      'months' => 'Jan — Feb',      'avg' => round(($occupancyData[0]  + $occupancyData[1])  / 2, 1) . '%', 'color' => '#f87171'],
            ['name' => 'Shoulder Season', 'months' => 'Mar — Mei, Sep', 'avg' => round(($occupancyData[2]  + $occupancyData[4] + $occupancyData[8]) / 3, 1) . '%', 'color' => '#fbbf24'],
            ['name' => 'Peak Season',     'months' => 'Jun — Agt, Okt', 'avg' => round(($occupancyData[5]  + $occupancyData[6] + $occupancyData[7] + $occupancyData[9]) / 4, 1) . '%', 'color' => '#4ade80'],
        ];

        // ── Kuartal ──
        $quarterDefs = [[1,3],[4,6],[7,9],[10,12]];
        $quarters = [];
        foreach ($quarterDefs as $i => [$from, $to]) {
            $qRevenue = 0;
            $qCount   = 0;
            $qOccSum  = 0;

            for ($m = $from; $m <= $to; $m++) {
                $qRevenue += Booking::whereIn('status', ['confirmed', 'completed'])
                    ->whereYear('tgl_checkin', $year)
                    ->whereMonth('tgl_checkin', $m)
                    ->sum('total_bayar');
                $qCount += Booking::whereIn('status', ['confirmed', 'completed', 'pending'])
                    ->whereYear('tgl_checkin', $year)
                    ->whereMonth('tgl_checkin', $m)
                    ->count();
                $qOccSum += $occupancyData[$m - 1];
            }

            $quarters[] = [
                'label'   => 'Q' . ($i + 1) . ' ' . $year,
                'months'  => $monthNames[$from - 1] . '–' . $monthNames[$to - 1],
                'count'   => number_format($qCount, 0, ',', '.'),
                'revenue' => 'Rp ' . number_format($qRevenue / 1000000, 1, ',', '.') . ' Jt',
                'avg_occ' => round($qOccSum / 3, 1) . '%',
                'icon'    => ['🌱', '☀️', '🍂', '❄️'][$i],
            ];
        }

        // ── Insight: best & worst month ──
        $maxOcc    = max($occupancyData);
        $minOcc    = min($occupancyData);
        $bestIdx   = array_search($maxOcc, $occupancyData);
        $worstIdx  = array_search($minOcc, $occupancyData);

        $insight = [
            'best_month'  => $monthNames[$bestIdx]  . ' (' . $maxOcc . '%)',
            'worst_month' => $monthNames[$worstIdx] . ' (' . $minOcc . '%)',
            'avg_annual'  => round(array_sum($occupancyData) / 12, 1) . '%',
        ];

        return view('user.trends', compact(
            'monthlyData', 'seasons', 'quarters',
            'bookingCountData', 'revenueData', 'monthTable', 'insight'
        ));
    }
}