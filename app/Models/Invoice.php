<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no','pelanggan_id','tanggal',
        'km','no_chasis','no_mesin','no_telp',
        'keluhan','jasa','barang',
        'total_jasa','total_part','grand_total',
        'metode_bayar','payment_awal','tanggal_bayar',
        'status_bayar','sisa'
    ];

    protected $casts = [
        'keluhan' => 'array',
        'jasa'    => 'array',
        'barang'  => 'array',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    // Helper saja, bukan accessor
    public function totalTerbayar()
    {
        return (int)$this->payment_awal +
               (int)$this->payments()->sum('jumlah');
    }

    public function isLunas()
    {
        return $this->sisa <= 0;
    }
}
