@extends('dashboard')

@php
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// === PERIODE ===
$today      = Carbon::today();
$startWeek  = Carbon::now()->startOfWeek();
$endWeek    = Carbon::now()->endOfWeek();
$startMonth = Carbon::now()->startOfMonth();
$endMonth   = Carbon::now()->endOfMonth();
$startLastMonth = Carbon::now()->subMonth()->startOfMonth();
$endLastMonth   = Carbon::now()->subMonth()->endOfMonth();
$startYear  = Carbon::now()->startOfYear();
$endYear    = Carbon::now()->endOfYear();

// === KARYAWAN ===
$totalKaryawan         = Karyawan::count();
$totalKaryawanCuti     = Karyawan::where('status','cuti')->count();
$totalKaryawanResign   = Karyawan::where('status','resign')->count();
$totalKaryawanNonAktif = Karyawan::where('status','nonaktif')->count();
$totaLKaryawanAktif    = Karyawan::where('status','aktif')->count();

// === ABSENSI ===
$count = fn($status, $from, $to) => Absensi::where('status', $status)
    ->whereBetween('tanggal', [$from, $to])->count();

$hariH  = $count('H',  $today, $today);
$hariL  = $count('L',  $today, $today);
$hariS  = $count('S',  $today, $today);
$hariK  = $count('K',  $today, $today);
$hariUM = $count('UM', $today, $today);

$mingguH  = $count('H',  $startWeek, $endWeek);
$mingguL  = $count('L',  $startWeek, $endWeek);
$mingguS  = $count('S',  $startWeek, $endWeek);
$mingguK  = $count('K',  $startWeek, $endWeek);
$mingguUM = $count('UM', $startWeek, $endWeek);

$bulanH  = $count('H',  $startMonth, $endMonth);
$bulanL  = $count('L',  $startMonth, $endMonth);
$bulanS  = $count('S',  $startMonth, $endMonth);
$bulanK  = $count('K',  $startMonth, $endMonth);
$bulanUM = $count('UM', $startMonth, $endMonth);

$absensiHariIni = Absensi::with('karyawan')
    ->whereDate('tanggal', $today)->latest()->take(10)->get();

$statusLabel = [
    'H'  => ['label' => 'Hadir',            'color' => '#059669'],
    'L'  => ['label' => 'Libur',           'color' => '#2563eb'],
    'S'  => ['label' => 'Sakit',            'color' => '#d97706'],
    'K'  => ['label' => 'Izin',             'color' => '#7c3aed'],
    'UM' => ['label' => 'Tanpa Keterangan', 'color' => '#dc2626'],
];

// === PELANGGAN ===
$totalPelanggan     = Pelanggan::count();
$totalPribadi       = Pelanggan::where('tipe','pribadi')->count();
$totalPerusahaan    = Pelanggan::where('tipe','perusahaan')->count();
$pelangganBulanIni  = Pelanggan::whereBetween('created_at',[$startMonth,$endMonth])->count();
$pelangganMingguIni = Pelanggan::whereBetween('created_at',[$startWeek,$endWeek])->count();
$pelangganHariIni   = Pelanggan::whereDate('created_at',$today)->count();
$topMerk = Pelanggan::selectRaw('merk_mobil, COUNT(*) as total')
    ->groupBy('merk_mobil')->orderByDesc('total')->limit(5)->get();
$pelangganTerbaru = Pelanggan::latest()->take(8)->get();

// === BARANG ===
$totalBarang         = Barang::count();
$totalStok           = Barang::sum('stok');
$stokHabis           = Barang::where('stok',0)->count();
$stokMenipis         = Barang::where('stok','>',0)->where('stok','<=',5)->count();
$stokAman            = Barang::where('stok','>',5)->count();
$nilaiStokPribadi    = Barang::selectRaw('SUM(harga_pribadi * stok) as total')->value('total') ?? 0;
$nilaiStokPerusahaan = Barang::selectRaw('SUM(harga_perusahaan * stok) as total')->value('total') ?? 0;
$topStok             = Barang::orderByDesc('stok')->limit(5)->get();
$barangKritis        = Barang::where('stok','<=',5)->orderBy('stok')->limit(8)->get();

// === INVOICE SUMMARY ===
$totalInvoice      = Invoice::count();
$invoiceBelumBayar = Invoice::where('status_bayar','belum')->count();
$invoiceSudahBayar = Invoice::where('status_bayar','sudah')->count();
$invoiceHariIni    = Invoice::whereDate('tanggal',$today)->count();
$invoiceMingguIni  = Invoice::whereBetween('tanggal',[$startWeek,$endWeek])->count();
$invoiceBulanIni   = Invoice::whereBetween('tanggal',[$startMonth,$endMonth])->count();

// === PENDAPATAN ===
// Helper: filter invoice lunas berdasarkan tanggal_bayar, fallback ke tanggal jika null
$invLunas = fn() => Invoice::where('status_bayar','sudah')->where(fn($q) =>
    $q->whereNotNull('tanggal_bayar')->orWhereNull('tanggal_bayar')
);
$tglLunas = fn($q, $col) =>
    $q->where(fn($sub) =>
        $sub->whereDate('tanggal_bayar', $col)
            ->orWhere(fn($fb) => $fb->whereNull('tanggal_bayar')->whereDate('tanggal', $col))
    );
$tglLunasRange = fn($q, $from, $to) =>
    $q->where(fn($sub) =>
        $sub->whereBetween('tanggal_bayar', [$from, $to])
            ->orWhere(fn($fb) => $fb->whereNull('tanggal_bayar')->whereBetween('tanggal', [$from, $to]))
    );
$tglLunasYear = fn($q, $year) =>
    $q->where(fn($sub) =>
        $sub->whereYear('tanggal_bayar', $year)
            ->orWhere(fn($fb) => $fb->whereNull('tanggal_bayar')->whereYear('tanggal', $year))
    );
$tglLunasYearMonth = fn($q, $year, $month) =>
    $q->where(fn($sub) =>
        $sub->whereYear('tanggal_bayar', $year)->whereMonth('tanggal_bayar', $month)
            ->orWhere(fn($fb) => $fb->whereNull('tanggal_bayar')->whereYear('tanggal', $year)->whereMonth('tanggal', $month))
    );

$pendapatanHariIni   = $tglLunasRange(Invoice::where('status_bayar','sudah'), $today, $today)->sum('grand_total');
$pendapatanMingguIni = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startWeek, $endWeek)->sum('grand_total');
$pendapatanBulanIni  = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startMonth, $endMonth)->sum('grand_total');
$pendapatanBulanLalu = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startLastMonth, $endLastMonth)->sum('grand_total');
$pendapatanTahunIni  = $tglLunasYear(Invoice::where('status_bayar','sudah'), Carbon::now()->year)->sum('grand_total');

// Jasa vs Part bulan ini
$jasaBulanIni  = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startMonth, $endMonth)->sum('total_jasa');
$partBulanIni  = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startMonth, $endMonth)->sum('total_part');
$jasaBulanLalu = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startLastMonth, $endLastMonth)->sum('total_jasa');
$partBulanLalu = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startLastMonth, $endLastMonth)->sum('total_part');

// Perbandingan bulan ini vs lalu
$selisihPendapatan = $pendapatanBulanIni - $pendapatanBulanLalu;
$persenPendapatan  = $pendapatanBulanLalu > 0
    ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
    : ($pendapatanBulanIni > 0 ? 100 : 0);

// === PIUTANG ===
$totalPiutang      = Invoice::where('status_bayar','belum')->sum('sisa');
$piutangCount      = Invoice::where('status_bayar','belum')->where('sisa','>',0)->count();
// Piutang terbesar
$piutangTerbesar = Invoice::with('pelanggan')
    ->where('status_bayar','belum')
    ->where('sisa','>',0)
    ->orderByDesc('sisa')
    ->limit(8)
    ->get();

// === METODE BAYAR ===
$metodeCash    = InvoicePayment::where('metode','cash')->whereBetween('tanggal_bayar',[$startMonth,$endMonth])->sum('jumlah');
$metodeMandiri = InvoicePayment::where('metode','mandiri')->whereBetween('tanggal_bayar',[$startMonth,$endMonth])->sum('jumlah');
$metodeBca     = InvoicePayment::where('metode','bca')->whereBetween('tanggal_bayar',[$startMonth,$endMonth])->sum('jumlah');

$invoiceTerbaru = Invoice::with('pelanggan')->latest()->take(8)->get();

// === GRAFIK: 12 BULAN TERAKHIR ===
$grafikBulan = collect();
for ($i = 11; $i >= 0; $i--) {
    $bulan = Carbon::now()->subMonths($i);
    $pendapatan = $tglLunasYearMonth(Invoice::where('status_bayar','sudah'), $bulan->year, $bulan->month)->sum('grand_total');
    $piutangBln = Invoice::where('status_bayar','belum')
        ->whereYear('tanggal', $bulan->year)
        ->whereMonth('tanggal', $bulan->month)
        ->sum('sisa');
    $grafikBulan->push([
        'label'      => $bulan->translatedFormat('M Y'),
        'pendapatan' => (int) $pendapatan,
        'piutang'    => (int) $piutangBln,
    ]);
}

// === GRAFIK: 8 MINGGU TERAKHIR ===
$grafikMinggu = collect();
for ($i = 7; $i >= 0; $i--) {
    $mulai = Carbon::now()->subWeeks($i)->startOfWeek();
    $akhir = Carbon::now()->subWeeks($i)->endOfWeek();
    $pendapatan = $tglLunasRange(Invoice::where('status_bayar','sudah'), $mulai, $akhir)->sum('grand_total');
    $grafikMinggu->push([
        'label'      => $mulai->format('d M') . ' - ' . $akhir->format('d M'),
        'pendapatan' => (int) $pendapatan,
    ]);
}

// === GRAFIK PER TAHUN (5 tahun) ===
$grafikTahunan = collect();
for ($i = 4; $i >= 0; $i--) {
    $tahun = Carbon::now()->subYears($i)->year;
    $pendapatan = $tglLunasYear(Invoice::where('status_bayar','sudah'), $tahun)->sum('grand_total');
    $grafikTahunan->push([
        'label'      => (string) $tahun,
        'pendapatan' => (int) $pendapatan,
    ]);
}
@endphp

@section('title', 'Dashboard')
<style>
    .dashboard-wrap { font-family: 'DM Sans', sans-serif; }

    .stat-card {
        position: relative; background: #ffffff;
        border: 1px solid #e8e8e8; border-radius: 16px;
        padding: 24px 20px;
        transition: box-shadow 0.2s ease, transform 0.2s ease; overflow: hidden;
    }
    .stat-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .accent-bar { position: absolute; top: 0; left: 0; width: 3px; height: 100%; border-radius: 16px 0 0 16px; }
    .stat-label { font-size: 11px; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: #9ca3af; margin-bottom: 10px; }
    .stat-number { font-family: 'DM Serif Display', serif; font-size: 36px; line-height: 1; }
    .stat-sub { font-size: 11px; color: #d1d5db; margin-top: 5px; }

    .section-title { font-family: 'DM Serif Display', serif; font-size: 18px; color: #111827; font-weight: 400; }
    .section-divider { width: 32px; height: 2px; background: #e5e7eb; margin: 6px 0 4px; border-radius: 2px; }
    .section-sub { font-size: 12px; color: #9ca3af; margin-bottom: 14px; }

    .tab-btn {
        padding: 6px 16px; border-radius: 8px; font-size: 12px; font-weight: 500;
        border: 1px solid #e5e7eb; background: #fff; color: #6b7280;
        cursor: pointer; transition: all 0.15s;
    }
    .tab-btn.active, .tab-btn:hover { background: #111827; color: #fff; border-color: #111827; }
    .absensi-grid { display: none; }
    .absensi-grid.active { display: grid; }

    .abs-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .abs-table thead tr { border-bottom: 2px solid #f3f4f6; }
    .abs-table th { text-align: left; padding: 10px 14px; font-size: 11px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; color: #9ca3af; }
    .abs-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background 0.1s; }
    .abs-table tbody tr:hover { background: #f9fafb; }
    .abs-table td { padding: 10px 14px; color: #374151; }
    .badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }

    .merk-bar-wrap { display: flex; flex-direction: column; gap: 10px; }
    .merk-row { display: flex; align-items: center; gap: 10px; }
    .merk-label { font-size: 12px; color: #4b5563; width: 110px; flex-shrink: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .merk-bar-bg { flex: 1; background: #f3f4f6; border-radius: 99px; height: 8px; overflow: hidden; }
    .merk-bar-fill { height: 100%; border-radius: 99px; transition: width 0.6s ease; }
    .merk-count { font-size: 12px; font-weight: 600; color: #111827; width: 50px; text-align: right; white-space: nowrap; }

    .card-box { background: #fff; border: 1px solid #e8e8e8; border-radius: 16px; overflow: hidden; }
    .card-box-header { padding: 20px 24px 0; }
    .card-box-body { padding: 20px 24px; }

    .greeting-title { font-family: 'DM Serif Display', serif; font-size: 26px; color: #111827; font-weight: 400; }
    .divider-line { width: 40px; height: 2px; background: #111827; margin: 10px 0 4px; border-radius: 2px; }

    .revenue-card {
        position: relative; border-radius: 16px; padding: 24px 22px; overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease; border: none;
    }
    .revenue-card:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.15); }

    /* Tren badge */
    .tren-up   { display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:#059669;background:#f0fdf4;padding:3px 10px;border-radius:20px; }
    .tren-down { display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:#dc2626;background:#fef2f2;padding:3px 10px;border-radius:20px; }
    .tren-flat { display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:#6b7280;background:#f3f4f6;padding:3px 10px;border-radius:20px; }

    .stok-alert { display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;background:#fef2f2;border:1px solid #fecaca;margin-bottom:12px;font-size:12px;color:#b91c1c; }
    .stok-alert-dot { width:8px;height:8px;border-radius:50%;background:#ef4444;flex-shrink:0;animation:pulse 1.5s infinite; }

    .metode-row { display:flex;align-items:center;gap:12px;margin-bottom:10px; }
    .metode-label { font-size:12px;color:#4b5563;width:70px;flex-shrink:0; }
    .metode-bar-bg { flex:1;background:#f3f4f6;border-radius:99px;height:8px;overflow:hidden; }
    .metode-bar-fill { height:100%;border-radius:99px; }
    .metode-val { font-size:11px;font-weight:600;color:#374151;text-align:right;white-space:nowrap;min-width:80px; }

    /* Chart tabs */
    .chart-panel { display:none; }
    .chart-panel.active { display:block; }

    /* Piutang highlight */
    .piutang-alert { display:flex;align-items:center;gap:8px;padding:12px 16px;border-radius:12px;background:#fff7ed;border:1px solid #fed7aa;margin-bottom:14px;font-size:12px;color:#c2410c; }

    @keyframes pulse { 0%,100%{opacity:1}50%{opacity:0.3} }
    </style>
@section('content')
<div class="w-full py-6">

{{-- ===== HEADER ===== --}}
    <div style="margin-bottom:2.5rem;">
        <p style="font-size:11px;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:#9ca3af;margin-bottom:4px;">5A Auto Service</p>
        <h1 class="greeting-title">Dashboard</h1>
        <div class="divider-line"></div>
        <p style="font-size:14px;color:#9ca3af;margin-top:8px;">
            Selamat datang, <span style="color:#4b5563;font-weight:500;">{{ auth()->user()->name }}</span>
            &nbsp;·&nbsp; {{ Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

 {{-- ===== PENDAPATAN & KEUANGAN ===== --}}
    <div style="margin-bottom:2.5rem;">
        <p class="section-title">Pendapatan & Keuangan</p>
        <div class="section-divider"></div>
        <p class="section-sub">Ringkasan finansial bengkel</p>

        {{-- Dark revenue cards --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px;">
            <div class="revenue-card" style="background:#111827;">
                <p class="stat-label" style="color:rgba(255,255,255,0.45);">Hari Ini</p>
                <p style="font-family:'DM Serif Display',serif;font-size:22px;color:#fff;line-height:1;">Rp {{ number_format($pendapatanHariIni,0,',','.') }}</p>
                <p style="font-size:11px;color:rgba(255,255,255,0.3);margin-top:4px;">pendapatan</p>
            </div>
            <div class="revenue-card" style="background:#1e3a5f;">
                <p class="stat-label" style="color:rgba(255,255,255,0.45);">Minggu Ini</p>
                <p style="font-family:'DM Serif Display',serif;font-size:22px;color:#fff;line-height:1;">Rp {{ number_format($pendapatanMingguIni,0,',','.') }}</p>
                <p style="font-size:11px;color:rgba(255,255,255,0.3);margin-top:4px;">pendapatan</p>
            </div>
            <div class="revenue-card" style="background:#14532d;">
                <p class="stat-label" style="color:rgba(255,255,255,0.45);">Bulan Ini</p>
                <p style="font-family:'DM Serif Display',serif;font-size:22px;color:#fff;line-height:1;">Rp {{ number_format($pendapatanBulanIni,0,',','.') }}</p>
                <p style="font-size:11px;color:rgba(255,255,255,0.3);margin-top:4px;">pendapatan</p>
            </div>
            <div class="revenue-card" style="background:#3b0764;">
                <p class="stat-label" style="color:rgba(255,255,255,0.45);">Tahun Ini</p>
                <p style="font-family:'DM Serif Display',serif;font-size:22px;color:#fff;line-height:1;">Rp {{ number_format($pendapatanTahunIni,0,',','.') }}</p>
                <p style="font-size:11px;color:rgba(255,255,255,0.3);margin-top:4px;">pendapatan</p>
            </div>
        </div>

        {{-- Perbandingan bulan ini vs lalu --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:12px;">
            <div class="stat-card">
                <div class="accent-bar" style="background:{{ $selisihPendapatan >= 0 ? '#059669' : '#ef4444' }};"></div>
                <p class="stat-label">vs Bulan Lalu</p>
                <p class="stat-number" style="color:{{ $selisihPendapatan >= 0 ? '#059669' : '#dc2626' }};font-size:28px;">
                    {{ $selisihPendapatan >= 0 ? '+' : '' }}Rp {{ number_format(abs($selisihPendapatan),0,',','.') }}
                </p>
                <div style="margin-top:8px;">
                    @if($persenPendapatan > 0)
                        <span class="tren-up">▲ {{ $persenPendapatan }}%</span>
                    @elseif($persenPendapatan < 0)
                        <span class="tren-down">▼ {{ abs($persenPendapatan) }}%</span>
                    @else
                        <span class="tren-flat">— 0%</span>
                    @endif
                    <span style="font-size:11px;color:#d1d5db;margin-left:6px;">dari bulan lalu</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="accent-bar" style="background:#0891b2;"></div>
                <p class="stat-label">Jasa Bulan Ini</p>
                <p class="stat-number" style="color:#0e7490;font-size:24px;">Rp {{ number_format($jasaBulanIni,0,',','.') }}</p>
                <p class="stat-sub">Bulan lalu: Rp {{ number_format($jasaBulanLalu,0,',','.') }}</p>
            </div>
            <div class="stat-card">
                <div class="accent-bar" style="background:#7c3aed;"></div>
                <p class="stat-label">Part Bulan Ini</p>
                <p class="stat-number" style="color:#7c3aed;font-size:24px;">Rp {{ number_format($partBulanIni,0,',','.') }}</p>
                <p class="stat-sub">Bulan lalu: Rp {{ number_format($partBulanLalu,0,',','.') }}</p>
            </div>
        </div>

        {{-- Invoice summary --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px;">
            <div class="stat-card"><div class="accent-bar" style="background:#111827;"></div><p class="stat-label">Total Invoice</p><p class="stat-number" style="color:#111827;">{{ $totalInvoice }}</p><p class="stat-sub">semua waktu</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Lunas</p><p class="stat-number" style="color:#059669;">{{ $invoiceSudahBayar }}</p><p class="stat-sub">invoice</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#ef4444;"></div><p class="stat-label">Belum Lunas</p><p class="stat-number" style="color:#dc2626;">{{ $invoiceBelumBayar }}</p><p class="stat-sub">invoice</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#6366f1;"></div><p class="stat-label">Invoice Bulan Ini</p><p class="stat-number" style="color:#4f46e5;">{{ $invoiceBulanIni }}</p><p class="stat-sub">dibuat</p></div>
        </div>

        {{-- Metode bayar + Invoice terbaru --}}
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;">
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Metode Pembayaran</p>
                    <p class="section-sub" style="margin-bottom:0;">Rekapan bulan ini</p>
                </div>
                <div class="card-box-body">
                    @php $totalMetode = max($metodeCash + $metodeMandiri + $metodeBca, 1); @endphp
                    <div class="metode-row">
                        <span class="metode-label">Cash</span>
                        <div class="metode-bar-bg"><div class="metode-bar-fill" style="width:{{ round(($metodeCash/$totalMetode)*100) }}%;background:#111827;"></div></div>
                        <span class="metode-val">Rp {{ number_format($metodeCash,0,',','.') }}</span>
                    </div>
                    <div class="metode-row">
                        <span class="metode-label">Mandiri</span>
                        <div class="metode-bar-bg"><div class="metode-bar-fill" style="width:{{ round(($metodeMandiri/$totalMetode)*100) }}%;background:#f59e0b;"></div></div>
                        <span class="metode-val">Rp {{ number_format($metodeMandiri,0,',','.') }}</span>
                    </div>
                    <div class="metode-row">
                        <span class="metode-label">BCA</span>
                        <div class="metode-bar-bg"><div class="metode-bar-fill" style="width:{{ round(($metodeBca/$totalMetode)*100) }}%;background:#2563eb;"></div></div>
                        <span class="metode-val">Rp {{ number_format($metodeBca,0,',','.') }}</span>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Invoice Terbaru</p>
                    <p class="section-sub" style="margin-bottom:0;">8 invoice terakhir</p>
                </div>
                @if($invoiceTerbaru->isEmpty())
                    <div class="card-box-body"><p style="font-size:12px;color:#d1d5db;">Belum ada data.</p></div>
                @else
                <table class="abs-table">
                    <thead><tr><th>No Invoice</th><th>Pelanggan</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($invoiceTerbaru as $inv)
                        <tr>
                            <td style="font-family:monospace;font-size:12px;color:#6b7280;">{{ $inv->invoice_no }}</td>
                            <td style="font-weight:500;">{{ $inv->pelanggan->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($inv->grand_total,0,',','.') }}</td>
                            <td>
                                @if($inv->status_bayar === 'sudah')
                                    <span class="badge" style="background:#f0fdf4;color:#059669;">Lunas</span>
                                @elseif($inv->payment_awal > 0)
                                    <span class="badge" style="background:#fffbeb;color:#d97706;">Sebagian</span>
                                @else
                                    <span class="badge" style="background:#fef2f2;color:#dc2626;">Belum</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== PIUTANG / SISA TAGIHAN ===== --}}
    <div style="margin-bottom:2.5rem;">
        <p class="section-title">Piutang & Sisa Tagihan</p>
        <div class="section-divider"></div>
        <p class="section-sub">Tagihan yang belum dilunasi pelanggan</p>

        @if($piutangCount > 0)
        <div class="piutang-alert">
            <div class="stok-alert-dot" style="background:#f97316;"></div>
            <span>Terdapat <strong>{{ $piutangCount }} invoice</strong> dengan total piutang <strong>Rp {{ number_format($totalPiutang,0,',','.') }}</strong> yang belum dilunasi.</span>
        </div>
        @endif

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
            <div class="stat-card">
                <div class="accent-bar" style="background:#ef4444;"></div>
                <p class="stat-label">Total Piutang</p>
                <p class="stat-number" style="color:#dc2626;font-size:26px;">Rp {{ number_format($totalPiutang,0,',','.') }}</p>
                <p class="stat-sub">sisa belum dibayar</p>
            </div>
            <div class="stat-card">
                <div class="accent-bar" style="background:#f97316;"></div>
                <p class="stat-label">Jumlah Invoice</p>
                <p class="stat-number" style="color:#ea580c;">{{ $piutangCount }}</p>
                <p class="stat-sub">invoice belum lunas</p>
            </div>
            <div class="stat-card">
                <div class="accent-bar" style="background:#eab308;"></div>
                <p class="stat-label">Rata-rata Piutang</p>
                <p class="stat-number" style="color:#ca8a04;font-size:26px;">
                    Rp {{ $piutangCount > 0 ? number_format(round($totalPiutang / $piutangCount),0,',','.') : '0' }}
                </p>
                <p class="stat-sub">per invoice</p>
            </div>
        </div>

        <div class="card-box">
            <div class="card-box-header">
                <p class="section-title" style="font-size:15px;">Tagihan Terbesar</p>
                <p class="section-sub" style="margin-bottom:0;">Invoice dengan sisa pembayaran tertinggi</p>
            </div>
            @if($piutangTerbesar->isEmpty())
                <div class="card-box-body"><p style="font-size:12px;color:#059669;font-weight:500;">✓ Tidak ada piutang. Semua invoice sudah lunas.</p></div>
            @else
            <table class="abs-table">
                <thead><tr><th>No Invoice</th><th>Pelanggan</th><th>Tanggal</th><th>Grand Total</th><th>Dibayar</th><th>Sisa</th></tr></thead>
                <tbody>
                    @foreach($piutangTerbesar as $inv)
                    <tr>
                        <td style="font-family:monospace;font-size:12px;color:#6b7280;">{{ $inv->invoice_no }}</td>
                        <td style="font-weight:500;">{{ $inv->pelanggan->nama ?? '-' }}</td>
                        <td style="color:#9ca3af;">{{ Carbon::parse($inv->tanggal)->format('d M Y') }}</td>
                        <td>Rp {{ number_format($inv->grand_total,0,',','.') }}</td>
                        <td style="color:#059669;">Rp {{ number_format($inv->payment_awal,0,',','.') }}</td>
                        <td>
                            <span style="font-weight:600;color:#dc2626;">Rp {{ number_format($inv->sisa,0,',','.') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- ===== KARYAWAN ===== --}}
    <div style="margin-bottom:2.5rem;">
        <p class="section-title">Data Karyawan</p>
        <div class="section-divider"></div>
        <p class="section-sub">Ringkasan status seluruh karyawan</p>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#111827;"></div><p class="stat-label">Total</p><p class="stat-number" style="color:#111827;">{{ $totalKaryawan }}</p><p class="stat-sub">karyawan</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#10b981;"></div><p class="stat-label">Aktif</p><p class="stat-number" style="color:#059669;">{{ $totaLKaryawanAktif }}</p><p class="stat-sub">karyawan</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#f59e0b;"></div><p class="stat-label">Cuti</p><p class="stat-number" style="color:#d97706;">{{ $totalKaryawanCuti }}</p><p class="stat-sub">karyawan</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#9ca3af;"></div><p class="stat-label">Nonaktif</p><p class="stat-number" style="color:#6b7280;">{{ $totalKaryawanNonAktif }}</p><p class="stat-sub">karyawan</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#ef4444;"></div><p class="stat-label">Resign</p><p class="stat-number" style="color:#dc2626;">{{ $totalKaryawanResign }}</p><p class="stat-sub">karyawan</p></div>
        </div>
    </div>

    {{-- ===== ABSENSI ===== --}}
    <div style="margin-bottom:2.5rem;">
        <p class="section-title">Rekap Absensi</p>
        <div class="section-divider"></div>
        <p class="section-sub">Statistik kehadiran berdasarkan periode</p>
        <div style="display:flex;gap:8px;margin-bottom:16px;">
            <button class="tab-btn active" onclick="switchTab('hari',this)">Hari Ini</button>
            <button class="tab-btn" onclick="switchTab('minggu',this)">Minggu Ini</button>
            <button class="tab-btn" onclick="switchTab('bulan',this)">Bulan Ini</button>
        </div>
        <div id="tab-hari" class="absensi-grid active" style="grid-template-columns:repeat(5,1fr);gap:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Hadir</p><p class="stat-number" style="color:#059669;">{{ $hariH }}</p><p class="stat-sub">orang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#2563eb;"></div><p class="stat-label">Libur</p><p class="stat-number" style="color:#2563eb;">{{ $hariL }}</p><p class="stat-sub">orang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#d97706;"></div><p class="stat-label">Sakit</p><p class="stat-number" style="color:#d97706;">{{ $hariS }}</p><p class="stat-sub">orang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#7c3aed;"></div><p class="stat-label">Izin</p><p class="stat-number" style="color:#7c3aed;">{{ $hariK }}</p><p class="stat-sub">orang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#dc2626;"></div><p class="stat-label">Tanpa Ket.</p><p class="stat-number" style="color:#dc2626;">{{ $hariUM }}</p><p class="stat-sub">orang</p></div>
        </div>
        <div id="tab-minggu" class="absensi-grid" style="grid-template-columns:repeat(5,1fr);gap:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Hadir</p><p class="stat-number" style="color:#059669;">{{ $mingguH }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#2563eb;"></div><p class="stat-label">Libur</p><p class="stat-number" style="color:#2563eb;">{{ $mingguL }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#d97706;"></div><p class="stat-label">Sakit</p><p class="stat-number" style="color:#d97706;">{{ $mingguS }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#7c3aed;"></div><p class="stat-label">Izin</p><p class="stat-number" style="color:#7c3aed;">{{ $mingguK }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#dc2626;"></div><p class="stat-label">Tanpa Ket.</p><p class="stat-number" style="color:#dc2626;">{{ $mingguUM }}</p><p class="stat-sub">total</p></div>
        </div>
        <div id="tab-bulan" class="absensi-grid" style="grid-template-columns:repeat(5,1fr);gap:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Hadir</p><p class="stat-number" style="color:#059669;">{{ $bulanH }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#2563eb;"></div><p class="stat-label">Libur</p><p class="stat-number" style="color:#2563eb;">{{ $bulanL }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#d97706;"></div><p class="stat-label">Sakit</p><p class="stat-number" style="color:#d97706;">{{ $bulanS }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#7c3aed;"></div><p class="stat-label">Izin</p><p class="stat-number" style="color:#7c3aed;">{{ $bulanK }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#dc2626;"></div><p class="stat-label">Tanpa Ket.</p><p class="stat-number" style="color:#dc2626;">{{ $bulanUM }}</p><p class="stat-sub">total</p></div>
        </div>
    </div>

    {{-- ===== PELANGGAN ===== --}}
    <div style="margin-bottom:2.5rem;">
        <p class="section-title">Data Pelanggan</p>
        <div class="section-divider"></div>
        <p class="section-sub">Statistik pelanggan bengkel</p>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#111827;"></div><p class="stat-label">Total</p><p class="stat-number" style="color:#111827;">{{ $totalPelanggan }}</p><p class="stat-sub">terdaftar</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#2563eb;"></div><p class="stat-label">Pribadi</p><p class="stat-number" style="color:#2563eb;">{{ $totalPribadi }}</p><p class="stat-sub">pelanggan</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#7c3aed;"></div><p class="stat-label">Perusahaan</p><p class="stat-number" style="color:#7c3aed;">{{ $totalPerusahaan }}</p><p class="stat-sub">pelanggan</p></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
            <div class="stat-card"><div class="accent-bar" style="background:#10b981;"></div><p class="stat-label">Hari Ini</p><p class="stat-number" style="color:#059669;">{{ $pelangganHariIni }}</p><p class="stat-sub">baru</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#10b981;"></div><p class="stat-label">Minggu Ini</p><p class="stat-number" style="color:#059669;">{{ $pelangganMingguIni }}</p><p class="stat-sub">baru</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#10b981;"></div><p class="stat-label">Bulan Ini</p><p class="stat-number" style="color:#059669;">{{ $pelangganBulanIni }}</p><p class="stat-sub">baru</p></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;">
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Top Merk Mobil</p>
                    <p class="section-sub" style="margin-bottom:0;">5 merk terbanyak</p>
                </div>
                <div class="card-box-body">
                    @php $maxMerk = $topMerk->max('total') ?: 1; @endphp
                    <div class="merk-bar-wrap">
                        @forelse($topMerk as $m)
                        <div class="merk-row">
                            <span class="merk-label">{{ $m->merk_mobil }}</span>
                            <div class="merk-bar-bg"><div class="merk-bar-fill" style="width:{{ round(($m->total/$maxMerk)*100) }}%;background:#111827;"></div></div>
                            <span class="merk-count">{{ $m->total }}</span>
                        </div>
                        @empty <p style="font-size:12px;color:#d1d5db;">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Pelanggan Terbaru</p>
                    <p class="section-sub" style="margin-bottom:0;">8 pendaftaran terakhir</p>
                </div>
                @if($pelangganTerbaru->isEmpty())
                    <div class="card-box-body"><p style="font-size:12px;color:#d1d5db;">Belum ada data.</p></div>
                @else
                <table class="abs-table">
                    <thead><tr><th>Nama</th><th>Plat</th><th>Mobil</th><th>Tipe</th></tr></thead>
                    <tbody>
                        @foreach($pelangganTerbaru as $p)
                        <tr>
                            <td style="font-weight:500;">{{ $p->nama }}</td>
                            <td style="font-family:monospace;font-size:12px;color:#6b7280;">{{ $p->plat_nomor }}</td>
                            <td style="color:#9ca3af;">{{ $p->merk_mobil }} {{ $p->model_mobil }}</td>
                            <td>
                                @if($p->tipe === 'pribadi')
                                    <span class="badge" style="background:#eff6ff;color:#2563eb;">Pribadi</span>
                                @else
                                    <span class="badge" style="background:#f5f3ff;color:#7c3aed;">Perusahaan</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== BARANG ===== --}}
    <div style="margin-bottom:2.5rem;">
        <p class="section-title">Data Barang & Stok</p>
        <div class="section-divider"></div>
        <p class="section-sub">Ringkasan inventaris bengkel</p>
        @if($stokHabis > 0 || $stokMenipis > 0)
        <div class="stok-alert">
            <div class="stok-alert-dot"></div>
            <span>
                Perhatian:
                @if($stokHabis > 0)<strong>{{ $stokHabis }} barang stok habis</strong>@endif
                @if($stokHabis > 0 && $stokMenipis > 0) &amp; @endif
                @if($stokMenipis > 0)<strong>{{ $stokMenipis }} barang stok menipis</strong> (≤5)@endif
                — segera lakukan pengisian ulang.
            </span>
        </div>
        @endif
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#111827;"></div><p class="stat-label">Total Barang</p><p class="stat-number" style="color:#111827;">{{ $totalBarang }}</p><p class="stat-sub">jenis</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Stok Aman</p><p class="stat-number" style="color:#059669;">{{ $stokAman }}</p><p class="stat-sub">jenis</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#f59e0b;"></div><p class="stat-label">Stok Menipis</p><p class="stat-number" style="color:#d97706;">{{ $stokMenipis }}</p><p class="stat-sub">≤ 5 unit</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#ef4444;"></div><p class="stat-label">Stok Habis</p><p class="stat-number" style="color:#dc2626;">{{ $stokHabis }}</p><p class="stat-sub">jenis</p></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
            <div class="stat-card"><div class="accent-bar" style="background:#6366f1;"></div><p class="stat-label">Total Unit Stok</p><p class="stat-number" style="color:#4f46e5;">{{ number_format($totalStok) }}</p><p class="stat-sub">unit</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#0891b2;"></div><p class="stat-label">Nilai Stok (Pribadi)</p><p class="stat-number" style="color:#0e7490;font-size:22px;">Rp {{ number_format($nilaiStokPribadi,0,',','.') }}</p><p class="stat-sub">estimasi</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#0891b2;"></div><p class="stat-label">Nilai Stok (Perusahaan)</p><p class="stat-number" style="color:#0e7490;font-size:22px;">Rp {{ number_format($nilaiStokPerusahaan,0,',','.') }}</p><p class="stat-sub">estimasi</p></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;">
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Stok Terbanyak</p>
                    <p class="section-sub" style="margin-bottom:0;">5 barang stok tertinggi</p>
                </div>
                <div class="card-box-body">
                    @php $maxStok = $topStok->max('stok') ?: 1; @endphp
                    <div class="merk-bar-wrap">
                        @forelse($topStok as $b)
                        <div class="merk-row">
                            <span class="merk-label">{{ $b->nama }}</span>
                            <div class="merk-bar-bg"><div class="merk-bar-fill" style="width:{{ round(($b->stok/$maxStok)*100) }}%;background:#4f46e5;"></div></div>
                            <span class="merk-count">{{ $b->stok }}</span>
                        </div>
                        @empty <p style="font-size:12px;color:#d1d5db;">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Stok Kritis</p>
                    <p class="section-sub" style="margin-bottom:0;">Barang habis & menipis (≤5 unit)</p>
                </div>
                @if($barangKritis->isEmpty())
                    <div class="card-box-body"><p style="font-size:12px;color:#059669;font-weight:500;">✓ Semua stok dalam kondisi aman.</p></div>
                @else
                <table class="abs-table">
                    <thead><tr><th>Nama Barang</th><th>Satuan</th><th>Harga Pribadi</th><th>Stok</th></tr></thead>
                    <tbody>
                        @foreach($barangKritis as $b)
                        <tr>
                            <td style="font-weight:500;">{{ $b->nama }}</td>
                            <td style="color:#9ca3af;">{{ $b->satuan }}</td>
                            <td style="color:#9ca3af;">Rp {{ number_format($b->harga_pribadi,0,',','.') }}</td>
                            <td>
                                @if($b->stok == 0)
                                    <span class="badge" style="background:#fef2f2;color:#dc2626;">Habis</span>
                                @else
                                    <span class="badge" style="background:#fffbeb;color:#d97706;">{{ $b->stok }} unit</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== ABSENSI HARI INI ===== --}}
    <div class="card-box" style="margin-bottom:2rem;">
        <div class="card-box-header">
            <p class="section-title" style="font-size:15px;">Absensi Hari Ini</p>
            <p class="section-sub">{{ Carbon::today()->translatedFormat('l, d F Y') }}</p>
        </div>
        @if($absensiHariIni->isEmpty())
            <div class="card-box-body"><p style="font-size:12px;color:#d1d5db;">Belum ada data absensi hari ini.</p></div>
        @else
        <table class="abs-table">
            <thead><tr><th>#</th><th>Nama Karyawan</th><th>Tanggal</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($absensiHariIni as $i => $abs)
                <tr>
                    <td style="color:#d1d5db;">{{ $i + 1 }}</td>
                    <td style="font-weight:500;">{{ $abs->karyawan->nama ?? '-' }}</td>
                    <td style="color:#9ca3af;">{{ Carbon::parse($abs->tanggal)->format('d M Y') }}</td>
                    <td>
                        @php $s = $statusLabel[$abs->status] ?? ['label'=>$abs->status,'color'=>'#9ca3af']; @endphp
                        <span class="badge" style="background:{{ $s['color'] }}18;color:{{ $s['color'] }};">{{ $s['label'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
<script>
// ---- Data dari Laravel ----
const dataMinggu = @json($grafikMinggu);
const dataBulan  = @json($grafikBulan);
const dataTahun  = @json($grafikTahunan);

// ---- Config default Chart.js ----
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.font.size   = 12;
Chart.defaults.color       = '#9ca3af';

function buildChart(canvasId, labels, data, color) {
    const ctx = document.getElementById(canvasId).getContext('2d');

    // Gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, color + '33');
    gradient.addColorStop(1, color + '00');

    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: data,
                borderColor: color,
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: color,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#d1d5db',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { maxRotation: 30 }
                },
                y: {
                    grid: { color: '#f3f4f6', drawBorder: false },
                    border: { display: false, dash: [4,4] },
                    ticks: {
                        callback: v => 'Rp ' + (v >= 1000000
                            ? (v/1000000).toFixed(1) + 'jt'
                            : v.toLocaleString('id-ID'))
                    }
                }
            }
        }
    });
}

// Inisialisasi semua chart
buildChart('grafikMinggu',
    dataMinggu.map(d => d.label),
    dataMinggu.map(d => d.pendapatan),
    '#111827'
);
buildChart('grafikBulan',
    dataBulan.map(d => d.label),
    dataBulan.map(d => d.pendapatan),
    '#2563eb'
);
buildChart('grafikTahun',
    dataTahun.map(d => d.label),
    dataTahun.map(d => d.pendapatan),
    '#059669'
);

// ---- Tab absensi ----
function switchTab(tab, el) {
    document.querySelectorAll('.absensi-grid').forEach(g => g.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    el.classList.add('active');
}

// ---- Tab chart ----
function switchChart(panel, el) {
    document.querySelectorAll('.chart-panel').forEach(p => p.classList.remove('active'));
    // tombol chart ada di card-box-header, pisahkan dari tab absensi
    el.closest('.card-box-header').querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('chart-' + panel).classList.add('active');
    el.classList.add('active');
}
</script>
@endsection
