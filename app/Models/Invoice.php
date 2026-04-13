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
        'status_bayar','sisa','notes'
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

    /**
     * Accessor — bisa dipanggil di blade sebagai $invoice->total_terbayar
     * Otomatis load dari relasi yang sudah di-eager load (tidak query ulang)
     */
    public function getTotalTerbayarAttribute(): int
    {
        return (int) $this->payment_awal
             + (int) $this->payments->sum('jumlah'); // pakai ->payments (relasi), bukan ->payments()
    }

    /**
     * Accessor — sisa real yang selalu sinkron dengan cicilan
     * Baca dari kolom sisa di DB yang sudah diupdate controller
     */
    public function getSisaTagihanAttribute(): int
    {
        return max(0, (int) $this->sisa);
    }

    /**
     * Helper boolean — boleh tetap ada
     */
    public function isLunas(): bool
    {
        return $this->sisa <= 0;
    }
}