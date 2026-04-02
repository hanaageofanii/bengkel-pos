@extends('dashboard')

@section('title', 'Edit Karyawan')

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
    --mz-emerald:  #10b981;
    --mz-emerald2: #059669;
    --mz-accent:   #4f8ef7;
        --mz-accent2:  #1e90ff;
        --mz-muted:    #6b7694;
        --mz-green:    #3ef08a;
        --mz-red:      #f26c6c;
        --mz-orange:   #f5c542;
}
    .ek-wrap {
        font-family: 'Inter', sans-serif;
        color: var(--mz-text);
    }

    /* ── header ── */
    .ek-header     { margin-bottom: 28px; }
    .ek-breadcrumb { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:10px; }
    .ek-breadcrumb a { color:var(--mz-muted); text-decoration:none; transition:color .15s; }
    .ek-breadcrumb a:hover { color:var(--mz-emerald); }
    .ek-breadcrumb span { color:var(--mz-border); }
    .ek-title    { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .ek-subtitle { font-size:12px; color:var(--mz-muted); margin-top:5px; }

    .ek-edit-badge {
        display:inline-flex; align-items:center; gap:6px;
        background:rgba(245,146,62,.1); border:1px solid rgba(245,146,62,.25);
        border-radius:20px; padding:4px 12px; margin-top:10px;
        font-size:11px; color:var(--mz-orange); font-weight:500;
    }
    .ek-edit-badge svg { width:11px; height:11px; fill:var(--mz-orange); }

    /* ── card ── */
    .ek-card {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px; overflow:hidden;
        box-shadow:0 0 0 1px rgba(245,146,62,.06), 0 20px 48px rgba(0,0,0,.4);
    }
    .ek-card-bar { height:3px; background:linear-gradient(90deg, #e05c00, var(--mz-orange), var(--mz-emerald)); }

    .ek-card-head {
        padding:18px 28px; border-bottom:1px solid var(--mz-border);
        display:flex; align-items:center; gap:12px;
    }
    .ek-card-icon {
        width:36px; height:36px; border-radius:8px;
        background:linear-gradient(135deg, #e05c00, var(--mz-orange));
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .ek-card-icon svg { width:18px; height:18px; fill:#fff; }
    .ek-card-head-text .cht { font-family:'Rajdhani',sans-serif; font-size:15px; font-weight:700; color:var(--mz-text); }
    .ek-card-head-text .chs { font-size:11px; color:var(--mz-muted); margin-top:1px; }

    /* status chip dynamic */
    .ek-status-chip {
        margin-left:auto; display:inline-flex; align-items:center; gap:6px;
        border-radius:20px; padding:4px 12px;
        font-size:11px; font-weight:600;
        border:1px solid; transition:all .2s;
    }
    .ek-status-dot { width:6px; height:6px; border-radius:50%; }

    .chip-aktif    { background:rgba(62,240,138,.08); border-color:rgba(62,240,138,.2);  color:#3ef08a; }
    .chip-aktif .ek-status-dot { background:#3ef08a; box-shadow:0 0 5px #3ef08a; }
    .chip-cuti     { background:rgba(245,197,66,.08); border-color:rgba(245,197,66,.2);  color:#f5c542; }
    .chip-cuti .ek-status-dot { background:#f5c542; }
    .chip-resign   { background:rgba(242,108,108,.08); border-color:rgba(242,108,108,.2); color:#f26c6c; }
    .chip-resign .ek-status-dot { background:#f26c6c; }
    .chip-nonaktif { background:rgba(107,118,148,.08); border-color:rgba(107,118,148,.2); color:#6b7694; }
    .chip-nonaktif .ek-status-dot { background:#6b7694; }

    /* ── form body ── */
    .ek-form-body { padding:28px; }
    .ek-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .col-full { grid-column:1/-1; }

    /* ── section label ── */
    .ek-section-label {
        font-size:10px; font-weight:700; letter-spacing:1px;
        text-transform:uppercase; color:var(--mz-orange);
        margin-bottom:16px; display:flex; align-items:center; gap:8px;
    }
    .ek-section-label::after { content:''; flex:1; height:1px; background:var(--mz-border); }

    .ek-sep { margin:24px 0; border:none; border-top:1px solid var(--mz-border); }

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

    /* select */
    .mz-select { appearance:none; cursor:pointer; }
    .mz-select-wrap { position:relative; }
    .mz-select-caret {
        position:absolute; right:13px; top:50%; transform:translateY(-50%);
        width:14px; height:14px; fill:var(--mz-muted); pointer-events:none;
    }

    .mz-hint { font-size:10px; color:var(--mz-muted); margin-top:2px; }

    /* ── avatar preview ── */
    .avatar-preview {
        display:flex; align-items:center; gap:16px;
        background:var(--mz-surface2); border:1px solid var(--mz-border);
        border-radius:8px; padding:14px 18px; grid-column:1/-1;
    }
    .avatar-circle {
        width:46px; height:46px; border-radius:50%;
        background:linear-gradient(135deg, #e05c00, var(--mz-orange));
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
        font-family:'Rajdhani',sans-serif; font-size:20px; font-weight:700; color:#fff;
    }
    .avatar-info .av-name    { font-family:'Rajdhani',sans-serif; font-size:16px; font-weight:700; color:var(--mz-text); line-height:1; }
    .avatar-info .av-jabatan { font-size:11px; color:var(--mz-muted); margin-top:3px; }

    /* status select options color */
    .status-option-row {
        display:grid; grid-template-columns:1fr 1fr; gap:10px; grid-column:1/-1;
    }
    .status-btn-group {
        display:flex; gap:8px; flex-wrap:wrap; grid-column:1/-1;
    }
    .status-radio-btn {
        flex:1; min-width:100px;
        display:flex; align-items:center; justify-content:center; gap:6px;
        padding:9px 12px; border-radius:6px; border:1px solid var(--mz-border);
        background:var(--mz-bg); cursor:pointer;
        font-size:12px; font-weight:600; color:var(--mz-muted);
        transition:all .15s; position:relative;
    }
    .status-radio-btn input[type="radio"] { position:absolute; opacity:0; width:0; height:0; }
    .status-radio-btn svg { width:12px; height:12px; flex-shrink:0; }

    .status-radio-btn.s-aktif:has(input:checked)    { border-color:rgba(62,240,138,.4);  background:rgba(62,240,138,.08);  color:#3ef08a; }
    .status-radio-btn.s-cuti:has(input:checked)     { border-color:rgba(245,197,66,.4);  background:rgba(245,197,66,.08);  color:#f5c542; }
    .status-radio-btn.s-resign:has(input:checked)   { border-color:rgba(242,108,108,.4); background:rgba(242,108,108,.08); color:#f26c6c; }
    .status-radio-btn.s-nonaktif:has(input:checked) { border-color:rgba(107,118,148,.4); background:rgba(107,118,148,.08); color:#9aa5be; }

    /* ── footer ── */
    .ek-footer {
        padding:18px 28px; border-top:1px solid var(--mz-border);
        display:flex; align-items:center; justify-content:space-between;
        background:var(--mz-surface2);
    }
    .ek-footer-hint { font-size:11px; color:var(--mz-muted); display:flex; align-items:center; gap:6px; }
    .ek-footer-hint svg { width:13px; height:13px; fill:var(--mz-muted); }
    .ek-actions { display:flex; gap:10px; align-items:center; }

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
        background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent);
        transform:translateX(-100%); transition:transform .45s;
    }
    .btn-submit:hover::after { transform:translateX(100%); }
    .btn-submit:hover  { opacity:.9; }
    .btn-submit:active { transform:scale(.98); }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="ek-wrap">

    {{-- ── Header ── --}}
    <div class="ek-header">
        <div class="ek-breadcrumb">
            <a href="{{ route('karyawan.index') }}">Karyawan</a>
            <span>/</span>
            <span style="color:var(--mz-text)">Edit</span>
        </div>
        <div class="ek-title">Edit Karyawan</div>
        <div class="ek-subtitle">Perbarui dan sesuaikan informasi karyawan</div>
        <div class="ek-edit-badge">
            <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            Mode Edit — {{ $karyawan->nama }}
        </div>
    </div>

    {{-- ── Card ── --}}
    <div class="ek-card">
        <div class="ek-card-bar"></div>

        <div class="ek-card-head">
            <div class="ek-card-icon">
                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            </div>
            <div class="ek-card-head-text">
                <div class="cht">Perbarui Data Karyawan</div>
                <div class="chs">Ubah field yang diperlukan lalu simpan</div>
            </div>
            <div class="ek-status-chip" id="statusChip">
                <div class="ek-status-dot"></div>
                <span id="statusChipText">—</span>
            </div>
        </div>

        <form method="POST" action="{{ route('karyawan.update', $karyawan->id) }}">
            @csrf
            @method('PUT')

            <div class="ek-form-body">

                {{-- ── Identitas ── --}}
                <div class="ek-section-label">Identitas Karyawan</div>

                <div class="ek-grid">

                    {{-- Avatar preview --}}
                    <div class="avatar-preview">
                        <div class="avatar-circle" id="avatarCircle">
                            {{ strtoupper(substr($karyawan->nama, 0, 1)) }}{{ strtoupper(strpos($karyawan->nama, ' ') !== false ? substr($karyawan->nama, strpos($karyawan->nama, ' ')+1, 1) : '') }}
                        </div>
                        <div class="avatar-info">
                            <div class="av-name"    id="avName">{{ $karyawan->nama }}</div>
                            <div class="av-jabatan" id="avJabatan">{{ $karyawan->jabatan ?? '—' }}</div>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div class="mz-field">
                        <label class="mz-label">Nama Karyawan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            <input type="text" name="nama"
                                   value="{{ old('nama', $karyawan->nama) }}"
                                   placeholder="Masukkan nama karyawan"
                                   class="mz-input"
                                   id="namaInput"
                                   data-original="{{ $karyawan->nama }}"
                                   oninput="updatePreview(); markChanged(this)">
                        </div>
                    </div>

                    {{-- Jabatan --}}
                    <div class="mz-field">
                        <label class="mz-label">Jabatan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.36C18 2.06 15.94 0 13.36 0c-1.31 0-2.44.55-3.31 1.41L9 2.5 7.95 1.41C7.08.55 5.95 0 4.64 0 2.06 0 0 2.06 0 4.64c0 .48.1.92.18 1.36H0v2h20c1.1 0 2-.9 2-2s-.9-2-2-2zM4.64 4c-.75 0-1.36-.61-1.36-1.36S3.89 1.28 4.64 1.28s1.36.61 1.36 1.36S5.39 4 4.64 4zm8.72 0c-.75 0-1.36-.61-1.36-1.36s.61-1.36 1.36-1.36 1.36.61 1.36 1.36S14.11 4 13.36 4zM2 22h20v-2H2v2zm0-4h20v-2H2v2zm0-4h20v-2H2v2z"/></svg>
                            <input type="text" name="jabatan"
                                   value="{{ old('jabatan', $karyawan->jabatan) }}"
                                   placeholder="Contoh: Mekanik, Admin"
                                   class="mz-input"
                                   id="jabatanInput"
                                   data-original="{{ $karyawan->jabatan }}"
                                   oninput="updatePreview(); markChanged(this)">
                        </div>
                    </div>

                </div>

                <hr class="ek-sep">

                {{-- ── Kontak ── --}}
                <div class="ek-section-label">Informasi Kontak</div>

                <div class="ek-grid">
                    {{-- No HP --}}
                    <div class="mz-field">
                        <label class="mz-label">No. HP</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            <input type="text" name="no_hp"
                                   value="{{ old('no_hp', $karyawan->no_hp) }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="mz-input"
                                   data-original="{{ $karyawan->no_hp }}"
                                   oninput="markChanged(this)">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mz-field">
                        <label class="mz-label">Email</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            <input type="email" name="email"
                                   value="{{ old('email', $karyawan->email) }}"
                                   placeholder="nama@email.com"
                                   class="mz-input"
                                   data-original="{{ $karyawan->email }}"
                                   oninput="markChanged(this)">
                        </div>
                    </div>
                </div>

                <hr class="ek-sep">

                {{-- ── Status ── --}}
                <div class="ek-section-label">Status Karyawan</div>

                <div class="ek-grid">
                    <div class="mz-field col-full">
                        <label class="mz-label">Status</label>
                        <div class="mz-select-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                            <select name="status" class="mz-select" id="statusSelect" onchange="updateStatusChip()">
                                <option value="aktif"    {{ old('status', $karyawan->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="cuti"     {{ old('status', $karyawan->status) === 'cuti'     ? 'selected' : '' }}>Cuti</option>
                                <option value="resign"   {{ old('status', $karyawan->status) === 'resign'   ? 'selected' : '' }}>Resign</option>
                                <option value="nonaktif" {{ old('status', $karyawan->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            <svg class="mz-select-caret" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                        </div>

                        {{-- Status info row --}}
                        <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
                            <div style="display:flex;align-items:center;gap:5px;font-size:11px;padding:4px 10px;border-radius:5px;background:rgba(62,240,138,.08);border:1px solid rgba(62,240,138,.2);color:#3ef08a;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#3ef08a;box-shadow:0 0 4px #3ef08a;display:inline-block"></span> Aktif — bisa absen
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;font-size:11px;padding:4px 10px;border-radius:5px;background:rgba(245,197,66,.08);border:1px solid rgba(245,197,66,.2);color:#f5c542;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#f5c542;display:inline-block"></span> Cuti — tidak bisa absen
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;font-size:11px;padding:4px 10px;border-radius:5px;background:rgba(242,108,108,.08);border:1px solid rgba(242,108,108,.2);color:#f26c6c;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#f26c6c;display:inline-block"></span> Resign / Nonaktif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="ek-footer">
                <div class="ek-footer-hint">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Perubahan akan langsung tersimpan ke database
                </div>
                <div class="ek-actions">
                    <a href="{{ route('karyawan.index') }}" class="btn-cancel">Batal</a>
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

function updatePreview() {
    const nama    = document.getElementById('namaInput').value.trim();
    const jabatan = document.getElementById('jabatanInput').value.trim();
    const circle  = document.getElementById('avatarCircle');
    const avName  = document.getElementById('avName');
    const avJab   = document.getElementById('avJabatan');

    if (nama) {
        const initials = nama.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase();
        circle.textContent  = initials;
        avName.textContent  = nama;
        avJab.textContent   = jabatan || '—';
    }
}

const statusConfig = {
    aktif:    { cls:'chip-aktif',    label:'Aktif' },
    cuti:     { cls:'chip-cuti',     label:'Cuti' },
    resign:   { cls:'chip-resign',   label:'Resign' },
    nonaktif: { cls:'chip-nonaktif', label:'Nonaktif' },
};

function updateStatusChip() {
    const val  = document.getElementById('statusSelect').value;
    const chip = document.getElementById('statusChip');
    const text = document.getElementById('statusChipText');
    const cfg  = statusConfig[val] || statusConfig.aktif;

    chip.className = 'ek-status-chip ' + cfg.cls;
    text.textContent = cfg.label;
}

document.addEventListener('DOMContentLoaded', function () {
    updateStatusChip();
    updatePreview();
});
</script>
@endsection
