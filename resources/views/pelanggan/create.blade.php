@extends('dashboard')

@section('title', 'Tambah Pelanggan')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/create-pelanggan.css') }}">
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

<script src="{{ asset('assets/js/create-pelanggan.js') }}"></script>

@endsection
