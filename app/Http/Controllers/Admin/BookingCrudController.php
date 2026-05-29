<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Channel;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingCrudController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['hotel','room','customer','channel'])->latest();

        if ($request->filled('hotel_id'))
            $query->where('hotel_id', $request->hotel_id);

        if ($request->filled('status'))
            $query->where('status', $request->status);

        if ($request->filled('search'))
            $query->whereHas('customer', fn($q) =>
                $q->where('nama', 'like', '%'.$request->search.'%')
            )->orWhere('kode_booking', 'like', '%'.$request->search.'%');

        $bookings  = $query->paginate(15)->withQueryString();
        $hotels    = Hotel::where('is_active', true)->get();
        $statuses  = ['confirmed','pending','cancelled','completed'];

        return view('admin.bookings.index', compact('bookings','hotels','statuses'));
    }

    public function create()
    {
        $hotels    = Hotel::where('is_active', true)->get();
        $customers = Customer::orderBy('nama')->get();
        $channels  = Channel::where('is_active', true)->get();
        $rooms     = collect();

        return view('admin.bookings.create', compact('hotels','customers','channels','rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id'       => 'required|exists:hotels,id',
            'room_id'        => 'required|exists:rooms,id',
            'customer_id'    => 'required|exists:customers,id',
            'channel_id'     => 'required|exists:channels,id',
            'tgl_checkin'    => 'required|date',
            'tgl_checkout'   => 'required|date|after:tgl_checkin',
            'jml_tamu'       => 'required|integer|min:1',
            'status'         => 'required|in:confirmed,pending,cancelled,completed',
            'diskon'         => 'nullable|numeric|min:0',
            'catatan'        => 'nullable|string',
        ]);

        $room      = Room::findOrFail($request->room_id);
        $checkin   = \Carbon\Carbon::parse($request->tgl_checkin);
        $checkout  = \Carbon\Carbon::parse($request->tgl_checkout);
        $nights    = $checkin->diffInDays($checkout);
        $harga     = $room->harga_dasar;
        $diskon    = $request->diskon ?? 0;
        $total     = ($harga * $nights) - $diskon;

        Booking::create([
            'kode_booking'    => Booking::generateKode(),
            'hotel_id'        => $request->hotel_id,
            'room_id'         => $request->room_id,
            'customer_id'     => $request->customer_id,
            'channel_id'      => $request->channel_id,
            'tgl_checkin'     => $request->tgl_checkin,
            'tgl_checkout'    => $request->tgl_checkout,
            'jml_malam'       => $nights,
            'jml_tamu'        => $request->jml_tamu,
            'harga_per_malam' => $harga,
            'total_bayar'     => $total,
            'diskon'          => $diskon,
            'status'          => $request->status,
            'catatan'         => $request->catatan,
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil ditambahkan!');
    }

    public function show(Booking $booking)
    {
        $booking->load(['hotel','room','customer','channel']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $hotels    = Hotel::where('is_active', true)->get();
        $customers = Customer::orderBy('nama')->get();
        $channels  = Channel::where('is_active', true)->get();
        $rooms     = Room::where('hotel_id', $booking->hotel_id)->get();
        return view('admin.bookings.edit', compact('booking','hotels','customers','channels','rooms'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'hotel_id'     => 'required|exists:hotels,id',
            'room_id'      => 'required|exists:rooms,id',
            'customer_id'  => 'required|exists:customers,id',
            'channel_id'   => 'required|exists:channels,id',
            'tgl_checkin'  => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
            'jml_tamu'     => 'required|integer|min:1',
            'status'       => 'required|in:confirmed,pending,cancelled,completed',
            'rating'       => 'nullable|integer|min:1|max:5',
            'diskon'       => 'nullable|numeric|min:0',
            'catatan'      => 'nullable|string',
        ]);

        $room    = Room::findOrFail($request->room_id);
        $nights  = \Carbon\Carbon::parse($request->tgl_checkin)
                    ->diffInDays(\Carbon\Carbon::parse($request->tgl_checkout));
        $diskon  = $request->diskon ?? 0;
        $total   = ($room->harga_dasar * $nights) - $diskon;

        $booking->update([
            'hotel_id'        => $request->hotel_id,
            'room_id'         => $request->room_id,
            'customer_id'     => $request->customer_id,
            'channel_id'      => $request->channel_id,
            'tgl_checkin'     => $request->tgl_checkin,
            'tgl_checkout'    => $request->tgl_checkout,
            'jml_malam'       => $nights,
            'jml_tamu'        => $request->jml_tamu,
            'harga_per_malam' => $room->harga_dasar,
            'total_bayar'     => $total,
            'diskon'          => $diskon,
            'status'          => $request->status,
            'rating'          => $request->rating,
            'catatan'         => $request->catatan,
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil diperbarui!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus!');
    }
}