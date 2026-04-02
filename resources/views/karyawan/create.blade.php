@extends('dashboard')

@section('title', 'Tambah Karyawan')

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
}
    .tk-wrap {
        font-family: 'Inter', sans-serif;
        color: var(--mz-text);
    }

    /* ── header ── */
    .tk-header     { margin-bottom: 28px; }
    .tk-breadcrumb { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:10px; }
    .tk-breadcrumb a { color:var(--mz-muted); text-decoration:none; transition:color .15s; }
    .tk-breadcrumb a:hover { color:var(--mz-emerald); }
    .tk-breadcrumb span { color:var(--mz-border); }
    .tk-title    { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .tk-subtitle { font-size:12px; color:var(--mz-muted); margin-top:5px; }

    /* ── card ── */
    .tk-card {
        background: var(--mz-surface);
        border: 1px solid var(--mz-border);
        border-radius: 10px; overflow: hidden;
        box-shadow: 0 0 0 1px rgba(16,185,129,.07), 0 20px 48px rgba(0,0,0,.4);
    }
    .tk-card-bar { height:3px; background:linear-gradient(90deg, var(--mz-emerald2), var(--mz-emerald), #6ee7b7); }

    .tk-card-head {
        padding: 18px 28px; border-bottom: 1px solid var(--mz-border);
        display: flex; align-items: center; gap: 12px;
    }
    .tk-card-icon {
        width:36px; height:36px; border-radius:8px;
        background: linear-gradient(135deg, var(--mz-emerald2), var(--mz-emerald));
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .tk-card-icon svg { width:18px; height:18px; fill:#fff; }
    .tk-card-head-text .cht { font-family:'Rajdhani',sans-serif; font-size:15px; font-weight:700; color:var(--mz-text); }
    .tk-card-head-text .chs { font-size:11px; color:var(--mz-muted); margin-top:1px; }

    /* status chip */
    .tk-status-chip {
        margin-left:auto; display:inline-flex; align-items:center; gap:6px;
        background:rgba(62,240,138,.08); border:1px solid rgba(62,240,138,.2);
        border-radius:20px; padding:4px 12px;
        font-size:11px; color:var(--mz-green); font-weight:600;
    }
    .tk-status-dot { width:6px; height:6px; border-radius:50%; background:var(--mz-green); box-shadow:0 0 5px var(--mz-green); }

    /* ── form body ── */
    .tk-form-body { padding: 28px; }
    .tk-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .col-full { grid-column: 1 / -1; }

    /* ── section label ── */
    .tk-section-label {
        font-size:10px; font-weight:700; letter-spacing:1px;
        text-transform:uppercase; color:var(--mz-emerald);
        margin-bottom:16px; display:flex; align-items:center; gap:8px;
    }
    .tk-section-label::after { content:''; flex:1; height:1px; background:var(--mz-border); }

    .tk-sep { margin:24px 0; border:none; border-top:1px solid var(--mz-border); }

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
    .mz-input-wrap:focus-within .mz-input-icon { fill:var(--mz-emerald); }

    .mz-input {
        width:100%; background:var(--mz-bg); border:1px solid var(--mz-border);
        border-radius:6px; padding:11px 14px 11px 38px;
        color:var(--mz-text); outline:none;
        font-family:'Inter',sans-serif; font-size:13px;
        transition:border-color .15s, box-shadow .15s;
    }
    .mz-input::placeholder {
    color: var(--mz-muted);
}
    .mz-input:focus {
        border-color:var(--mz-emerald);
        box-shadow:0 0 0 3px rgba(16,185,129,.15);
    }

    .mz-hint { font-size:10px; color:var(--mz-muted); margin-top:2px; }

    /* ── avatar preview ── */
    .avatar-preview {
        display:flex; align-items:center; gap:16px;
        background:var(--mz-surface2); border:1px solid var(--mz-border);
        border-radius:8px; padding:14px 18px;
        grid-column: 1 / -1;
    }
    .avatar-circle {
        width:46px; height:46px; border-radius:50%;
        background:linear-gradient(135deg, var(--mz-emerald2), var(--mz-emerald));
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
        font-family:'Rajdhani',sans-serif; font-size:20px; font-weight:700; color: #fff;
        transition:background .2s;
    }
    .avatar-info .av-name  { font-family:'Rajdhani',sans-serif; font-size:16px; font-weight:700; color:var(--mz-text); line-height:1; }
    .avatar-info .av-jabatan { font-size:11px; color:var(--mz-muted); margin-top:3px; }
    .avatar-info .av-empty { font-size:12px; color: var(--mz-muted); font-style:italic; }

    /* ── footer ── */
    .tk-footer {
        padding:18px 28px; border-top:1px solid var(--mz-border);
        display:flex; align-items:center; justify-content:space-between;
        background:var(--mz-surface2);
    }
    .tk-footer-hint { font-size:11px; color:var(--mz-muted); display:flex; align-items:center; gap:6px; }
    .tk-footer-hint svg { width:13px; height:13px; fill:var(--mz-muted); }
    .tk-actions { display:flex; gap:10px; align-items:center; }

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
        letter-spacing:.8px; text-transform:uppercase; color:var(--mz-text);
        background:linear-gradient(135deg, var(--mz-emerald2), var(--mz-emerald));
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

<div class="tk-wrap">

    {{-- ── Header ── --}}
    <div class="tk-header">
        <div class="tk-breadcrumb">
            <a href="{{ route('karyawan.index') }}">Karyawan</a>
            <span>/</span>
            <span style="color:var(--mz-text)">Tambah</span>
        </div>
        <div class="tk-title">Tambah Karyawan</div>
        <div class="tk-subtitle">Lengkapi data karyawan dengan benar sebelum menyimpan</div>
    </div>

    {{-- ── Card ── --}}
    <div class="tk-card">
        <div class="tk-card-bar"></div>

        <div class="tk-card-head">
            <div class="tk-card-icon">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div class="tk-card-head-text">
                <div class="cht">Data Karyawan Baru</div>
                <div class="chs">Karyawan akan otomatis berstatus aktif</div>
            </div>
            <div class="tk-status-chip">
                <div class="tk-status-dot"></div>
                Status: Aktif
            </div>
        </div>

        <form method="POST" action="{{ route('karyawan.store') }}">
            @csrf
            <input type="hidden" name="status" value="aktif">

            <div class="tk-form-body">

                {{-- ── Identitas ── --}}
                <div class="tk-section-label">Identitas Karyawan</div>

                <div class="tk-grid">

                    {{-- Avatar Preview --}}
                    <div class="avatar-preview">
                        <div class="avatar-circle" id="avatarCircle">?</div>
                        <div class="avatar-info">
                            <div class="av-name"   id="avName"    style="display:none"></div>
                            <div class="av-jabatan" id="avJabatan" style="display:none"></div>
                            <div class="av-empty"  id="avEmpty">Isi nama untuk melihat preview karyawan</div>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div class="mz-field">
                        <label class="mz-label">Nama Karyawan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            <input type="text" name="nama" required
                                   value="{{ old('nama') }}"
                                   placeholder="Contoh: Budi Santoso"
                                   class="mz-input"
                                   id="namaInput"
                                   oninput="updatePreview()">
                        </div>
                    </div>

                    {{-- Jabatan --}}
                    <div class="mz-field">
                        <label class="mz-label">Jabatan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.36C18 2.06 15.94 0 13.36 0c-1.31 0-2.44.55-3.31 1.41L9 2.5 7.95 1.41C7.08.55 5.95 0 4.64 0 2.06 0 0 2.06 0 4.64c0 .48.1.92.18 1.36H0v2h20c1.1 0 2-.9 2-2s-.9-2-2-2zM4.64 4c-.75 0-1.36-.61-1.36-1.36S3.89 1.28 4.64 1.28s1.36.61 1.36 1.36S5.39 4 4.64 4zm8.72 0c-.75 0-1.36-.61-1.36-1.36s.61-1.36 1.36-1.36 1.36.61 1.36 1.36S14.11 4 13.36 4zM2 22h20v-2H2v2zm0-4h20v-2H2v2zm0-4h20v-2H2v2z"/></svg>
                            <input type="text" name="jabatan"
                                   value="{{ old('jabatan') }}"
                                   placeholder="Contoh: Mekanik, Admin"
                                   class="mz-input"
                                   id="jabatanInput"
                                   oninput="updatePreview()">
                        </div>
                    </div>

                </div>

                <hr class="tk-sep">

                {{-- ── Kontak ── --}}
                <div class="tk-section-label">Informasi Kontak</div>

                <div class="tk-grid">

                    {{-- No HP --}}
                    <div class="mz-field">
                        <label class="mz-label">No. HP</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            <input type="text" name="no_hp"
                                   value="{{ old('no_hp') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="mz-input">
                        </div>
                        <div class="mz-hint">Opsional — untuk keperluan komunikasi</div>
                    </div>

                    {{-- Email --}}
                    <div class="mz-field">
                        <label class="mz-label">Email</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            <input type="email" name="email"
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com"
                                   class="mz-input">
                        </div>
                        <div class="mz-hint">Opsional — untuk notifikasi sistem</div>
                    </div>

                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="tk-footer">
                <div class="tk-footer-hint">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Karyawan baru otomatis berstatus <strong style="color:var(--mz-green);margin-left:3px">Aktif</strong>
                </div>
                <div class="tk-actions">
                    <a href="{{ route('karyawan.index') }}" class="btn-cancel">Batal</a>
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

function updatePreview() {
    const nama    = document.getElementById('namaInput').value.trim();
    const jabatan = document.getElementById('jabatanInput').value.trim();

    const circle   = document.getElementById('avatarCircle');
    const avName   = document.getElementById('avName');
    const avJab    = document.getElementById('avJabatan');
    const avEmpty  = document.getElementById('avEmpty');

    if (nama) {
        const initials = nama.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase();
        circle.textContent   = initials;
        avName.textContent   = nama;
        avName.style.display = 'block';
        avEmpty.style.display = 'none';

        if (jabatan) {
            avJab.textContent   = jabatan;
            avJab.style.display = 'block';
        } else {
            avJab.style.display = 'none';
        }
    } else {
        circle.textContent    = '?';
        avName.style.display  = 'none';
        avJab.style.display   = 'none';
        avEmpty.style.display = 'block';
    }
}
</script>
@endsection
