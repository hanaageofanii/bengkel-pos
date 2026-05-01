<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimasi extends Model
{
    protected $fillable = [
        'pelanggan_id','tanggal','km','no_telp',
        'no_chasis','no_mesin','keluhan','jasa',
        'barang','total_jasa','total_part','grand_total','notes',
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
}
