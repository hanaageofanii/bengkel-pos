@extends('dashboard')

@section('title', 'Data Karyawan')

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
        --mz-emerald:  #10b981;
        --mz-emerald2: #065f46;
}


    .ky-wrap{ font-family:'Inter',sans-serif;
                color:var(--mz-text);
            }

    /* ── alert ── */
    .mz-alert-success {
        display:flex; align-items:center; gap:10px;
        background:rgba(62,240,138,.08); border:1px solid rgba(62,240,138,.25);
        border-radius:8px; padding:12px 16px; margin-bottom:20px;
        font-size:13px; color:var(--mz-green);
        animation:fadeSlide .35s ease both;
    }
    .mz-alert-success svg { width:16px; height:16px; fill:var(--mz-green); flex-shrink:0; }
    @keyframes fadeSlide { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }

    /* ── header ── */
    .ky-header   { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:20px; }
    .ky-title    { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .ky-subtitle { font-size:12px; color:var(--mz-muted); margin-top:4px; }

    .btn-add {
        display:inline-flex; align-items:center; gap:7px;
        background:linear-gradient(135deg, var(--mz-emerald2), var(--mz-emerald));
        color:#fff; padding:9px 18px; border-radius:7px;
        font-family:'Rajdhani',sans-serif; font-size:13px; font-weight:700;
        letter-spacing:.6px; text-transform:uppercase; text-decoration:none;
        transition:opacity .15s, transform .1s; position:relative; overflow:hidden;
    }
    .btn-add::after {
        content:''; position:absolute; inset:0;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent);
        transform:translateX(-100%); transition:transform .4s;
    }
    .btn-add:hover::after { transform:translateX(100%); }
    .btn-add:hover  { opacity:.9; }
    .btn-add:active { transform:scale(.98); }
    .btn-add svg    { width:15px; height:15px; fill:#fff; }

    /* ── stats ── */
    .ky-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:20px; }
    .stat-card { background:var(--mz-surface); border:1px solid var(--mz-border); border-radius:8px; padding:14px 16px; display:flex; align-items:center; gap:12px; }
    .stat-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .stat-icon svg { width:17px; height:17px; fill:#fff; }
    .stat-icon.emerald { background:linear-gradient(135deg,var(--mz-emerald2),var(--mz-emerald)); }
    .stat-icon.green   { background:linear-gradient(135deg,#166534,#4ade80); }
    .stat-icon.yellow  { background:linear-gradient(135deg,#b8860b,#f5c542); }
    .stat-icon.red     { background:linear-gradient(135deg,#7f1d1d,#f26c6c); }
    .stat-val { font-family:'Rajdhani',sans-serif; font-size:20px; font-weight:700; line-height:1; color:var(--mz-text); }
    .stat-lbl { font-size:10.5px; color:var(--mz-muted); margin-top:2px; }

    /* ── table card ── */
    .ky-card {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.35);
    }
    .ky-card-bar { height:3px; background:linear-gradient(90deg,var(--mz-emerald2),var(--mz-emerald),#6ee7b7); }

    .ky-table { width:100%; border-collapse:collapse; font-size:12.5px; }
    .ky-table thead tr {
        background:var(--mz-surface2); text-transform:uppercase;
        letter-spacing:.5px; font-size:10px; color:var(--mz-muted);
    }
    .ky-table th, .ky-table td { padding:12px 16px; border-bottom:1px solid var(--mz-border); }
    .ky-table th { font-weight:700; text-align:left; }
    .ky-table tbody tr:last-child td { border-bottom:none; }
    .ky-table tbody tr:hover td { background:var(--mz-surface2); transition:background .12s; }

    .th-aksi { text-align:right !important; }

    /* ── nama cell ── */
    .td-nama-wrap { display:flex; align-items:center; gap:12px; }
    .avatar-mini {
        width:34px; height:34px; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg,var(--mz-emerald2),var(--mz-emerald));
        display:flex; align-items:center; justify-content:center;
        font-family:'Rajdhani',sans-serif; font-size:13px; font-weight:700; color:#fff;
    }
    .td-nama-text .tn  { font-weight:600; color:var(--mz-text); }
    .td-nama-text .tid { font-size:10px; color:var(--mz-muted); margin-top:1px; }

    /* ── jabatan ── */
    .jabatan-pill {
        display:inline-flex; align-items:center; gap:5px;
        background:var(--mz-surface2); border:1px solid var(--mz-border);
        border-radius:5px; padding:3px 9px;
        font-size:11px; color:var(--mz-text-soft, #b0b8d0); font-weight:500;
    }
    .jabatan-pill svg { width:10px; height:10px; fill:var(--mz-muted); }

    /* ── kontak ── */
    .kontak-row { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:3px; }
    .kontak-row svg { width:11px; height:11px; fill:var(--mz-muted); flex-shrink:0; }
    .kontak-row:last-child { margin-bottom:0; }

    /* ── status badges ── */
    .status-badge {
        display:inline-flex; align-items:center; gap:5px;
        border-radius:20px; padding:3px 10px;
        font-size:10.5px; font-weight:600; border:1px solid;
    }
    .status-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }

    .st-aktif    { background:rgba(62,240,138,.1);  border-color:rgba(62,240,138,.25);  color:#3ef08a; }
    .st-aktif    .status-dot { background:#3ef08a; box-shadow:0 0 5px #3ef08a; }
    .st-cuti     { background:rgba(245,197,66,.1);  border-color:rgba(245,197,66,.25);  color:#f5c542; }
    .st-cuti     .status-dot { background:#f5c542; }
    .st-resign   { background:rgba(107,118,148,.1); border-color:rgba(107,118,148,.25); color:#6b7694; }
    .st-resign   .status-dot { background:#6b7694; }
    .st-nonaktif { background:rgba(242,108,108,.1); border-color:rgba(242,108,108,.25); color:#f26c6c; }
    .st-nonaktif .status-dot { background:#f26c6c; }

    /* ── actions ── */
    .td-aksi { text-align:right; }
    .action-group { display:flex; justify-content:flex-end; gap:6px; }
    .btn-edit, .btn-del {
        display:inline-flex; align-items:center; gap:5px;
        padding:5px 12px; border-radius:5px; font-size:11.5px; font-weight:600;
        border:1px solid; cursor:pointer; text-decoration:none;
        transition:background .15s; font-family:'Inter',sans-serif;
    }
    .btn-edit { color:var(--mz-accent); border-color:rgba(79,142,247,.3); background:rgba(79,142,247,.06); }
    .btn-edit:hover { background:rgba(79,142,247,.15); }
    .btn-edit svg { width:12px; height:12px; fill:var(--mz-accent); }
    .btn-del  { color:var(--mz-red); border-color:rgba(242,108,108,.3); background:rgba(242,108,108,.06); }
    .btn-del:hover  { background:rgba(242,108,108,.15); }
    .btn-del svg  { width:12px; height:12px; fill:var(--mz-red); }

    /* ── empty ── */
    .empty-state { padding:60px 20px; text-align:center; }
    .empty-state svg { width:40px; height:40px; fill:var(--mz-border); margin:0 auto 12px; display:block; }
    .empty-state p { font-size:13px; color:var(--mz-muted); }

    /* ── modal ── */
    .mz-backdrop {
        position:fixed; inset:0; z-index:50;
        display:flex; align-items:center; justify-content:center;
        background:rgba(0,0,0,.65); backdrop-filter:blur(3px);
    }
    .mz-modal {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px;
        box-shadow:0 0 0 1px rgba(242,108,108,.1), 0 24px 48px rgba(0,0,0,.5);
        width:100%; max-width:360px; overflow:hidden; text-align:center;
    }
    .mz-modal-bar-red { height:3px; background:linear-gradient(90deg,#c0392b,var(--mz-red),#ff9f9f); }
    .mz-modal-body { padding:28px 24px 24px; }
    .del-icon {
        width:52px; height:52px; border-radius:50%;
        background:rgba(242,108,108,.12); border:1px solid rgba(242,108,108,.25);
        display:flex; align-items:center; justify-content:center; margin:0 auto 16px;
    }
    .del-icon svg { width:24px; height:24px; fill:var(--mz-red); }
    .del-title { font-family:'Rajdhani',sans-serif; font-size:18px; font-weight:700; color:var(--mz-text); margin-bottom:8px; }
    .del-desc  { font-size:12.5px; color:var(--mz-muted); line-height:1.6; }
    .del-name  { display:block; margin-top:5px; color:var(--mz-text); font-weight:600; font-size:13px; }
    .del-sub   { display:block; margin-top:6px; font-size:11px; color:var(--mz-muted); }
    .del-actions {
        display:flex; gap:10px; margin-top:22px;
        padding-top:18px; border-top:1px solid var(--mz-border);
    }
    .del-btn-cancel {
        flex:1; padding:9px; border-radius:6px;
        border:1px solid var(--mz-border); background:transparent;
        color:var(--mz-muted); font-size:12.5px; font-weight:500;
        cursor:pointer; font-family:'Inter',sans-serif; transition:border-color .15s,color .15s;
    }
    .del-btn-cancel:hover { border-color:var(--mz-muted); color:var(--mz-text); }
    .del-btn-confirm {
        flex:1; padding:9px; border-radius:6px; border:none;
        background:linear-gradient(135deg,#c0392b,var(--mz-red));
        color:#fff; font-family:'Rajdhani',sans-serif;
        font-size:13px; font-weight:700; letter-spacing:.5px;
        cursor:pointer; transition:opacity .15s;
    }
    .del-btn-confirm:hover { opacity:.88; }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div x-data="deleteModal()" class="ky-wrap">

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="mz-alert-success">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="ky-header">
        <div>
            <div class="ky-title">Data Karyawan</div>
            <div class="ky-subtitle">Daftar karyawan yang terdaftar di sistem</div>
        </div>
        <a href="{{ route('karyawan.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Karyawan
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="ky-stats">
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $karyawans->count() }}</div>
                <div class="stat-lbl">Total Karyawan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $karyawans->where('status','aktif')->count() }}</div>
                <div class="stat-lbl">Aktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $karyawans->where('status','cuti')->count() }}</div>
                <div class="stat-lbl">Cuti</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $karyawans->whereIn('status',['resign','nonaktif'])->count() }}</div>
                <div class="stat-lbl">Nonaktif / Resign</div>
            </div>
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="ky-card">
        <div class="ky-card-bar"></div>

        <table class="ky-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th class="th-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $k)
                <tr>
                    {{-- Nama --}}
                    <td>
                        <div class="td-nama-wrap">
                            <div class="avatar-mini">
                                {{ collect(explode(' ', $k->nama))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('') }}
                            </div>
                            <div class="td-nama-text">
                                <div class="tn">{{ $k->nama }}</div>
                                <div class="tid">#{{ $k->id }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Jabatan --}}
                    <td>
                        @if($k->jabatan)
                            <span class="jabatan-pill">
                                <svg viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.36C18 2.06 15.94 0 13.36 0c-1.31 0-2.44.55-3.31 1.41L9 2.5 7.95 1.41C7.08.55 5.95 0 4.64 0 2.06 0 0 2.06 0 4.64c0 .48.1.92.18 1.36H0v2h20c1.1 0 2-.9 2-2s-.9-2-2-2zM4.64 4c-.75 0-1.36-.61-1.36-1.36S3.89 1.28 4.64 1.28s1.36.61 1.36 1.36S5.39 4 4.64 4zm8.72 0c-.75 0-1.36-.61-1.36-1.36s.61-1.36 1.36-1.36 1.36.61 1.36 1.36S14.11 4 13.36 4zM2 22h20v-2H2v2zm0-4h20v-2H2v2zm0-4h20v-2H2v2z"/></svg>
                                {{ $k->jabatan }}
                            </span>
                        @else
                            <span style="color:var(--mz-muted);font-size:12px">—</span>
                        @endif
                    </td>

                    {{-- Kontak --}}
                    <td>
                        <div class="kontak-row">
                            <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            {{ $k->no_hp ?? '—' }}
                        </div>
                        <div class="kontak-row">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            {{ $k->email ?? '—' }}
                        </div>
                    </td>

                    {{-- Status --}}
                    <td>
                        @switch($k->status)
                            @case('aktif')
                                <span class="status-badge st-aktif">
                                    <span class="status-dot"></span>Aktif
                                </span>
                                @break
                            @case('cuti')
                                <span class="status-badge st-cuti">
                                    <span class="status-dot"></span>Cuti
                                </span>
                                @break
                            @case('resign')
                                <span class="status-badge st-resign">
                                    <span class="status-dot"></span>Resign
                                </span>
                                @break
                            @case('nonaktif')
                                <span class="status-badge st-nonaktif">
                                    <span class="status-dot"></span>Nonaktif
                                </span>
                                @break
                        @endswitch
                    </td>

                    {{-- Aksi --}}
                    <td class="td-aksi">
                        <div class="action-group">
                            <a href="{{ route('karyawan.edit', $k->id) }}" class="btn-edit">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            <button @click="open({{ $k->id }}, '{{ $k->nama }}')" class="btn-del">
                                <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            <p>Belum ada data karyawan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Delete Modal ── --}}
    <div x-show="show" x-transition class="mz-backdrop" style="display:none">
        <div @click.away="show = false" class="mz-modal">
            <div class="mz-modal-bar-red"></div>
            <div class="mz-modal-body">
                <div class="del-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                </div>
                <div class="del-title">Konfirmasi Hapus</div>
                <p class="del-desc">
                    Apakah kamu yakin ingin menghapus
                    <span class="del-name" x-text="nama"></span>
                    <span class="del-sub">Data yang dihapus tidak bisa dikembalikan.</span>
                </p>
                <div class="del-actions">
                    <button @click="show = false" class="del-btn-cancel">Batal</button>
                    <form :action="url" method="POST" style="flex:1;display:flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="del-btn-confirm" style="width:100%">Ya, Hapus</button>
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
            this.url = `/karyawan/${id}`;
            this.nama = nama;
            this.show = true;
        }
    }
}
</script>
@endsection
