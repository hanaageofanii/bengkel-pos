<?php

namespace App\Http\Controllers;

use App\Models\Estimasi;
use App\Models\Pelanggan;
use App\Models\Jasa;
use App\Models\Barang;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstimasiController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->q;
        $dari   = $request->dari;
        $sampai = $request->sampai;

        $estimasis = Estimasi::with('pelanggan')
            ->when($q, function($query) use ($q) {
                $query->whereHas('pelanggan', function($pq) use ($q) {
                    $pq->where('nama', 'like', "%$q%")
                       ->orWhere('plat_nomor', 'like', "%$q%")
                       ->orWhere('merk_mobil', 'like', "%$q%")
                       ->orWhere('model_mobil', 'like', "%$q%");
                });
            })
            ->when($dari, fn($q) => $q->whereDate('tanggal', '>=', $dari))
            ->when($sampai, fn($q) => $q->whereDate('tanggal', '<=', $sampai))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('estimasi.index', compact('estimasis', 'q', 'dari', 'sampai'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $jasas      = Jasa::all();
        $barangs    = Barang::all();
        return view('estimasi.create', compact('pelanggans', 'jasas', 'barangs'));
    }

    public function store(Request $request)
    {
        $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);

        $jasaList = [];
        foreach ((array) $request->jasa_id as $i => $id) {
            if (!$id) continue;
            $jasaList[] = [
                'id'    => $id,
                'nama'  => $request->jasa_nama[$i] ?? '',
                'harga' => (int) ($request->jasa_harga[$i] ?? 0),
            ];
        }

        $barangList = [];
        $totalPart  = 0;
        foreach ((array) $request->barang_id as $i => $id) {
            if (!$id) continue;
            $harga      = (int) ($request->barang_harga[$i] ?? 0);
            $qty        = (int) ($request->barang_qty[$i] ?? 1);
            $total      = $harga * $qty;
            $totalPart += $total;
            $barangList[] = [
                'id'    => $id,
                'nama'  => $request->barang_nama[$i] ?? '',
                'qty'   => $qty,
                'harga' => $harga,
                'total' => $total,
            ];
        }

        $totalJasa  = array_sum(array_column($jasaList, 'harga'));
        $grandTotal = $totalJasa + $totalPart;

        Estimasi::create([
            'pelanggan_id' => $pelanggan->id,
            'tanggal'      => $request->tanggal,
            'km'           => $request->km,
            'no_telp'      => $request->no_telp,
            'no_chasis'    => $request->no_chasis,
            'no_mesin'     => $request->no_mesin,
            'keluhan'      => $request->keluhan,
            'jasa'         => $jasaList,
            'barang'       => $barangList,
            'total_jasa'   => $totalJasa,
            'total_part'   => $totalPart,
            'grand_total'  => $grandTotal,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('estimasi.index')
            ->with('success', 'Estimasi berhasil disimpan');
    }

    public function show(Estimasi $estimasi)
    {
        return view('estimasi.show', compact('estimasi'));
    }

    public function print(Estimasi $estimasi)
    {
        $data = [
            'tanggal'     => $estimasi->tanggal,
            'km'          => $estimasi->km,
            'no_telp'     => $estimasi->no_telp,
            'no_chasis'   => $estimasi->no_chasis,
            'no_mesin'    => $estimasi->no_mesin,
            'keluhan'     => $estimasi->keluhan ?? [],
            'jasa'        => $estimasi->jasa ?? [],
            'barang'      => $estimasi->barang ?? [],
            'total_jasa'  => $estimasi->total_jasa,
            'total_part'  => $estimasi->total_part,
            'grand_total' => $estimasi->grand_total,
            'notes'       => $estimasi->notes,
        ];

        return view('estimasi.print', compact('estimasi', 'data'));
    }

    public function edit(Estimasi $estimasi)
    {
        $pelanggans = Pelanggan::all();
        $jasas      = Jasa::all();
        $barangs    = Barang::all();
        return view('estimasi.edit', compact('estimasi', 'pelanggans', 'jasas', 'barangs'));
    }

    public function update(Request $request, Estimasi $estimasi)
    {
        $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);

        $jasaList = [];
        foreach ((array) $request->jasa_id as $i => $id) {
            if (!$id) continue;
            $jasaList[] = [
                'id'    => $id,
                'nama'  => $request->jasa_nama[$i] ?? '',
                'harga' => (int) ($request->jasa_harga[$i] ?? 0),
            ];
        }

        $barangList = [];
        $totalPart  = 0;
        foreach ((array) $request->barang_id as $i => $id) {
            if (!$id) continue;
            $harga      = (int) ($request->barang_harga[$i] ?? 0);
            $qty        = (int) ($request->barang_qty[$i] ?? 1);
            $total      = $harga * $qty;
            $totalPart += $total;
            $barangList[] = [
                'id'    => $id,
                'nama'  => $request->barang_nama[$i] ?? '',
                'qty'   => $qty,
                'harga' => $harga,
                'total' => $total,
            ];
        }

        $totalJasa  = array_sum(array_column($jasaList, 'harga'));
        $grandTotal = $totalJasa + $totalPart;

        $estimasi->update([
            'pelanggan_id' => $pelanggan->id,
            'tanggal'      => $request->tanggal,
            'km'           => $request->km,
            'no_telp'      => $request->no_telp,
            'no_chasis'    => $request->no_chasis,
            'no_mesin'     => $request->no_mesin,
            'keluhan'      => $request->keluhan,
            'jasa'         => $jasaList,
            'barang'       => $barangList,
            'total_jasa'   => $totalJasa,
            'total_part'   => $totalPart,
            'grand_total'  => $grandTotal,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('estimasi.index')
            ->with('success', 'Estimasi berhasil diperbarui');
    }

    public function destroy(Estimasi $estimasi)
    {
        $estimasi->delete();
        return redirect()->route('estimasi.index')
            ->with('success', 'Estimasi berhasil dihapus');
    }

    // ================= APPROVE =================
    public function approve(Estimasi $estimasi)
    {
        DB::transaction(function () use ($estimasi) {

            $invoiceNo = 'INV-' . date('Ymd') . '-' . rand(100, 999);

            // Cek & potong stok barang
            $barangFinal = [];
            foreach ($estimasi->barang ?? [] as $item) {
                $barangModel = Barang::lockForUpdate()->findOrFail($item['id']);

                if ($barangModel->stok < $item['qty']) {
                    abort(422, "Stok {$barangModel->nama} tidak mencukupi untuk approve estimasi ini");
                }

                $barangModel->decrement('stok', $item['qty']);
                $barangFinal[] = $item;
            }

            // Buat Invoice dari data Estimasi
            Invoice::create([
                'invoice_no'   => $invoiceNo,
                'pelanggan_id' => $estimasi->pelanggan_id,
                'tanggal'      => $estimasi->tanggal,
                'km'           => $estimasi->km,
                'no_telp'      => $estimasi->no_telp,
                'no_chasis'    => $estimasi->no_chasis,
                'no_mesin'     => $estimasi->no_mesin,
                'keluhan'      => $estimasi->keluhan ?? [],
                'jasa'         => $estimasi->jasa ?? [],
                'barang'       => $barangFinal,
                'total_jasa'   => $estimasi->total_jasa,
                'total_part'   => $estimasi->total_part,
                'grand_total'  => $estimasi->grand_total,
                'payment_awal' => 0,
                'sisa'         => $estimasi->grand_total,
                'status_bayar' => 'belum',
                'metode_bayar' => null,
                'notes'        => $estimasi->notes,
            ]);

            // Hapus estimasi setelah jadi invoice
            $estimasi->delete();
        });

        return redirect()->route('invoice.index')
            ->with('success', 'Estimasi berhasil di-approve dan Invoice telah dibuat');
    }
}
