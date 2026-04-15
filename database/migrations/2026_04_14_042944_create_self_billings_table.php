<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('self_billings', function (Blueprint $table) {
            $table->id();
        $table->date('tanggal');
        $table->string('nama_vendor');
        $table->string('jenis_barang');
        $table->integer('jumlah_barang');
        $table->bigInteger('total_tagihan');
        $table->text('payment_notes')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_billings');
    }
};