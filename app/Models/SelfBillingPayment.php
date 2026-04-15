<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfBillingPayment extends Model
{
    protected $fillable = ['self_billing_id', 'tanggal_bayar', 'nominal_bayar', 'metode_bayar', 'catatan'];

    public function selfBilling()
    {
        return $this->belongsTo(SelfBilling::class);
    }
}