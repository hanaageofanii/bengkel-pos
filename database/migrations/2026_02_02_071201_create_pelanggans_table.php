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
            $table->string('nama')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('tipe', ['pribadi', 'perusahaan'])->default('pribadi');
            $table->string('plat_nomor')->unique();
            $table->string('merk_mobil');
            $table->string('model_mobil');
            $table->string('no_chasis')->nullable();
            $table->string('no_mesin')->nullable();
            $table->year('tahun_mobil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
