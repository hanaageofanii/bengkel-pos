@extends('dashboard')

@section('title', 'Stok Barang')

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


    .sb-wrap { font-family: 'Inter', sans-serif; color: var(--mz-text); }

    /* ── alert success ── */
    .mz-alert-success {
        display: flex; align-items: center; gap: 10px;
        background: rgba(62,240,138,.08);
        border: 1px solid rgba(62,240,138,.25);
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 13px;
        color: var(--mz-green);
        animation: fadeSlide .35s ease both;
    }
    .mz-alert-success svg { width: 16px; height: 16px; fill: var(--mz-green); flex-shrink: 0; }

    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── page header ── */
    .sb-header {
        display: flex; align-items: flex-end; justify-content: space-between;
        margin-bottom: 20px;
    }
    .sb-title       { font-family: 'Rajdhani', sans-serif; font-size: 26px; font-weight: 700; letter-spacing: .4px; line-height: 1; }
    .sb-subtitle    { font-size: 12px; color: var(--mz-muted); margin-top: 4px; }

    .btn-add {
        display: inline-flex; align-items: center; gap: 7px;
        background: linear-gradient(135deg, var(--mz-accent2), var(--mz-accent));
        color: #fff;
        padding: 9px 18px;
        border-radius: 7px;
        font-family: 'Rajdhani', sans-serif;
        font-size: 13px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase;
        text-decoration: none;
        transition: opacity .15s, transform .1s;
        position: relative; overflow: hidden;
    }
    .btn-add::after {
        content:''; position:absolute; inset:0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.1), transparent);
        transform: translateX(-100%); transition: transform .4s;
    }
    .btn-add:hover::after { transform: translateX(100%); }
    .btn-add:hover  { opacity: .9; }
    .btn-add:active { transform: scale(.98); }
    .btn-add svg    { width: 15px; height: 15px; fill: #fff; }

    /* ── stats row ── */
    .sb-stats {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 12px; margin-bottom: 20px;
    }
    .stat-card {
        background: var(--mz-surface);
        border: 1px solid var(--mz-border);
        border-radius: 8px;
        padding: 14px 18px;
        display: flex; align-items: center; gap: 14px;
    }
    .stat-icon {
        width: 38px; height: 38px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-icon svg { width: 18px; height: 18px; fill: #fff; }
    .stat-icon.blue   { background: linear-gradient(135deg, var(--mz-accent2), var(--mz-accent)); }
    .stat-icon.green  { background: linear-gradient(135deg, #1fa356, #3ef08a); }
    .stat-icon.yellow { background: linear-gradient(135deg, #b8860b, #f5c542); }
    .stat-val  { font-family: 'Rajdhani', sans-serif; font-size: 22px; font-weight: 700; line-height: 1; color: var(--mz-text); }
    .stat-lbl  { font-size: 11px; color: var(--mz-muted); margin-top: 2px; }

    /* ── table card ── */
    .sb-card {
        background: var(--mz-surface);
        border: 1px solid var(--mz-border);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,.35);
    }
    .sb-card-bar { height: 3px; background: linear-gradient(90deg, #1e90ff, var(--mz-accent), #8ab6ff); }

    .sb-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }

    .sb-table thead tr {
        background: var(--mz-surface2);
        text-transform: uppercase;
        letter-spacing: .5px;
        font-size: 10px;
        color: var(--mz-muted);
    }

    .sb-table th, .sb-table td {
        padding: 11px 16px;
        border-bottom: 1px solid var(--mz-border);
    }

    .sb-table th { font-weight: 700; }
    .sb-table tbody tr:last-child td { border-bottom: none; }
    .sb-table tbody tr:hover td { background: var(--mz-surface2); }

    .th-nama  { text-align: left; }
    .th-num   { text-align: right; }
    .th-center{ text-align: center; }
    .th-aksi  { text-align: right; }

    .td-nama  { font-weight: 600; color: var(--mz-text); }

    .td-price {
        text-align: right;
        font-variant-numeric: tabular-nums;
        color: var(--mz-text);
    }

    .td-stok  { text-align: center; }

    .stok-badge {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 36px; padding: 3px 8px;
        border-radius: 5px;
        font-family: 'Rajdhani', sans-serif;
        font-size: 13px; font-weight: 700;
    }
    .stok-ok    { background: rgba(62,240,138,.12); color: #3ef08a; }
    .stok-low   { background: rgba(245,197,66,.12);  color: #f5c542; }
    .stok-empty { background: rgba(242,108,108,.12); color: #f26c6c; }

    .td-satuan {
        text-align: center;
        font-size: 11px;
        color: var(--mz-muted);
    }
    .satuan-pill {
        display: inline-block;
        background: var(--mz-surface2);
        border: 1px solid var(--mz-border);
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 10.5px;
        letter-spacing: .3px;
    }

    /* ── actions ── */
    .td-aksi { text-align: right; }
    .action-group { display: flex; justify-content: flex-end; gap: 6px; }

    .btn-edit, .btn-del {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border-radius: 5px;
        font-size: 11.5px; font-weight: 600;
        border: 1px solid; cursor: pointer;
        text-decoration: none; transition: background .15s, color .15s;
        font-family: 'Inter', sans-serif;
    }
    .btn-edit {
        color: var(--mz-accent); border-color: rgba(79,142,247,.3);
        background: rgba(79,142,247,.06);
    }
    .btn-edit:hover { background: rgba(79,142,247,.15); }
    .btn-edit svg   { width: 12px; height: 12px; fill: var(--mz-accent); }

    .btn-del {
        color: var(--mz-red); border-color: rgba(242,108,108,.3);
        background: rgba(242,108,108,.06);
    }
    .btn-del:hover { background: rgba(242,108,108,.15); }
    .btn-del svg   { width: 12px; height: 12px; fill: var(--mz-red); }

    /* ── empty state ── */
    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-state svg { width: 40px; height: 40px; fill: var(--mz-border); margin: 0 auto 12px; display: block; }
    .empty-state p { font-size: 13px; color: var(--mz-muted); }

    /* ── delete modal ── */
    .mz-backdrop {
        position: fixed; inset: 0; z-index: 50;
        display: flex; align-items: center; justify-content: center;
        background: rgba(0,0,0,.65); backdrop-filter: blur(3px);
    }
    .mz-modal {
        background: var(--mz-surface);
        border: 1px solid var(--mz-border);
        border-radius: 10px;
        box-shadow: 0 0 0 1px rgba(242,108,108,.1), 0 24px 48px rgba(0,0,0,.5);
        width: 100%; max-width: 360px;
        overflow: hidden;
        text-align: center;
    }
    .mz-modal-bar-red { height: 3px; background: linear-gradient(90deg, #c0392b, var(--mz-red), #ff9f9f); }
    .mz-modal-body    { padding: 28px 24px 24px; }

    .del-icon {
        width: 52px; height: 52px; border-radius: 50%;
        background: rgba(242,108,108,.12);
        border: 1px solid rgba(242,108,108,.25);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
    }
    .del-icon svg { width: 24px; height: 24px; fill: var(--mz-red); }

    .del-title { font-family: 'Rajdhani', sans-serif; font-size: 18px; font-weight: 700; color: var(--mz-text); margin-bottom: 8px; }
    .del-desc  { font-size: 12.5px; color: var(--mz-muted); line-height: 1.6; }
    .del-name  { display: block; margin-top: 5px; color: var(--mz-text); font-weight: 600; font-size: 13px; }

    .del-actions {
        display: flex; gap: 10px; margin-top: 22px;
        padding-top: 18px; border-top: 1px solid var(--mz-border);
    }
    .del-btn-cancel {
        flex: 1; padding: 9px;
        border-radius: 6px; border: 1px solid var(--mz-border);
        background: transparent; color: var(--mz-muted);
        font-size: 12.5px; font-weight: 500; cursor: pointer;
        font-family: 'Inter', sans-serif; transition: border-color .15s, color .15s;
    }
    .del-btn-cancel:hover { border-color: var(--mz-muted); color: var(--mz-text); }
    .del-btn-confirm {
        flex: 1; padding: 9px;
        border-radius: 6px; border: none;
        background: linear-gradient(135deg, #c0392b, var(--mz-red));
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-size: 13px; font-weight: 700; letter-spacing: .5px;
        cursor: pointer; transition: opacity .15s;
    }
    .del-btn-confirm:hover { opacity: .88; }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="sb-wrap" x-data="deleteModal()">

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="mz-alert-success">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="sb-header">
        <div>
            <div class="sb-title">Stok Barang</div>
            <div class="sb-subtitle">Daftar barang dan harga</div>
        </div>
        <a href="{{ route('barang.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Barang
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="sb-stats">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $barangs->count() }}</div>
                <div class="stat-lbl">Total Jenis Barang</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $barangs->sum('stok') }}</div>
                <div class="stat-lbl">Total Unit Stok</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $barangs->where('stok', '<=', 5)->count() }}</div>
                <div class="stat-lbl">Stok Hampir Habis</div>
            </div>
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="sb-card">
        <div class="sb-card-bar"></div>

        <table class="sb-table">
            <thead>
                <tr>
                    <th class="th-nama">Nama Barang</th>
                    <th class="th-num">Harga Pribadi</th>
                    <th class="th-num">Harga Perusahaan</th>
                    <th class="th-center">Stok</th>
                    <th class="th-center">Satuan</th>
                    <th class="th-aksi">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($barangs as $b)
                <tr>
                    <td class="td-nama">{{ $b->nama }}</td>

                    <td class="td-price">
                        <span style="font-size:10px;color:var(--mz-muted);margin-right:2px">Rp</span>{{ number_format($b->harga_pribadi) }}
                    </td>

                    <td class="td-price">
                        <span style="font-size:10px;color:var(--mz-muted);margin-right:2px">Rp</span>{{ number_format($b->harga_perusahaan) }}
                    </td>

                    <td class="td-stok">
                        <span class="stok-badge
                            @if($b->stok <= 0)   stok-empty
                            @elseif($b->stok <= 5) stok-low
                            @else                  stok-ok
                            @endif
                        ">{{ $b->stok }}</span>
                    </td>

                    <td class="td-satuan">
                        <span class="satuan-pill">{{ $b->satuan }}</span>
                    </td>

                    <td class="td-aksi">
                        <div class="action-group">
                            <a href="{{ route('barang.edit', $b->id) }}" class="btn-edit">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            <button @click="open({{ $b->id }}, '{{ $b->nama }}')" class="btn-del">
                                <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                            <p>Belum ada data barang</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Delete Modal ── --}}
    <div x-show="show" x-transition class="mz-backdrop" style="display:none">
        <div @click.away="close" class="mz-modal">
            <div class="mz-modal-bar-red"></div>
            <div class="mz-modal-body">

                <div class="del-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                </div>

                <div class="del-title">Konfirmasi Hapus</div>
                <p class="del-desc">
                    Yakin ingin menghapus barang ini? Tindakan tidak bisa dibatalkan.
                    <span class="del-name" x-text="nama"></span>
                </p>

                <div class="del-actions">
                    <button @click="close" class="del-btn-cancel">Batal</button>
                    <form :action="url" method="POST" style="flex:1;display:flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="del-btn-confirm" style="width:100%">Hapus</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

function deleteModal() {
    return {
        show: false,
        url: '',
        nama: '',
        open(id, nama) {
            this.url = `/barang/${id}`
            this.nama = nama
            this.show = true
        },
        close() {
            this.show = false
        }
    }
}
</script>
@endsection
