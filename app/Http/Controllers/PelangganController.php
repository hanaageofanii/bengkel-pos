<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::latest()->get();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'no_hp'       => 'nullable|string|max:20',
            'tipe'        => 'required|in:pribadi,perusahaan',
            'plat_nomor'  => 'required|string|max:15|unique:pelanggans,plat_nomor',
            'merk_mobil'  => 'required|string|max:100',
            'model_mobil' => 'required|string|max:100',
            'tahun_mobil' => 'nullable|digits:4',
        ]);

        Pelanggan::create($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'no_hp'       => 'nullable|string|max:20',
            'tipe'        => 'required|in:pribadi,perusahaan',
            'plat_nomor'  => 'required|string|max:15|unique:pelanggans,plat_nomor,' . $pelanggan->id,
            'merk_mobil'  => 'required|string|max:100',
            'model_mobil' => 'required|string|max:100',
            'tahun_mobil' => 'nullable|digits:4',
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }
}