@extends('dashboard')

@section('title', $invoice->invoice_no)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

<style>
/* ── Theme vars (sama persis seperti edit) ── */
:root {
    --mz-bg:       #0f1117;
    --mz-surface:  #181c27;
    --mz-surface2: #1e2333;
    --mz-border:   #262c3d;
    --mz-text:     #e4e8f0;
    --mz-muted:    #6b7694;
    --mz-accent:   #4f8ef7;
    --mz-accent2:  #1e90ff;
    --mz-green:    #3ef08a;
    --mz-red:      #f26c6c;
    --mz-yellow:   #f5c542;
    --mz-orange:   #f5923e;
}
[data-theme="light"] {
    --mz-bg:       #ffffff;
    --mz-surface:  #f9fafb;
    --mz-surface2: #f1f5f9;
    --mz-border:   #e5e7eb;
    --mz-text:     #111827;
    --mz-muted:    #6b7280;
}

body { color: var(--mz-text); }

/* ── Wrapper ── */
.inv-wrap { font-family: 'Inter', sans-serif; color: var(--mz-text); }

.inv-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 11px; color: var(--mz-muted); margin-bottom: 10px;
}
.inv-breadcrumb a { color: var(--mz-muted); text-decoration: none; transition: color .15s; }
.inv-breadcrumb a:hover { color: var(--mz-orange); }
.inv-breadcrumb span { color: var(--mz-border); }

.inv-title {
    font-family: 'Rajdhani', sans-serif;
    font-size: 26px; font-weight: 700; letter-spacing: .4px; line-height: 1;
}
.inv-subtitle { font-size: 12px; color: var(--mz-muted); margin-top: 4px; margin-bottom: 6px; }

.view-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(79,142,247,.1); border: 1px solid rgba(79,142,247,.25);
    border-radius: 20px; padding: 4px 12px; margin-bottom: 20px;
    font-size: 11px; color: var(--mz-accent); font-weight: 500;
}

/* ── Card ── */
.inv-card {
    background: var(--mz-surface); border: 1px solid var(--mz-border);
    border-radius: 10px; overflow: hidden;
    box-shadow: 0 0 0 1px rgba(79,142,247,.06), 0 20px 48px rgba(0,0,0,.4);
}
.inv-card-bar { height: 3px; background: linear-gradient(90deg, #1a56e0, var(--mz-accent), #6ee7b7); }

/* ── Sections ── */
.inv-section { padding: 22px 28px; border-bottom: 1px solid var(--mz-border); }
.inv-section:last-child { border-bottom: none; }

.inv-section-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid var(--mz-border);
}
.inv-section-title {
    display: flex; align-items: center; gap: 8px;
    font-family: 'Rajdhani', sans-serif; font-size: 13px; font-weight: 700;
    letter-spacing: .8px; text-transform: uppercase; color: var(--mz-accent);
}
.inv-section-title svg { width: 14px; height: 14px; fill: var(--mz-accent); }

/* ── Header block ── */
.inv-header-block {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 24px;
    flex-wrap: wrap;
}
.shop-logo-wrap img {
    height: 52px; object-fit: contain;
    filter: brightness(0) invert(.85);
    opacity: .7;
}
.shop-name {
    font-family: 'Rajdhani', sans-serif;
    font-size: 22px; font-weight: 700; letter-spacing: .5px;
    color: var(--mz-text); margin-top: 4px;
}
.shop-name span { color: var(--mz-orange); }
.shop-addr { font-size: 11px; color: var(--mz-muted); line-height: 1.7; margin-top: 3px; }

.inv-no-block { text-align: right; }
.inv-no-badge {
    display: inline-block;
    background: rgba(79,142,247,.12); border: 1px solid rgba(79,142,247,.3);
    border-radius: 20px; padding: 3px 14px;
    font-size: 9.5px; font-weight: 700; letter-spacing: 3px;
    text-transform: uppercase; color: var(--mz-accent);
}
.inv-no-num {
    font-family: 'JetBrains Mono', monospace;
    font-size: 19px; font-weight: 700; color: var(--mz-text);
    margin-top: 6px; letter-spacing: 1px;
}
.inv-no-date { font-size: 11px; color: var(--mz-muted); margin-top: 3px; }

/* ── Meta grid ── */
.inv-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

.meta-group-label {
    font-size: 9.5px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--mz-muted); margin-bottom: 12px;
}
.meta-row { display: flex; align-items: baseline; gap: 10px; margin-bottom: 7px; }
.meta-key {
    font-size: 10.5px; color: var(--mz-muted); font-weight: 500;
    min-width: 90px; flex-shrink: 0;
}
.meta-val { font-size: 13px; font-weight: 600; color: var(--mz-text); }
.meta-val.plate {
    font-family: 'JetBrains Mono', monospace;
    background: var(--mz-bg); border: 1px solid var(--mz-border);
    color: var(--mz-yellow); padding: 1px 9px; border-radius: 4px;
    font-size: 11.5px; letter-spacing: 1.5px;
}

/* ── Tables ── */
.mz-table { width: 100%; border-collapse: collapse; font-size: 12px; }
.mz-table thead tr { background: var(--mz-surface2); }
.mz-table th {
    padding: 9px 14px; font-size: 10px; font-weight: 700;
    letter-spacing: .6px; text-transform: uppercase; color: var(--mz-muted);
    text-align: left; border-bottom: 1px solid var(--mz-border);
}
.mz-table td {
    padding: 10px 14px; border-bottom: 1px solid var(--mz-border); color: var(--mz-text);
}
.mz-table tbody tr:last-child td { border-bottom: none; }
.mz-table tbody tr:hover td { background: var(--mz-surface2); }
.mz-table tfoot tr { background: var(--mz-surface2); }
.mz-table tfoot td {
    padding: 9px 14px; border-top: 1px solid var(--mz-border);
    font-weight: 700; font-size: 12px;
}
.mono { font-family: 'JetBrains Mono', monospace; font-size: 11.5px; }
.rnum { width: 36px; text-align: center; color: var(--mz-muted); }

/* ── Summary bar ── */
.inv-summary {
    background: var(--mz-surface2); border-top: 2px solid var(--mz-border);
    padding: 16px 28px;
    display: flex; align-items: center; justify-content: flex-end; gap: 24px;
}
.sum-item { text-align: right; }
.sum-label {
    font-size: 10px; font-weight: 700; letter-spacing: .6px;
    text-transform: uppercase; color: var(--mz-muted);
}
.sum-val {
    font-family: 'Rajdhani', sans-serif; font-size: 20px; font-weight: 700;
    color: var(--mz-text); line-height: 1; margin-top: 2px;
}
.sum-val.grand { font-size: 24px; color: var(--mz-orange); }

/* ── Totals block ── */
.totals-section { padding: 20px 28px 24px; border-top: 1px solid var(--mz-border); }
.totals-inner { max-width: 300px; margin-left: auto; }

.t-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 6px 0; border-bottom: 1px dashed var(--mz-border); font-size: 12.5px;
}
.t-row:last-child { border-bottom: none; }
.t-row .tl { color: var(--mz-muted); font-weight: 500; }
.t-row .tv { font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 12px; }

.t-row.grand-row {
    background: var(--mz-bg); border: 1px solid var(--mz-border);
    border-radius: 8px; padding: 11px 14px; margin-top: 6px; border-bottom: none;
}
.t-row.grand-row .tl { color: var(--mz-muted); font-family: 'Rajdhani', sans-serif; font-size: 14px; font-weight: 700; letter-spacing: .4px; }
.t-row.grand-row .tv { color: var(--mz-orange); font-size: 16px; }

.t-row.inst-row .tl { color: var(--mz-text); font-size: 11.5px; }

.t-row.paid-row {
    background: rgba(62,240,138,.06); border: 1px solid rgba(62,240,138,.2);
    border-radius: 7px; padding: 8px 12px; margin-top: 5px; border-bottom: none;
}
.t-row.paid-row .tl { color: var(--mz-green); font-weight: 600; }
.t-row.paid-row .tv { color: var(--mz-green); }

.t-row.sisa-row {
    background: rgba(242,108,108,.06); border: 1px solid rgba(242,108,108,.2);
    border-radius: 7px; padding: 8px 12px; margin-top: 5px; border-bottom: none;
}
.t-row.sisa-row .tl { color: var(--mz-red); font-weight: 600; }
.t-row.sisa-row .tv { color: var(--mz-red); }

.badge-lunas {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(62,240,138,.1); border: 1px solid rgba(62,240,138,.3);
    color: var(--mz-green); border-radius: 20px;
    padding: 4px 14px 4px 10px; font-weight: 700; font-size: 11.5px;
    letter-spacing: .5px; font-family: 'Rajdhani', sans-serif;
}

/* ── Footer ── */
.inv-foot {
    display: grid; grid-template-columns: 1fr 1fr;
    background: var(--mz-surface2); border-top: 1px solid var(--mz-border);
}
.foot-col { padding: 22px 28px; }
.foot-col:first-child { border-right: 1px solid var(--mz-border); }
.foot-label {
    font-size: 9.5px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--mz-muted); margin-bottom: 10px;
}
.sign-area { position: relative; height: 80px; }
.sign-area img {
    position: absolute; top: -22px; left: -10px;
    height: 115px; opacity: .75;
    filter: brightness(.9) invert(0);
}
.bank-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.bank-chip {
    font-size: 9.5px; font-weight: 700; padding: 3px 10px;
    border-radius: 4px; min-width: 58px; text-align: center; letter-spacing: .5px;
}
.chip-mandiri { background: #f7941d; color: #000; }
.chip-bca     { background: #003d82; color: #fff; }
.bank-num {
    font-family: 'JetBrains Mono', monospace;
    font-size: 13px; font-weight: 600; color: var(--mz-text); letter-spacing: 1.5px;
}
.bank-an { font-size: 11px; color: var(--mz-muted); margin-top: 4px; }

/* ── Action bar ── */
.inv-actions {
    display: flex; justify-content: flex-end; align-items: center;
    gap: 10px; padding: 14px 28px;
    border-top: 1px solid var(--mz-border); background: var(--mz-surface2);
}
.btn-back {
    padding: 8px 18px; border-radius: 6px; font-size: 12.5px; font-weight: 500;
    color: var(--mz-muted); background: transparent; border: 1px solid var(--mz-border);
    cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    transition: border-color .15s, color .15s;
}
.btn-back:hover { border-color: var(--mz-muted); color: var(--mz-text); }

.btn-edit {
    padding: 8px 20px; border-radius: 6px;
    font-family: 'Rajdhani', sans-serif; font-size: 14px; font-weight: 700;
    letter-spacing: .8px; text-transform: uppercase; color: #fff;
    background: linear-gradient(135deg, #e05c00, var(--mz-orange));
    border: none; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 7px;
    transition: opacity .15s;
}
.btn-edit:hover { opacity: .88; color: #fff; }

.btn-print {
    padding: 8px 20px; border-radius: 6px;
    font-family: 'Rajdhani', sans-serif; font-size: 14px; font-weight: 700;
    letter-spacing: .8px; text-transform: uppercase; color: #fff;
    background: linear-gradient(135deg, #065f46, #10b981);
    border: none; cursor: pointer;
    display: inline-flex; align-items: center; gap: 7px;
    transition: opacity .15s;
}
.btn-print:hover { opacity: .88; }

/* ── Print ── */
@media print {
    .inv-actions { display: none !important; }
    body { background: #fff !important; color: #000 !important; }
    .inv-card { box-shadow: none !important; }
}
</style>

<div class="inv-wrap">

    {{-- Breadcrumb --}}
    <div class="inv-breadcrumb">
        <a href="{{ route('invoice.index') }}">Invoice</a>
        <span>/</span>
        <span style="color:var(--mz-text)">Detail</span>
    </div>
    <div class="inv-title">Detail Invoice</div>
    <div class="inv-subtitle">Lihat rincian transaksi servis</div>
    <div class="view-badge">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
        {{ $invoice->invoice_no }}
    </div>

    <div class="inv-card">
        <div class="inv-card-bar"></div>

        {{-- ── Header Bengkel ── --}}
        <div class="inv-section">
            <div class="inv-header-block">
                <div>
                    <div class="shop-logo-wrap">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
                    </div>
                    <div class="shop-name">5a <span>AUTO</span> SERVICE</div>
                    <div class="shop-addr">
                        Ruko Permata Harapan Baru Blok H No.17<br>
                        Jl. Raya Pejuang Harapan Indah Medan Satria Bekasi<br>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" style="color:var(--mz-orange);margin-right:3px"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.61 21 3 13.39 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.26.2 2.47.57 3.58a1 1 0 0 1-.24 1.01l-2.21 2.2z"/></svg>
                        0878-7880-6657
                    </div>
                </div>
                <div class="inv-no-block">
                    <div class="inv-no-badge">Invoice</div>
                    <div class="inv-no-num">{{ $invoice->invoice_no }}</div>
                    <div class="inv-no-date">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" style="color:var(--mz-muted);margin-right:3px"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
                        {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Data Pelanggan & Kendaraan ── --}}
        <div class="inv-section">
            <div class="inv-section-head">
                <div class="inv-section-title">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    Data Pelanggan & Kendaraan
                </div>
            </div>
            <div class="inv-meta-grid">
                <div>
                    <div class="meta-group-label">Pelanggan</div>
                    <div class="meta-row">
                        <span class="meta-key">Nama</span>
                        <span class="meta-val">{{ $invoice->pelanggan->nama }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Merk / Type</span>
                        <span class="meta-val">{{ $invoice->pelanggan->merk_mobil }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">No. Polisi</span>
                        <span class="meta-val plate">{{ strtoupper($invoice->pelanggan->plat_nomor) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">KM</span>
                        <span class="meta-val">{{ $invoice->km }} km</span>
                    </div>
                </div>
                <div>
                    <div class="meta-group-label">Kendaraan</div>
                    <div class="meta-row">
                        <span class="meta-key">Tanggal</span>
                        <span class="meta-val">{{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">No. Chasis</span>
                        <span class="meta-val">{{ $invoice->no_chasis }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">No. Mesin</span>
                        <span class="meta-val">{{ $invoice->no_mesin }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">No. Telp</span>
                        <span class="meta-val">{{ $invoice->no_telp }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Jasa ── --}}
        <div class="inv-section">
            <div class="inv-section-head">
                <div class="inv-section-title">
                    <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
                    Jasa Pekerjaan
                </div>
            </div>
            <table class="mz-table">
                <thead>
                    <tr>
                        <th class="rnum">#</th>
                        <th>Keluhan</th>
                        <th class="rnum">#</th>
                        <th>Pekerjaan</th>
                        <th class="text-end">Harga Jasa</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($invoice->jasa as $j)
                    <tr>
                        <td class="rnum">{{ $no }}</td>
                        <td style="color:var(--mz-muted)">{{ $invoice->keluhan[$no-1] ?? '' }}</td>
                        <td class="rnum">{{ $no }}</td>
                        <td>{{ $j['nama'] }}</td>
                        <td class="text-end mono" style="color:var(--mz-yellow)">Rp {{ number_format($j['harga']) }}</td>
                    </tr>
                    @php $no++; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end" style="color:var(--mz-muted);font-size:11px;letter-spacing:.5px;text-transform:uppercase">Total Jasa</td>
                        <td class="text-end mono" style="color:var(--mz-orange)">Rp {{ number_format($invoice->total_jasa) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- ── Spare Part ── --}}
        <div class="inv-section">
            <div class="inv-section-head">
                <div class="inv-section-title">
                    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                    Spare Part
                </div>
            </div>
            <table class="mz-table">
                <thead>
                    <tr>
                        <th class="rnum">#</th>
                        <th>Nama Part</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->barang as $i => $b)
                    <tr>
                        <td class="rnum">{{ $i+1 }}</td>
                        <td>{{ $b['nama'] }}</td>
                        <td class="text-center mono" style="color:var(--mz-muted)">{{ $b['qty'] }}</td>
                        <td class="text-end mono" style="color:var(--mz-muted)">Rp {{ number_format($b['harga']) }}</td>
                        <td class="text-end mono" style="color:var(--mz-yellow)">Rp {{ number_format($b['total']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end" style="color:var(--mz-muted);font-size:11px;letter-spacing:.5px;text-transform:uppercase">Total Part</td>
                        <td class="text-end mono" style="color:var(--mz-orange)">Rp {{ number_format($invoice->total_part) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- ── Summary bar ── --}}
<div class="inv-summary">
    <div class="sum-item">
        <div class="sum-label">Total Jasa</div>
        <div class="sum-val">Rp {{ number_format($invoice->total_jasa) }}</div>
    </div>

    <div style="color:var(--mz-border);font-size:20px">+</div>

    <div class="sum-item">
        <div class="sum-label">Total Part</div>
        <div class="sum-val">Rp {{ number_format($invoice->total_part) }}</div>
    </div>

    <div style="color:var(--mz-border);font-size:20px">=</div>

    <div class="sum-item">
        <div class="sum-label">Grand Total</div>
        <div class="sum-val grand">Rp {{ number_format($invoice->grand_total) }}</div>
    </div>

    <div style="color:var(--mz-border);font-size:20px">|</div>

    <div class="sum-item">
        <div class="sum-label">Total Terbayar</div>
        <div class="sum-val" style="color:var(--mz-green)">
            Rp {{ number_format($invoice->total_terbayar) }}
        </div>
    </div>

    @if($invoice->sisa_tagihan > 0)
    <div style="color:var(--mz-border);font-size:20px">|</div>
    <div class="sum-item">
        <div class="sum-label">Sisa Tagihan</div>
        <div class="sum-val" style="color:var(--mz-red)">
            Rp {{ number_format($invoice->sisa_tagihan) }}
        </div>
    </div>
    @else
    <div style="color:var(--mz-border);font-size:20px">|</div>
    <div class="sum-item">
        <div class="sum-label">Status</div>
        <div class="sum-val" style="color:var(--mz-green);font-size:14px;letter-spacing:1px">
            ✓ LUNAS
        </div>
    </div>
    @endif
</div>

        {{-- ── Pembayaran ── --}}
        <div class="inv-section">
            <div class="inv-section-head">
                <div class="inv-section-title">
                    <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                    Pembayaran
                </div>
            </div>
            <div class="totals-inner">
                <div class="t-row">
                    <span class="tl">Grand Total</span>
                    <span class="tv" style="color:var(--mz-text)">Rp {{ number_format($invoice->grand_total) }}</span>
                </div>

                @if($invoice->payment_awal > 0)
                <div class="t-row inst-row" style="margin-top:8px">
                    <span class="tl">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" style="color:var(--mz-orange);margin-right:4px"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                        Payment Awal
                    </span>
                    <span class="tv">Rp {{ number_format($invoice->payment_awal) }}</span>
                </div>
                @endif

                @foreach($invoice->payments as $i => $pay)
                <div class="t-row inst-row">
                    <span class="tl">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" style="color:var(--mz-muted);margin-right:4px"><path d="M19 15l-6 6-1.42-1.42L15.17 16H4V4h2v10h9.17l-3.59-3.58L13 9l6 6z"/></svg>
                        Cicilan {{ $i+1 }}
                        <span style="font-size:10px;color:var(--mz-muted);margin-left:4px">({{ \Carbon\Carbon::parse($pay->tanggal_bayar)->format('d/m/Y') }})</span>
                    </span>
                    <span class="tv">Rp {{ number_format($pay->jumlah) }}</span>
                </div>
                @endforeach

                <div class="t-row paid-row">
                    <span class="tl">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="margin-right:5px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        Total Terbayar
                    </span>
                    <span class="tv">Rp {{ number_format($invoice->total_terbayar) }}</span>
                </div>

                @if($invoice->sisa_tagihan > 0)
                <div class="t-row sisa-row">
                    <span class="tl">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="margin-right:5px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        Sisa Tagihan
                    </span>
                    <span class="tv">Rp {{ number_format($invoice->sisa_tagihan) }}</span>
                </div>
                @else
                <div class="text-end mt-3">
                    <span class="badge-lunas">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M23 12l-2.44-2.78.34-3.68-3.61-.82-1.89-3.18L12 3 8.6 1.54 6.71 4.72l-3.61.81.34 3.68L1 12l2.44 2.78-.34 3.69 3.61.82 1.89 3.18L12 21l3.4 1.46 1.89-3.18 3.61-.82-.34-3.68L23 12zm-12.91 4.72l-3.8-3.81 1.48-1.48 2.32 2.33 5.85-5.87 1.48 1.48-7.33 7.35z"/></svg>
                        LUNAS
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Footer TTD & Bank ── --}}
        <div class="inv-foot">
            <div class="foot-col">
                <div class="foot-label">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" style="margin-right:4px;color:var(--mz-orange)"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    Tanda Tangan
                </div>
                <div style="font-size:11px;color:var(--mz-muted)">
                    Bekasi, {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}
                </div>
                <div style="font-size:11px;color:var(--mz-muted);margin-bottom:4px">Hormat Kami,</div>
                <div class="sign-area">
                    <img src="{{ asset('assets/images/ttd.png') }}" alt="TTD">
                </div>
            </div>
            <div class="foot-col">
                <div class="foot-label">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" style="margin-right:4px;color:var(--mz-accent)"><path d="M4 10h3v7H4zm6.5 0h3v7h-3zM2 19h20v3H2zm15-9h3v7h-3zM12 1L2 6v2h20V6z"/></svg>
                    Pembayaran Via Rekening
                </div>
                <div class="bank-row">
                    <span class="bank-chip chip-mandiri">Mandiri</span>
                    <span class="bank-num">1560 0105 20965</span>
                </div>
                <div class="bank-row">
                    <span class="bank-chip chip-bca">BCA</span>
                    <span class="bank-num">5315 0644 97</span>
                </div>
                <div class="bank-an">a.n <strong style="color:var(--mz-text)">Hari Kuswanto</strong></div>
            </div>
        </div>

        {{-- ── Action Bar ── --}}
        <div class="inv-actions">
            <a href="{{ route('invoice.index') }}" class="btn-back">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Kembali
            </a>
            <a href="{{ route('invoice.edit', $invoice) }}" class="btn-edit">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                Edit
            </a>
            <a href="{{ route('invoice.print', $invoice->id) }}" class="btn-print">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
        <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
    </svg>
    Cetak
</a>
        </div>

    </div>{{-- /inv-card --}}
</div>{{-- /inv-wrap --}}

@endsection
