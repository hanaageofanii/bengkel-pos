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
    'H'  => ['label' => 'Hadir',            'color' => '#3ef08a'],
    'L'  => ['label' => 'Libur',            'color' => '#4f8ef7'],
    'S'  => ['label' => 'Sakit',            'color' => '#f5c542'],
    'K'  => ['label' => 'Izin',             'color' => '#a78bfa'],
    'UM' => ['label' => 'Tanpa Keterangan', 'color' => '#f26c6c'],
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

$jasaBulanIni  = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startMonth, $endMonth)->sum('total_jasa');
$partBulanIni  = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startMonth, $endMonth)->sum('total_part');
$jasaBulanLalu = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startLastMonth, $endLastMonth)->sum('total_jasa');
$partBulanLalu = $tglLunasRange(Invoice::where('status_bayar','sudah'), $startLastMonth, $endLastMonth)->sum('total_part');

$selisihPendapatan = $pendapatanBulanIni - $pendapatanBulanLalu;
$persenPendapatan  = $pendapatanBulanLalu > 0
    ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
    : ($pendapatanBulanIni > 0 ? 100 : 0);

// === PIUTANG ===
$totalPiutang      = Invoice::where('status_bayar','belum')->sum('sisa');
$piutangCount      = Invoice::where('status_bayar','belum')->where('sisa','>',0)->count();
// BARU — tambahkan 'payments' di with()
$piutangTerbesar = Invoice::with(['pelanggan', 'payments'])
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

// === GRAFIK ===
$grafikBulan = collect();
for ($i = 11; $i >= 0; $i--) {
    $bulan = Carbon::now()->subMonths($i);
    $pendapatan = $tglLunasYearMonth(Invoice::where('status_bayar','sudah'), $bulan->year, $bulan->month)->sum('grand_total');
    $piutangBln = Invoice::where('status_bayar','belum')
        ->whereYear('tanggal', $bulan->year)
        ->whereMonth('tanggal', $bulan->month)
        ->sum('sisa');
    $grafikBulan->push(['label' => $bulan->translatedFormat('M Y'), 'pendapatan' => (int) $pendapatan, 'piutang' => (int) $piutangBln]);
}

$grafikMinggu = collect();
for ($i = 7; $i >= 0; $i--) {
    $mulai = Carbon::now()->subWeeks($i)->startOfWeek();
    $akhir = Carbon::now()->subWeeks($i)->endOfWeek();
    $pendapatan = $tglLunasRange(Invoice::where('status_bayar','sudah'), $mulai, $akhir)->sum('grand_total');
    $grafikMinggu->push(['label' => $mulai->format('d M') . ' - ' . $akhir->format('d M'), 'pendapatan' => (int) $pendapatan]);
}

$grafikTahunan = collect();
for ($i = 4; $i >= 0; $i--) {
    $tahun = Carbon::now()->subYears($i)->year;
    $pendapatan = $tglLunasYear(Invoice::where('status_bayar','sudah'), $tahun)->sum('grand_total');
    $grafikTahunan->push(['label' => (string) $tahun, 'pendapatan' => (int) $pendapatan]);
}
@endphp

@section('title', 'Dashboard')

@section('content')
<style>
/* ══════════════════════════════════════
   MAZER DASHBOARD VARIABLES
══════════════════════════════════════ */
body {
    color: var(--mz-text);
}
    /* DEFAULT DARK */
:root {
    --mz-bg:       #0f1117;
    --mz-surface:  #181c27;
    --mz-surface2: #1e2333;
    --mz-border:   #262c3d;
    --mz-text:     #e4e8f0;
    --mz-muted:    #6b7694;
}

/* LIGHT MODE */
[data-theme="light"] {
    --mz-bg:       #ffffff;
    --mz-surface:  #f9fafb;
    --mz-surface2: #f1f5f9;
    --mz-border:   #e5e7eb;
    --mz-text:     #111827;
    --mz-muted:    #6b7280;
  --mz-accent:   #4f8ef7;
    --mz-accent2:  #1e90ff;
    --mz-muted:    #6b7694;
    --mz-green:    #3ef08a;
    --mz-red:      #f26c6c;
    --mz-yellow:   #f5c542;
    --mz-orange:   #f5923e;
    --mz-purple:   #a78bfa;
    --mz-teal:     #2dd4bf;
    --mz-emerald:  #10b981;
}



.db-wrap { font-family: 'Inter', sans-serif; color: var(--mz-text); }

/* ── greeting ── */
.db-greeting {
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--mz-border);
    display: flex; align-items: center; justify-content: space-between;
}
.db-greeting-left .db-sup { font-size:10px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:var(--mz-muted); margin-bottom:6px; }
.db-greeting-left .db-title { font-family:'Rajdhani',sans-serif; font-size:28px; font-weight:700; color:var(--mz-text); line-height:1; }
.db-greeting-left .db-sub { font-size:12px; color:var(--mz-muted); margin-top:6px; }
.db-greeting-left .db-sub span { color:var(--mz-teal); font-weight:500; }
.db-date-chip {
    display:flex; align-items:center; gap:8px;
    background:var(--mz-surface); border:1px solid var(--mz-border);
    border-radius:8px; padding:10px 16px; font-size:12px; color:var(--mz-muted);
}
.db-date-chip svg { width:14px; height:14px; fill:var(--mz-accent); }
.db-date-chip strong { color:var(--mz-text); }

/* ── section heading ── */
.db-section { margin-bottom: 20px; margin-top: 32px; }
.db-section-head { display:flex; align-items:center; gap:10px; margin-bottom:4px; }
.db-section-icon { width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.db-section-icon svg { width:14px; height:14px; fill:#fff; }
.db-section-title { font-family:'Rajdhani',sans-serif; font-size:16px; font-weight:700; letter-spacing:.3px; color:var(--mz-text); }
.db-section-sub { font-size:11px; color:var(--mz-muted); margin-top:1px; margin-left:38px; margin-bottom:14px; }

/* ── stat card ── */
.mz-stat {
    background: var(--mz-surface);
    border: 1px solid var(--mz-border);
    border-radius: 8px;
    padding: 16px 18px;
    position: relative;
    overflow: hidden;
    transition: transform .15s, box-shadow .15s;
}
.mz-stat:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.3); }
.mz-stat-bar { position:absolute; top:0; left:0; width:3px; height:100%; border-radius:8px 0 0 8px; }
.mz-stat-label { font-size:10px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; color:var(--mz-muted); margin-bottom:8px; }
.mz-stat-val { font-family:'Rajdhani',sans-serif; font-size:28px; font-weight:700; line-height:1; }
.mz-stat-val.sm { font-size:18px; }
.mz-stat-sub { font-size:10.5px; color:var(--mz-muted); margin-top:5px; }

/* revenue dark cards */
.mz-rev {
    border-radius:8px; padding:18px 20px; position:relative; overflow:hidden;
    transition:transform .15s, box-shadow .15s;
}
.mz-rev:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.4); }
.mz-rev::after { content:''; position:absolute; top:-30px; right:-30px; width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,.04); }
.mz-rev-label { font-size:10px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; color:rgba(255,255,255,.4); margin-bottom:8px; }
.mz-rev-val { font-family:'Rajdhani',sans-serif; font-size:20px; font-weight:700; color:#fff; line-height:1; }
.mz-rev-sub { font-size:10.5px; color:rgba(255,255,255,.3); margin-top:5px; }

/* trend badges */
.tren-up   { display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:var(--mz-green);background:rgba(62,240,138,.1);border:1px solid rgba(62,240,138,.2);padding:3px 8px;border-radius:20px; }
.tren-down { display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:var(--mz-red);background:rgba(242,108,108,.1);border:1px solid rgba(242,108,108,.2);padding:3px 8px;border-radius:20px; }
.tren-flat { display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:var(--mz-muted);background:rgba(107,118,148,.1);border:1px solid rgba(107,118,148,.2);padding:3px 8px;border-radius:20px; }

/* alert banners */
.mz-alert-warn {
    display:flex; align-items:center; gap:10px;
    background:rgba(245,146,62,.07); border:1px solid rgba(245,146,62,.25);
    border-radius:8px; padding:12px 16px; margin-bottom:16px;
    font-size:12px; color:var(--mz-orange);
}
.mz-alert-warn svg { width:15px; height:15px; fill:var(--mz-orange); flex-shrink:0; }
.mz-alert-danger {
    display:flex; align-items:center; gap:10px;
    background:rgba(242,108,108,.07); border:1px solid rgba(242,108,108,.25);
    border-radius:8px; padding:12px 16px; margin-bottom:16px;
    font-size:12px; color:var(--mz-red);
}
.mz-alert-danger svg { width:15px; height:15px; fill:var(--mz-red); flex-shrink:0; }
.pulse-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; animation:pulse 1.5s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.25} }

/* ── card box ── */
.mz-box {
    background:var(--mz-surface); border:1px solid var(--mz-border);
    border-radius:10px; overflow:hidden;
}
.mz-box-bar { height:2px; }
.mz-box-head { padding:16px 20px 0; }
.mz-box-title { font-family:'Rajdhani',sans-serif; font-size:14px; font-weight:700; color:var(--mz-text); }
.mz-box-sub   { font-size:11px; color:var(--mz-muted); margin-top:2px; margin-bottom:14px; }
.mz-box-body  { padding:16px 20px; }

/* ── table ── */
.mz-table { width:100%; border-collapse:collapse; font-size:12px; }
.mz-table thead tr { background:var(--mz-surface2); }
.mz-table th { padding:9px 14px; font-size:10px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color:var(--mz-muted); text-align:left; border-bottom:1px solid var(--mz-border); }
.mz-table td { padding:10px 14px; border-bottom:1px solid var(--mz-border); color:var(--mz-text); }
.mz-table tbody tr:last-child td { border-bottom:none; }
.mz-table tbody tr:hover td { background:var(--mz-surface2); }

/* badge */
.mz-badge { display:inline-block; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:600; border:1px solid; }
.badge-lunas    { background:rgba(62,240,138,.1); color:var(--mz-green); border-color:rgba(62,240,138,.2); }
.badge-sebagian { background:rgba(245,197,66,.1);  color:var(--mz-yellow); border-color:rgba(245,197,66,.2); }
.badge-belum    { background:rgba(242,108,108,.1); color:var(--mz-red);   border-color:rgba(242,108,108,.2); }
.badge-pribadi  { background:rgba(45,212,191,.1);  color:var(--mz-teal);  border-color:rgba(45,212,191,.2); }
.badge-perusahaan { background:rgba(245,197,66,.1); color:var(--mz-yellow); border-color:rgba(245,197,66,.2); }
.badge-habis    { background:rgba(242,108,108,.1); color:var(--mz-red);   border-color:rgba(242,108,108,.2); }
.badge-menipis  { background:rgba(245,197,66,.1);  color:var(--mz-yellow); border-color:rgba(245,197,66,.2); }

/* ── bar chart ── */
.bar-row { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
.bar-row:last-child { margin-bottom:0; }
.bar-label { font-size:11px; color:var(--mz-muted); width:100px; flex-shrink:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.bar-bg    { flex:1; background:var(--mz-surface2); border:1px solid var(--mz-border); border-radius:99px; height:8px; overflow:hidden; }
.bar-fill  { height:100%; border-radius:99px; transition:width .6s cubic-bezier(.22,1,.36,1); }
.bar-count { font-size:11px; font-weight:700; color:var(--mz-text); width:40px; text-align:right; }

/* ── tab buttons ── */
.mz-tabs { display:flex; gap:6px; margin-bottom:16px; }
.mz-tab {
    padding:6px 14px; border-radius:6px; font-size:11.5px; font-weight:600;
    border:1px solid var(--mz-border); background:transparent;
    color:var(--mz-muted); cursor:pointer; transition:all .15s; font-family:'Inter',sans-serif;
}
.mz-tab.active { background:var(--mz-accent); border-color:var(--mz-accent); color:#fff; }
.mz-tab:hover:not(.active) { border-color:var(--mz-muted); color:var(--mz-text); }

.abs-grid { display:none; }
.abs-grid.active { display:grid; }

/* ── absensi status badges ── */
.abs-badge { display:inline-flex; align-items:center; gap:5px; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:600; border:1px solid; }

/* ── status karyawan ── */
.ky-badge { display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:600; border:1px solid; }
.ky-dot   { width:5px; height:5px; border-radius:50%; }

/* mono */
.mono { font-family:'Courier New',monospace; font-size:11px; color:var(--mz-muted); }

/* ── metode bar ── */
.met-row { display:flex; align-items:center; gap:10px; margin-bottom:12px; }
.met-row:last-child { margin-bottom:0; }
.met-label { font-size:11px; color:var(--mz-muted); width:60px; flex-shrink:0; }
.met-bg    { flex:1; background:var(--mz-surface2); border:1px solid var(--mz-border); border-radius:99px; height:7px; overflow:hidden; }
.met-fill  { height:100%; border-radius:99px; }
.met-val   { font-size:10.5px; font-weight:600; color:var(--mz-text); width:100px; text-align:right; white-space:nowrap; }

/* grid helpers */
.grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
.grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.grid-5 { display:grid; grid-template-columns:repeat(5,1fr); gap:12px; }
.grid-2 { display:grid; grid-template-columns:1fr 2fr; gap:14px; }
.grid-2s{ display:grid; grid-template-columns:2fr 1fr; gap:14px; }

/* chart canvas wrapper */
.chart-wrap { position:relative; height:220px; }
.chart-panel { display:none; }
.chart-panel.active { display:block; }

.abs-panel { display:none; }
.abs-panel.active { display:block; }

.abs-layout {
    display: grid;
    grid-template-columns: 160px 1fr;
    gap: 14px;
    align-items: stretch;
}

/* Card besar kiri — angka hadir */
.abs-main-card {
    background: var(--mz-surface);
    border: 1px solid var(--mz-border);
    border-top: 3px solid var(--abs-color);
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 24px 16px;
    text-align: center;
}
.abs-main-icon {
    font-size: 22px;
    color: var(--abs-color);
    line-height: 1;
    font-weight: 700;
}
.abs-main-val {
    font-family: 'Rajdhani', sans-serif;
    font-size: 48px;
    font-weight: 700;
    color: var(--abs-color);
    line-height: 1;
}
.abs-main-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: var(--mz-muted);
}

/* Daftar kanan */
.abs-side-list {
    background: var(--mz-surface);
    border: 1px solid var(--mz-border);
    border-radius: 10px;
    padding: 14px 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    gap: 10px;
}

.abs-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.abs-item-left {
    display: flex;
    align-items: center;
    gap: 7px;
    width: 90px;
    flex-shrink: 0;
}
.abs-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.abs-item-label {
    font-size: 12px;
    color: var(--mz-muted);
    white-space: nowrap;
}
.abs-item-bar-wrap {
    flex: 1;
}
.abs-item-bar {
    background: var(--mz-surface2);
    border: 1px solid var(--mz-border);
    border-radius: 99px;
    height: 7px;
    overflow: hidden;
}
.abs-item-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .6s cubic-bezier(.22,1,.36,1);
}
.abs-item-val {
    font-size: 13px;
    font-weight: 700;
    width: 32px;
    text-align: right;
    flex-shrink: 0;
}
{{-- CSS tambahan --}}
.mz-select {
    background: var(--mz-surface);
    border: 1px solid var(--mz-border);
    color: var(--mz-text);
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 12px;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    outline: none;
    transition: border-color .15s;
}
.mz-select:focus { border-color: var(--mz-accent); }
.mz-select option { background: var(--mz-surface); }

#abs-bulan-side.abs-loading { opacity: 0.4; pointer-events: none; transition: opacity .2s; }
</style>



<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<div class="db-wrap">

{{-- ══ GREETING ══ --}}
<div class="db-greeting">
    <div class="db-greeting-left">
        <div class="db-sup">5A Auto Service</div>
        <div class="db-title">Dashboard</div>
        <div class="db-sub">Selamat datang, <span>{{ auth()->user()->name }}</span></div>
    </div>
    <div class="db-date-chip">
        <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
        <strong>{{ Carbon::now()->translatedFormat('l, d F Y') }}</strong>
    </div>
</div>

{{-- ══ PENDAPATAN ══ --}}
<div class="db-section">
    <div class="db-section-head">
        <div class="db-section-icon" style="background:linear-gradient(135deg,#1a6fe8,#4f8ef7)">
            <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1H8.3c.12 2.19 1.76 3.42 3.7 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
        </div>
        <div class="db-section-title">Pendapatan & Keuangan</div>
    </div>
    <div class="db-section-sub">Ringkasan finansial bengkel</div>

    {{-- Revenue dark cards --}}
    <div class="grid-4" style="margin-bottom:12px">
        <div class="mz-rev" style="background:linear-gradient(135deg,#0f172a,#1e293b)">
            <div class="mz-rev-label">Hari Ini</div>
            <div class="mz-rev-val">Rp {{ number_format($pendapatanHariIni,0,',','.') }}</div>
            <div class="mz-rev-sub">pendapatan</div>
        </div>
        <div class="mz-rev" style="background:linear-gradient(135deg,#0c2340,#1e3a5f)">
            <div class="mz-rev-label">Minggu Ini</div>
            <div class="mz-rev-val">Rp {{ number_format($pendapatanMingguIni,0,',','.') }}</div>
            <div class="mz-rev-sub">pendapatan</div>
        </div>
        <div class="mz-rev" style="background:linear-gradient(135deg,#052e16,#166534)">
            <div class="mz-rev-label">Bulan Ini</div>
            <div class="mz-rev-val">Rp {{ number_format($pendapatanBulanIni,0,',','.') }}</div>
            <div class="mz-rev-sub">pendapatan</div>
        </div>
        <div class="mz-rev" style="background:linear-gradient(135deg,#2e1065,#4c1d95)">
            <div class="mz-rev-label">Tahun Ini</div>
            <div class="mz-rev-val">Rp {{ number_format($pendapatanTahunIni,0,',','.') }}</div>
            <div class="mz-rev-sub">pendapatan</div>
        </div>
    </div>

    {{-- vs bulan lalu + jasa + part --}}
    <div class="grid-3" style="margin-bottom:12px">
        <div class="mz-stat">
            <div class="mz-stat-bar" style="background:{{ $selisihPendapatan >= 0 ? '#3ef08a' : '#f26c6c' }}"></div>
            <div class="mz-stat-label">vs Bulan Lalu</div>
            <div class="mz-stat-val sm" style="color:{{ $selisihPendapatan >= 0 ? '#3ef08a' : '#f26c6c' }}">
                {{ $selisihPendapatan >= 0 ? '+' : '' }}Rp {{ number_format(abs($selisihPendapatan),0,',','.') }}
            </div>
            <div style="margin-top:8px">
                @if($persenPendapatan > 0) <span class="tren-up">▲ {{ $persenPendapatan }}%</span>
                @elseif($persenPendapatan < 0) <span class="tren-down">▼ {{ abs($persenPendapatan) }}%</span>
                @else <span class="tren-flat">— 0%</span>
                @endif
                <span style="font-size:10.5px;color:var(--mz-muted);margin-left:6px">dari bulan lalu</span>
            </div>
        </div>
        <div class="mz-stat">
            <div class="mz-stat-bar" style="background:var(--mz-teal)"></div>
            <div class="mz-stat-label">Jasa Bulan Ini</div>
            <div class="mz-stat-val sm" style="color:var(--mz-teal)">Rp {{ number_format($jasaBulanIni,0,',','.') }}</div>
            <div class="mz-stat-sub">Bulan lalu: Rp {{ number_format($jasaBulanLalu,0,',','.') }}</div>
        </div>
        <div class="mz-stat">
            <div class="mz-stat-bar" style="background:var(--mz-purple)"></div>
            <div class="mz-stat-label">Part Bulan Ini</div>
            <div class="mz-stat-val sm" style="color:var(--mz-purple)">Rp {{ number_format($partBulanIni,0,',','.') }}</div>
            <div class="mz-stat-sub">Bulan lalu: Rp {{ number_format($partBulanLalu,0,',','.') }}</div>
        </div>
    </div>

    {{-- Invoice summary --}}
    <div class="grid-4" style="margin-bottom:16px">
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-text)"></div><div class="mz-stat-label">Total Invoice</div><div class="mz-stat-val">{{ $totalInvoice }}</div><div class="mz-stat-sub">semua waktu</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-green)"></div><div class="mz-stat-label">Lunas</div><div class="mz-stat-val" style="color:var(--mz-green)">{{ $invoiceSudahBayar }}</div><div class="mz-stat-sub">invoice</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-red)"></div><div class="mz-stat-label">Belum Lunas</div><div class="mz-stat-val" style="color:var(--mz-red)">{{ $invoiceBelumBayar }}</div><div class="mz-stat-sub">invoice</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-accent)"></div><div class="mz-stat-label">Bulan Ini</div><div class="mz-stat-val" style="color:var(--mz-accent)">{{ $invoiceBulanIni }}</div><div class="mz-stat-sub">dibuat</div></div>
    </div>

    {{-- Metode bayar + invoice terbaru --}}
    <div class="grid-2s">
        <div class="mz-box">
        <div class="mz-box-bar" style="background:linear-gradient(90deg,#1e90ff,#4f8ef7)"></div>
        <div class="mz-box-head">
            <div class="mz-box-title">Invoice Terbaru</div>
            <div class="mz-box-sub">8 invoice terakhir</div>
        </div>
        @if($invoiceTerbaru->isEmpty())
            <div class="mz-box-body"><p style="font-size:12px;color:var(--mz-muted)">Belum ada data.</p></div>
        @else
        <table class="mz-table" style="font-size:12.5px;">
            <thead>
                <tr>
                    <th>No Invoice</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th style="text-align:center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceTerbaru as $inv)
                <tr>
                    <td class="mono" style="font-size:11.5px;">{{ $inv->invoice_no }}</td>
                    <td style="font-weight:600;color:var(--mz-text)">{{ $inv->pelanggan->nama ?? '-' }}</td>
                    <td style="font-weight:600;">Rp {{ number_format($inv->grand_total,0,',','.') }}</td>
                    <td style="text-align:center">
                        @if($inv->status_bayar === 'sudah') <span class="mz-badge badge-lunas">Lunas</span>
                        @elseif($inv->payment_awal > 0) <span class="mz-badge badge-sebagian">Sebagian</span>
                        @else <span class="mz-badge badge-belum">Belum</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
        <div class="mz-box">
            <div class="mz-box-bar" style="background:linear-gradient(90deg,#0d9488,#2dd4bf)"></div>
            <div class="mz-box-head"><div class="mz-box-title">Metode Pembayaran</div><div class="mz-box-sub">Rekapan bulan ini</div></div>
            <div class="mz-box-body">
                @php $totalMetode = max($metodeCash + $metodeMandiri + $metodeBca, 1); @endphp
                <div class="met-row">
                    <span class="met-label">Cash</span>
                    <div class="met-bg"><div class="met-fill" style="width:{{ round(($metodeCash/$totalMetode)*100) }}%;background:var(--mz-green)"></div></div>
                    <span class="met-val">Rp {{ number_format($metodeCash,0,',','.') }}</span>
                </div>
                <div class="met-row">
                    <span class="met-label">Mandiri</span>
                    <div class="met-bg"><div class="met-fill" style="width:{{ round(($metodeMandiri/$totalMetode)*100) }}%;background:var(--mz-yellow)"></div></div>
                    <span class="met-val">Rp {{ number_format($metodeMandiri,0,',','.') }}</span>
                </div>
                <div class="met-row">
                    <span class="met-label">BCA</span>
                    <div class="met-bg"><div class="met-fill" style="width:{{ round(($metodeBca/$totalMetode)*100) }}%;background:var(--mz-accent)"></div></div>
                    <span class="met-val">Rp {{ number_format($metodeBca,0,',','.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ PIUTANG ══ --}}
<div class="db-section">
    <div class="db-section-head">
        <div class="db-section-icon" style="background:linear-gradient(135deg,#b91c1c,#f26c6c)">
            <svg viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
        </div>
        <div class="db-section-title">Piutang & Sisa Tagihan</div>
    </div>
    <div class="db-section-sub">Tagihan yang belum dilunasi pelanggan</div>

    @if($piutangCount > 0)
    <div class="mz-alert-danger">
        <div class="pulse-dot" style="background:var(--mz-orange)"></div>
        Terdapat <strong style="margin:0 4px">{{ $piutangCount }} invoice</strong> dengan total piutang <strong style="margin:0 4px">Rp {{ number_format($totalPiutang,0,',','.') }}</strong> yang belum dilunasi.
    </div>
    @endif

    <div class="grid-3" style="margin-bottom:16px">
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-red)"></div><div class="mz-stat-label">Total Piutang</div><div class="mz-stat-val sm" style="color:var(--mz-red)">Rp {{ number_format($totalPiutang,0,',','.') }}</div><div class="mz-stat-sub">sisa belum dibayar</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-orange)"></div><div class="mz-stat-label">Jumlah Invoice</div><div class="mz-stat-val" style="color:var(--mz-orange)">{{ $piutangCount }}</div><div class="mz-stat-sub">invoice belum lunas</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-yellow)"></div><div class="mz-stat-label">Rata-rata</div><div class="mz-stat-val sm" style="color:var(--mz-yellow)">Rp {{ $piutangCount > 0 ? number_format(round($totalPiutang/$piutangCount),0,',','.') : '0' }}</div><div class="mz-stat-sub">per invoice</div></div>
    </div>

    <div class="mz-box">
        <div class="mz-box-bar" style="background:linear-gradient(90deg,#b91c1c,#f26c6c)"></div>
        <div class="mz-box-head"><div class="mz-box-title">Tagihan Terbesar</div><div class="mz-box-sub">Invoice dengan sisa pembayaran tertinggi</div></div>
        @if($piutangTerbesar->isEmpty())
            <div class="mz-box-body"><p style="font-size:12px;color:var(--mz-green)">✓ Tidak ada piutang. Semua invoice sudah lunas.</p></div>
        @else
        <table class="mz-table">
            {{-- BARU --}}
            <thead><tr><th>No Invoice</th><th>Pelanggan</th><th>Tanggal</th><th>Grand Total</th><th>Total Terbayar</th><th>Sisa Tagihan</th></tr></thead>
            <tbody>
                @foreach($piutangTerbesar as $inv)
                <tr>
                    <td class="mono">{{ $inv->invoice_no }}</td>
                    <td style="font-weight:500">{{ $inv->pelanggan->nama ?? '-' }}</td>
                    <td style="color:var(--mz-muted)">{{ Carbon::parse($inv->tanggal)->format('d M Y') }}</td>
                    <td>Rp {{ number_format($inv->grand_total,0,',','.') }}</td>
                    <{{-- BARU — pakai accessor total_terbayar & sisa_tagihan --}}
                    <td style="color:var(--mz-green)">Rp {{ number_format($inv->total_terbayar,0,',','.') }}</td>
                    <td><span style="font-weight:700;color:var(--mz-red)">Rp {{ number_format($inv->sisa_tagihan,0,',','.') }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- ══ KARYAWAN ══ --}}
<div class="db-section">
    <div class="db-section-head">
        <div class="db-section-icon" style="background:linear-gradient(135deg,#065f46,#10b981)">
            <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        </div>
        <div class="db-section-title">Data Karyawan</div>
    </div>
    <div class="db-section-sub">Ringkasan status seluruh karyawan</div>
    <div class="grid-5">
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-text)"></div><div class="mz-stat-label">Total</div><div class="mz-stat-val">{{ $totalKaryawan }}</div><div class="mz-stat-sub">karyawan</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-emerald)"></div><div class="mz-stat-label">Aktif</div><div class="mz-stat-val" style="color:var(--mz-emerald)">{{ $totaLKaryawanAktif }}</div><div class="mz-stat-sub">karyawan</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-yellow)"></div><div class="mz-stat-label">Cuti</div><div class="mz-stat-val" style="color:var(--mz-yellow)">{{ $totalKaryawanCuti }}</div><div class="mz-stat-sub">karyawan</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-muted)"></div><div class="mz-stat-label">Nonaktif</div><div class="mz-stat-val" style="color:var(--mz-muted)">{{ $totalKaryawanNonAktif }}</div><div class="mz-stat-sub">karyawan</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-red)"></div><div class="mz-stat-label">Resign</div><div class="mz-stat-val" style="color:var(--mz-red)">{{ $totalKaryawanResign }}</div><div class="mz-stat-sub">karyawan</div></div>
    </div>
</div>

{{-- ══ ABSENSI ══ --}}
<div class="db-section">
    <div class="db-section-head">
        <div class="db-section-icon" style="background:linear-gradient(135deg,#1e40af,#4f8ef7)">
            <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
        </div>
        <div class="db-section-title">Rekap Absensi</div>
    </div>
    <div class="db-section-sub">Statistik kehadiran berdasarkan periode</div>

    {{-- Controls: tab + dropdown bulan/tahun --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
        <div class="mz-tabs" style="margin-bottom:0">
            <button class="mz-tab active" onclick="switchAbsensi('hari',this)">Hari Ini</button>
            <button class="mz-tab" onclick="switchAbsensi('minggu',this)">Minggu Ini</button>
            <button class="mz-tab abs-bulan-tab" onclick="switchAbsensi('bulan',this)">Bulan Ini</button>
        </div>
        <div id="abs-bulan-filter" style="display:none;align-items:center;gap:8px;">
            <select id="abs-select-bulan" class="mz-select">
                @foreach(range(1,12) as $mb)
                <option value="{{ $mb }}" {{ $mb == now()->month ? 'selected' : '' }}>
                    {{ Carbon::create(null,$mb)->translatedFormat('F') }}
                </option>
                @endforeach
            </select>
            <select id="abs-select-tahun" class="mz-select">
                @foreach(range(now()->year, now()->year - 4) as $yr)
                <option value="{{ $yr }}" {{ $yr == now()->year ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- HARI --}}
    <div id="abs-hari" class="abs-panel active">
        <div class="abs-layout">
            <div class="abs-main-card" style="--abs-color:var(--mz-green)">
                <div class="abs-main-icon">✓</div>
                <div class="abs-main-val">{{ $hariH }}</div>
                <div class="abs-main-label">Hadir</div>
            </div>
            <div class="abs-side-list">
                @php $totalHari = max($hariH+$hariL+$hariS+$hariK+$hariUM,1); @endphp
                <div class="abs-item">
                    <div class="abs-item-left"><span class="abs-dot" style="background:var(--mz-accent)"></span><span class="abs-item-label">Libur</span></div>
                    <div class="abs-item-bar-wrap"><div class="abs-item-bar"><div class="abs-item-fill" style="width:{{ round(($hariL/$totalHari)*100) }}%;background:var(--mz-accent)"></div></div></div>
                    <span class="abs-item-val" style="color:var(--mz-accent)">{{ $hariL }}</span>
                </div>
                <div class="abs-item">
                    <div class="abs-item-left"><span class="abs-dot" style="background:var(--mz-yellow)"></span><span class="abs-item-label">Sakit</span></div>
                    <div class="abs-item-bar-wrap"><div class="abs-item-bar"><div class="abs-item-fill" style="width:{{ round(($hariS/$totalHari)*100) }}%;background:var(--mz-yellow)"></div></div></div>
                    <span class="abs-item-val" style="color:var(--mz-yellow)">{{ $hariS }}</span>
                </div>
                <div class="abs-item">
                    <div class="abs-item-left"><span class="abs-dot" style="background:var(--mz-purple)"></span><span class="abs-item-label">Izin</span></div>
                    <div class="abs-item-bar-wrap"><div class="abs-item-bar"><div class="abs-item-fill" style="width:{{ round(($hariK/$totalHari)*100) }}%;background:var(--mz-purple)"></div></div></div>
                    <span class="abs-item-val" style="color:var(--mz-purple)">{{ $hariK }}</span>
                </div>
                <div class="abs-item">
                    <div class="abs-item-left"><span class="abs-dot" style="background:var(--mz-red)"></span><span class="abs-item-label">Tanpa Ket.</span></div>
                    <div class="abs-item-bar-wrap"><div class="abs-item-bar"><div class="abs-item-fill" style="width:{{ round(($hariUM/$totalHari)*100) }}%;background:var(--mz-red)"></div></div></div>
                    <span class="abs-item-val" style="color:var(--mz-red)">{{ $hariUM }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MINGGU --}}
    <div id="abs-minggu" class="abs-panel">
        <div class="abs-layout">
            <div class="abs-main-card" style="--abs-color:var(--mz-green)">
                <div class="abs-main-icon">✓</div>
                <div class="abs-main-val">{{ $mingguH }}</div>
                <div class="abs-main-label">Hadir</div>
            </div>
            <div class="abs-side-list">
                @php $totalMinggu = max($mingguH+$mingguL+$mingguS+$mingguK+$mingguUM,1); @endphp
                <div class="abs-item">
                    <div class="abs-item-left"><span class="abs-dot" style="background:var(--mz-accent)"></span><span class="abs-item-label">Libur</span></div>
                    <div class="abs-item-bar-wrap"><div class="abs-item-bar"><div class="abs-item-fill" style="width:{{ round(($mingguL/$totalMinggu)*100) }}%;background:var(--mz-accent)"></div></div></div>
                    <span class="abs-item-val" style="color:var(--mz-accent)">{{ $mingguL }}</span>
                </div>
                <div class="abs-item">
                    <div class="abs-item-left"><span class="abs-dot" style="background:var(--mz-yellow)"></span><span class="abs-item-label">Sakit</span></div>
                    <div class="abs-item-bar-wrap"><div class="abs-item-bar"><div class="abs-item-fill" style="width:{{ round(($mingguS/$totalMinggu)*100) }}%;background:var(--mz-yellow)"></div></div></div>
                    <span class="abs-item-val" style="color:var(--mz-yellow)">{{ $mingguS }}</span>
                </div>
                <div class="abs-item">
                    <div class="abs-item-left"><span class="abs-dot" style="background:var(--mz-purple)"></span><span class="abs-item-label">Izin</span></div>
                    <div class="abs-item-bar-wrap"><div class="abs-item-bar"><div class="abs-item-fill" style="width:{{ round(($mingguK/$totalMinggu)*100) }}%;background:var(--mz-purple)"></div></div></div>
                    <span class="abs-item-val" style="color:var(--mz-purple)">{{ $mingguK }}</span>
                </div>
                <div class="abs-item">
                    <div class="abs-item-left"><span class="abs-dot" style="background:var(--mz-red)"></span><span class="abs-item-label">Tanpa Ket.</span></div>
                    <div class="abs-item-bar-wrap"><div class="abs-item-bar"><div class="abs-item-fill" style="width:{{ round(($mingguUM/$totalMinggu)*100) }}%;background:var(--mz-red)"></div></div></div>
                    <span class="abs-item-val" style="color:var(--mz-red)">{{ $mingguUM }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- BULAN (AJAX) --}}
    <div id="abs-bulan" class="abs-panel">
        <div class="abs-layout">
            <div class="abs-main-card" style="--abs-color:var(--mz-green)">
                <div class="abs-main-icon" id="abs-bulan-icon">✓</div>
                <div class="abs-main-val" id="abs-bulan-H">{{ $bulanH }}</div>
                <div class="abs-main-label">Hadir</div>
            </div>
            <div class="abs-side-list" id="abs-bulan-side">
                @php $totalBulan = max($bulanH+$bulanL+$bulanS+$bulanK+$bulanUM,1); @endphp
                @foreach([
                    ['id'=>'L','label'=>'Libur','color'=>'var(--mz-accent)','val'=>$bulanL],
                    ['id'=>'S','label'=>'Sakit','color'=>'var(--mz-yellow)','val'=>$bulanS],
                    ['id'=>'K','label'=>'Izin','color'=>'var(--mz-purple)','val'=>$bulanK],
                    ['id'=>'UM','label'=>'Tanpa Ket.','color'=>'var(--mz-red)','val'=>$bulanUM],
                ] as $row)
                <div class="abs-item" id="abs-row-{{ $row['id'] }}">
                    <div class="abs-item-left">
                        <span class="abs-dot" style="background:{{ $row['color'] }}"></span>
                        <span class="abs-item-label">{{ $row['label'] }}</span>
                    </div>
                    <div class="abs-item-bar-wrap">
                        <div class="abs-item-bar">
                            <div class="abs-item-fill abs-fill-{{ $row['id'] }}"
                                 style="width:{{ round(($row['val']/$totalBulan)*100) }}%;background:{{ $row['color'] }}">
                            </div>
                        </div>
                    </div>
                    <span class="abs-item-val abs-val-{{ $row['id'] }}" style="color:{{ $row['color'] }}">{{ $row['val'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

    {{-- Absensi hari ini table --}}
    <div class="mz-box" style="margin-top:14px">
        <div class="mz-box-bar" style="background:linear-gradient(90deg,#1e40af,#4f8ef7,#93c5fd)"></div>
        <div class="mz-box-head"><div class="mz-box-title">Absensi Hari Ini</div><div class="mz-box-sub">{{ Carbon::today()->translatedFormat('l, d F Y') }}</div></div>
        @if($absensiHariIni->isEmpty())
            <div class="mz-box-body"><p style="font-size:12px;color:var(--mz-muted)">Belum ada data absensi hari ini.</p></div>
        @else
        <table class="mz-table">
            <thead><tr><th>#</th><th>Nama Karyawan</th><th>Tanggal</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($absensiHariIni as $i => $abs)
                @php $s = $statusLabel[$abs->status] ?? ['label'=>$abs->status,'color'=>'#6b7694']; @endphp
                <tr>
                    <td style="color:var(--mz-muted)">{{ $i + 1 }}</td>
                    <td style="font-weight:500">{{ $abs->karyawan->nama ?? '-' }}</td>
                    <td style="color:var(--mz-muted)">{{ Carbon::parse($abs->tanggal)->format('d M Y') }}</td>
                    <td><span class="mz-badge" style="background:{{ $s['color'] }}18;color:{{ $s['color'] }};border-color:{{ $s['color'] }}33">{{ $s['label'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- ══ PELANGGAN ══ --}}
<div class="db-section">
    <div class="db-section-head">
        <div class="db-section-icon" style="background:linear-gradient(135deg,#0d9488,#2dd4bf)">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <div class="db-section-title">Data Pelanggan</div>
    </div>
    <div class="db-section-sub">Statistik pelanggan bengkel</div>

    <div class="grid-3" style="margin-bottom:12px">
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-text)"></div><div class="mz-stat-label">Total</div><div class="mz-stat-val">{{ $totalPelanggan }}</div><div class="mz-stat-sub">terdaftar</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-teal)"></div><div class="mz-stat-label">Pribadi</div><div class="mz-stat-val" style="color:var(--mz-teal)">{{ $totalPribadi }}</div><div class="mz-stat-sub">pelanggan</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-yellow)"></div><div class="mz-stat-label">Perusahaan</div><div class="mz-stat-val" style="color:var(--mz-yellow)">{{ $totalPerusahaan }}</div><div class="mz-stat-sub">pelanggan</div></div>
    </div>
    <div class="grid-3" style="margin-bottom:16px">
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-emerald)"></div><div class="mz-stat-label">Hari Ini</div><div class="mz-stat-val" style="color:var(--mz-emerald)">{{ $pelangganHariIni }}</div><div class="mz-stat-sub">baru</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-emerald)"></div><div class="mz-stat-label">Minggu Ini</div><div class="mz-stat-val" style="color:var(--mz-emerald)">{{ $pelangganMingguIni }}</div><div class="mz-stat-sub">baru</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-emerald)"></div><div class="mz-stat-label">Bulan Ini</div><div class="mz-stat-val" style="color:var(--mz-emerald)">{{ $pelangganBulanIni }}</div><div class="mz-stat-sub">baru</div></div>
    </div>

    <div class="grid-2">
        <div class="mz-box">
            <div class="mz-box-bar" style="background:linear-gradient(90deg,#0d9488,#2dd4bf)"></div>
            <div class="mz-box-head"><div class="mz-box-title">Pelanggan Terbaru</div><div class="mz-box-sub">8 pendaftaran terakhir</div></div>
            @if($pelangganTerbaru->isEmpty())
                <div class="mz-box-body"><p style="font-size:12px;color:var(--mz-muted)">Belum ada data.</p></div>
            @else
            <table class="mz-table">
                <thead><tr><th>Nama</th><th>Plat</th><th>Mobil</th><th>Tipe</th></tr></thead>
                <tbody>
                    @foreach($pelangganTerbaru as $p)
                    <tr>
                        <td style="font-weight:500">{{ $p->nama }}</td>
                        <td class="mono">{{ $p->plat_nomor }}</td>
                        <td style="color:var(--mz-muted)">{{ $p->merk_mobil }} {{ $p->model_mobil }}</td>
                        <td>
                            @if($p->tipe === 'pribadi') <span class="mz-badge badge-pribadi">Pribadi</span>
                            @else <span class="mz-badge badge-perusahaan">Perusahaan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        <div class="mz-box">
            <div class="mz-box-bar" style="background:linear-gradient(90deg,#0d9488,#2dd4bf)"></div>
            <div class="mz-box-head"><div class="mz-box-title">Top Merk Mobil</div><div class="mz-box-sub">5 merk terbanyak</div></div>
            <div class="mz-box-body">
                @php $maxMerk = $topMerk->max('total') ?: 1; @endphp
                @forelse($topMerk as $m)
                <div class="bar-row">
                    <span class="bar-label">{{ $m->merk_mobil }}</span>
                    <div class="bar-bg"><div class="bar-fill" style="width:{{ round(($m->total/$maxMerk)*100) }}%;background:var(--mz-teal)"></div></div>
                    <span class="bar-count">{{ $m->total }}</span>
                </div>
                @empty <p style="font-size:12px;color:var(--mz-muted)">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ══ BARANG ══ --}}
<div class="db-section">
    <div class="db-section-head">
        <div class="db-section-icon" style="background:linear-gradient(135deg,#1e3a8a,#4f8ef7)">
            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
        </div>
        <div class="db-section-title">Data Barang & Stok</div>
    </div>
    <div class="db-section-sub">Ringkasan inventaris bengkel</div>

    @if($stokHabis > 0 || $stokMenipis > 0)
    <div class="mz-alert-warn">
        <div class="pulse-dot" style="background:var(--mz-orange)"></div>
        Perhatian:
        @if($stokHabis > 0)<strong style="margin:0 4px">{{ $stokHabis }} barang stok habis</strong>@endif
        @if($stokHabis > 0 && $stokMenipis > 0) & @endif
        @if($stokMenipis > 0)<strong style="margin:0 4px">{{ $stokMenipis }} barang stok menipis</strong> (≤5)@endif
        — segera lakukan pengisian ulang.
    </div>
    @endif

    <div class="grid-4" style="margin-bottom:12px">
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-text)"></div><div class="mz-stat-label">Total Barang</div><div class="mz-stat-val">{{ $totalBarang }}</div><div class="mz-stat-sub">jenis</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-green)"></div><div class="mz-stat-label">Stok Aman</div><div class="mz-stat-val" style="color:var(--mz-green)">{{ $stokAman }}</div><div class="mz-stat-sub">jenis</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-yellow)"></div><div class="mz-stat-label">Stok Menipis</div><div class="mz-stat-val" style="color:var(--mz-yellow)">{{ $stokMenipis }}</div><div class="mz-stat-sub">≤ 5 unit</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-red)"></div><div class="mz-stat-label">Stok Habis</div><div class="mz-stat-val" style="color:var(--mz-red)">{{ $stokHabis }}</div><div class="mz-stat-sub">jenis</div></div>
    </div>
    <div class="grid-3" style="margin-bottom:16px">
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-accent)"></div><div class="mz-stat-label">Total Unit Stok</div><div class="mz-stat-val" style="color:var(--mz-accent)">{{ number_format($totalStok) }}</div><div class="mz-stat-sub">unit</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-teal)"></div><div class="mz-stat-label">Nilai Stok (Pribadi)</div><div class="mz-stat-val sm" style="color:var(--mz-teal)">Rp {{ number_format($nilaiStokPribadi,0,',','.') }}</div><div class="mz-stat-sub">estimasi</div></div>
        <div class="mz-stat"><div class="mz-stat-bar" style="background:var(--mz-teal)"></div><div class="mz-stat-label">Nilai Stok (Perusahaan)</div><div class="mz-stat-val sm" style="color:var(--mz-teal)">Rp {{ number_format($nilaiStokPerusahaan,0,',','.') }}</div><div class="mz-stat-sub">estimasi</div></div>
    </div>

    <div class="grid-2">
        <div class="mz-box">
            <div class="mz-box-bar" style="background:linear-gradient(90deg,#1e3a8a,#4f8ef7)"></div>
            <div class="mz-box-head"><div class="mz-box-title">Stok Kritis</div><div class="mz-box-sub">Barang habis & menipis (≤5 unit)</div></div>
            @if($barangKritis->isEmpty())
                <div class="mz-box-body"><p style="font-size:12px;color:var(--mz-green)">✓ Semua stok dalam kondisi aman.</p></div>
            @else
            <table class="mz-table">
                <thead><tr><th>Nama Barang</th><th>Satuan</th><th>Harga Pribadi</th><th>Stok</th></tr></thead>
                <tbody>
                    @foreach($barangKritis as $b)
                    <tr>
                        <td style="font-weight:500">{{ $b->nama }}</td>
                        <td style="color:var(--mz-muted)">{{ $b->satuan }}</td>
                        <td style="color:var(--mz-muted)">Rp {{ number_format($b->harga_pribadi,0,',','.') }}</td>
                        <td>
                            @if($b->stok == 0) <span class="mz-badge badge-habis">Habis</span>
                            @else <span class="mz-badge badge-menipis">{{ $b->stok }} unit</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        <div class="mz-box">
            <div class="mz-box-bar" style="background:linear-gradient(90deg,#1e3a8a,#4f8ef7)"></div>
            <div class="mz-box-head"><div class="mz-box-title">Stok Terbanyak</div><div class="mz-box-sub">5 barang stok tertinggi</div></div>
            <div class="mz-box-body">
                @php $maxStok = $topStok->max('stok') ?: 1; @endphp
                @forelse($topStok as $b)
                <div class="bar-row">
                    <span class="bar-label">{{ $b->nama }}</span>
                    <div class="bar-bg"><div class="bar-fill" style="width:{{ round(($b->stok/$maxStok)*100) }}%;background:var(--mz-accent)"></div></div>
                    <span class="bar-count">{{ $b->stok }}</span>
                </div>
                @empty <p style="font-size:12px;color:var(--mz-muted)">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

</div>{{-- end db-wrap --}}

<script>
const dataMinggu = @json($grafikMinggu);
const dataBulan  = @json($grafikBulan);
const dataTahun  = @json($grafikTahunan);

Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#6b7694';

function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

function buildChart(canvasId, labels, data, color) {
    const el = document.getElementById(canvasId);
    if (!el) return;
    const ctx = el.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 220);
    gradient.addColorStop(0, color + '44');
    gradient.addColorStop(1, color + '00');
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Pendapatan', data,
                borderColor: color, backgroundColor: gradient,
                borderWidth: 2, pointBackgroundColor: color,
                pointBorderColor: '#181c27', pointBorderWidth: 2,
                pointRadius: 4, pointHoverRadius: 6,
                fill: true, tension: 0.4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e2333', titleColor: '#e4e8f0',
                    bodyColor: '#6b7694', padding: 12, cornerRadius: 8,
                    borderColor: '#262c3d', borderWidth: 1,
                    callbacks: { label: c => ' Rp ' + c.parsed.y.toLocaleString('id-ID') }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { maxRotation: 30 } },
                y: {
                    grid: { color: '#262c3d' }, border: { display: false, dash: [4,4] },
                    ticks: { callback: v => v >= 1e6 ? 'Rp '+(v/1e6).toFixed(1)+'jt' : 'Rp '+v.toLocaleString('id-ID') }
                }
            }
        }
    });
}

buildChart('grafikMinggu', dataMinggu.map(d=>d.label), dataMinggu.map(d=>d.pendapatan), '#4f8ef7');
buildChart('grafikBulan',  dataBulan.map(d=>d.label),  dataBulan.map(d=>d.pendapatan),  '#3ef08a');
buildChart('grafikTahun',  dataTahun.map(d=>d.label),  dataTahun.map(d=>d.pendapatan),  '#f5c542');

function switchTab(tab, el) {
    document.querySelectorAll('.abs-grid').forEach(g => g.classList.remove('active'));
    document.querySelectorAll('.mz-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    el.classList.add('active');
}

function switchChart(panel, el) {
    document.querySelectorAll('.chart-panel').forEach(p => p.classList.remove('active'));
    el.closest('.mz-box-head').querySelectorAll('.mz-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('chart-' + panel).classList.add('active');
    el.classList.add('active');
}

function switchAbsensi(tab, btn) {
    document.querySelectorAll('.abs-panel').forEach(p => p.classList.remove('active'));
    // hanya reset tab absensi, bukan semua .mz-tab
    btn.closest('.mz-tabs').querySelectorAll('.mz-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('abs-' + tab).classList.add('active');
    btn.classList.add('active');

    const filter = document.getElementById('abs-bulan-filter');
    filter.style.display = tab === 'bulan' ? 'flex' : 'none';
}

function fetchAbsensi() {
    const bulan = document.getElementById('abs-select-bulan').value;
    const tahun = document.getElementById('abs-select-tahun').value;
    const side  = document.getElementById('abs-bulan-side');

    side.classList.add('abs-loading');

    fetch(`{{ route('dashboard.absensiRekap') }}?bulan=${bulan}&tahun=${tahun}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const total = Math.max(data.H + data.L + data.S + data.K + data.UM, 1);

        document.getElementById('abs-bulan-H').textContent = data.H;

        ['L','S','K','UM'].forEach(key => {
            const pct = Math.round((data[key] / total) * 100);
            document.querySelector(`.abs-val-${key}`).textContent  = data[key];
            document.querySelector(`.abs-fill-${key}`).style.width = pct + '%';
        });

        side.classList.remove('abs-loading');
    })
    .catch(() => side.classList.remove('abs-loading'));
}

document.getElementById('abs-select-bulan').addEventListener('change', fetchAbsensi);
document.getElementById('abs-select-tahun').addEventListener('change', fetchAbsensi);
</script>
@endsection
