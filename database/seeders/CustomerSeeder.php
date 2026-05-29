<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['nama'=>'Budi Santoso',      'email'=>'budi.s@email.com',     'telepon'=>'08111111001', 'segmen'=>'corporate', 'negara'=>'Indonesia', 'kota_asal'=>'Jakarta',   'tgl_lahir'=>'1985-03-15', 'tier'=>'platinum', 'total_kunjungan'=>24, 'total_spending'=>38200000],
            ['nama'=>'Siti Rahayu',       'email'=>'siti.r@email.com',     'telepon'=>'08111111002', 'segmen'=>'vip',       'negara'=>'Indonesia', 'kota_asal'=>'Surabaya',  'tgl_lahir'=>'1990-07-22', 'tier'=>'platinum', 'total_kunjungan'=>18, 'total_spending'=>42500000],
            ['nama'=>'Jessica Tan',       'email'=>'jessica.t@email.com',  'telepon'=>'08111111003', 'segmen'=>'leisure',   'negara'=>'Singapura', 'kota_asal'=>'Singapore', 'tgl_lahir'=>'1992-11-08', 'tier'=>'gold',     'total_kunjungan'=>12, 'total_spending'=>28700000],
            ['nama'=>'Ahmad Fauzi',       'email'=>'ahmad.f@email.com',    'telepon'=>'08111111004', 'segmen'=>'group',     'negara'=>'Indonesia', 'kota_asal'=>'Bandung',   'tgl_lahir'=>'1988-05-30', 'tier'=>'gold',     'total_kunjungan'=>8,  'total_spending'=>22100000],
            ['nama'=>'Dewi Kusuma',       'email'=>'dewi.k@email.com',     'telepon'=>'08111111005', 'segmen'=>'vip',       'negara'=>'Indonesia', 'kota_asal'=>'Jakarta',   'tgl_lahir'=>'1987-09-14', 'tier'=>'platinum', 'total_kunjungan'=>15, 'total_spending'=>35800000],
            ['nama'=>'Kevin Wijaya',      'email'=>'kevin.w@email.com',    'telepon'=>'08111111006', 'segmen'=>'leisure',   'negara'=>'Indonesia', 'kota_asal'=>'Medan',     'tgl_lahir'=>'1995-01-25', 'tier'=>'silver',   'total_kunjungan'=>9,  'total_spending'=>19400000],
            ['nama'=>'Rina Agustina',     'email'=>'rina.a@email.com',     'telepon'=>'08111111007', 'segmen'=>'vip',       'negara'=>'Indonesia', 'kota_asal'=>'Jakarta',   'tgl_lahir'=>'1983-06-18', 'tier'=>'platinum', 'total_kunjungan'=>20, 'total_spending'=>48000000],
            ['nama'=>'Michael Wong',      'email'=>'michael.w@email.com',  'telepon'=>'08111111008', 'segmen'=>'corporate', 'negara'=>'Australia', 'kota_asal'=>'Sydney',    'tgl_lahir'=>'1980-12-03', 'tier'=>'gold',     'total_kunjungan'=>10, 'total_spending'=>25000000],
            ['nama'=>'Putri Handayani',   'email'=>'putri.h@email.com',    'telepon'=>'08111111009', 'segmen'=>'leisure',   'negara'=>'Indonesia', 'kota_asal'=>'Yogyakarta','tgl_lahir'=>'1993-04-11', 'tier'=>'silver',   'total_kunjungan'=>6,  'total_spending'=>12000000],
            ['nama'=>'Rizky Pratama',     'email'=>'rizky.p@email.com',    'telepon'=>'08111111010', 'segmen'=>'group',     'negara'=>'Indonesia', 'kota_asal'=>'Makassar',  'tgl_lahir'=>'1991-08-27', 'tier'=>'silver',   'total_kunjungan'=>5,  'total_spending'=>9500000],
            ['nama'=>'Sarah Johnson',     'email'=>'sarah.j@email.com',    'telepon'=>'08111111011', 'segmen'=>'leisure',   'negara'=>'Amerika',   'kota_asal'=>'New York',  'tgl_lahir'=>'1989-02-14', 'tier'=>'gold',     'total_kunjungan'=>7,  'total_spending'=>18000000],
            ['nama'=>'David Lee',         'email'=>'david.l@email.com',    'telepon'=>'08111111012', 'segmen'=>'corporate', 'negara'=>'Singapura', 'kota_asal'=>'Singapore', 'tgl_lahir'=>'1984-10-05', 'tier'=>'platinum', 'total_kunjungan'=>16, 'total_spending'=>40000000],
            ['nama'=>'Anita Permata',     'email'=>'anita.p@email.com',    'telepon'=>'08111111013', 'segmen'=>'leisure',   'negara'=>'Indonesia', 'kota_asal'=>'Denpasar',  'tgl_lahir'=>'1996-03-20', 'tier'=>'silver',   'total_kunjungan'=>4,  'total_spending'=>8000000],
            ['nama'=>'Hendra Gunawan',    'email'=>'hendra.g@email.com',   'telepon'=>'08111111014', 'segmen'=>'corporate', 'negara'=>'Indonesia', 'kota_asal'=>'Semarang',  'tgl_lahir'=>'1986-07-09', 'tier'=>'gold',     'total_kunjungan'=>11, 'total_spending'=>27000000],
            ['nama'=>'Lily Chen',         'email'=>'lily.c@email.com',     'telepon'=>'08111111015', 'segmen'=>'leisure',   'negara'=>'Malaysia',  'kota_asal'=>'Kuala Lumpur','tgl_lahir'=>'1994-05-16','tier'=>'silver',  'total_kunjungan'=>5,  'total_spending'=>11000000],
        ];

        foreach ($customers as $c) {
            Customer::create($c);
        }
    }
}