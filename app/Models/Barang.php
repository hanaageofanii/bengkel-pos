<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama',
        'harga_pribadi',
        'harga_perusahaan',
        'stok',
        'satuan',
    ];
}