@extends('dashboard')

@section('title', 'Data Pelanggan')

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
        --mz-teal:     #2dd4bf;
        --mz-teal2:    #0d9488;
}

    .pl-wrap { font-family:'Inter',sans-serif; color:var(--mz-text); }

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
    .pl-header  { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:20px; }
    .pl-title   { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .pl-subtitle{ font-size:12px; color:var(--mz-muted); margin-top:4px; }

    .btn-add {
        display:inline-flex; align-items:center; gap:7px;
        background:linear-gradient(135deg, var(--mz-teal2), var(--mz-teal));
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
    .pl-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:20px; }
    .stat-card { background:var(--mz-surface); border:1px solid var(--mz-border); border-radius:8px; padding:14px 18px; display:flex; align-items:center; gap:14px; }
    .stat-icon { width:38px; height:38px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .stat-icon svg { width:18px; height:18px; fill:#fff; }
    .stat-icon.teal   { background:linear-gradient(135deg,var(--mz-teal2),var(--mz-teal)); }
    .stat-icon.blue   { background:linear-gradient(135deg,var(--mz-accent2),var(--mz-accent)); }
    .stat-icon.yellow { background:linear-gradient(135deg,#b8860b,#f5c542); }
    .stat-val { font-family:'Rajdhani',sans-serif; font-size:22px; font-weight:700; line-height:1; color:var(--mz-text); }
    .stat-lbl { font-size:11px; color:var(--mz-muted); margin-top:2px; }

    /* ── table card ── */
    .pl-card {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.35);
    }
    .pl-card-bar { height:3px; background:linear-gradient(90deg,var(--mz-teal2),var(--mz-teal),#99f6e4); }

    .pl-table { width:100%; border-collapse:collapse; font-size:12.5px; }
    .pl-table thead tr {
        background:var(--mz-surface2); text-transform:uppercase;
        letter-spacing:.5px; font-size:10px; color:var(--mz-muted);
    }
    .pl-table th, .pl-table td { padding:12px 16px; border-bottom:1px solid var(--mz-border); }
    .pl-table th { font-weight:700; }
    .pl-table tbody tr:last-child td { border-bottom:none; }
    .pl-table tbody tr:hover td { background:var(--mz-surface2); transition:background .12s; }

    .th-nama   { text-align:left; }
    .th-center { text-align:center; }
    .th-aksi   { text-align:right; }

    /* nama cell */
    .td-nama { font-weight:600; color:var(--mz-text); }
    .td-hp   { font-size:11px; color:var(--mz-muted); margin-top:2px; display:flex; align-items:center; gap:4px; }
    .td-hp svg { width:10px; height:10px; fill:var(--mz-muted); }

    /* plat */
    .plat-badge {
        display:inline-block;
        background:var(--mz-surface2); border:1px solid var(--mz-border);
        border-radius:5px; padding:3px 10px;
        font-family:'Rajdhani',sans-serif; font-size:14px; font-weight:700;
        letter-spacing:1.5px; text-transform:uppercase; color:var(--mz-text);
    }

    /* mobil cell */
    .td-mobil      { text-align:center; }
    .mobil-name    { font-weight:500; color:var(--mz-text); }
    .mobil-tahun   { font-size:10.5px; color:var(--mz-muted); margin-top:2px; }

    /* tipe badge */
    .td-tipe { text-align:center; }
    .tipe-pribadi {
        display:inline-flex; align-items:center; gap:5px;
        background:rgba(45,212,191,.1); border:1px solid rgba(45,212,191,.25);
        color:var(--mz-teal); border-radius:20px; padding:3px 10px;
        font-size:10.5px; font-weight:600;
    }
    .tipe-pribadi svg { width:10px; height:10px; fill:var(--mz-teal); }
    .tipe-perusahaan {
        display:inline-flex; align-items:center; gap:5px;
        background:rgba(245,197,66,.1); border:1px solid rgba(245,197,66,.25);
        color:var(--mz-yellow); border-radius:20px; padding:3px 10px;
        font-size:10.5px; font-weight:600;
    }
    .tipe-perusahaan svg { width:10px; height:10px; fill:var(--mz-yellow); }

    /* actions */
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

<div class="pl-wrap" x-data="deleteModal()">

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="mz-alert-success">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="pl-header">
        <div>
            <div class="pl-title">Data Pelanggan</div>
            <div class="pl-subtitle">Daftar pelanggan dan kendaraan yang terdaftar</div>
        </div>
        <a href="{{ route('pelanggan.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Pelanggan
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="pl-stats">
        <div class="stat-card">
            <div class="stat-icon teal">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $pelanggans->count() }}</div>
                <div class="stat-lbl">Total Pelanggan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $pelanggans->where('tipe','pribadi')->count() }}</div>
                <div class="stat-lbl">Pelanggan Pribadi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg viewBox="0 0 24 24"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $pelanggans->where('tipe','perusahaan')->count() }}</div>
                <div class="stat-lbl">Pelanggan Perusahaan</div>
            </div>
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="pl-card">
        <div class="pl-card-bar"></div>

        <table class="pl-table">
            <thead>
                <tr>
                    <th class="th-nama">Nama Pelanggan</th>
                    <th class="th-center">Plat Nomor</th>
                    <th class="th-center">Mobil</th>
                    <th class="th-center">Tipe</th>
                    <th class="th-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggans as $p)
                <tr>
                    <td class="td-nama">
                        {{ $p->nama }}
                        @if($p->no_hp)
                            <div class="td-hp">
                                <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                                {{ $p->no_hp }}
                            </div>
                        @endif
                    </td>

                    <td style="text-align:center">
                        <span class="plat-badge">{{ $p->plat_nomor }}</span>
                    </td>

                    <td class="td-mobil">
                        <div class="mobil-name">{{ $p->merk_mobil }} {{ $p->model_mobil }}</div>
                        @if($p->tahun_mobil)
                            <div class="mobil-tahun">{{ $p->tahun_mobil }}</div>
                        @endif
                    </td>

                    <td class="td-tipe">
                        @if($p->tipe === 'pribadi')
                            <span class="tipe-pribadi">
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                Pribadi
                            </span>
                        @else
                            <span class="tipe-perusahaan">
                                <svg viewBox="0 0 24 24"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                                Perusahaan
                            </span>
                        @endif
                    </td>

                    <td class="td-aksi">
                        <div class="action-group">
                            <a href="{{ route('pelanggan.edit', $p->id) }}" class="btn-edit">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            <button @click="open({{ $p->id }}, '{{ $p->nama }}')" class="btn-del">
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
                            <p>Belum ada data pelanggan</p>
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
                    Apakah kamu yakin ingin menghapus
                    <span class="del-name" x-text="nama"></span>
                    <span class="del-sub">Data yang dihapus tidak bisa dikembalikan.</span>
                </p>
                <div class="del-actions">
                    <button @click="close" class="del-btn-cancel">Batal</button>
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
            this.url = `/pelanggan/${id}`
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
