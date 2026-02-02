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
        'status_bayar','metode_bayar'
    ];

    protected $casts = [
        'keluhan' => 'array',
        'jasa' => 'array',
        'barang' => 'array',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function barangs()
    {
        return $this->belongsTo(Barang::class);
    }

    public function jasas()
    {
        return $this->belongsTo(Jasa::class);
    }
}