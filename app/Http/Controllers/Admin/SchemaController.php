<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SchemaController extends Controller
{
    public function index()
    {
        // Skema yang sudah diperbarui sesuai dengan dw_hospitality
        $dimensions = [
            'DIM_TIME' => [
                'color'  => '#a855f7',
                'fields' => [
                    'date_key (PK)', 'date', 'year', 'quarter',
                    'month', 'month_name', 'day', 'day_of_week', 'is_weekend',
                ],
            ],
            'DIM_HOTEL' => [
                'color'  => '#22d3ee',
                'fields' => [
                    'hotel_key (PK)', 'hotel_id', 'hotel_name',
                    'city', 'star_rating', 'hotel_type',
                ],
            ],
            'DIM_ROOM' => [
                'color'  => '#ec4899',
                'fields' => [
                    'room_key (PK)', 'room_id', 'hotel_key (FK)',
                    'room_type', 'capacity', 'base_rate',
                ],
            ],
            'DIM_GUEST' => [
                'color'  => '#f59e0b',
                'fields' => [
                    'guest_key (PK)', 'guest_id', 'guest_name',
                    'nationality', 'segment', 'city',
                ],
            ],
            'DIM_BOOKING_CHANNEL' => [
                'color'  => '#4ade80',
                'fields' => [
                    'channel_key (PK)', 'channel_name', 'channel_type',
                ],
            ],
        ];

        $fact = [
            'name'   => 'FACT_RESERVATION',
            'fields' => [
                'reservation_key (PK)',
                'date_key (FK → DIM_TIME)',
                'hotel_key (FK → DIM_HOTEL)',
                'room_key (FK → DIM_ROOM)',
                'guest_key (FK → DIM_GUEST)',
                'channel_key (FK → DIM_BOOKING_CHANNEL)',
                'nights',
                'rooms_booked',
                'room_revenue',
                'is_cancelled (Yes/No)',
            ],
        ];

        return view('admin.schema', compact('dimensions', 'fact'));
    }
}