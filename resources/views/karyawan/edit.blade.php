@extends('dashboard')

@section('title', 'Edit Karyawan')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/edit-karyawan.css') }}">
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
            <svg viewBox="0 0 24 24">
                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
            </svg>
            Mode Edit — {{ $karyawan->nama }}
        </div>
    </div>

    {{-- ── Card ── --}}
    <div class="ek-card">
        <div class="ek-card-bar"></div>

        <div class="ek-card-head">
            <div class="ek-card-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
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
                            <div class="av-name" id="avName">{{ $karyawan->nama }}</div>
                            <div class="av-jabatan" id="avJabatan">{{ $karyawan->jabatan ?? '—' }}</div>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div class="mz-field">
                        <label class="mz-label">Nama Karyawan</label>
                        <div class="mz-input-wrap">
                            <svg class="mz-input-icon" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
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
                            <svg class="mz-input-icon" viewBox="0 0 24 24">
                                <path d="M20 6h-2.18c.07-.44.18-.88.18-1.36C18 2.06 15.94 0 13.36 0c-1.31 0-2.44.55-3.31 1.41L9 2.5 7.95 1.41C7.08.55 5.95 0 4.64 0 2.06 0 0 2.06 0 4.64c0 .48.1.92.18 1.36H0v2h20c1.1 0 2-.9 2-2s-.9-2-2-2zM4.64 4c-.75 0-1.36-.61-1.36-1.36S3.89 1.28 4.64 1.28s1.36.61 1.36 1.36S5.39 4 4.64 4zm8.72 0c-.75 0-1.36-.61-1.36-1.36s.61-1.36 1.36-1.36 1.36.61 1.36 1.36S14.11 4 13.36 4zM2 22h20v-2H2v2zm0-4h20v-2H2v2zm0-4h20v-2H2v2z"/>
                            </svg>
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

                {{-- ── Salary ── --}}
                <div class="ek-section-label">Informasi Gaji</div>

                <div class="ek-grid">
    <div class="mz-field col-full">
        <label class="mz-label">Salary</label>
        <div class="mz-input-wrap" style="position:relative;">
            <svg class="mz-input-icon" viewBox="0 0 24 24">
                <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
            </svg>
            <span style="position:absolute;left:2.4rem;top:50%;transform:translateY(-50%);color:#6b7280;font-size:.875rem;pointer-events:none;user-select:none;">Rp</span>
            <input type="text"
                   value="{{ old('salary', $karyawan->salary) ? number_format((int) old('salary', $karyawan->salary), 0, ',', '.') : '' }}"
                   placeholder="3.000.000"
                   class="mz-input"
                   style="padding-left:3.5rem;"
                   data-original="{{ $karyawan->salary }}"
                   oninput="markChanged(this);var r=this.value.replace(/\D/g,'');this.value=r?r.replace(/\B(?=(\d{3})+(?!\d))/g,'.'):'';document.getElementById('salary_raw').value=r;">
            {{-- hidden input ini yang dikirim ke controller --}}
            <input type="hidden" id="salary_raw" name="salary"
                   value="{{ old('salary', $karyawan->salary) }}">
        </div>
        <div class="mz-hint">Opsional — nominal gaji dalam Rupiah</div>
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
                            <svg class="mz-input-icon" viewBox="0 0 24 24">
                                <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
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
                            <svg class="mz-input-icon" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
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
                            <svg class="mz-input-icon" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                            </svg>
                            <select name="status" class="mz-select" id="statusSelect" onchange="updateStatusChip()">
                                <option value="aktif"    {{ old('status', $karyawan->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="cuti"     {{ old('status', $karyawan->status) === 'cuti'     ? 'selected' : '' }}>Cuti</option>
                                <option value="resign"   {{ old('status', $karyawan->status) === 'resign'   ? 'selected' : '' }}>Resign</option>
                                <option value="nonaktif" {{ old('status', $karyawan->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            <svg class="mz-select-caret" viewBox="0 0 24 24">
                                <path d="M7 10l5 5 5-5z"/>
                            </svg>
                        </div>

                        {{-- Status info row --}}
                        <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
                            <div style="display:flex;align-items:center;gap:5px;font-size:11px;padding:4px 10px;border-radius:5px;background:rgba(62,240,138,.08);border:1px solid rgba(62,240,138,.2);color:#3ef08a;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#3ef08a;box-shadow:0 0 4px #3ef08a;display:inline-block"></span>
                                Aktif — bisa absen
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;font-size:11px;padding:4px 10px;border-radius:5px;background:rgba(245,197,66,.08);border:1px solid rgba(245,197,66,.2);color:#f5c542;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#f5c542;display:inline-block"></span>
                                Cuti — tidak bisa absen
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;font-size:11px;padding:4px 10px;border-radius:5px;background:rgba(242,108,108,.08);border:1px solid rgba(242,108,108,.2);color:#f26c6c;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#f26c6c;display:inline-block"></span>
                                Resign / Nonaktif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="ek-footer">
                <div class="ek-footer-hint">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
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

<script src="{{ asset('assets/js/edit-karyawan.js') }}"></script>
@endsection