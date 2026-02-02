<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Tampilkan daftar karyawan
     */
    public function index()
    {
        $karyawans = Karyawan::orderBy('nama')->get();
        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Form tambah karyawan
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Simpan data karyawan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        Karyawan::create([
            'nama'    => $request->nama,
            'jabatan' => $request->jabatan,
            'aktif'   => true,
        ]);

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan');
    }

    /**
     * Form edit karyawan
     */
    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'status'  => 'required|in:aktif,cuti,resign,nonaktif',
        ]);

        $karyawan->update([
            'nama'    => $request->nama,
            'jabatan' => $request->jabatan,
            'status'  => $request->status,
        ]);

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui');
    }

    /**
     * Hapus karyawan
     */
    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus');
    }

    /**
     * Toggle status aktif / nonaktif
     */
    public function toggleStatus(Karyawan $karyawan)
    {
        $karyawan->update([
            'aktif' => !$karyawan->aktif
        ]);

        return redirect()->route('karyawan.index');
    }
}
