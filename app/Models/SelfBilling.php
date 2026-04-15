<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfBilling extends Model
{
    protected $table = 'self_billings';

    protected $fillable = [
        'tanggal',
        'nama_vendor',
        'jenis_barang',
        'jumlah_barang',
        'total_tagihan',
        'payment_notes',
    ];

    public function payments()
    {
        return $this->hasMany(SelfBillingPayment::class);
    }

    public function getSisaTagihanAttribute()
    {
        $totalTerbayar = $this->payments()->sum('nominal_bayar');
        return $this->total_tagihan - $totalTerbayar;
    }
}