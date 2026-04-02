@extends('dashboard')

@section('title','Invoice')

@section('content')
<style>
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
}


    .inv-wrap { font-family:'Inter',sans-serif; color:var(--mz-text); }

    /* header */
    .inv-header { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:20px; }
    .inv-title   { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .inv-subtitle{ font-size:12px; color:var(--mz-muted); margin-top:4px; }

    .btn-add {
        display:inline-flex; align-items:center; gap:7px;
        background:linear-gradient(135deg,var(--mz-accent2),var(--mz-accent));
        color:#fff; padding:9px 18px; border-radius:7px;
        font-family:'Rajdhani',sans-serif; font-size:13px; font-weight:700;
        letter-spacing:.6px; text-transform:uppercase; text-decoration:none;
        transition:opacity .15s, transform .1s; position:relative; overflow:hidden;
    }
    .btn-add::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent); transform:translateX(-100%); transition:transform .4s; }
    .btn-add:hover::after { transform:translateX(100%); }
    .btn-add:hover { opacity:.9; } .btn-add:active { transform:scale(.98); }
    .btn-add svg { width:15px; height:15px; fill:#fff; }

    /* search */
    .inv-search { margin-bottom:20px; }
    .search-wrap { position:relative; width:420px; }
    .search-input {
        width:100%; padding:9px 100px 9px 14px;
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:7px; color:var(--mz-text); font-size:12.5px;
        outline:none; font-family:'Inter',sans-serif;
        transition:border-color .15s, box-shadow .15s;
    }
    .search-input::placeholder { color:#3a4059; }
    .search-input:focus { border-color:var(--mz-accent); box-shadow:0 0 0 3px rgba(79,142,247,.15); }
    .search-btn {
        position:absolute; right:4px; top:4px; bottom:4px;
        padding:0 16px; background:var(--mz-accent);
        color:#fff; border:none; border-radius:5px;
        font-size:11.5px; font-weight:600; cursor:pointer;
        font-family:'Inter',sans-serif; transition:opacity .15s;
    }
    .search-btn:hover { opacity:.88; }

    /* card */
    .inv-card { background:var(--mz-surface); border:1px solid var(--mz-border); border-radius:10px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.35); }
    .inv-card-bar { height:3px; background:linear-gradient(90deg,#1e90ff,var(--mz-accent),#8ab6ff); }

    /* table */
    .inv-table { width:100%; border-collapse:collapse; font-size:12.5px; }
    .inv-table thead tr { background:var(--mz-surface2); text-transform:uppercase; letter-spacing:.5px; font-size:10px; color:var(--mz-muted); }
    .inv-table th, .inv-table td { padding:12px 16px; border-bottom:1px solid var(--mz-border); }
    .inv-table th { font-weight:700; text-align:center; }
    .inv-table th:first-child { text-align:left; }
    .inv-table tbody tr:last-child td { border-bottom:none; }
    .inv-table tbody tr:hover td { background:var(--mz-surface2); transition:background .12s; }

    .td-inv  { font-family:'Courier New',monospace; font-size:12px; font-weight:600; color:var(--mz-accent); }
    .td-nama { font-weight:600; color:var(--mz-text); }
    .td-plat { font-size:10px; color:var(--mz-muted); text-transform:uppercase; letter-spacing:.8px; margin-top:2px; }
    .td-mobil{ font-size:10.5px; color:var(--mz-muted); margin-top:1px; }

    .td-center { text-align:center; }
    .td-right  { text-align:right; font-variant-numeric:tabular-nums; }
    .td-num-bold { font-weight:700; color:var(--mz-text); }

    .mz-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:10.5px; font-weight:600; border:1px solid; }
    .badge-lunas  { background:rgba(62,240,138,.1); color:var(--mz-green); border-color:rgba(62,240,138,.2); }
    .badge-belum  { background:rgba(242,108,108,.1); color:var(--mz-red);  border-color:rgba(242,108,108,.2); }
    .badge-dot    { width:5px; height:5px; border-radius:50%; flex-shrink:0; }

    .lunas-text { color:var(--mz-green); font-weight:700; }
    .sisa-text  { color:var(--mz-red);   font-weight:700; }

    /* action buttons */
    .act-group { display:flex; justify-content:center; gap:5px; }
    .act-btn {
        display:inline-flex; align-items:center; gap:4px;
        padding:4px 10px; border-radius:5px; font-size:11px; font-weight:600;
        border:1px solid; text-decoration:none; transition:background .15s;
    }
    .act-lihat  { color:var(--mz-muted);   border-color:var(--mz-border);  background:var(--mz-surface2); }
    .act-lihat:hover  { background:var(--mz-border); }
    .act-edit   { color:var(--mz-yellow);  border-color:rgba(245,197,66,.3); background:rgba(245,197,66,.06); }
    .act-edit:hover   { background:rgba(245,197,66,.15); }
    .act-print  { color:var(--mz-accent);  border-color:rgba(79,142,247,.3); background:rgba(79,142,247,.06); }
    .act-print:hover  { background:rgba(79,142,247,.15); }

    /* empty */
    .empty-state { padding:60px 20px; text-align:center; }
    .empty-state svg { width:40px; height:40px; fill:var(--mz-border); margin:0 auto 12px; display:block; }
    .empty-state p { font-size:13px; color:var(--mz-muted); }

    /* pagination */
    .inv-pagination { margin-top:16px; }
    .inv-pagination .pagination { display:flex; gap:4px; }
    .inv-pagination .page-item .page-link {
        display:flex; align-items:center; padding:6px 12px;
        background:var(--mz-surface); border:1px solid var(--mz-border);
        color:var(--mz-muted); border-radius:5px; font-size:12px;
        text-decoration:none; transition:all .15s;
    }
    .inv-pagination .page-item.active .page-link { background:var(--mz-accent); border-color:var(--mz-accent); color:#fff; }
    .inv-pagination .page-item .page-link:hover { border-color:var(--mz-accent); color:var(--mz-text); }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="inv-wrap">

    {{-- Header --}}
    <div class="inv-header">
        <div>
            <div class="inv-title">Invoice</div>
            <div class="inv-subtitle">Daftar transaksi servis & perbaikan kendaraan</div>
        </div>
        <a href="{{ route('invoice.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Buat Invoice
        </a>
    </div>

    {{-- Search --}}
    <div class="inv-search">
        <form method="GET">
            <div class="search-wrap">
                <input name="q" value="{{ $q }}" placeholder="Cari invoice, pelanggan, plat, atau mobil…" class="search-input">
                <button type="submit" class="search-btn">Cari</button>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="inv-card">
        <div class="inv-card-bar"></div>

        <table class="inv-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>DP</th>
                    <th>Sisa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $i)
                <tr>
                    <td class="td-inv">{{ $i->invoice_no }}</td>
                    <td>
                        <div class="td-nama">{{ $i->pelanggan->nama }}</div>
                        <div class="td-plat">{{ $i->pelanggan->plat_nomor }}</div>
                        <div class="td-mobil">{{ $i->pelanggan->merk_mobil }} {{ $i->pelanggan->model_mobil }}</div>
                    </td>
                    <td class="td-center" style="color:var(--mz-muted)">{{ \Carbon\Carbon::parse($i->tanggal)->format('d M Y') }}</td>
                    <td class="td-right td-num-bold">Rp {{ number_format($i->grand_total) }}</td>
                    <td class="td-center">
                        @if($i->status_bayar === 'sudah')
                            <span class="mz-badge badge-lunas"><span class="badge-dot" style="background:var(--mz-green)"></span>Sudah Bayar</span>
                        @else
                            <span class="mz-badge badge-belum"><span class="badge-dot" style="background:var(--mz-red)"></span>Belum Bayar</span>
                        @endif
                    </td>
                    <td class="td-right">
                        @if($i->payment_awal == $i->grand_total && $i->grand_total > 0)
                            <span class="lunas-text">LUNAS</span>
                        @else
                            Rp {{ number_format($i->payment_awal) }}
                        @endif
                    </td>
                    <td class="td-right">
                        @if($i->sisa == 0)
                            <span class="lunas-text">LUNAS</span>
                        @else
                            <span class="sisa-text">Rp {{ number_format($i->sisa) }}</span>
                        @endif
                    </td>
                    <td class="td-center">
                        <div class="act-group">
                            <a href="{{ route('invoice.show',$i) }}" class="act-btn act-lihat">Lihat</a>
                            <a href="{{ route('invoice.edit',$i) }}" class="act-btn act-edit">Edit</a>
                            <a href="{{ route('invoice.print',$i) }}" class="act-btn act-print">Print</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                            <p>Belum ada invoice</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="inv-pagination">
        {{ $invoices->links() }}
    </div>

</div>
<script>
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}
</script>
@endsection
