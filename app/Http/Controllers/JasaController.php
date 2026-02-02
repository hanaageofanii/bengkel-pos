<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\Request;

class JasaController extends Controller
{
    public function index()
    {
        $jasas = Jasa::orderBy('nama')->get();
        return view('jasa.index', compact('jasas'));
    }

    public function create()
    {
        return view('jasa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_pribadi' => 'required|integer|min:0',
            'harga_perusahaan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        Jasa::create($request->all());

        return redirect()->route('jasa.index')
            ->with('success', 'Jasa berhasil ditambahkan');
    }

    public function edit(Jasa $jasa)
    {
        return view('jasa.edit', compact('jasa'));
    }

    public function update(Request $request, Jasa $jasa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_pribadi' => 'required|integer|min:0',
            'harga_perusahaan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $jasa->update($request->all());

        return redirect()->route('jasa.index')
            ->with('success', 'Jasa berhasil diperbarui');
    }

    public function destroy(Jasa $jasa)
    {
        $jasa->delete();

        return redirect()->route('jasa.index')
            ->with('success', 'Jasa berhasil dihapus');
    }
}