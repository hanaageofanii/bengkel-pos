<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\Jasa;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\InvoicePayment;

class InvoiceController extends Controller
{
    // ================= INDEX + SEARCH =================
    public function index(Request $request)
{
    $q      = $request->q;
    $dari   = $request->dari;
    $sampai = $request->sampai;

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
        ->when($dari,   fn($q) => $q->whereDate('tanggal', '>=', $dari))
        ->when($sampai, fn($q) => $q->whereDate('tanggal', '<=', $sampai))
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('invoice.index', compact('invoices', 'q', 'dari', 'sampai'));
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
            return redirect()->back()
                ->withInput()
                ->with('error', "Stock {$barangModel->nama} tidak mencukupi");
        }

            $barangModel->decrement('stok', $item['qty']);

            $barangFinal[] = $item;
        }

        /* ================= TOTAL ================= */
        $totalJasa = collect($jasa)->sum('harga');
        $totalPart = collect($barangFinal)->sum('total');
        $grandTotal = $totalJasa + $totalPart;

        $paymentAwal = (int) ($request->payment_awal ?? 0);

        if ($paymentAwal < 0) {
            abort(400,'Payment tidak boleh minus');
        }

        if ($paymentAwal > $grandTotal) {
            abort(400,'Payment tidak boleh melebihi total');
        }

        $sisa = $grandTotal - $paymentAwal;
        /* ================= STATUS BAYAR ================= */

        $statusBayar = $sisa == 0 ? 'sudah' : 'belum';

        // /* ================= STATUS BAYAR ================= */
        // $statusBayar = $request->status_bayar === 'lunas'
        //     ? 'sudah'
        //     : 'belum';

        /* ================= SIMPAN INVOICE ================= */
/* ================= SIMPAN INVOICE ================= */
$invoice = new Invoice();

$invoice->invoice_no   = $invoiceNo;
$invoice->pelanggan_id = $request->pelanggan_id;
$invoice->tanggal      = $request->tanggal;

$invoice->km        = $request->km;
$invoice->no_chasis = $request->no_chasis;
$invoice->no_mesin  = $request->no_mesin;
$invoice->no_telp   = $request->no_telp;

$invoice->keluhan = array_values(array_filter($request->keluhan ?? []));
$invoice->jasa    = $jasa;
$invoice->barang  = $barangFinal;

$invoice->total_jasa  = $totalJasa;
$invoice->total_part  = $totalPart;
$invoice->grand_total = $grandTotal;

$paymentAwal = (int) ($request->payment_awal ?? 0);

if ($paymentAwal < 0 || $paymentAwal > $grandTotal) {
    abort(400, 'Payment tidak valid');
}

$invoice->payment_awal = $paymentAwal;
$invoice->status_bayar = $paymentAwal >= $grandTotal ? 'sudah' : 'belum';
$invoice->tanggal_bayar = $paymentAwal >= $grandTotal ? now() : null;
$invoice->metode_bayar  = $request->metode_bayar;
$invoice->sisa = $grandTotal - $paymentAwal;

$invoice->notes = $request->notes;
$invoice->save();

// SIMPAN CICILAN TAMBAHAN
if ($request->cicilan_jumlah) {

    foreach ($request->cicilan_jumlah as $i => $jumlah) {

        if (!$jumlah || $jumlah <= 0) continue;

        $invoice->payments()->create([
            'jumlah' => $jumlah,
            'tanggal_bayar' => $request->cicilan_tanggal[$i] ?? now(),
            'metode' => $request->cicilan_metode[$i] ?? 'cash',
        ]);
    }
}
    });

    return redirect()->route('invoice.index')
        ->with('success', 'Invoice berhasil dibuat');
}

// ================= SHOW =================
  public function show(Invoice $invoice)
{
    $invoice->load('payments', 'pelanggan');
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

        // /* ================= BALIKIN STOK LAMA ================= */
        // foreach ($invoice->barang as $b) {
        //     Barang::where('id', $b['id'])
        //         ->increment('stok', $b['qty']);
        // }

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

/* ================= BARANG ================= */
$barangMap = [];

foreach ($request->barang_id ?? [] as $i => $id) {
    if (empty($id)) continue;

    $qty   = (int) ($request->barang_qty[$i] ?? 0);
    $harga = (int) ($request->barang_harga[$i] ?? 0);

    if ($qty <= 0) continue;

    // REPLACE bukan +=, cegah dobel kalau id sama muncul 2x
    $barangMap[$id] = [
        'id'    => (int) $id,
        'nama'  => $request->barang_nama[$i] ?? '',
        'qty'   => $qty,
        'harga' => $harga,
        'total' => $qty * $harga,
    ];
}

// Data barang lama dari DB, di-index by id
$barangLama = collect($invoice->barang)->keyBy('id');
$barangFinal = [];

foreach ($barangMap as $id => $item) {
    $barangModel = Barang::lockForUpdate()->findOrFail($item['id']);

    $qtyLama = (int) ($barangLama->get($id)['qty'] ?? 0);
    $selisih = $item['qty'] - $qtyLama; // positif=tambah, negatif=kurang

    if ($selisih > 0 && $barangModel->stok < $selisih) {
        abort(422, "Stok {$barangModel->nama} tidak mencukupi");
    }

    if ($selisih !== 0) {
        $barangModel->increment('stok', -$selisih);
    }

    $barangFinal[] = $item;
}

foreach ($barangLama as $id => $lama) {
    if (!isset($barangMap[$id])) {
        Barang::where('id', $id)->increment('stok', $lama['qty']);
    }
}
        /* ================= TOTAL ================= */
        $totalJasa  = collect($jasa)->sum('harga');
        $totalPart  = collect($barangFinal)->sum('total');
        $grandTotal = $totalJasa + $totalPart;

        $paymentAwal = $request->status_bayar === 'sudah'
            ? $grandTotal
            : (int) ($request->payment_awal ?? 0);

        if ($paymentAwal > $grandTotal) {
            abort(400,'Payment tidak boleh melebihi total');
        }

$totalCicilan = $invoice->payments()->sum('jumlah');
$sisa = max(0, $grandTotal - $paymentAwal - $totalCicilan);

$statusBayar = $sisa == 0 ? 'sudah' : 'belum';
        /* ================= UPDATE INVOICE ================= */
        $invoice->update([
            'pelanggan_id' => $request->pelanggan_id,
            'tanggal'      => $request->tanggal,
            'tanggal_bayar'=> $paymentAwal > 0 ? now() : null,

            'km'        => $request->km,
            'no_chasis' => $request->no_chasis,
            'no_mesin'  => $request->no_mesin,
            'no_telp'   => $request->no_telp,

            'keluhan' => array_values(array_filter($request->keluhan ?? [])),
            'jasa'    => $jasa,
            'barang'  => $barangFinal,

            'total_jasa'  => $totalJasa,
            'total_part'  => $totalPart,
            'grand_total' => $grandTotal,

            'payment_awal' => $paymentAwal,
            'sisa'         => $sisa,
            'status_bayar' => $statusBayar,
            'metode_bayar' => $request->metode_bayar,
            'notes' => $request->notes,
            ]);
    });

    return redirect()->route('invoice.index')
        ->with('success','Invoice berhasil diupdate');
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
    // Load payments supaya $invoice->total_terbayar bisa hitung dengan benar
    $invoice->load('payments', 'pelanggan');

    return view('invoice.print', compact('invoice'));
}

    // ================= OUTSTANDING =================

public function outstanding()
{
    $invoices = Invoice::with('pelanggan')
        ->where('sisa', '>', 0)
        ->latest()
        ->get();

$totalAll = Invoice::where('sisa', '>', 0)
    ->sum('grand_total');

    $totalOutstanding = Invoice::where('sisa', '>', 0)
        ->sum('sisa');

    return view('invoice.outstanding', compact(
        'invoices',
        'totalAll',
        'totalOutstanding'
    ));
}

public function cicilanStore(Request $request, Invoice $invoice)
{
    $request->validate([
        'jumlah'        => 'required|numeric|min:1',
        'tanggal_bayar' => 'required|date',
        'metode'        => 'required|in:cash,bca,mandiri', // ← tambahkan ini
    ]);

    if ($request->jumlah > $invoice->sisa) {
        return back()->with('error', 'Jumlah melebihi sisa tagihan');
    }

    $invoice->payments()->create([
        'jumlah'        => $request->jumlah,
        'tanggal_bayar' => $request->tanggal_bayar,
        'metode'        => $request->metode,
    ]);

    // Hitung ulang sisa dari nol (DP + semua cicilan)
    $invoice->refresh();
    $totalTerbayar = $invoice->payment_awal + $invoice->payments()->sum('jumlah');
    $sisaBaru      = max(0, $invoice->grand_total - $totalTerbayar);

    $invoice->update([
        'sisa'        => $sisaBaru,
        'status_bayar' => $sisaBaru == 0 ? 'sudah' : 'belum',
    ]);

    return back()->with('success', 'Cicilan berhasil ditambahkan');
}

public function cicilanDelete(InvoicePayment $payment)
{
    DB::transaction(function () use ($payment) {

        $invoice = $payment->invoice;

        $payment->delete();

        // Hitung ulang sisa
        $totalTerbayar = $invoice->payment_awal +
                         $invoice->payments()->sum('jumlah');

        $sisaBaru = $invoice->grand_total - $totalTerbayar;

        if ($sisaBaru < 0) {
            $sisaBaru = 0;
        }

        $invoice->update([
            'sisa' => $sisaBaru,
            'status_bayar' => $sisaBaru == 0 ? 'sudah' : 'belum'
        ]);
    });

    return back()->with('success','Cicilan berhasil dihapus');
}
}