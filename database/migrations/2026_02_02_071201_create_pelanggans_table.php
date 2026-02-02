<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();

            // NAMA PELANGGAN (ORANG / PERUSAHAAN)
            $table->string('nama');

            $table->string('no_hp')->nullable();
            $table->enum('tipe', ['pribadi', 'perusahaan'])->default('pribadi');

            // DATA MOBIL
            $table->string('plat_nomor')->unique();
            $table->string('merk_mobil');
            $table->string('model_mobil');
            $table->year('tahun_mobil')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};