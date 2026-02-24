<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->bigInteger('payment_awal')->default(0)->after('grand_total');
            $table->bigInteger('sisa')->default(0)->after('payment_awal');
            $table->date('tanggal_bayar')->nullable()->after('tanggal');

        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->dropColumn([
                'payment_awal',
                'sisa',
                'tanggal_bayar'
            ]);

        });
    }
};
