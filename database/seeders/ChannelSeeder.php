<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $channels = [
            ['nama' => 'Booking.com',     'tipe' => 'online',  'platform' => 'booking.com',    'komisi_pct' => 15.0, 'is_online' => true],
            ['nama' => 'Traveloka',       'tipe' => 'online',  'platform' => 'traveloka.com',  'komisi_pct' => 12.0, 'is_online' => true],
            ['nama' => 'Agoda',           'tipe' => 'online',  'platform' => 'agoda.com',      'komisi_pct' => 14.0, 'is_online' => true],
            ['nama' => 'Tiket.com',       'tipe' => 'online',  'platform' => 'tiket.com',      'komisi_pct' => 11.0, 'is_online' => true],
            ['nama' => 'Direct Website',  'tipe' => 'direct',  'platform' => 'nhg.com',        'komisi_pct' => 0.0,  'is_online' => true],
            ['nama' => 'Reservation Tel', 'tipe' => 'offline', 'platform' => 'Telepon',        'komisi_pct' => 0.0,  'is_online' => false],
            ['nama' => 'Walk-in',         'tipe' => 'offline', 'platform' => 'Front Desk',     'komisi_pct' => 0.0,  'is_online' => false],
            ['nama' => 'CRM / Corporate', 'tipe' => 'direct',  'platform' => 'CRM System',     'komisi_pct' => 5.0,  'is_online' => false],
        ];

        foreach ($channels as $channel) {
            Channel::create(array_merge($channel, ['is_active' => true]));
        }
    }
}