<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            // [hotel_id, tipe, jumlah, harga_dasar, lantai_mulai]
            [1, 'standard', 40, 600000,  1],
            [1, 'deluxe',   80, 900000,  3],
            [1, 'suite',    40, 1500000, 8],
            [1, 'villa',    20, 2500000, 12],

            [2, 'standard', 20, 800000,  1],
            [2, 'deluxe',   50, 1200000, 2],
            [2, 'suite',    30, 2000000, 4],
            [2, 'villa',    20, 3500000, 5],

            [3, 'standard', 30, 500000,  1],
            [3, 'deluxe',   40, 750000,  3],
            [3, 'suite',    20, 1200000, 6],
        ];

        foreach ($configs as [$hotelId, $tipe, $jumlah, $harga, $lantaiMulai]) {
            for ($i = 1; $i <= $jumlah; $i++) {
                Room::create([
                    'hotel_id'     => $hotelId,
                    'nomor_kamar'  => strtoupper(substr($tipe, 0, 1)) . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'tipe'         => $tipe,
                    'kapasitas'    => match($tipe) {
                        'standard'  => 2,
                        'deluxe'    => 2,
                        'suite'     => 4,
                        'villa'     => 6,
                        'penthouse' => 8,
                        default     => 2,
                    },
                    'harga_dasar'  => $harga,
                    'lantai'       => $lantaiMulai + intdiv($i - 1, 10),
                    'fasilitas'    => match($tipe) {
                        'standard'  => 'AC, TV, WiFi, Kamar Mandi',
                        'deluxe'    => 'AC, TV 55", WiFi, Kamar Mandi, Mini Bar',
                        'suite'     => 'AC, TV 65", WiFi, Bathtub, Mini Bar, Ruang Tamu',
                        'villa'     => 'AC, TV, WiFi, Private Pool, Dapur, Taman',
                        default     => 'AC, TV, WiFi',
                    },
                    'is_available' => true,
                ]);
            }
        }
    }
}