<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CustomersExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    public function collection()
    {
        return Customer::withSum('reservations', 'room_revenue')
            ->withCount('reservations')
            ->orderByDesc('reservations_sum_room_revenue')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama', 'Email', 'Telepon', 'Segmen',
            'Negara', 'Kota Asal', 'Tanggal Lahir',
            'Tier', 'Total Kunjungan', 'Total Spending (Rp)',
        ];
    }

    public function map($c): array
    {
        return [
            $c->nama,
            $c->email,
            $c->telepon ?? '-',
            ucfirst($c->segmen),
            $c->negara,
            $c->kota_asal ?? '-',
            $c->tgl_lahir ? (is_string($c->tgl_lahir) ? $c->tgl_lahir : $c->tgl_lahir->format('d/m/Y')) : '-',
            ucfirst($c->tier),
            $c->reservations_count ?? 0,
            $c->reservations_sum_room_revenue ?? 0,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFEC4899'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Tamu';
    }
}