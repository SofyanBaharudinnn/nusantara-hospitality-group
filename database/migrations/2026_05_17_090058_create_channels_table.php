<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe', ['online', 'offline', 'direct']);
            $table->string('platform')->nullable();
            $table->decimal('komisi_pct', 5, 2)->default(0);
            $table->boolean('is_online')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};