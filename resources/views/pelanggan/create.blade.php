@extends('dashboard')

@section('title', 'Tambah Pelanggan')

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
        --mz-teal:     #2dd4bf;
        --mz-teal2:    #0d9488;
}


    .tp-wrap {
        font-family: 'Inter', sans-serif;
        color: var(--mz-text);

    }

    /* ── header ── */
    .tp-header     { margin-bottom: 28px; }
    .tp-breadcrumb { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:10px; }
    .tp-breadcrumb a { color:var(--mz-muted); text-decoration:none; transition:color .15s; }
    .tp-breadcrumb a:hover { color:var(--mz-teal); }
    .tp-breadcrumb span { color:var(--mz-border); }
    .tp-title    { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .tp-subtitle { font-size:12px; color:var(--mz-muted); margin-top:5px; }

    /* ── card ── */
    .tp-card {
        background: var(--mz-surface);
        border: 1px solid var(--mz-border);
        border-radius: 10px; overflow: hidden;
        box-shadow: 0 0 0 1px rgba(45,212,191,.07), 0 20px 48px rgba(0,0,0,.4);
    }
    .tp-card-bar { height:3px; background:linear-gradient(90deg, var(--mz-teal2), var(--mz-teal), #99f6e4); }

    .tp-card-head {
        padding: 18px 28px; border-bottom: 1px solid var(--mz-border);
        display: flex; align-items: center; gap: 12px;
    }
    .tp-card-icon {
        width:36px; height:36px; border-radius:8px;
        background: linear-gradient(135deg, var(--mz-teal2), var(--mz-teal));
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .tp-card-icon svg { width:18px; height:18px; fill:#fff; }
    .tp-card-head-text .cht { font-family:'Rajdhani',sans-serif; font-size:15px; font-weight:700; color:var(--mz-text); }
    .tp-card-head-text .chs { font-size:11px; color:var(--mz-muted); margin-top:1px; }

    /* ── form body ── */
    .tp-form-body { padding: 28px; }
    .tp-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .col-full { grid-column: 1 / -1; }

    /* ── section label ── */
    .tp-section-label {
        font-size:10px; font-weight:700; letter-spacing:1px;
        text-transform:uppercase; color:var(--mz-teal);
        margin-bottom:16px; display:flex; align-items:center; gap:8px;
    }
    .tp-section-label::after { content:''; flex:1; height:1px; background:var(--mz-border); }

    .tp-sep { margin:24px 0; border:none; border-top:1px solid var(--mz-border); }

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
    .mz-input-wrap:focus-within .mz-input-icon { fill:var(--mz-teal); }

    .mz-input, .mz-select {
        width:100%; background:var(--mz-bg); border:1px solid var(--mz-border);
        border-radius:6px; padding:11px 14px 11px 38px;
        color:var(--mz-text); outline:none;
        font-family:'Inter',sans-serif; font-size:13px;
        transition:border-color .15s, box-shadow .15s;
    }
    .mz-input::placeholder { color:#3a4059; }
    .mz-input:focus, .mz-select:focus {
        border-color:var(--mz-teal);
        box-shadow:0 0 0 3px rgba(45,212,191,.15);
    }

    /* uppercase for plat */
    .mz-input.uppercase-input { text-transform:uppercase; }

    /* select */
    .mz-select { appearance:none; cursor:pointer; }
    .mz-select-wrap { position:relative; }
    .mz-select-wrap .mz-input-icon { pointer-events:none; }
    .mz-select-caret {
        position:absolute; right:13px; top:50%; transform:translateY(-50%);
        width:14px; height:14px; fill:var(--mz-muted); pointer-events:none;
    }

    .mz-hint { font-size:10px; color:var(--mz-muted); margin-top:2px; }

    /* ── tipe badge preview ── */
    .tipe-preview {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11px; margin-top: 4px; min-height: 16px;
        color: var(--mz-teal); font-weight: 500;
    }
    .tipe-preview svg { width:11px; height:11px; fill:var(--mz-teal); }

    /* ── plat preview ── */
    .plat-preview {
        font-size:11px; color:var(--mz-green);
        margin-top:4px; min-height:16px; font-weight:600; letter-spacing:1px;
    }

    /* ── footer ── */
    .tp-footer {
        padding:18px 28px; border-top:1px solid var(--mz-border);
        display:flex; align-items:center; justify-content:space-between;
        background:var(--mz-surface2);
    }
    .tp-footer-hint { font-size:11px; color:var(--mz-muted); display:flex; align-items:center; gap:6px; }
    .tp-footer-hint svg { width:13px; height:13px; fill:var(--mz-muted); }
    .tp-actions { display:flex; gap:10px; align-items:center; }

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
        background:linear-gradient(135deg, var(--mz-teal2), var(--mz-teal));
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

    /* ── vehicle visual card ── */
    .car-visual {
        grid-column: 1 / -1;
        background: var(--mz-surface2);
        border: 1px solid var(--mz-border);
        border-radius: 8px;
        padding: 14px 18px;
        display: flex; align-items: center; gap: 16px;
        min-height: 62px;
    }
    .car-visual-icon { width:36px; height:36px; fill:var(--mz-muted); flex-shrink:0; }
    .car-visual-text .cv-plat { font-family:'Rajdhani',sans-serif; font-size:17px; font-weight:700; letter-spacing:2px; color:var(--mz-text); }
    .car-visual-text .cv-model { font-size:11px; color:var(--mz-muted); margin-top:1px; }
    .car-visual-text .cv-empty { font-size:12px; color:#3a4059; font-style:italic; }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="tp-wrap">

    {{-- ── Header ── --}}
    <div class="tp-header">
        <div class="tp-breadcrumb">
            <a href="{{ route('pelanggan.index') }}">Pelanggan</a>
            <span>/</span>
            <span style="color:var(--mz-text)">Tambah</span>
        </div>
        <div class="tp-title">Tambah Pelanggan</div>
        <div class="tp-subtitle">Masukkan data pelanggan dan kendaraan dengan lengkap</div>
    </div>

    {{-- ── Card ── --}}
    <div class="tp-card">
        <div class="tp-card-bar"></div>

        <div class="tp-card-head">
            <div class="tp-card-icon">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div class="tp-card-head-text">
                <div class="cht">Data Pelanggan Baru</div>
                <div class="chs">Isi data identitas dan kendaraan pelanggan</div>
            </div>
        </div>

        <form method="POST" action="{{ route('pelanggan.store') }}">
            @csrf

            <div class="tp-form-body">

                {{-- ── Identitas ── --}}
                <div class="tp-section-label">Identitas Pelanggan</div>

                <div class="tp-grid">

                    {{-- Nama --}}
                    <div class="mz-field col-full">
                        <label class="mz-label">Nama Pelanggan / Perusahaan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            <input name="nama" required value="{{ old('nama') }}"
                                   placeholder="Contoh: Andi Pratama / PT Maju Jaya"
                                   class="mz-input">
                        </div>
                    </div>

                    {{-- No HP --}}
                    <div class="mz-field">
                        <label class="mz-label">No. HP</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            <input name="no_hp" value="{{ old('no_hp') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="mz-input">
                        </div>
                    </div>

                    {{-- Tipe --}}
                    <div class="mz-field">
                        <label class="mz-label">Tipe Pelanggan</label>
                        <div class="mz-select-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                            <select name="tipe" class="mz-select" id="tipeSelect" onchange="updateTipePreview()">
                                <option value="pribadi"    {{ old('tipe') == 'pribadi'    ? 'selected' : '' }}>Pribadi</option>
                                <option value="perusahaan" {{ old('tipe') == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                            </select>
                            <svg class="mz-select-caret" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                        </div>
                        <div class="tipe-preview" id="tipePreview">
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            Pelanggan Pribadi
                        </div>
                    </div>

                </div>

                <hr class="tp-sep">

                {{-- ── Kendaraan ── --}}
                <div class="tp-section-label">Data Kendaraan</div>

                <div class="tp-grid">

                    {{-- Plat Nomor --}}
                    <div class="mz-field">
                        <label class="mz-label">Plat Nomor</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-7 11h-2v-2h2v2zm0-4h-2V7h2v4z"/></svg>
                            <input name="plat_nomor" required value="{{ old('plat_nomor') }}"
                                   placeholder="B 1234 ABC"
                                   class="mz-input uppercase-input"
                                   id="platInput"
                                   oninput="updateCarVisual()">
                        </div>
                        <div class="plat-preview" id="platPreview"></div>
                    </div>

                    {{-- Merk --}}
                    <div class="mz-field">
                        <label class="mz-label">Merk Mobil</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                            <input name="merk_mobil" required value="{{ old('merk_mobil') }}"
                                   placeholder="Toyota, Honda, Mitsubishi"
                                   class="mz-input"
                                   id="merkInput"
                                   oninput="updateCarVisual()">
                        </div>
                    </div>

                    {{-- Model --}}
                    <div class="mz-field">
                        <label class="mz-label">Model Mobil</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                            <input name="model_mobil" required value="{{ old('model_mobil') }}"
                                   placeholder="Avanza, Pajero, Brio"
                                   class="mz-input"
                                   id="modelInput"
                                   oninput="updateCarVisual()">
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
                            <input name="tahun_mobil" value="{{ old('tahun_mobil') }}"
                                   placeholder="2020"
                                   class="mz-input"
                                   id="tahunInput"
                                   oninput="updateCarVisual()">
                        </div>
                        <div class="mz-hint">Kosongkan jika tidak diketahui</div>
                    </div>

                    {{-- Car Visual Preview --}}
                    <div class="car-visual" id="carVisual">
                        <svg class="car-visual-icon" viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                        <div class="car-visual-text">
                            <div class="cv-plat" id="cvPlat" style="display:none"></div>
                            <div class="cv-model" id="cvModel" style="display:none"></div>
                            <div class="cv-empty" id="cvEmpty">Isi plat dan merk/model untuk preview kendaraan</div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="tp-footer">
                <div class="tp-footer-hint">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Data pelanggan tersimpan langsung ke database
                </div>
                <div class="tp-actions">
                    <a href="{{ route('pelanggan.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Data</button>
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

function updateTipePreview() {
    const val = document.getElementById('tipeSelect').value;
    const el  = document.getElementById('tipePreview');
    if (val === 'perusahaan') {
        el.innerHTML = `<svg viewBox="0 0 24 24" style="width:11px;height:11px;fill:#f5c542"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>`;
        el.innerHTML += ' Pelanggan Perusahaan';
        el.style.color = '#f5c542';
    } else {
        el.innerHTML = `<svg viewBox="0 0 24 24" style="width:11px;height:11px;fill:#2dd4bf"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>`;
        el.innerHTML += ' Pelanggan Pribadi';
        el.style.color = '#2dd4bf';
    }
}

function updateCarVisual() {
    const plat  = document.getElementById('platInput').value.toUpperCase().trim();
    const merk  = document.getElementById('merkInput').value.trim();
    const model = document.getElementById('modelInput').value.trim();
    const tahun = document.getElementById('tahunInput').value.trim();

    const cvPlat  = document.getElementById('cvPlat');
    const cvModel = document.getElementById('cvModel');
    const cvEmpty = document.getElementById('cvEmpty');

    if (plat || merk || model) {
        cvEmpty.style.display = 'none';
        cvPlat.style.display  = plat  ? 'block' : 'none';
        cvModel.style.display = (merk || model) ? 'block' : 'none';
        cvPlat.textContent  = plat || '';
        cvModel.textContent = [merk, model, tahun ? `(${tahun})` : ''].filter(Boolean).join(' ');
    } else {
        cvPlat.style.display  = 'none';
        cvModel.style.display = 'none';
        cvEmpty.style.display = 'block';
    }
}

// init on load
updateTipePreview();
</script>
@endsection
