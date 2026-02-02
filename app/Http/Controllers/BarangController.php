<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::orderBy('nama')->get();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_pribadi' => 'required|integer|min:0',
            'harga_perusahaan' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_pribadi' => 'required|integer|min:0',
            'harga_perusahaan' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }
}