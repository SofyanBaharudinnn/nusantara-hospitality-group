<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('telepon')->nullable();
            $table->enum('segmen', ['vip', 'corporate', 'leisure', 'group'])->default('leisure');
            $table->string('negara')->default('Indonesia');
            $table->string('kota_asal')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('tier', ['silver', 'gold', 'platinum'])->default('silver');
            $table->integer('total_kunjungan')->default(0);
            $table->decimal('total_spending', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};