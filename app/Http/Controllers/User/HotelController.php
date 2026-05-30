<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $hotels = Hotel::withCount(['rooms', 'bookings'])->get();
        return view('user.hotels', compact('hotels'));
    }
}
