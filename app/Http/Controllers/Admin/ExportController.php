<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Hotel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    // ── EXCEL ──

    public function excelBookings(Request $request)
    {
        $filename = 'booking_' . ($request->year ?? now()->year) . '_' . now()->format('Ymd') . '.xlsx';

        // Jika kelas BookingsExport ada, gunakan; jika tidak, export manual
        if (class_exists(\App\Exports\BookingsExport::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\BookingsExport($request->hotel_key, $request->status, $request->year),
                $filename
            );
        }

        return back()->with('info', 'Export Excel belum tersedia.');
    }

    public function excelCustomers()
    {
        $filename = 'tamu_nhg_' . now()->format('Ymd') . '.xlsx';
        if (class_exists(\App\Exports\CustomersExport::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CustomersExport(), $filename);
        }
        return back()->with('info', 'Export Excel belum tersedia.');
    }

    public function excelOccupancy(Request $request)
    {
        $filename = 'okupansi_' . ($request->year ?? now()->year) . '_' . now()->format('Ymd') . '.xlsx';
        if (class_exists(\App\Exports\OccupancyExport::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\OccupancyExport($request->year), $filename);
        }
        return back()->with('info', 'Export Excel belum tersedia.');
    }

    // ── PDF ──

    public function pdfBookings(Request $request)
    {
        $query = Booking::with(['hotel', 'room', 'customer', 'channel'])
                    ->latest('reservation_key');

        if ($request->hotel_key) $query->where('hotel_key', $request->hotel_key);
        if ($request->status) {
            $isCancelled = $request->status === 'cancelled' ? 'Yes' : 'No';
            $query->where('is_cancelled', $isCancelled);
        }

        $bookings = $query->limit(100)->get();
        $hotels   = Hotel::all();
        $year     = $request->year ?? now()->year;

        $pdf = Pdf::loadView('admin.exports.pdf-bookings', compact('bookings', 'hotels', 'year'))
                ->setPaper('a4', 'landscape')
                ->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('laporan_booking_' . $year . '_' . now()->format('Ymd') . '.pdf');
    }

    public function pdfOccupancy(Request $request)
    {
        $year   = $request->year ?? now()->year;
        $hotels = Hotel::all()->map(function ($hotel) use ($year) {
            $totalKamar = \App\Models\Room::where('hotel_key', $hotel->hotel_key)->count();
            $terisi     = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->count();
            $rate       = $totalKamar > 0
                           ? round(($terisi / $totalKamar) * 100, 1) : 0;
            $revenue    = Booking::where('hotel_key', $hotel->hotel_key)
                            ->where('is_cancelled', 'No')
                            ->sum('room_revenue');

            return array_merge($hotel->toArray(), [
                'nama'            => $hotel->nama,
                'tipe'            => $hotel->tipe,
                'kota'            => $hotel->kota,
                'kapasitas_total' => $totalKamar,
                'terisi'          => $terisi,
                'rate'            => $rate,
                'revenue'         => $revenue,
            ]);
        });

        $pdf = Pdf::loadView('admin.exports.pdf-occupancy', compact('hotels', 'year'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('laporan_okupansi_' . $year . '.pdf');
    }

    public function pdfCustomers()
    {
        $customers = Customer::orderByDesc('guest_key')->limit(50)->get();
        $pdf = Pdf::loadView('admin.exports.pdf-customers', compact('customers'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('laporan_tamu_' . now()->format('Ymd') . '.pdf');
    }
}