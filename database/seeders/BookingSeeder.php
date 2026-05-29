<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = [
            // Hotel 1 - NHG Jakarta
            ['hotel_id'=>1,'room_id'=>1, 'customer_id'=>1, 'channel_id'=>8,'checkin'=>'-60 days','nights'=>3,'tamu'=>2,'harga'=>600000, 'status'=>'completed','rating'=>5],
            ['hotel_id'=>1,'room_id'=>2, 'customer_id'=>2, 'channel_id'=>1,'checkin'=>'-45 days','nights'=>4,'tamu'=>2,'harga'=>900000, 'status'=>'completed','rating'=>4],
            ['hotel_id'=>1,'room_id'=>3, 'customer_id'=>5, 'channel_id'=>5,'checkin'=>'-30 days','nights'=>2,'tamu'=>1,'harga'=>900000, 'status'=>'completed','rating'=>5],
            ['hotel_id'=>1,'room_id'=>4, 'customer_id'=>7, 'channel_id'=>8,'checkin'=>'-20 days','nights'=>5,'tamu'=>2,'harga'=>1500000,'status'=>'completed','rating'=>5],
            ['hotel_id'=>1,'room_id'=>5, 'customer_id'=>8, 'channel_id'=>2,'checkin'=>'-15 days','nights'=>3,'tamu'=>2,'harga'=>900000, 'status'=>'completed','rating'=>4],
            ['hotel_id'=>1,'room_id'=>6, 'customer_id'=>4, 'channel_id'=>6,'checkin'=>'-10 days','nights'=>2,'tamu'=>4,'harga'=>1500000,'status'=>'confirmed','rating'=>null],
            ['hotel_id'=>1,'room_id'=>7, 'customer_id'=>9, 'channel_id'=>1,'checkin'=>'-5 days', 'nights'=>3,'tamu'=>2,'harga'=>600000, 'status'=>'confirmed','rating'=>null],
            ['hotel_id'=>1,'room_id'=>8, 'customer_id'=>3, 'channel_id'=>3,'checkin'=>'today',   'nights'=>4,'tamu'=>2,'harga'=>900000, 'status'=>'confirmed','rating'=>null],
            ['hotel_id'=>1,'room_id'=>9, 'customer_id'=>6, 'channel_id'=>7,'checkin'=>'+3 days', 'nights'=>2,'tamu'=>1,'harga'=>600000, 'status'=>'pending',  'rating'=>null],
            ['hotel_id'=>1,'room_id'=>10,'customer_id'=>10,'channel_id'=>4,'checkin'=>'+7 days', 'nights'=>5,'tamu'=>2,'harga'=>2500000,'status'=>'pending',  'rating'=>null],

            // Hotel 2 - NHG Bali Resort
            ['hotel_id'=>2,'room_id'=>41,'customer_id'=>7, 'channel_id'=>1,'checkin'=>'-55 days','nights'=>5,'tamu'=>2,'harga'=>1200000,'status'=>'completed','rating'=>5],
            ['hotel_id'=>2,'room_id'=>42,'customer_id'=>1, 'channel_id'=>5,'checkin'=>'-40 days','nights'=>3,'tamu'=>2,'harga'=>2000000,'status'=>'completed','rating'=>4],
            ['hotel_id'=>2,'room_id'=>43,'customer_id'=>3, 'channel_id'=>2,'checkin'=>'-25 days','nights'=>7,'tamu'=>2,'harga'=>3500000,'status'=>'completed','rating'=>5],
            ['hotel_id'=>2,'room_id'=>44,'customer_id'=>11,'channel_id'=>3,'checkin'=>'-18 days','nights'=>4,'tamu'=>2,'harga'=>1200000,'status'=>'completed','rating'=>4],
            ['hotel_id'=>2,'room_id'=>45,'customer_id'=>12,'channel_id'=>8,'checkin'=>'-12 days','nights'=>6,'tamu'=>2,'harga'=>2000000,'status'=>'confirmed','rating'=>null],
            ['hotel_id'=>2,'room_id'=>46,'customer_id'=>2, 'channel_id'=>1,'checkin'=>'-8 days', 'nights'=>4,'tamu'=>2,'harga'=>3500000,'status'=>'confirmed','rating'=>null],
            ['hotel_id'=>2,'room_id'=>47,'customer_id'=>15,'channel_id'=>4,'checkin'=>'-3 days', 'nights'=>5,'tamu'=>2,'harga'=>1200000,'status'=>'confirmed','rating'=>null],
            ['hotel_id'=>2,'room_id'=>48,'customer_id'=>13,'channel_id'=>7,'checkin'=>'+2 days', 'nights'=>3,'tamu'=>1,'harga'=>800000, 'status'=>'pending',  'rating'=>null],
            ['hotel_id'=>2,'room_id'=>49,'customer_id'=>5, 'channel_id'=>5,'checkin'=>'+5 days', 'nights'=>7,'tamu'=>4,'harga'=>3500000,'status'=>'pending',  'rating'=>null],
            ['hotel_id'=>2,'room_id'=>50,'customer_id'=>8, 'channel_id'=>2,'checkin'=>'+10 days','nights'=>4,'tamu'=>2,'harga'=>2000000,'status'=>'cancelled','rating'=>null],

            // Hotel 3 - NHG Surabaya
            ['hotel_id'=>3,'room_id'=>91,'customer_id'=>4, 'channel_id'=>6,'checkin'=>'-50 days','nights'=>2,'tamu'=>2,'harga'=>500000, 'status'=>'completed','rating'=>4],
            ['hotel_id'=>3,'room_id'=>92,'customer_id'=>6, 'channel_id'=>1,'checkin'=>'-35 days','nights'=>3,'tamu'=>2,'harga'=>750000, 'status'=>'completed','rating'=>3],
            ['hotel_id'=>3,'room_id'=>93,'customer_id'=>14,'channel_id'=>8,'checkin'=>'-22 days','nights'=>4,'tamu'=>2,'harga'=>1200000,'status'=>'completed','rating'=>5],
            ['hotel_id'=>3,'room_id'=>94,'customer_id'=>9, 'channel_id'=>2,'checkin'=>'-16 days','nights'=>2,'tamu'=>1,'harga'=>500000, 'status'=>'completed','rating'=>4],
            ['hotel_id'=>3,'room_id'=>95,'customer_id'=>10,'channel_id'=>7,'checkin'=>'-9 days', 'nights'=>3,'tamu'=>2,'harga'=>750000, 'status'=>'confirmed','rating'=>null],
            ['hotel_id'=>3,'room_id'=>96,'customer_id'=>15,'channel_id'=>3,'checkin'=>'-4 days', 'nights'=>2,'tamu'=>2,'harga'=>500000, 'status'=>'confirmed','rating'=>null],
            ['hotel_id'=>3,'room_id'=>97,'customer_id'=>2, 'channel_id'=>5,'checkin'=>'+1 days', 'nights'=>5,'tamu'=>2,'harga'=>1200000,'status'=>'pending',  'rating'=>null],
            ['hotel_id'=>3,'room_id'=>98,'customer_id'=>13,'channel_id'=>4,'checkin'=>'+6 days', 'nights'=>3,'tamu'=>1,'harga'=>750000, 'status'=>'pending',  'rating'=>null],
        ];

        foreach ($bookings as $b) {
            $checkin  = Carbon::parse($b['checkin'])->startOfDay();
            $checkout = $checkin->copy()->addDays($b['nights']);
            $total    = ($b['harga'] * $b['nights']);
            $diskon   = in_array($b['status'], ['completed','confirmed']) && $b['nights'] >= 5
                        ? $total * 0.1 : 0;

            Booking::create([
                'kode_booking'    => Booking::generateKode(),
                'hotel_id'        => $b['hotel_id'],
                'room_id'         => $b['room_id'],
                'customer_id'     => $b['customer_id'],
                'channel_id'      => $b['channel_id'],
                'tgl_checkin'     => $checkin,
                'tgl_checkout'    => $checkout,
                'jml_malam'       => $b['nights'],
                'jml_tamu'        => $b['tamu'],
                'harga_per_malam' => $b['harga'],
                'total_bayar'     => $total - $diskon,
                'diskon'          => $diskon,
                'status'          => $b['status'],
                'rating'          => $b['rating'],
            ]);
        }
    }
}