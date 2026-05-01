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
    Schema::create('estimasis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
        $table->date('tanggal');
        $table->string('km')->nullable();
        $table->string('no_telp')->nullable();
        $table->string('no_chasis')->nullable();
        $table->string('no_mesin')->nullable();
        $table->json('keluhan')->nullable();
        $table->json('jasa')->nullable();
        $table->json('barang')->nullable();
        $table->bigInteger('total_jasa')->default(0);
        $table->bigInteger('total_part')->default(0);
        $table->bigInteger('grand_total')->default(0);
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimasis');
    }
};