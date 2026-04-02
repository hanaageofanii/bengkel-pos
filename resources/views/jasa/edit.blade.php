@extends('dashboard')

@section('title', 'Edit Jasa')

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
        --mz-purple:   #a78bfa;
        --mz-purple2:  #7c3aed;
}


    .tj-wrap {
        font-family: 'Inter', sans-serif;
        color: var(--mz-text);
    }

    /* ── header ── */
    .tj-header     { margin-bottom: 28px; }
    .tj-breadcrumb { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:10px; }
    .tj-breadcrumb a { color:var(--mz-muted); text-decoration:none; transition:color .15s; }
    .tj-breadcrumb a:hover { color:var(--mz-accent); }
    .tj-breadcrumb span { color:var(--mz-border); }
    .tj-title      { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .tj-subtitle   { font-size:12px; color:var(--mz-muted); margin-top:5px; line-height:1.6; }

    /* edit badge */
    .tj-edit-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(245,146,62,.1); border: 1px solid rgba(245,146,62,.25);
        border-radius: 20px; padding: 4px 12px; margin-top: 10px;
        font-size: 11px; color: var(--mz-orange); font-weight: 500;
    }
    .tj-edit-badge svg { width:11px; height:11px; fill:var(--mz-orange); }

    /* ── card ── */
    .tj-card {
        background: var(--mz-surface);
        border: 1px solid var(--mz-border);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 0 1px rgba(245,146,62,.06), 0 20px 48px rgba(0,0,0,.4);
    }

    /* orange-to-purple gradient bar for edit mode jasa */
    .tj-card-bar { height: 3px; background: linear-gradient(90deg, #e05c00, var(--mz-orange), var(--mz-purple)); }

    .tj-card-head {
        padding: 18px 28px;
        border-bottom: 1px solid var(--mz-border);
        display: flex; align-items: center; gap: 12px;
    }

    .tj-card-icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: linear-gradient(135deg, #e05c00, var(--mz-orange));
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .tj-card-icon svg { width: 18px; height: 18px; fill: #fff; }

    .tj-card-head-text .cht { font-family:'Rajdhani',sans-serif; font-size:15px; font-weight:700; color:var(--mz-text); }
    .tj-card-head-text .chs { font-size:11px; color:var(--mz-muted); margin-top:1px; }

    .tj-item-chip {
        margin-left: auto;
        background: var(--mz-surface2);
        border: 1px solid var(--mz-border);
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 12px; color: var(--mz-yellow); font-weight: 500;
        display: flex; align-items: center; gap: 6px;
    }
    .tj-item-chip svg { width:12px; height:12px; fill:var(--mz-yellow); }

    /* ── form body ── */
    .tj-form-body { padding: 28px; }

    .tj-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .col-full { grid-column: 1 / -1; }

    /* ── section label ── */
    .tj-section-label {
        font-size: 10px; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; color: var(--mz-orange);
        margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }
    .tj-section-label::after { content:''; flex:1; height:1px; background: var(--mz-border); }

    .tj-sep { margin: 24px 0; border: none; border-top: 1px solid var(--mz-border); }

    /* ── field ── */
    .mz-field { display:flex; flex-direction:column; gap:6px; }

    .mz-label {
        font-size: 10.5px; font-weight: 600; letter-spacing: .7px;
        text-transform: uppercase; color: var(--mz-muted);
    }

    .mz-input-wrap { position: relative; }

    .mz-input-icon {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        width: 15px; height: 15px; fill: var(--mz-muted); pointer-events: none;
        transition: fill .15s;
    }

    .mz-input, .mz-textarea {
        width: 100%;
        background: var(--mz-bg);
        border: 1px solid var(--mz-border);
        border-radius: 6px;
        color: var(--mz-text);
        outline: none;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        transition: border-color .15s, box-shadow .15s;
        -moz-appearance: textfield;
    }
    .mz-input::-webkit-outer-spin-button,
    .mz-input::-webkit-inner-spin-button { -webkit-appearance: none; }

    .mz-input { padding: 11px 14px 11px 38px; }

    .mz-input::placeholder,
    .mz-textarea::placeholder { color: #3a4059; }

    .mz-input:focus {
        border-color: var(--mz-orange);
        box-shadow: 0 0 0 3px rgba(245,146,62,.15);
    }
    .mz-input-wrap:focus-within .mz-input-icon { fill: var(--mz-orange); }

    .mz-input.is-changed { border-color: rgba(245,197,66,.4); }

    /* textarea */
    .mz-textarea-wrap { position: relative; }
    .mz-textarea-icon {
        position: absolute; left: 13px; top: 14px;
        width: 15px; height: 15px; fill: var(--mz-muted); pointer-events: none;
        transition: fill .15s;
    }
    .mz-textarea-wrap:focus-within .mz-textarea-icon { fill: var(--mz-orange); }
    .mz-textarea {
        padding: 12px 14px 12px 38px;
        resize: vertical; min-height: 100px;
    }
    .mz-textarea:focus {
        border-color: var(--mz-orange);
        box-shadow: 0 0 0 3px rgba(245,146,62,.15);
    }

    .mz-hint { font-size:10px; color:var(--mz-muted); margin-top:2px; }

    /* price preview */
    .price-preview {
        font-size: 11px; color: var(--mz-green); margin-top: 4px;
        min-height: 16px; font-weight: 500; letter-spacing: .3px;
    }

    /* char counter */
    .char-count { font-size:10px; color:var(--mz-muted); margin-top:3px; text-align:right; }

    /* ── footer ── */
    .tj-footer {
        padding: 18px 28px;
        border-top: 1px solid var(--mz-border);
        display: flex; align-items: center; justify-content: space-between;
        background: var(--mz-surface2);
    }
    .tj-footer-hint { font-size:11px; color:var(--mz-muted); display:flex; align-items:center; gap:6px; }
    .tj-footer-hint svg { width:13px; height:13px; fill:var(--mz-muted); }

    .tj-actions { display:flex; gap:10px; align-items:center; }

    .btn-cancel {
        padding: 9px 20px; border-radius: 6px;
        font-size: 12.5px; font-weight: 500; color: var(--mz-muted);
        background: transparent; border: 1px solid var(--mz-border);
        cursor: pointer; text-decoration: none; display: inline-block;
        transition: border-color .15s, color .15s;
    }
    .btn-cancel:hover { border-color: var(--mz-muted); color: var(--mz-text); }

    .btn-submit {
        padding: 9px 28px; border-radius: 6px;
        font-family: 'Rajdhani', sans-serif;
        font-size: 14px; font-weight: 700; letter-spacing: .8px;
        text-transform: uppercase; color: #fff;
        background: linear-gradient(135deg, #e05c00, var(--mz-orange));
        border: none; cursor: pointer;
        transition: opacity .15s, transform .1s;
        position: relative; overflow: hidden;
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

<div class="tj-wrap">

    {{-- ── Header ── --}}
    <div class="tj-header">
        <div class="tj-breadcrumb">
            <a href="{{ route('jasa.index') }}">Jasa Pekerjaan</a>
            <span>/</span>
            <span style="color:var(--mz-text)">Edit</span>
        </div>
        <div class="tj-title">Edit Jasa</div>
        <div class="tj-subtitle">Perbarui informasi jasa pekerjaan, harga pelanggan pribadi dan perusahaan.</div>
        <div class="tj-edit-badge">
            <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            Mode Edit — {{ $jasa->nama }}
        </div>
    </div>

    {{-- ── Card ── --}}
    <div class="tj-card">
        <div class="tj-card-bar"></div>

        <div class="tj-card-head">
            <div class="tj-card-icon">
                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            </div>
            <div class="tj-card-head-text">
                <div class="cht">Perbarui Data Jasa</div>
                <div class="chs">Ubah field yang perlu diperbarui lalu simpan</div>
            </div>
            <div class="tj-item-chip">
                <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
                ID #{{ $jasa->id }}
            </div>
        </div>

        <form method="POST" action="{{ route('jasa.update', $jasa->id) }}">
            @csrf
            @method('PUT')

            <div class="tj-form-body">

                {{-- ── Identitas Jasa ── --}}
                <div class="tj-section-label">Identitas Jasa</div>

                <div class="tj-grid">
                    <div class="mz-field col-full">
                        <label class="mz-label">Nama Jasa</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
                            <input
                                name="nama"
                                required
                                value="{{ old('nama', $jasa->nama) }}"
                                placeholder="Contoh: Servis Ringan, Ganti Oli"
                                class="mz-input"
                                data-original="{{ $jasa->nama }}"
                                oninput="markChanged(this)"
                            >
                        </div>
                    </div>
                </div>

                <hr class="tj-sep">

                {{-- ── Harga ── --}}
                <div class="tj-section-label">Harga</div>

                <div class="tj-grid">
                    <div class="mz-field">
                        <label class="mz-label">Harga Pribadi</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1H8.3c.12 2.19 1.76 3.42 3.7 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                            <input
                                name="harga_pribadi"
                                type="number"
                                required
                                id="harga_pribadi"
                                value="{{ old('harga_pribadi', $jasa->harga_pribadi) }}"
                                class="mz-input"
                                data-original="{{ $jasa->harga_pribadi }}"
                                oninput="previewHarga('harga_pribadi','prev_pribadi'); markChanged(this)"
                            >
                        </div>
                        <div class="price-preview" id="prev_pribadi">
                            → Rp {{ number_format($jasa->harga_pribadi, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="mz-field">
                        <label class="mz-label">Harga Perusahaan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1H8.3c.12 2.19 1.76 3.42 3.7 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                            <input
                                name="harga_perusahaan"
                                type="number"
                                required
                                id="harga_perusahaan"
                                value="{{ old('harga_perusahaan', $jasa->harga_perusahaan) }}"
                                class="mz-input"
                                data-original="{{ $jasa->harga_perusahaan }}"
                                oninput="previewHarga('harga_perusahaan','prev_perusahaan'); markChanged(this)"
                            >
                        </div>
                        <div class="price-preview" id="prev_perusahaan">
                            → Rp {{ number_format($jasa->harga_perusahaan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <hr class="tj-sep">

                {{-- ── Keterangan ── --}}
                <div class="tj-section-label">Keterangan</div>

                <div class="tj-grid">
                    <div class="mz-field col-full">
                        <label class="mz-label">
                            Keterangan
                            <span style="color:var(--mz-muted);font-weight:400;text-transform:none;letter-spacing:0">(Opsional)</span>
                        </label>
                        <div class="mz-textarea-wrap">
                            <svg class="mz-textarea-icon" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                            <textarea
                                name="keterangan"
                                rows="4"
                                placeholder="Catatan tambahan tentang jasa, contoh: estimasi waktu pengerjaan"
                                class="mz-textarea"
                                id="keterangan"
                                data-original="{{ $jasa->keterangan }}"
                                oninput="countChars(); markChanged(this)"
                            >{{ old('keterangan', $jasa->keterangan) }}</textarea>
                        </div>
                        <div class="char-count" id="charCount">0 karakter</div>
                    </div>
                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="tj-footer">
                <div class="tj-footer-hint">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Perubahan akan langsung tersimpan ke database
                </div>
                <div class="tj-actions">
                    <a href="{{ route('jasa.index') }}" class="btn-cancel">Batal</a>
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

function previewHarga(inputId, previewId) {
    const val = parseInt(document.getElementById(inputId).value);
    const el  = document.getElementById(previewId);
    if (!isNaN(val) && val > 0) {
        el.textContent = '→ Rp ' + val.toLocaleString('id-ID');
    } else {
        el.textContent = '';
    }
}

function markChanged(input) {
    const original = input.dataset.original ?? '';
    input.classList.toggle('is-changed', input.value !== original);
}

function countChars() {
    const len = document.getElementById('keterangan').value.length;
    document.getElementById('charCount').textContent = len + ' karakter';
}

// Init char count on load
document.addEventListener('DOMContentLoaded', function () {
    countChars();
});
</script>
@endsection
