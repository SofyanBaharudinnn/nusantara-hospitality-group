<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BookingsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected $hotelId;
    protected $status;
    protected $year;

    public function __construct($hotelId = null, $status = null, $year = null)
    {
        $this->hotelId = $hotelId;
        $this->status  = $status;
        $this->year    = $year ?? now()->year;
    }

    public function collection()
    {
        $query = Booking::with(['hotel','room','customer','channel'])
                    ->orderBy('reservation_key', 'desc');

        if ($this->hotelId) {
            $query->where('hotel_key', $this->hotelId);
        }
        
        if ($this->status) {
            $isCancelled = $this->status === 'cancelled' ? 'Yes' : 'No';
            $query->where('is_cancelled', $isCancelled);
        }
        
        if ($this->year) {
            $query->whereBetween('date_key', [(int)($this->year . '0101'), (int)($this->year . '1231')]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Kode Booking', 'Nama Tamu', 'Email Tamu',
            'Hotel', 'Tipe Kamar', 'No Kamar',
            'Channel', 'Check-in', 'Check-out',
            'Jml Malam', 'Jml Tamu',
            'Harga/Malam', 'Diskon', 'Total Bayar',
            'Status', 'Rating',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->kode_booking,
            $booking->customer->nama ?? '-',
            $booking->customer->email ?? '-',
            $booking->hotel->nama ?? '-',
            ucfirst($booking->room->tipe ?? '-'),
            $booking->room->nomor_kamar ?? '-',
            $booking->channel->nama ?? '-',
            $booking->tgl_checkin->format('d/m/Y'),
            $booking->tgl_checkout->format('d/m/Y'),
            $booking->jml_malam,
            $booking->jml_tamu,
            $booking->harga_per_malam,
            $booking->diskon,
            $booking->total_bayar,
            ucfirst($booking->status),
            $booking->rating ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF7C3AED'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Booking ' . $this->year;
    }
}