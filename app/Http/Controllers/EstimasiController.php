<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Jasa;
use App\Models\Barang;
use Illuminate\Http\Request;

class EstimasiController extends Controller
{
    public function create()
    {
        return view('estimasi.create', [
            'pelanggans' => Pelanggan::orderBy('nama')->get(),
            'jasas'      => Jasa::orderBy('nama')->get(),
            'barangs'    => Barang::orderBy('nama')->get(),
        ]);
    }

    public function preview(Request $request)
    {
        // Ambil data pelanggan
        $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);

        // Susun jasa
        $jasa = [];
        foreach ($request->jasa_id ?? [] as $i => $id) {
            if (empty($id)) continue;
            $jasa[] = [
                'id'    => (int) $id,
                'nama'  => $request->jasa_nama[$i] ?? '',
                'harga' => (int) ($request->jasa_harga[$i] ?? 0),
            ];
        }

        // Susun barang
        $barang = [];
        foreach ($request->barang_id ?? [] as $i => $id) {
            if (empty($id)) continue;
            $qty   = (int) ($request->barang_qty[$i] ?? 1);
            $harga = (int) ($request->barang_harga[$i] ?? 0);
            if ($qty <= 0) continue;
            $barang[] = [
                'id'    => (int) $id,
                'nama'  => $request->barang_nama[$i] ?? '',
                'qty'   => $qty,
                'harga' => $harga,
                'total' => $qty * $harga,
            ];
        }

        $totalJasa  = collect($jasa)->sum('harga');
        $totalPart  = collect($barang)->sum('total');
        $grandTotal = $totalJasa + $totalPart;

        $data = [
            'pelanggan'  => $pelanggan,
            'tanggal'    => $request->tanggal,
            'km'         => $request->km,
            'no_chasis'  => $request->no_chasis,
            'no_mesin'   => $request->no_mesin,
            'no_telp'    => $request->no_telp,
            'keluhan'    => array_values(array_filter($request->keluhan ?? [])),
            'jasa'       => $jasa,
            'barang'     => $barang,
            'total_jasa' => $totalJasa,
            'total_part' => $totalPart,
            'grand_total'=> $grandTotal,
        ];

        return view('estimasi.preview', compact('data'));
    }
}