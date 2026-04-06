@extends('dashboard')

@section('title', 'Tambah Barang')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/create-barang.css') }}">
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
<script src="{{ asset('assets/js/create-barang.js') }}"></script>
@endsection
