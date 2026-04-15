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
        Schema::create('self_billing_payments', function (Blueprint $table) {
           $table->id();
        $table->foreignId('self_billing_id')->constrained('self_billings')->onDelete('cascade');
        $table->date('tanggal_bayar');
        $table->bigInteger('nominal_bayar');
        $table->string('metode_bayar');
        $table->text('catatan')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_billing_payments');
    }
};