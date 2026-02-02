<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    protected $fillable = [
        'nama',
        'harga_pribadi',
        'harga_perusahaan',
        'keterangan',
    ];
}