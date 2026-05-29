<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SchemaController extends Controller
{
    public function index()
    {
        $dimensions = [
            'DIM_DATE' => [
                'color'  => '#a855f7',
                'fields' => [
                    'date_id (PK)', 'tanggal', 'hari', 'minggu',
                    'bulan', 'kuartal', 'tahun', 'musim', 'is_weekend',
                ],
            ],
            'DIM_HOTEL' => [
                'color'  => '#22d3ee',
                'fields' => [
                    'hotel_id (PK)', 'nama_hotel', 'tipe',
                    'kota', 'provinsi', 'bintang', 'kapasitas_total',
                ],
            ],
            'DIM_ROOM' => [
                'color'  => '#ec4899',
                'fields' => [
                    'room_id (PK)', 'hotel_id (FK)', 'tipe_kamar',
                    'kapasitas', 'fasilitas', 'harga_dasar', 'lantai',
                ],
            ],
            'DIM_CUSTOMER' => [
                'color'  => '#f59e0b',
                'fields' => [
                    'customer_id (PK)', 'nama', 'email', 'telepon',
                    'segmen', 'negara', 'kota_asal', 'tgl_lahir',
                ],
            ],
            'DIM_CHANNEL' => [
                'color'  => '#4ade80',
                'fields' => [
                    'channel_id (PK)', 'nama_channel',
                    'tipe', 'platform', 'komisi_pct',
                ],
            ],
        ];

        $fact = [
            'name'   => 'FACT_BOOKING',
            'fields' => [
                'booking_id (PK)',
                'date_id (FK)',
                'hotel_id (FK)',
                'room_id (FK)',
                'customer_id (FK)',
                'channel_id (FK)',
                'tgl_checkin',
                'tgl_checkout',
                'jml_malam',
                'jml_tamu',
                'harga_per_malam',
                'total_bayar',
                'diskon',
                'status',
                'rating',
                'created_at',
            ],
        ];

        return view('admin.schema', compact('dimensions', 'fact'));
    }
}