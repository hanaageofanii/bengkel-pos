@extends('dashboard')

@section('title', 'Tambah Karyawan')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/create-karyawan.css') }}">
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

<script src="{{ asset('assets/js/create-karyawan.js') }}"></script>
@endsection
