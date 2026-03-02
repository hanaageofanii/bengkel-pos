@extends('dashboard')

@php
use App\Models\Karyawan;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\Jasa;
use App\Models\Invoice;
use App\Models\InvoicePayment;

$totalKaryawan = Karyawan::count();
$totalKaryawanCuti = Karyawan::where('status','cuti')->count();
$totalKaryawanResign = Karyawan::where('status','resign')->count();
$totalKaryawanNonAktif = Karyawan::whereIn('status',['nonaktif'])->count();
$totaLKaryawanAktif = Karyawan::where('status','aktif')->count();

// === ABSENSI ===
$today     = Carbon::today();
$startWeek = Carbon::now()->startOfWeek();
$endWeek   = Carbon::now()->endOfWeek();
$startMonth = Carbon::now()->startOfMonth();
$endMonth   = Carbon::now()->endOfMonth();

// Helper closure hitung per status & periode
$count = fn($status, $from, $to) => Absensi::where('status', $status)
    ->whereBetween('tanggal', [$from, $to])->count();

// Hari ini
$hariH  = $count('H',  $today, $today);
$hariL  = $count('L',  $today, $today);
$hariS  = $count('S',  $today, $today);
$hariK  = $count('K',  $today, $today);
$hariUM = $count('UM', $today, $today);

// Minggu ini
$mingguH  = $count('H',  $startWeek, $endWeek);
$mingguL  = $count('L',  $startWeek, $endWeek);
$mingguS  = $count('S',  $startWeek, $endWeek);
$mingguK  = $count('K',  $startWeek, $endWeek);
$mingguUM = $count('UM', $startWeek, $endWeek);

// Bulan ini
$bulanH  = $count('H',  $startMonth, $endMonth);
$bulanL  = $count('L',  $startMonth, $endMonth);
$bulanS  = $count('S',  $startMonth, $endMonth);
$bulanK  = $count('K',  $startMonth, $endMonth);
$bulanUM = $count('UM', $startMonth, $endMonth);

// Tabel: absensi hari ini dengan relasi karyawan
$absensiHariIni = Absensi::with('karyawan')
    ->whereDate('tanggal', $today)
    ->latest()
    ->take(10)
    ->get();

$statusLabel = [
    'H'  => ['label' => 'Hadir',       'color' => '#059669'],
    'L'  => ['label' => 'Libur',      'color' => '#2563eb'],
    'S'  => ['label' => 'Sakit',       'color' => '#d97706'],
    'K'  => ['label' => 'Izin',        'color' => '#7c3aed'],
    'UM' => ['label' => 'Tanpa Keterangan', 'color' => '#dc2626'],
];
// === PELANGGAN ===
$totalPelanggan        = Pelanggan::count();
$totalPribadi          = Pelanggan::where('tipe', 'pribadi')->count();
$totalPerusahaan       = Pelanggan::where('tipe', 'perusahaan')->count();
$pelangganBulanIni     = Pelanggan::whereBetween('created_at', [$startMonth, $endMonth])->count();
$pelangganMingguIni    = Pelanggan::whereBetween('created_at', [$startWeek, $endWeek])->count();
$pelangganHariIni      = Pelanggan::whereDate('created_at', $today)->count();

// Top 5 merk mobil
$topMerk = Pelanggan::selectRaw('merk_mobil, COUNT(*) as total')
    ->groupBy('merk_mobil')
    ->orderByDesc('total')
    ->limit(5)
    ->get();

// Pelanggan terbaru
$pelangganTerbaru = Pelanggan::latest()->take(8)->get();
// === BARANG ===
$totalBarang       = Barang::count();
$totalStok         = Barang::sum('stok');
$stokHabis         = Barang::where('stok', 0)->count();
$stokMenipis       = Barang::where('stok', '>', 0)->where('stok', '<=', 5)->count();
$stokAman          = Barang::where('stok', '>', 5)->count();
$nilaiStokPribadi  = Barang::selectRaw('SUM(harga_pribadi * stok) as total')->value('total') ?? 0;
$nilaiStokPerusahaan = Barang::selectRaw('SUM(harga_perusahaan * stok) as total')->value('total') ?? 0;

// Top 5 stok terbanyak
$topStok = Barang::orderByDesc('stok')->limit(5)->get();

// Barang stok habis/menipis
$barangKritis = Barang::where('stok', '<=', 5)->orderBy('stok')->limit(8)->get();
@endphp

@section('title', 'Dashboard')
<style>
 .dashboard-wrap { font-family: 'DM Sans', sans-serif; }

    .stat-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #b3b0b0;
        border-radius: 16px;
        padding: 24px 20px;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        overflow: hidden;
    }
    .stat-card:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .accent-bar {
        position: absolute; top: 0; left: 0;
        width: 3px; height: 100%;
        border-radius: 16px 0 0 16px;
    }
    .stat-label {
        font-size: 11px; font-weight: 500;
        letter-spacing: 0.1em; text-transform: uppercase;
        color: #181818; margin-bottom: 10px;
    }
    .stat-number {
        font-family: 'DM Serif Display', serif;
        font-size: 36px; line-height: 1;
    }
    .stat-sub { font-size: 11px; color: #181818; margin-top: 5px; }

    .section-title {
        font-family: 'DM Serif Display', serif;
        font-size: 18px; color: #111827; font-weight: 400;
    }
    .section-divider {
        width: 32px; height: 2px;
        background: #181818; margin: 6px 0 4px; border-radius: 2px;
    }
    .section-sub { font-size: 12px; color: #181818; margin-bottom: 14px; }

    .tab-btn {
        padding: 6px 16px; border-radius: 8px;
        font-size: 12px; font-weight: 500;
        border: 1px solid #181818;
        background: #fff; color: #181818;
        cursor: pointer; transition: all 0.15s;
    }
    .tab-btn.active, .tab-btn:hover {
        background: #111827; color: #fff; border-color: #111827;
    }
    .absensi-grid { display: none; }
    .absensi-grid.active { display: grid; }

    .abs-table {
        width: 100%; border-collapse: collapse; font-size: 13px;
    }
    .abs-table thead tr { border-bottom: 2px solid #f3f4f6; }
    .abs-table th {
        text-align: left; padding: 10px 14px;
        font-size: 11px; font-weight: 500;
        letter-spacing: 0.08em; text-transform: uppercase; color: #181818;
    }
    .abs-table tbody tr {
        border-bottom: 1px solid #f9fafb;
        transition: background 0.1s;
    }
    .abs-table tbody tr:hover { background: #f9fafb; }
    .abs-table td { padding: 10px 14px; color: #181818; }

    .badge {
        display: inline-block; padding: 2px 10px;
        border-radius: 20px; font-size: 11px; font-weight: 500;
    }

    .merk-bar-wrap { display: flex; flex-direction: column; gap: 10px; }
    .merk-row { display: flex; align-items: center; gap: 10px; }
    .merk-label { font-size: 12px; color: #181818; width: 110px; flex-shrink: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .merk-bar-bg {
        flex: 1; background: #f3f4f6;
        border-radius: 99px; height: 8px; overflow: hidden;
    }
    .merk-bar-fill {
        height: 100%; border-radius: 99px;
        transition: width 0.6s ease;
    }
    .merk-count { font-size: 12px; font-weight: 600; color: #111827; width: 32px; text-align: right; }

    .greeting-title {
        font-family: 'DM Serif Display', serif;
        font-size: 26px; color: #111827; font-weight: 400;
    }
    .divider-line {
        width: 40px; height: 2px;
        background: #111827; margin: 10px 0 4px; border-radius: 2px;
    }
    .card-box {
        background: #fff; border: 1px solid #181818;
        border-radius: 16px; overflow: hidden;
    }
    .card-box-header { padding: 20px 24px 0; }
    .card-box-body { padding: 20px 24px; }

    /* Alert stok kritis */
    .stok-alert {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 14px;
        border-radius: 10px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        margin-bottom: 12px;
        font-size: 12px; color: #b91c1c;
    }
    .stok-alert-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #ef4444; flex-shrink: 0;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
    </style>
@section('content')
<div class="w-full py-6">

    {{-- Header --}}
    <div style="margin-bottom: 2rem;">
        <p style="font-size:11px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; color:##181818; margin-bottom:4px;">
            5A Auto Service
        </p>
        <h1 class="greeting-title">Dashboard</h1>
        <div class="divider-line"></div>
        <p style="font-size:14px; color:#181818; margin-top:8px;">
            Selamat datang, <span style="color:##181818; font-weight:500;">{{ auth()->user()->name }}</span>
        </p>
    </div>

    {{-- Stats Grid --}}
    <div style="display:grid; grid-template-columns: repeat(5, 1fr); gap: 16px;">

        <div class="stat-card">
            <div class="accent-bar" style="background:#111827;"></div>
            <p class="stat-label">Total</p>
            <p class="stat-number" style="color:#111827;">{{ $totalKaryawan }}</p>
            <p class="stat-sub">karyawan</p>
        </div>

        <div class="stat-card">
            <div class="accent-bar" style="background:#10b981;"></div>
            <p class="stat-label">Aktif</p>
            <p class="stat-number" style="color:#059669;">{{ $totaLKaryawanAktif }}</p>
            <p class="stat-sub">karyawan</p>
        </div>

        <div class="stat-card">
            <div class="accent-bar" style="background:#f59e0b;"></div>
            <p class="stat-label">Cuti</p>
            <p class="stat-number" style="color:#d97706;">{{ $totalKaryawanCuti }}</p>
            <p class="stat-sub">karyawan</p>
        </div>

        <div class="stat-card">
            <div class="accent-bar" style="background:##181818;"></div>
            <p class="stat-label">Nonaktif</p>
            <p class="stat-number" style="color:##181818;">{{ $totalKaryawanNonAktif }}</p>
            <p class="stat-sub">karyawan</p>
        </div>

        <div class="stat-card">
            <div class="accent-bar" style="background:#ef4444;"></div>
            <p class="stat-label">Resign</p>
            <p class="stat-number" style="color:#dc2626;">{{ $totalKaryawanResign }}</p>
            <p class="stat-sub">karyawan</p>
        </div>

    </div>
</div>

{{-- ===== ABSENSI SECTION ===== --}}
    <div style="margin-bottom:2rem;">
        <p class="section-title">Rekap Absensi</p>
        <p class="section-sub">Statistik kehadiran berdasarkan periode</p>

        {{-- Tab Buttons --}}
        <div style="display:flex; gap:8px; margin-bottom:16px;">
            <button class="tab-btn active" onclick="switchTab('hari', this)">Hari Ini</button>
            <button class="tab-btn" onclick="switchTab('minggu', this)">Minggu Ini</button>
            <button class="tab-btn" onclick="switchTab('bulan', this)">Bulan Ini</button>
        </div>

        {{-- Cards: Hari Ini --}}
        <div id="tab-hari" class="absensi-grid active" style="grid-template-columns: repeat(5,1fr); gap:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Hadir</p><p class="stat-number" style="color:#059669;">{{ $hariH }}</p><p class="stat-sub">orang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#2563eb;"></div><p class="stat-label">Libur</p><p class="stat-number" style="color:#2563eb;">{{ $hariL }}</p><p class="stat-sub">orang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#d97706;"></div><p class="stat-label">Sakit</p><p class="stat-number" style="color:#d97706;">{{ $hariS }}</p><p class="stat-sub">orang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#7c3aed;"></div><p class="stat-label">Izin</p><p class="stat-number" style="color:#7c3aed;">{{ $hariK }}</p><p class="stat-sub">orang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#dc2626;"></div><p class="stat-label">Tanpa Ket.</p><p class="stat-number" style="color:#dc2626;">{{ $hariUM }}</p><p class="stat-sub">orang</p></div>
        </div>

        {{-- Cards: Minggu Ini --}}
        <div id="tab-minggu" class="absensi-grid" style="grid-template-columns: repeat(5,1fr); gap:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Hadir</p><p class="stat-number" style="color:#059669;">{{ $mingguH }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#2563eb;"></div><p class="stat-label">Libur</p><p class="stat-number" style="color:#2563eb;">{{ $mingguL }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#d97706;"></div><p class="stat-label">Sakit</p><p class="stat-number" style="color:#d97706;">{{ $mingguS }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#7c3aed;"></div><p class="stat-label">Izin</p><p class="stat-number" style="color:#7c3aed;">{{ $mingguK }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#dc2626;"></div><p class="stat-label">Tanpa Ket.</p><p class="stat-number" style="color:#dc2626;">{{ $mingguUM }}</p><p class="stat-sub">total</p></div>
        </div>

        {{-- Cards: Bulan Ini --}}
        <div id="tab-bulan" class="absensi-grid" style="grid-template-columns: repeat(5,1fr); gap:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Hadir</p><p class="stat-number" style="color:#059669;">{{ $bulanH }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#2563eb;"></div><p class="stat-label">Libur</p><p class="stat-number" style="color:#2563eb;">{{ $bulanL }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#d97706;"></div><p class="stat-label">Sakit</p><p class="stat-number" style="color:#d97706;">{{ $bulanS }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#7c3aed;"></div><p class="stat-label">Izin</p><p class="stat-number" style="color:#7c3aed;">{{ $bulanK }}</p><p class="stat-sub">total</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#dc2626;"></div><p class="stat-label">Tanpa Ket.</p><p class="stat-number" style="color:#dc2626;">{{ $bulanUM }}</p><p class="stat-sub">total</p></div>
        </div>
    </div>

    {{-- ===== TABEL ABSENSI HARI INI ===== --}}
    <div style="background:#fff; border:1px solid #181818; border-radius:16px; overflow:hidden;">
        <div style="padding: 20px 24px 0;">
            <p class="section-title">Absensi Hari Ini</p>
            <p class="section-sub">{{ Carbon::today()->translatedFormat('l, d F Y') }}</p>
        </div>

        @if($absensiHariIni->isEmpty())
            <div style="padding:40px; text-align:center; color:#181818;">
                <p style="font-size:13px;">Belum ada data absensi hari ini.</p>
            </div>
        @else
        <table class="abs-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Karyawan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensiHariIni as $i => $abs)
                <tr>
                    <td style="color:#181818;">{{ $i + 1 }}</td>
                    <td style="font-weight:500;">{{ $abs->karyawan->nama ?? '-' }}</td>
                    <td style="color:#181818;">{{ \Carbon\Carbon::parse($abs->tanggal)->format('d M Y') }}</td>
                    <td>
                        @php $s = $statusLabel[$abs->status] ?? ['label'=>$abs->status,'color'=>'#181818']; @endphp
                        <span class="badge" style="background:{{ $s['color'] }}18; color:{{ $s['color'] }};">
                            {{ $s['label'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
{{-- ===== PELANGGAN ===== --}}
    <div style="margin-bottom:2.5rem;">
        <p class="section-title">Data Pelanggan</p>
        <div class="section-divider"></div>
        <p class="section-sub">Statistik pelanggan bengkel</p>

        {{-- Summary Cards --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
            <div class="stat-card"><div class="accent-bar" style="background:#111827;"></div><p class="stat-label">Total Pelanggan</p><p class="stat-number" style="color:#111827;">{{ $totalPelanggan }}</p><p class="stat-sub">terdaftar</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#2563eb;"></div><p class="stat-label">Pribadi</p><p class="stat-number" style="color:#2563eb;">{{ $totalPribadi }}</p><p class="stat-sub">pelanggan</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#7c3aed;"></div><p class="stat-label">Perusahaan</p><p class="stat-number" style="color:#7c3aed;">{{ $totalPerusahaan }}</p><p class="stat-sub">pelanggan</p></div>
        </div>

        {{-- Periode Cards --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
            <div class="stat-card"><div class="accent-bar" style="background:#10b981;"></div><p class="stat-label">Daftar Hari Ini</p><p class="stat-number" style="color:#059669;">{{ $pelangganHariIni }}</p><p class="stat-sub">pelanggan baru</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#10b981;"></div><p class="stat-label">Minggu Ini</p><p class="stat-number" style="color:#059669;">{{ $pelangganMingguIni }}</p><p class="stat-sub">pelanggan baru</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#10b981;"></div><p class="stat-label">Bulan Ini</p><p class="stat-number" style="color:#059669;">{{ $pelangganBulanIni }}</p><p class="stat-sub">pelanggan baru</p></div>
        </div>

        {{-- Bottom Row: Merk + Tabel Terbaru --}}
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;">

            {{-- Top Merk --}}
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Top Merk Mobil</p>
                    <p class="section-sub" style="margin-bottom:0;">5 merk terbanyak</p>
                </div>
                <div class="card-box-body">
                    @php $maxMerk = $topMerk->max('total') ?: 1; @endphp
                    <div class="merk-bar-wrap">
                        @foreach($topMerk as $m)
                        <div class="merk-row">
                            <span class="merk-label">{{ $m->merk_mobil }}</span>
                            <div class="merk-bar-bg">
                                <div class="merk-bar-fill" style="width:{{ round(($m->total / $maxMerk) * 100) }}%;"></div>
                            </div>
                            <span class="merk-count">{{ $m->total }}</span>
                        </div>
                        @endforeach
                        @if($topMerk->isEmpty())
                            <p style="font-size:12px;color:#181818;">Belum ada data.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pelanggan Terbaru --}}
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Pelanggan Terbaru</p>
                    <p class="section-sub" style="margin-bottom:0;">8 pendaftaran terakhir</p>
                </div>
                @if($pelangganTerbaru->isEmpty())
                    <div class="card-box-body">
                        <p style="font-size:12px;color:#181818;">Belum ada data pelanggan.</p>
                    </div>
                @else
                <table class="abs-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Plat</th>
                            <th>Mobil</th>
                            <th>Tipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelangganTerbaru as $p)
                        <tr>
                            <td style="font-weight:500;">{{ $p->nama }}</td>
                            <td style="font-family:monospace;font-size:12px;color:#181818;">{{ $p->plat_nomor }}</td>
                            <td style="color:#181818;">{{ $p->merk_mobil }} {{ $p->model_mobil }}</td>
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

        {{-- Alert stok kritis --}}
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

        {{-- Summary Cards --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px;">
            <div class="stat-card"><div class="accent-bar" style="background:#111827;"></div><p class="stat-label">Total Barang</p><p class="stat-number" style="color:#111827;">{{ $totalBarang }}</p><p class="stat-sub">jenis barang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#059669;"></div><p class="stat-label">Stok Aman</p><p class="stat-number" style="color:#059669;">{{ $stokAman }}</p><p class="stat-sub">jenis barang</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#f59e0b;"></div><p class="stat-label">Stok Menipis</p><p class="stat-number" style="color:#d97706;">{{ $stokMenipis }}</p><p class="stat-sub">≤ 5 unit</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#ef4444;"></div><p class="stat-label">Stok Habis</p><p class="stat-number" style="color:#dc2626;">{{ $stokHabis }}</p><p class="stat-sub">jenis barang</p></div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
            <div class="stat-card"><div class="accent-bar" style="background:#6366f1;"></div><p class="stat-label">Total Unit Stok</p><p class="stat-number" style="color:#4f46e5;">{{ number_format($totalStok) }}</p><p class="stat-sub">unit tersedia</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#0891b2;"></div><p class="stat-label">Nilai Stok (Pribadi)</p><p class="stat-number" style="color:#0e7490;font-size:24px;">Rp {{ number_format($nilaiStokPribadi,0,',','.') }}</p><p class="stat-sub">estimasi nilai</p></div>
            <div class="stat-card"><div class="accent-bar" style="background:#0891b2;"></div><p class="stat-label">Nilai Stok (Perusahaan)</p><p class="stat-number" style="color:#0e7490;font-size:24px;">Rp {{ number_format($nilaiStokPerusahaan,0,',','.') }}</p><p class="stat-sub">estimasi nilai</p></div>
        </div>

        {{-- Bottom Row: Top Stok + Tabel Kritis --}}
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;">

            {{-- Top 5 Stok --}}
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Stok Terbanyak</p>
                    <p class="section-sub" style="margin-bottom:0;">5 barang dengan stok tertinggi</p>
                </div>
                <div class="card-box-body">
                    @php $maxStok = $topStok->max('stok') ?: 1; @endphp
                    <div class="merk-bar-wrap">
                        @forelse($topStok as $b)
                        <div class="merk-row">
                            <span class="merk-label">{{ $b->nama }}</span>
                            <div class="merk-bar-bg">
                                <div class="merk-bar-fill" style="width:{{ round(($b->stok/$maxStok)*100) }}%;background:#4f46e5;"></div>
                            </div>
                            <span class="merk-count">{{ $b->stok }}</span>
                        </div>
                        @empty
                            <p style="font-size:12px;color:#181818;">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Barang Stok Kritis --}}
            <div class="card-box">
                <div class="card-box-header">
                    <p class="section-title" style="font-size:15px;">Stok Kritis</p>
                    <p class="section-sub" style="margin-bottom:0;">Barang habis & menipis (≤5 unit)</p>
                </div>
                @if($barangKritis->isEmpty())
                    <div class="card-box-body">
                        <p style="font-size:12px;color:#059669;font-weight:500;">✓ Semua stok dalam kondisi aman.</p>
                    </div>
                @else
                <table class="abs-table">
                    <thead><tr><th>Nama Barang</th><th>Satuan</th><th>Harga Pribadi</th><th>Stok</th></tr></thead>
                    <tbody>
                        @foreach($barangKritis as $b)
                        <tr>
                            <td style="font-weight:500;">{{ $b->nama }}</td>
                            <td style="color:#181818;">{{ $b->satuan }}</td>
                            <td style="color:#181818;">Rp {{ number_format($b->harga_pribadi,0,',','.') }}</td>
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

</div>


<script>
    function switchTab(tab, el) {
        document.querySelectorAll('.absensi-grid').forEach(g => g.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        el.classList.add('active');
    }
</script>
@endsection
