<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{


public function absensiRekap(Request $request)
{
    $bulan = $request->bulan ?? now()->month;
    $tahun = $request->tahun ?? now()->year;

    $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
    $end   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

    $count = fn($status) => \App\Models\Absensi::where('status', $status)
        ->whereBetween('tanggal', [$start, $end])->count();

    return response()->json([
        'H'  => $count('H'),
        'L'  => $count('L'),
        'S'  => $count('S'),
        'K'  => $count('K'),
        'UM' => $count('UM'),
    ]);
}}