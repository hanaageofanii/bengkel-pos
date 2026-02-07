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
            if (empty($id)) continue;

            $jasa[] = [
                'id'    => (int) $id,
                'nama'  => $request->jasa_nama[$i] ?? '',
                'harga' => (int) ($request->jasa_harga[$i] ?? 0),
            ];
        }

        /* ================= BARANG (SUPER AMAN) ================= */
        $barangMap = [];

        foreach ($request->barang_id ?? [] as $i => $id) {

            if (empty($id)) continue;

            $qty   = (int) ($request->barang_qty[$i] ?? 0);
            $harga = (int) ($request->barang_harga[$i] ?? 0);

            if ($qty <= 0) continue;

            if (!isset($barangMap[$id])) {
                $barangMap[$id] = [
                    'id'    => (int) $id,
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

        /* ================= CEK & POTONG STOK (ANTI DOBEL) ================= */
        $barangFinal = [];

        foreach ($barangMap as $item) {

            $barangModel = Barang::lockForUpdate()->findOrFail($item['id']);

            if ($barangModel->stok < $item['qty']) {
                abort(400, "Stock {$barangModel->nama} tidak mencukupi");
            }

            $barangModel->decrement('stok', $item['qty']);

            $barangFinal[] = $item;
        }

        /* ================= TOTAL ================= */
        $totalJasa = collect($jasa)->sum('harga');
        $totalPart = collect($barangFinal)->sum('total');

        /* ================= STATUS BAYAR ================= */
        $statusBayar = $request->status_bayar === 'lunas'
            ? 'sudah'
            : 'belum';

        /* ================= SIMPAN INVOICE ================= */
        Invoice::create([
            'invoice_no'   => $invoiceNo,
            'pelanggan_id' => $request->pelanggan_id,
            'tanggal'      => $request->tanggal,
            'km'           => $request->km,
            'no_chasis'    => $request->no_chasis,
            'no_mesin'     => $request->no_mesin,
            'no_telp'      => $request->no_telp,
            'keluhan'      => array_values(array_filter($request->keluhan ?? [])),
            'jasa'         => $jasa,
            'barang'       => $barangFinal,
            'total_jasa'   => $totalJasa,
            'total_part'   => $totalPart,
            'grand_total'  => $totalJasa + $totalPart,
            'status_bayar' => $statusBayar,
            'metode_bayar' => $request->metode_bayar,
        ]);
    });

    return redirect()->route('invoice.index')
        ->with('success', 'Invoice berhasil dibuat');
}

// ================= SHOW =================
    public function show(Invoice $invoice)
    {
        return view('invoice.show', compact('invoice'));
    }

    // ================= EDIT =================
    public function edit(Invoice $invoice)
    {
        return view('invoice.edit', [
            'invoice'    => $invoice,
            'pelanggans' => Pelanggan::all(),
            'jasas'      => Jasa::all(),
            'barangs'    => Barang::all(),
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request, Invoice $invoice)
    {
        DB::transaction(function () use ($request, $invoice) {

            // balikin stok lama
            foreach ($invoice->barang as $b) {
                Barang::where('id', $b['id'])->increment('stok', $b['qty']);
            }

            // pakai logic STORE lagi (ringkas)
            $request->merge(['_method' => 'STORE']);
            $this->store($request);

            $invoice->delete(); // hapus invoice lama
        });

        return redirect()->route('invoice.index')->with('success','Invoice diupdate');
    }

    // ================= DELETE =================
    public function destroy(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            foreach ($invoice->barang as $b) {
                Barang::where('id', $b['id'])->increment('stok', $b['qty']);
            }
            $invoice->delete();
        });

        return back()->with('success','Invoice dihapus');
    }

    // ================= PRINT =================
    public function print(Invoice $invoice)
    {
        return view('invoice.print', compact('invoice'));
    }
}
