<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\Jasa;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $invoiceNo = 'INV-' . date('Ymd') . '-' . rand(100, 999);

        DB::transaction(function () use ($request, $invoiceNo) {

            /* ================= JASA ================= */
            $jasa = [];
            foreach ($request->jasa_id ?? [] as $i => $id) {
                if (!$id) continue;

                $jasa[] = [
                    'id'    => $id,
                    'nama'  => $request->jasa_nama[$i] ?? '',
                    'harga' => (int) ($request->jasa_harga[$i] ?? 0),
                ];
            }

            /* ================= BARANG (AKUMULASI PER ID) ================= */
            $barangMap = [];

            foreach ($request->barang_id ?? [] as $i => $id) {
                if (!$id) continue;

                $qty   = (int) ($request->barang_qty[$i] ?? 0);
                $harga = (int) ($request->barang_harga[$i] ?? 0);

                if ($qty <= 0) continue;

                if (!isset($barangMap[$id])) {
                    $barangMap[$id] = [
                        'id'    => $id,
                        'nama'  => $request->barang_nama[$i] ?? '',
                        'qty'   => 0,
                        'harga' => $harga,
                        'total' => 0,
                    ];
                }

                $barangMap[$id]['qty'] += $qty;
                $barangMap[$id]['total'] =
                    $barangMap[$id]['qty'] * $barangMap[$id]['harga'];
            }

            /* ================= CEK & POTONG STOK ================= */
            $barang = [];

            foreach ($barangMap as $item) {

                $barangModel = Barang::lockForUpdate()->findOrFail($item['id']);

                if ($barangModel->stok < $item['qty']) {
                    abort(400, "Stock {$barangModel->nama} tidak mencukupi");
                }

                $barangModel->decrement('stok', $item['qty']);

                $barang[] = $item;
            }

            /* ================= TOTAL ================= */
            $totalJasa = collect($jasa)->sum('harga');
            $totalPart = collect($barang)->sum('total');

            /* ================= STATUS BAYAR (ENUM AMAN) ================= */
            $statusBayar = $request->status_bayar === 'lunas'
                ? 'sudah'
                : $request->status_bayar;

            /* ================= SIMPAN INVOICE ================= */
            Invoice::create([
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
                'status_bayar' => $statusBayar,
                'metode_bayar' => $request->metode_bayar,
            ]);
        });

        return redirect()->route('invoice.index');
    }

    // ================= PRINT =================
    public function print(Invoice $invoice)
    {
        return view('invoice.print', compact('invoice'));
    }
}