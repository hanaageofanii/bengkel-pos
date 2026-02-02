<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\Jasa;
use App\Models\Barang;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // ================= INDEX + SEARCH =================
    public function index(Request $request)
    {
        $q = $request->q;

        $invoices = Invoice::with('pelanggan')
            ->when($q, function ($query) use ($q) {
                $query->where('invoice_no', 'like', "%$q%")
                    ->orWhereHas('pelanggan', function ($p) use ($q) {
                        $p->where('nama', 'like', "%$q%")
                          ->orWhere('plat_nomor', 'like', "%$q%")
                          ->orWhere('merk_mobil', 'like', "%$q%")
                          ->orWhere('model_mobil', 'like', "%$q%");
                    });
            })
            ->latest()
            ->paginate(10);

        return view('invoice.index', compact('invoices', 'q'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('invoice.create', [
            'pelanggans' => Pelanggan::orderBy('nama')->get(),
            'jasas'      => Jasa::orderBy('nama')->get(),
            'barangs'    => Barang::orderBy('nama')->get(),
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $invoiceNo = 'INV-' . date('Ymd') . '-' . rand(100,999);

        // JASA
        $jasa = [];
        foreach ($request->jasa_id ?? [] as $i => $id) {
            $jasa[] = [
                'id'    => $id,
                'nama'  => $request->jasa_nama[$i],
                'harga' => (int)$request->jasa_harga[$i],
            ];
        }

        // BARANG
        $barang = [];
        foreach ($request->barang_id ?? [] as $i => $id) {
            $qty   = (int)$request->barang_qty[$i];
            $harga = (int)$request->barang_harga[$i];

            $barang[] = [
                'id'    => $id,
                'nama'  => $request->barang_nama[$i],
                'qty'   => $qty,
                'harga' => $harga,
                'total' => $qty * $harga,
            ];
        }

        $totalJasa = collect($jasa)->sum('harga');
        $totalPart = collect($barang)->sum('total');

        $invoice = Invoice::create([
            'invoice_no'   => $invoiceNo,
            'pelanggan_id' => $request->pelanggan_id,
            'tanggal'      => $request->tanggal,
            'km'           => $request->km,
            'no_chasis'    => $request->no_chasis,
            'no_mesin'     => $request->no_mesin,
            'no_telp'      => $request->no_telp,
            'keluhan'      => $request->keluhan,
            'jasa'         => $jasa,
            'barang'       => $barang,
            'total_jasa'   => $totalJasa,
            'total_part'   => $totalPart,
            'grand_total'  => $totalJasa + $totalPart,
            'status_bayar' => $request->status_bayar,
            'metode_bayar' => $request->metode_bayar,
        ]);

        return redirect()->route('invoice.print', $invoice);
    }

    // ================= PRINT =================
    public function print(Invoice $invoice)
    {
        return view('invoice.print', compact('invoice'));
    }
}