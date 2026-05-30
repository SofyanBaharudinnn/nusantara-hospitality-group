<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class DemographicController extends Controller
{
    public function index(Request $request)
    {
        $segments = Customer::select('segment', DB::raw('count(*) as count'))
            ->groupBy('segment')
            ->pluck('count', 'segment');
            
        $nationalities = Customer::select('nationality', DB::raw('count(*) as count'))
            ->groupBy('nationality')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->pluck('count', 'nationality');
            
        // Tamu terbaru
        $recentGuests = Customer::orderBy('guest_key', 'desc')->limit(12)->get();

        return view('user.demographics', compact('segments', 'nationalities', 'recentGuests'));
    }
}
