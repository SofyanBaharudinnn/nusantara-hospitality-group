<?php

namespace App\Exports;

use App\Models\Booking;
use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OccupancyExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected $year;

    public function __construct($year = null)
    {
        $this->year = $year ?? now()->year;
    }

    public function array(): array
    {
        $rows   = [];
        $hotels = Hotel::all();
        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];

        foreach ($hotels as $hotel) {
            for ($m = 1; $m <= 12; $m++) {
                $keys = \App\Models\DimTime::where('year', $this->year)
                                ->where('month', $m)
                                ->pluck('date_key')->toArray();

                $booked  = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->whereIn('date_key', $keys)
                            ->count();

                $revenue = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->whereIn('date_key', $keys)
                            ->sum('room_revenue');

                $rate = $hotel->kapasitas_total > 0
                        ? round(($booked / $hotel->kapasitas_total) * 100, 1) : 0;

                $rows[] = [
                    $hotel->nama,
                    $months[$m - 1],
                    $this->year,
                    $hotel->kapasitas_total,
                    $booked,
                    $rate . '%',
                    $revenue,
                ];
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Hotel', 'Bulan', 'Tahun',
            'Total Kamar', 'Kamar Terisi',
            'Occupancy Rate', 'Revenue (Rp)',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF22D3EE'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Okupansi ' . $this->year;
    }
}