<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BookingsExport;
use App\Exports\CustomersExport;
use App\Exports\OccupancyExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Hotel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    // ── EXCEL ──

    public function excelBookings(Request $request)
    {
        $filename = 'booking_' . ($request->year ?? now()->year) . '_' . now()->format('Ymd') . '.xlsx';

        return Excel::download(
            new BookingsExport($request->hotel_id, $request->status, $request->year),
            $filename
        );
    }

    public function excelCustomers()
    {
        $filename = 'tamu_nhg_' . now()->format('Ymd') . '.xlsx';
        return Excel::download(new CustomersExport(), $filename);
    }

    public function excelOccupancy(Request $request)
    {
        $filename = 'okupansi_' . ($request->year ?? now()->year) . '_' . now()->format('Ymd') . '.xlsx';
        return Excel::download(new OccupancyExport($request->year), $filename);
    }

    // ── PDF ──

    public function pdfBookings(Request $request)
    {
        $query = Booking::with(['hotel','room','customer','channel'])
                    ->orderBy('tgl_checkin', 'desc');

        if ($request->hotel_id) $query->where('hotel_id', $request->hotel_id);
        if ($request->status)   $query->where('status', $request->status);
        if ($request->year)     $query->whereYear('tgl_checkin', $request->year);

        $bookings  = $query->limit(100)->get();
        $hotels    = Hotel::all();
        $year      = $request->year ?? now()->year;

        $pdf = Pdf::loadView('admin.exports.pdf-bookings', compact('bookings','hotels','year'))
                ->setPaper('a4', 'landscape')
                ->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('laporan_booking_' . $year . '_' . now()->format('Ymd') . '.pdf');
    }

    public function pdfOccupancy(Request $request)
    {
        $year   = $request->year ?? now()->year;
        $today  = today();
        $hotels = Hotel::where('is_active', true)->get()->map(function ($hotel) use ($today, $year) {
            $terisi  = Booking::where('hotel_id', $hotel->id)
                        ->whereIn('status', ['confirmed','completed'])
                        ->whereDate('tgl_checkin', '<=', $today)
                        ->whereDate('tgl_checkout', '>=', $today)
                        ->count();
            $rate    = $hotel->kapasitas_total > 0
                       ? round(($terisi / $hotel->kapasitas_total) * 100, 1) : 0;
            $revenue = Booking::where('hotel_id', $hotel->id)
                        ->whereIn('status', ['confirmed','completed'])
                        ->whereYear('tgl_checkin', $year)
                        ->sum('total_bayar');

            return array_merge($hotel->toArray(), [
                'terisi'  => $terisi,
                'rate'    => $rate,
                'revenue' => $revenue,
            ]);
        });

        $pdf = Pdf::loadView('admin.exports.pdf-occupancy', compact('hotels','year'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('laporan_okupansi_' . $year . '.pdf');
    }

    public function pdfCustomers()
    {
        $customers = Customer::orderByDesc('total_spending')->limit(50)->get();
        $pdf = Pdf::loadView('admin.exports.pdf-customers', compact('customers'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('laporan_tamu_' . now()->format('Ymd') . '.pdf');
    }
}