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
        $hotels = Hotel::withCount(['rooms','bookings'])->latest()->get();
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'tipe'            => 'required|in:hotel,resort,restoran',
            'kota'            => 'required|string|max:100',
            'provinsi'        => 'required|string|max:100',
            'bintang'         => 'required|integer|min:1|max:5',
            'kapasitas_total' => 'required|integer|min:1',
            'alamat'          => 'nullable|string',
            'telepon'         => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:100',
        ]);

        Hotel::create($request->all());

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel berhasil ditambahkan!');
    }

    public function show(Hotel $hotel)
    {
        $hotel->loadCount(['rooms','bookings']);
        $recentBookings = Booking::where('hotel_id', $hotel->id)
                            ->with(['customer','room','channel'])
                            ->latest()->limit(10)->get();
        $rooms = Room::where('hotel_id', $hotel->id)->get();
        return view('admin.hotels.show', compact('hotel','recentBookings','rooms'));
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'tipe'            => 'required|in:hotel,resort,restoran',
            'kota'            => 'required|string|max:100',
            'provinsi'        => 'required|string|max:100',
            'bintang'         => 'required|integer|min:1|max:5',
            'kapasitas_total' => 'required|integer|min:1',
            'alamat'          => 'nullable|string',
            'telepon'         => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:100',
            'is_active'       => 'boolean',
        ]);

        $hotel->update($request->all());

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