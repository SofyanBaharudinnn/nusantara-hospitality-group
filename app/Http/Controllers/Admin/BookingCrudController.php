<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Channel;
use App\Models\Customer;
use App\Models\DimTime;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingCrudController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['hotel', 'room', 'customer', 'channel', 'dimTime'])
                        ->latest('reservation_key');

        if ($request->filled('hotel_id'))
            $query->where('hotel_key', $request->hotel_id);

        if ($request->filled('status')) {
            $isCancelled = $request->status === 'cancelled' ? 'Yes' : 'No';
            $query->where('is_cancelled', $isCancelled);
        }

        if ($request->filled('search')) {
            $guestKeys = Customer::where('guest_name', 'like', '%'.$request->search.'%')
                            ->pluck('guest_key');
            $query->whereIn('guest_key', $guestKeys);
        }

        $bookings = $query->paginate(15)->withQueryString();
        $hotels   = Hotel::all();
        $statuses = ['confirmed', 'cancelled'];

        return view('admin.bookings.index', compact('bookings', 'hotels', 'statuses'));
    }

    public function create()
    {
        $hotels    = Hotel::all();
        $customers = Customer::orderBy('guest_name')->get();
        $channels  = Channel::all();
        $rooms     = collect();

        return view('admin.bookings.create', compact('hotels', 'customers', 'channels', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_key'    => 'required|exists:dim_hotel,hotel_key',
            'room_key'     => 'required|exists:dim_room,room_key',
            'guest_key'    => 'required|exists:dim_guest,guest_key',
            'channel_key'  => 'required|exists:dim_booking_channel,channel_key',
            'date_key'     => 'required|integer',
            'nights'       => 'required|integer|min:1',
            'rooms_booked' => 'required|integer|min:1',
        ]);

        $room    = Room::findOrFail($request->room_key);
        $revenue = $room->base_rate * $request->nights;

        Booking::create([
            'hotel_key'    => $request->hotel_key,
            'room_key'     => $request->room_key,
            'guest_key'    => $request->guest_key,
            'channel_key'  => $request->channel_key,
            'date_key'     => $request->date_key,
            'nights'       => $request->nights,
            'rooms_booked' => $request->rooms_booked,
            'room_revenue' => $revenue,
            'is_cancelled' => 'No',
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Reservasi berhasil ditambahkan!');
    }

    public function show(Booking $booking)
    {
        $booking->load(['hotel', 'room', 'customer', 'channel', 'dimTime']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $hotels    = Hotel::all();
        $customers = Customer::orderBy('guest_name')->get();
        $channels  = Channel::all();
        $rooms     = Room::where('hotel_key', $booking->hotel_key)->get();
        return view('admin.bookings.edit', compact('booking', 'hotels', 'customers', 'channels', 'rooms'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'is_cancelled' => 'required|in:Yes,No',
            'nights'       => 'required|integer|min:1',
            'rooms_booked' => 'required|integer|min:1',
        ]);

        $room    = Room::find($booking->room_key);
        $revenue = $room ? $room->base_rate * $request->nights : $booking->room_revenue;

        $booking->update([
            'nights'       => $request->nights,
            'rooms_booked' => $request->rooms_booked,
            'room_revenue' => $revenue,
            'is_cancelled' => $request->is_cancelled,
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Reservasi berhasil diperbarui!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Reservasi berhasil dihapus!');
    }
}