@extends('dashboard')

@section('title', 'Edit Pelanggan')

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
        --mz-muted:    #6b7694;
        --mz-green:    #3ef08a;
        --mz-yellow:   #f5c542;
        --mz-orange:   #f5923e;
        --mz-teal:     #2dd4bf;
        --mz-teal2:    #0d9488;
    }


    .ep-wrap {
        font-family: 'Inter', sans-serif;
        color: var(--mz-text);
    }

    /* ── header ── */
    .ep-header     { margin-bottom: 28px; }
    .ep-breadcrumb { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:10px; }
    .ep-breadcrumb a { color:var(--mz-muted); text-decoration:none; transition:color .15s; }
    .ep-breadcrumb a:hover { color:var(--mz-teal); }
    .ep-breadcrumb span { color:var(--mz-border); }
    .ep-title    { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .ep-subtitle { font-size:12px; color:var(--mz-muted); margin-top:5px; }

    .ep-edit-badge {
        display:inline-flex; align-items:center; gap:6px;
        background:rgba(245,146,62,.1); border:1px solid rgba(245,146,62,.25);
        border-radius:20px; padding:4px 12px; margin-top:10px;
        font-size:11px; color:var(--mz-orange); font-weight:500;
    }
    .ep-edit-badge svg { width:11px; height:11px; fill:var(--mz-orange); }

    /* ── card ── */
    .ep-card {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px; overflow:hidden;
        box-shadow:0 0 0 1px rgba(245,146,62,.06), 0 20px 48px rgba(0,0,0,.4);
    }
    /* teal-to-orange for edit pelanggan */
    .ep-card-bar { height:3px; background:linear-gradient(90deg, #e05c00, var(--mz-orange), var(--mz-teal)); }

    .ep-card-head {
        padding:18px 28px; border-bottom:1px solid var(--mz-border);
        display:flex; align-items:center; gap:12px;
    }
    .ep-card-icon {
        width:36px; height:36px; border-radius:8px;
        background:linear-gradient(135deg, #e05c00, var(--mz-orange));
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .ep-card-icon svg { width:18px; height:18px; fill:#fff; }
    .ep-card-head-text .cht { font-family:'Rajdhani',sans-serif; font-size:15px; font-weight:700; color:var(--mz-text); }
    .ep-card-head-text .chs { font-size:11px; color:var(--mz-muted); margin-top:1px; }

    .ep-item-chip {
        margin-left:auto; background:var(--mz-surface2);
        border:1px solid var(--mz-border); border-radius:6px;
        padding:5px 12px; font-size:12px; color:var(--mz-yellow); font-weight:500;
        display:flex; align-items:center; gap:6px;
    }
    .ep-item-chip svg { width:12px; height:12px; fill:var(--mz-yellow); }

    /* ── form body ── */
    .ep-form-body { padding:28px; }
    .ep-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .col-full { grid-column:1/-1; }

    /* ── section label ── */
    .ep-section-label {
        font-size:10px; font-weight:700; letter-spacing:1px;
        text-transform:uppercase; color:var(--mz-orange);
        margin-bottom:16px; display:flex; align-items:center; gap:8px;
    }
    .ep-section-label::after { content:''; flex:1; height:1px; background:var(--mz-border); }

    .ep-sep { margin:24px 0; border:none; border-top:1px solid var(--mz-border); }

    /* ── field ── */
    .mz-field { display:flex; flex-direction:column; gap:6px; }
    .mz-label {
        font-size:10.5px; font-weight:600; letter-spacing:.7px;
        text-transform:uppercase; color:var(--mz-muted);
    }
    .mz-input-wrap { position:relative; }
    .mz-input-icon {
        position:absolute; left:13px; top:50%; transform:translateY(-50%);
        width:15px; height:15px; fill:var(--mz-muted); pointer-events:none; transition:fill .15s;
    }
    .mz-input-wrap:focus-within .mz-input-icon { fill:var(--mz-orange); }

    .mz-input, .mz-select {
        width:100%; background:var(--mz-bg); border:1px solid var(--mz-border);
        border-radius:6px; padding:11px 14px 11px 38px;
        color:var(--mz-text); outline:none;
        font-family:'Inter',sans-serif; font-size:13px;
        transition:border-color .15s, box-shadow .15s;
    }
    .mz-input::placeholder { color:#3a4059; }
    .mz-input:focus, .mz-select:focus {
        border-color:var(--mz-orange);
        box-shadow:0 0 0 3px rgba(245,146,62,.15);
    }
    .mz-input.is-changed { border-color:rgba(245,197,66,.4); }
    .mz-input.uppercase-input { text-transform:uppercase; }

    /* select */
    .mz-select { appearance:none; cursor:pointer; }
    .mz-select-wrap { position:relative; }
    .mz-select-caret {
        position:absolute; right:13px; top:50%; transform:translateY(-50%);
        width:14px; height:14px; fill:var(--mz-muted); pointer-events:none;
    }

    .mz-hint { font-size:10px; color:var(--mz-muted); margin-top:2px; }

    /* tipe preview */
    .tipe-preview {
        display:inline-flex; align-items:center; gap:5px;
        font-size:11px; margin-top:4px; min-height:16px; font-weight:500;
    }
    .tipe-preview svg { width:11px; height:11px; flex-shrink:0; }

    /* ── vehicle visual ── */
    .car-visual {
        grid-column:1/-1;
        background:var(--mz-surface2); border:1px solid var(--mz-border);
        border-radius:8px; padding:14px 18px;
        display:flex; align-items:center; gap:16px; min-height:62px;
    }
    .car-visual-icon { width:36px; height:36px; fill:var(--mz-muted); flex-shrink:0; }
    .car-visual-text .cv-plat  { font-family:'Rajdhani',sans-serif; font-size:17px; font-weight:700; letter-spacing:2px; color:var(--mz-text); }
    .car-visual-text .cv-model { font-size:11px; color:var(--mz-muted); margin-top:1px; }
    .car-visual-text .cv-empty { font-size:12px; color:#3a4059; font-style:italic; }

    /* ── footer ── */
    .ep-footer {
        padding:18px 28px; border-top:1px solid var(--mz-border);
        display:flex; align-items:center; justify-content:space-between;
        background:var(--mz-surface2);
    }
    .ep-footer-hint { font-size:11px; color:var(--mz-muted); display:flex; align-items:center; gap:6px; }
    .ep-footer-hint svg { width:13px; height:13px; fill:var(--mz-muted); }
    .ep-actions { display:flex; gap:10px; align-items:center; }

    .btn-cancel {
        padding:9px 20px; border-radius:6px; font-size:12.5px; font-weight:500;
        color:var(--mz-muted); background:transparent; border:1px solid var(--mz-border);
        cursor:pointer; text-decoration:none; display:inline-block;
        transition:border-color .15s, color .15s;
    }
    .btn-cancel:hover { border-color:var(--mz-muted); color:var(--mz-text); }

    .btn-submit {
        padding:9px 28px; border-radius:6px;
        font-family:'Rajdhani',sans-serif; font-size:14px; font-weight:700;
        letter-spacing:.8px; text-transform:uppercase; color:#fff;
        background:linear-gradient(135deg, #e05c00, var(--mz-orange));
        border:none; cursor:pointer;
        transition:opacity .15s, transform .1s; position:relative; overflow:hidden;
    }
    .btn-submit::after {
        content:''; position:absolute; inset:0;
        background:linear-gradient(90deg, transparent, rgba(255,255,255,.1), transparent);
        transform:translateX(-100%); transition:transform .45s;
    }
    .btn-submit:hover::after { transform:translateX(100%); }
    .btn-submit:hover  { opacity:.9; }
    .btn-submit:active { transform:scale(.98); }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="ep-wrap">

    {{-- ── Header ── --}}
    <div class="ep-header">
        <div class="ep-breadcrumb">
            <a href="{{ route('pelanggan.index') }}">Pelanggan</a>
            <span>/</span>
            <span style="color:var(--mz-text)">Edit</span>
        </div>
        <div class="ep-title">Edit Pelanggan</div>
        <div class="ep-subtitle">Perbarui data pelanggan dan kendaraan</div>
        <div class="ep-edit-badge">
            <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            Mode Edit — {{ $pelanggan->nama }}
        </div>
    </div>

    {{-- ── Card ── --}}
    <div class="ep-card">
        <div class="ep-card-bar"></div>

        <div class="ep-card-head">
            <div class="ep-card-icon">
                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            </div>
            <div class="ep-card-head-text">
                <div class="cht">Perbarui Data Pelanggan</div>
                <div class="chs">Ubah field yang perlu diperbarui lalu simpan</div>
            </div>
            <div class="ep-item-chip">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                ID #{{ $pelanggan->id }}
            </div>
        </div>

        <form method="POST" action="{{ route('pelanggan.update', $pelanggan->id) }}">
            @csrf
            @method('PUT')

            <div class="ep-form-body">

                {{-- ── Identitas ── --}}
                <div class="ep-section-label">Identitas Pelanggan</div>

                <div class="ep-grid">

                    {{-- Nama --}}
                    <div class="mz-field col-full">
                        <label class="mz-label">Nama Pelanggan / Perusahaan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            <input name="nama" required
                                   value="{{ old('nama', $pelanggan->nama) }}"
                                   data-original="{{ $pelanggan->nama }}"
                                   oninput="markChanged(this)"
                                   class="mz-input">
                        </div>
                    </div>

                    {{-- No HP --}}
                    <div class="mz-field">
                        <label class="mz-label">No. HP</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            <input name="no_hp"
                                   value="{{ old('no_hp', $pelanggan->no_hp) }}"
                                   data-original="{{ $pelanggan->no_hp }}"
                                   oninput="markChanged(this)"
                                   class="mz-input">
                        </div>
                    </div>

                    {{-- Tipe --}}
                    <div class="mz-field">
                        <label class="mz-label">Tipe Pelanggan</label>
                        <div class="mz-select-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                            <select name="tipe" class="mz-select" id="tipeSelect" onchange="updateTipePreview()">
                                <option value="pribadi"    {{ old('tipe', $pelanggan->tipe) === 'pribadi'    ? 'selected' : '' }}>Pribadi</option>
                                <option value="perusahaan" {{ old('tipe', $pelanggan->tipe) === 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                            </select>
                            <svg class="mz-select-caret" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                        </div>
                        <div class="tipe-preview" id="tipePreview"></div>
                    </div>

                </div>

                <hr class="ep-sep">

                {{-- ── Kendaraan ── --}}
                <div class="ep-section-label">Data Kendaraan</div>

                <div class="ep-grid">

                    {{-- Plat --}}
                    <div class="mz-field">
                        <label class="mz-label">Plat Nomor</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-7 11h-2v-2h2v2zm0-4h-2V7h2v4z"/></svg>
                            <input name="plat_nomor" required
                                   value="{{ old('plat_nomor', $pelanggan->plat_nomor) }}"
                                   data-original="{{ $pelanggan->plat_nomor }}"
                                   oninput="markChanged(this); updateCarVisual()"
                                   class="mz-input uppercase-input"
                                   id="platInput">
                        </div>
                    </div>

                    {{-- Merk --}}
                    <div class="mz-field">
                        <label class="mz-label">Merk Mobil</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                            <input name="merk_mobil" required
                                   value="{{ old('merk_mobil', $pelanggan->merk_mobil) }}"
                                   data-original="{{ $pelanggan->merk_mobil }}"
                                   oninput="markChanged(this); updateCarVisual()"
                                   class="mz-input"
                                   id="merkInput">
                        </div>
                    </div>

                    {{-- Model --}}
                    <div class="mz-field">
                        <label class="mz-label">Model Mobil</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                            <input name="model_mobil" required
                                   value="{{ old('model_mobil', $pelanggan->model_mobil) }}"
                                   data-original="{{ $pelanggan->model_mobil }}"
                                   oninput="markChanged(this); updateCarVisual()"
                                   class="mz-input"
                                   id="modelInput">
                        </div>
                    </div>

                    {{-- Tahun --}}
                    <div class="mz-field">
                        <label class="mz-label">
                            Tahun Mobil
                            <span style="color:var(--mz-muted);font-weight:400;text-transform:none;letter-spacing:0">(Opsional)</span>
                        </label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                            <input name="tahun_mobil"
                                   value="{{ old('tahun_mobil', $pelanggan->tahun_mobil) }}"
                                   data-original="{{ $pelanggan->tahun_mobil }}"
                                   oninput="markChanged(this); updateCarVisual()"
                                   class="mz-input"
                                   id="tahunInput">
                        </div>
                        <div class="mz-hint">Kosongkan jika tidak diketahui</div>
                    </div>

                    {{-- Car Visual --}}
                    <div class="car-visual" id="carVisual">
                        <svg class="car-visual-icon" viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                        <div class="car-visual-text">
                            <div class="cv-plat" id="cvPlat"></div>
                            <div class="cv-model" id="cvModel"></div>
                            <div class="cv-empty" id="cvEmpty" style="display:none">Isi plat dan merk/model untuk preview kendaraan</div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="ep-footer">
                <div class="ep-footer-hint">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Perubahan akan langsung tersimpan ke database
                </div>
                <div class="ep-actions">
                    <a href="{{ route('pelanggan.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
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

function markChanged(input) {
    const original = input.dataset.original ?? '';
    input.classList.toggle('is-changed', input.value !== original);
}

function updateTipePreview() {
    const val = document.getElementById('tipeSelect').value;
    const el  = document.getElementById('tipePreview');
    if (val === 'perusahaan') {
        el.innerHTML = `<svg viewBox="0 0 24 24" style="width:11px;height:11px;fill:#f5c542;flex-shrink:0"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg> Pelanggan Perusahaan`;
        el.style.color = '#f5c542';
    } else {
        el.innerHTML = `<svg viewBox="0 0 24 24" style="width:11px;height:11px;fill:#2dd4bf;flex-shrink:0"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg> Pelanggan Pribadi`;
        el.style.color = '#2dd4bf';
    }
}

function updateCarVisual() {
    const plat  = (document.getElementById('platInput').value || '').toUpperCase().trim();
    const merk  = (document.getElementById('merkInput').value || '').trim();
    const model = (document.getElementById('modelInput').value || '').trim();
    const tahun = (document.getElementById('tahunInput').value || '').trim();

    const cvPlat  = document.getElementById('cvPlat');
    const cvModel = document.getElementById('cvModel');
    const cvEmpty = document.getElementById('cvEmpty');

    if (plat || merk || model) {
        cvEmpty.style.display = 'none';
        cvPlat.textContent  = plat || '';
        cvModel.textContent = [merk, model, tahun ? `(${tahun})` : ''].filter(Boolean).join(' ');
    } else {
        cvEmpty.style.display = 'block';
        cvPlat.textContent  = '';
        cvModel.textContent = '';
    }
}

// Init on load with existing data
document.addEventListener('DOMContentLoaded', function () {
    updateTipePreview();
    updateCarVisual();
});
</script>
@endsection
