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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_no')->unique();

        $table->foreignId('pelanggan_id')->constrained();

        $table->date('tanggal');
        $table->integer('km')->nullable();
        $table->string('no_chasis')->nullable();
        $table->string('no_mesin')->nullable();
        $table->string('no_telp')->nullable();

        $table->json('keluhan')->nullable();
        $table->json('jasa')->nullable();
        $table->json('barang')->nullable();

        $table->integer('total_jasa')->default(0);
        $table->integer('total_part')->default(0);
        $table->integer('grand_total')->default(0);

        $table->enum('status_bayar', ['belum', 'sudah'])->default('belum');
        $table->enum('metode_bayar', ['cash', 'mandiri', 'bca'])->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
