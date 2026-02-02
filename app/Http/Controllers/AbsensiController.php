<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * ===============================
     * HALAMAN ABSENSI
     * ===============================
     */
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $jumlahHari = Carbon::create($tahun, $bulan)->daysInMonth;

        $karyawans = Karyawan::with(['absensis' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
            }])
            ->orderBy('nama')
            ->get();

        return view('absensi.index', compact(
            'karyawans',
            'bulan',
            'tahun',
            'jumlahHari'
        ));
    }

    /**
     * ===============================
     * TAMBAH / UPDATE ABSENSI
     * ===============================
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal'     => 'required|date',
            'status'      => 'required|in:H,L,S,K,UM',
        ]);

        $karyawan = Karyawan::findOrFail($request->karyawan_id);

        if ($karyawan->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak aktif, tidak bisa absen'
            ], 403);
        }

        Absensi::updateOrCreate(
            [
                'karyawan_id' => $request->karyawan_id,
                'tanggal'     => $request->tanggal,
            ],
            [
                'status'      => $request->status,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan'
        ]);
    }

    /**
     * ===============================
     * HAPUS ABSENSI
     * ===============================
     */
    public function destroy(Absensi $absensi)
    {
        $karyawan = $absensi->karyawan;

        if ($karyawan->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak aktif, tidak bisa menghapus absensi'
            ], 403);
        }

        $absensi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dihapus'
        ]);
    }
}
