<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class HotelCrudController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all()->map(function ($hotel) {
            $hotel->rooms_count    = Room::where('hotel_key', $hotel->hotel_key)->count();
            $hotel->bookings_count = Booking::where('hotel_key', $hotel->hotel_key)->count();
            return $hotel;
        });
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_name'  => 'required|string|max:255',
            'hotel_type'  => 'required|string|max:100',
            'city'        => 'required|string|max:100',
            'star_rating' => 'required|integer|min:1|max:5',
        ]);

        Hotel::create($request->only(['hotel_id', 'hotel_name', 'hotel_type', 'city', 'star_rating']));

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel berhasil ditambahkan!');
    }

    public function show(Hotel $hotel)
    {
        $recentBookings = Booking::where('hotel_key', $hotel->hotel_key)
                            ->with(['customer', 'room', 'channel'])
                            ->latest('reservation_key')
                            ->limit(10)
                            ->get();
        $rooms = Room::where('hotel_key', $hotel->hotel_key)->get();
        return view('admin.hotels.show', compact('hotel', 'recentBookings', 'rooms'));
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'hotel_name'  => 'required|string|max:255',
            'hotel_type'  => 'required|string|max:100',
            'city'        => 'required|string|max:100',
            'star_rating' => 'required|integer|min:1|max:5',
        ]);

        $hotel->update($request->only(['hotel_name', 'hotel_type', 'city', 'star_rating']));

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel berhasil diperbarui!');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel berhasil dihapus!');
    }
}