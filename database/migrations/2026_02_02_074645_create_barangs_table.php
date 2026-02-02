<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->integer('harga_pribadi');
            $table->integer('harga_perusahaan');
            $table->integer('stok')->default(0);
            $table->string('satuan')->default('pcs');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
