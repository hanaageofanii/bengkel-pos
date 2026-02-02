<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = ['nama', 'jabatan', 'status'];

    public function absensis()
{
    return $this->hasMany(Absensi::class);
}

}