<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $fillable = [
        'invoice_id',
        'jumlah',
        'tanggal_bayar',
        'metode'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

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
