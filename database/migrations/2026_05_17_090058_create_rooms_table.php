<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('nomor_kamar');
            $table->enum('tipe', ['standard','deluxe','suite','villa','penthouse']);
            $table->unsignedSmallInteger('kapasitas')->default(2);
            $table->decimal('harga_dasar', 12, 2);
            $table->unsignedTinyInteger('lantai')->default(1);
            $table->text('fasilitas')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('rooms');
        Schema::enableForeignKeyConstraints();
    }
};