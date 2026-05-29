<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::updateOrCreate(['email' => 'admin@nhg.com'], [
            'name'              => 'Admin NHG',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate(['email' => 'user@nhg.com'], [
            'name'              => 'Staff NHG',
            'password'          => Hash::make('password'),
            'role'              => 'user',
            'email_verified_at' => now(),
        ]);

        // Data
        $this->call([
            HotelSeeder::class,
            RoomSeeder::class,
            ChannelSeeder::class,
            CustomerSeeder::class,
            BookingSeeder::class,
        ]);

        $this->command->info('✅ Semua data berhasil di-seed!');
        $this->command->info('   Admin : admin@nhg.com / password');
        $this->command->info('   User  : user@nhg.com  / password');
    }
}