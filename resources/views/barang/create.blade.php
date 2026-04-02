@extends('dashboard')

@section('title', 'Tambah Barang')

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
        --mz-yellow:   #f5c542;
}


.tb-wrap  { font-family: 'Inter', sans-serif; color: var(--mz-text); }

    /* ── header ── */
    .tb-header        { margin-bottom: 28px; }
    .tb-breadcrumb    { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:10px; }
    .tb-breadcrumb a  { color:var(--mz-muted); text-decoration:none; transition:color .15s; }
    .tb-breadcrumb a:hover { color:var(--mz-accent); }
    .tb-breadcrumb span   { color:var(--mz-border); }
    .tb-title         { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .tb-subtitle      { font-size:12px; color:var(--mz-muted); margin-top:5px; }

    /* ── card ── */
    .tb-card {
        background: var(--mz-surface);
        border: 1px solid var(--mz-border);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 0 1px rgba(79,142,247,.08), 0 20px 48px rgba(0,0,0,.4);
    }

    .tb-card-bar { height: 3px; background: linear-gradient(90deg, #1e90ff, var(--mz-accent), #8ab6ff); }

    .tb-card-head {
        padding: 18px 28px;
        border-bottom: 1px solid var(--mz-border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tb-card-icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: linear-gradient(135deg, var(--mz-accent2), var(--mz-accent));
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .tb-card-icon svg { width: 18px; height: 18px; fill: #fff; }

    .tb-card-head-text .cht { font-family:'Rajdhani',sans-serif; font-size:15px; font-weight:700; color:var(--mz-text); }
    .tb-card-head-text .chs { font-size:11px; color:var(--mz-muted); margin-top:1px; }

    /* ── form body ── */
    .tb-form-body { padding: 28px; }

    .tb-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .col-full { grid-column: 1 / -1; }

    /* ── field ── */
    .mz-field { display: flex; flex-direction: column; gap: 6px; }

    .mz-label {
        font-size: 10.5px;
        font-weight: 600;
        letter-spacing: .7px;
        text-transform: uppercase;
        color: var(--mz-muted);
    }

    .mz-input-wrap { position: relative; }

    .mz-input-icon {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        width: 15px; height: 15px; fill: var(--mz-muted); pointer-events: none;
        transition: fill .15s;
    }

    .mz-input {
        width: 100%;
        background: var(--mz-bg);
        border: 1px solid var(--mz-border);
        border-radius: 6px;
        padding: 11px 14px 11px 38px;
        font-size: 13px;
        color: var(--mz-text);
        outline: none;
        font-family: 'Inter', sans-serif;
        transition: border-color .15s, box-shadow .15s;
        -moz-appearance: textfield;
    }
    .mz-input::-webkit-outer-spin-button,
    .mz-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    .mz-input::placeholder { color: #3a4059; }
    .mz-input:focus {
        border-color: var(--mz-accent);
        box-shadow: 0 0 0 3px rgba(79,142,247,.15);
    }
    .mz-input:focus ~ .mz-input-icon,
    .mz-input-wrap:focus-within .mz-input-icon { fill: var(--mz-accent); }

    /* hint tag */
    .mz-hint { font-size:10px; color:var(--mz-muted); margin-top:2px; }

    /* ── price display ── */
    .price-preview {
        font-size: 11px; color: var(--mz-green); margin-top: 4px;
        min-height: 16px; font-weight: 500; letter-spacing: .3px;
    }

    /* ── separator ── */
    .tb-sep {
        margin: 24px 0;
        border: none;
        border-top: 1px solid var(--mz-border);
    }

    /* ── section label ── */
    .tb-section-label {
        font-size: 10px; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; color: var(--mz-accent);
        margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }
    .tb-section-label::after {
        content:''; flex:1; height:1px; background: var(--mz-border);
    }

    /* ── footer ── */
    .tb-footer {
        padding: 18px 28px;
        border-top: 1px solid var(--mz-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--mz-surface2);
    }

    .tb-footer-hint { font-size: 11px; color: var(--mz-muted); display:flex; align-items:center; gap:6px; }
    .tb-footer-hint svg { width:13px; height:13px; fill:var(--mz-muted); }

    .tb-actions { display:flex; gap:10px; align-items:center; }

    .btn-cancel {
        padding: 9px 20px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 500;
        color: var(--mz-muted);
        background: transparent;
        border: 1px solid var(--mz-border);
        cursor: pointer;
        text-decoration: none;
        transition: border-color .15s, color .15s;
        display: inline-block;
    }
    .btn-cancel:hover { border-color: var(--mz-muted); color: var(--mz-text); }

    .btn-submit {
        padding: 9px 28px;
        border-radius: 6px;
        font-family: 'Rajdhani', sans-serif;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: #fff;
        background: linear-gradient(135deg, var(--mz-accent2), var(--mz-accent));
        border: none;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        position: relative;
        overflow: hidden;
    }
    .btn-submit::after {
        content:''; position:absolute; inset:0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.1), transparent);
        transform: translateX(-100%); transition: transform .45s;
    }
    .btn-submit:hover::after { transform: translateX(100%); }
    .btn-submit:hover  { opacity: .9; }
    .btn-submit:active { transform: scale(.98); }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="tb-wrap">

    {{-- ── Header ── --}}
    <div class="tb-header">
        <div class="tb-breadcrumb">
            <a href="{{ route('barang.index') }}">Barang</a>
            <span>/</span>
            <span style="color:var(--mz-text)">Tambah</span>
        </div>
        <div class="tb-title">Tambah Barang</div>
        <div class="tb-subtitle">Masukkan data barang, harga, dan stok dengan benar</div>
    </div>

    {{-- ── Card ── --}}
    <div class="tb-card">
        <div class="tb-card-bar"></div>

        <div class="tb-card-head">
            <div class="tb-card-icon">
                <svg viewBox="0 0 24 24"><path d="M19 11H7.83l4.88-4.88c.39-.39.39-1.03 0-1.42-.39-.39-1.02-.39-1.41 0l-6.59 6.59c-.39.39-.39 1.02 0 1.41l6.59 6.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L7.83 13H19c.55 0 1-.45 1-1s-.45-1-1-1z"/></svg>
            </div>
            <div class="tb-card-head-text">
                <div class="cht">Data Barang Baru</div>
                <div class="chs">Semua field bertanda wajib diisi</div>
            </div>
        </div>

        <form method="POST" action="{{ route('barang.store') }}">
            @csrf

            <div class="tb-form-body">

                {{-- ── Identitas Barang ── --}}
                <div class="tb-section-label">Identitas Barang</div>

                <div class="tb-grid">
                    {{-- Nama Barang --}}
                    <div class="mz-field col-full">
                        <label class="mz-label">Nama Barang</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.36C18 2.06 15.94 0 13.36 0c-1.31 0-2.44.55-3.31 1.41L9 2.5 7.95 1.41C7.08.55 5.95 0 4.64 0 2.06 0 0 2.06 0 4.64c0 .48.1.92.18 1.36H0v2h20c1.1 0 2-.9 2-2s-.9-2-2-2zM4.64 4c-.75 0-1.36-.61-1.36-1.36S3.89 1.28 4.64 1.28s1.36.61 1.36 1.36S5.39 4 4.64 4zm8.72 0c-.75 0-1.36-.61-1.36-1.36s.61-1.36 1.36-1.36 1.36.61 1.36 1.36S14.11 4 13.36 4zM2 22h20v-2H2v2zm0-4h20v-2H2v2zm0-4h20v-2H2v2z"/></svg>
                            <input
                                name="nama"
                                required
                                value="{{ old('nama') }}"
                                placeholder="Contoh: Oli Mesin, Kampas Rem"
                                class="mz-input"
                            >
                        </div>
                    </div>
                </div>

                <hr class="tb-sep">

                {{-- ── Harga ── --}}
                <div class="tb-section-label">Harga</div>

                <div class="tb-grid">
                    {{-- Harga Pribadi --}}
                    <div class="mz-field">
                        <label class="mz-label">Harga Pribadi</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1H8.3c.12 2.19 1.76 3.42 3.7 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                            <input
                                name="harga_pribadi"
                                type="number"
                                required
                                value="{{ old('harga_pribadi') }}"
                                placeholder="75000"
                                class="mz-input"
                                id="harga_pribadi"
                                oninput="previewHarga('harga_pribadi','prev_pribadi')"
                            >
                        </div>
                        <div class="price-preview" id="prev_pribadi"></div>
                    </div>

                    {{-- Harga Perusahaan --}}
                    <div class="mz-field">
                        <label class="mz-label">Harga Perusahaan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1H8.3c.12 2.19 1.76 3.42 3.7 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                            <input
                                name="harga_perusahaan"
                                type="number"
                                required
                                value="{{ old('harga_perusahaan') }}"
                                placeholder="65000"
                                class="mz-input"
                                id="harga_perusahaan"
                                oninput="previewHarga('harga_perusahaan','prev_perusahaan')"
                            >
                        </div>
                        <div class="price-preview" id="prev_perusahaan"></div>
                    </div>
                </div>

                <hr class="tb-sep">

                {{-- ── Stok & Satuan ── --}}
                <div class="tb-section-label">Stok & Satuan</div>

                <div class="tb-grid">
                    {{-- Stok --}}
                    <div class="mz-field">
                        <label class="mz-label">Stok</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 9h-2V5h2v6zm0 4h-2v-2h2v2z"/></svg>
                            <input
                                name="stok"
                                type="number"
                                required
                                value="{{ old('stok', 0) }}"
                                class="mz-input"
                            >
                        </div>
                        <div class="mz-hint">Jumlah unit yang tersedia saat ini</div>
                    </div>

                    {{-- Satuan --}}
                    <div class="mz-field">
                        <label class="mz-label">Satuan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M21 6.5l-4-4-9 9-3 7 7-3 9-9zm-14.09 9.68L5.5 17.5l1.32-1.41.09.09zM19 8.5l-7 7-1.5-1.5 7-7 1.5 1.5z"/></svg>
                            <input
                                name="satuan"
                                value="{{ old('satuan', 'pcs') }}"
                                placeholder="pcs / liter / set"
                                class="mz-input"
                            >
                        </div>
                        <div class="mz-hint">Contoh: pcs, liter, set, botol</div>
                    </div>
                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="tb-footer">
                <div class="tb-footer-hint">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Data tersimpan ke database secara langsung
                </div>
                <div class="tb-actions">
                    <a href="{{ route('barang.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Barang</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

function previewHarga(inputId, previewId) {
    const val = parseInt(document.getElementById(inputId).value);
    const el  = document.getElementById(previewId);
    if (!isNaN(val) && val > 0) {
        el.textContent = '→ Rp ' + val.toLocaleString('id-ID');
    } else {
        el.textContent = '';
    }
}
</script>
@endsection
