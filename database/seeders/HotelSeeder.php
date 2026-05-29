<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            [
                'nama'            => 'NHG Jakarta',
                'tipe'            => 'hotel',
                'kota'            => 'Jakarta',
                'provinsi'        => 'DKI Jakarta',
                'bintang'         => 5,
                'kapasitas_total' => 180,
                'alamat'          => 'Jl. Sudirman No. 1, Jakarta Pusat',
                'telepon'         => '021-5551234',
                'email'           => 'jakarta@nhg.com',
                'is_active'       => true,
            ],
            [
                'nama'            => 'NHG Bali Resort',
                'tipe'            => 'resort',
                'kota'            => 'Badung',
                'provinsi'        => 'Bali',
                'bintang'         => 5,
                'kapasitas_total' => 120,
                'alamat'          => 'Jl. Pantai Kuta No. 88, Badung',
                'telepon'         => '0361-5559876',
                'email'           => 'bali@nhg.com',
                'is_active'       => true,
            ],
            [
                'nama'            => 'NHG Surabaya',
                'tipe'            => 'hotel',
                'kota'            => 'Surabaya',
                'provinsi'        => 'Jawa Timur',
                'bintang'         => 4,
                'kapasitas_total' => 90,
                'alamat'          => 'Jl. Tunjungan No. 45, Surabaya',
                'telepon'         => '031-5554567',
                'email'           => 'surabaya@nhg.com',
                'is_active'       => true,
            ],
        ];

        foreach ($hotels as $hotel) {
            Hotel::create($hotel);
        }
    }
}