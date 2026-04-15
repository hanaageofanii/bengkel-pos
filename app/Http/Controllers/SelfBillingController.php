<?php

namespace App\Http\Controllers;

use App\Models\SelfBilling;
use Illuminate\Http\Request;

class SelfBillingController extends Controller
{
   public function index()
{
    $selfBillings = SelfBilling::with('payments')->orderByDesc('tanggal')->get();

    $totalSisaHutang = $selfBillings->sum('total_tagihan') - $selfBillings->flatMap->payments->sum('nominal_bayar');

    return view('selfbilling.index', compact('selfBillings', 'totalSisaHutang'));
}

    public function create()
    {
        return view('selfbilling.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'nama_vendor'   => 'required|string|max:255',
            'jenis_barang'  => 'required|string|max:255',
            'jumlah_barang' => 'required|integer|min:1',
            'total_tagihan' => 'required|numeric|min:0',
        ]);

        SelfBilling::create($request->all());

        return redirect()->route('selfbilling.index')
                         ->with('success', 'Tagihan berhasil ditambahkan');
    }

    public function updateNotes(Request $request, $id)
    {
        $sb = SelfBilling::findOrFail($id);
        $sb->update(['payment_notes' => $request->payment_notes]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        SelfBilling::findOrFail($id)->delete();

        return redirect()->route('selfbilling.index')
                         ->with('success', 'Tagihan berhasil dihapus');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'tanggal'       => 'required|date',
        'nama_vendor'   => 'required|string|max:255',
        'jenis_barang'  => 'required|string|max:255',
        'jumlah_barang' => 'required|integer|min:1',
        'total_tagihan' => 'required|numeric|min:0',
    ]);

    SelfBilling::findOrFail($id)->update($request->all());

    return redirect()->route('selfbilling.index')
                     ->with('success', 'Tagihan berhasil diperbarui');
}

public function storePayment(Request $request, $id)
{
    $sb = SelfBilling::findOrFail($id);

    // Validasi agar tidak bayar melebihi sisa tagihan
    $request->validate([
        'tanggal_bayar' => 'required|date',
        'nominal_bayar' => 'required|numeric|max:' . $sb->sisa_tagihan,
        'metode_bayar' => 'required',
    ]);

    $sb->payments()->create($request->all());

    return redirect()->back()->with('success', 'Pembayaran berhasil dicatat!');
}

public function show($id)
{
    $selfbilling = SelfBilling::with('payments')->findOrFail($id);

    return view('selfbilling.show', compact('selfbilling'));
}
}
