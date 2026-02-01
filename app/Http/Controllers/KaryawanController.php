<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::orderBy('nama')->get();
        return view('karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        Karyawan::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'aktif' => true,
        ]);

        return redirect()->route('karyawan.index');
    }
}