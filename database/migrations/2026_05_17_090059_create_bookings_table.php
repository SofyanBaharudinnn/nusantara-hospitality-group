<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();

            // Pakai unsignedBigInteger + foreign manual (lebih aman)
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('channel_id');

            $table->date('tgl_checkin');
            $table->date('tgl_checkout');
            $table->unsignedSmallInteger('jml_malam');
            $table->unsignedSmallInteger('jml_tamu')->default(1);
            $table->decimal('harga_per_malam', 12, 2);
            $table->decimal('total_bayar', 15, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->enum('status', ['confirmed','pending','cancelled','completed'])->default('pending');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign keys manual
            $table->foreign('hotel_id')   ->references('id')->on('hotels')   ->onDelete('cascade');
            $table->foreign('room_id')    ->references('id')->on('rooms')     ->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers') ->onDelete('cascade');
            $table->foreign('channel_id') ->references('id')->on('channels')  ->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('bookings');
        Schema::enableForeignKeyConstraints();
    }
};