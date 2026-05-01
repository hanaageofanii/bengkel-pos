@extends('dashboard')

@section('title', 'Edit Barang')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/edit-barang.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="tb-wrap">

    {{-- ── Header ── --}}
    <div class="tb-header">
        <div class="tb-breadcrumb">
            <a href="{{ route('barang.index') }}">Barang</a>
            <span>/</span>
            <span style="color:var(--mz-text)">Edit</span>
        </div>
        <div class="tb-title">Edit Barang</div>
        <div class="tb-subtitle">Perbarui data barang, harga, dan stok</div>
        <div class="tb-edit-badge">
            <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            Mode Edit — {{ $barang->nama }}
        </div>
    </div>

    {{-- ── Card ── --}}
    <div class="tb-card">
        <div class="tb-card-bar"></div>

        <div class="tb-card-head">
            <div class="tb-card-icon">
                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            </div>
            <div class="tb-card-head-text">
                <div class="cht">Perbarui Data Barang</div>
                <div class="chs">Ubah field yang perlu diperbarui lalu simpan</div>
            </div>
            <div class="tb-item-chip">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15v-4H7l5-8v4h4l-5 8z"/></svg>
                ID #{{ $barang->id }}
            </div>
        </div>

        <form method="POST" action="{{ route('barang.update', $barang->id) }}">
            @csrf
            @method('PUT')

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
                                value="{{ old('nama', $barang->nama) }}"
                                placeholder="Contoh: Oli Mesin, Kampas Rem"
                                class="mz-input"
                                data-original="{{ $barang->nama }}"
                                oninput="markChanged(this)"
                            >
                        </div>
                    </div>
                </div>

                <hr class="tb-sep">

                {{-- ── Harga ── --}}
                <div class="tb-section-label">Harga</div>

                <div class="tb-grid">
                    {{-- Harga Pribadi --}}
                    <div class="mz-field col-full">
                        <label class="mz-label">Harga Modal</label>
                        <div class="mz-input-wrap">
                        <svg class="mz-input-icon" viewBox="0 0 24 24">
                            <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
                        </svg>
                        <input type="hidden" name="harga_pribadi" id="harga_pribadi_val" value="{{ old('harga_pribadi', $barang->harga_pribadi) }}">
                            <input
                                type="text"
                                id="harga_pribadi"
                                value="Rp. {{ number_format(old('harga_pribadi', $barang->harga_pribadi), 0, ',', '.') }}"
                                class="mz-input"
                                data-original="{{ $barang->harga_pribadi }}"
                                oninput="formatRupiah(this, 'harga_pribadi_val', 'prev_pribadi'); markChanged(this)"
                            >

                        </div>
                        <div class="price-preview" id="prev_pribadi">
                            → Rp {{ number_format($barang->harga_pribadi, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Harga Perusahaan --}}
                    {{-- <div class="mz-field">
                        <label class="mz-label">Harga Perusahaan</label>
                        <div class="mz-input-wrap">
                        <svg class="mz-input-icon" viewBox="0 0 24 24">
                            <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
                        </svg>
                        <input type="hidden" name="harga_perusahaan" id="harga_perusahaan_val" value="{{ old('harga_perusahaan', $barang->harga_perusahaan) }}">
                            <input
                                type="text"
                                id="harga_perusahaan"
                                value="Rp. {{ number_format(old('harga_perusahaan', $barang->harga_perusahaan), 0, ',', '.') }}"
                                class="mz-input"
                                data-original="{{ $barang->harga_perusahaan }}"
                                oninput="formatRupiah(this, 'harga_perusahaan_val', 'prev_perusahaan'); markChanged(this)"
                            >
                        </div>
                        <div class="price-preview" id="prev_perusahaan">
                            → Rp {{ number_format($barang->harga_perusahaan, 0, ',', '.') }}
                        </div>
                    </div> --}}
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
                                value="{{ old('stok', $barang->stok) }}"
                                class="mz-input"
                                data-original="{{ $barang->stok }}"
                                oninput="markChanged(this)"
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
                                value="{{ old('satuan', $barang->satuan) }}"
                                placeholder="pcs / liter / set"
                                class="mz-input"
                                data-original="{{ $barang->satuan }}"
                                oninput="markChanged(this)"
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
                    Perubahan akan langsung tersimpan ke database
                </div>
                <div class="tb-actions">
                    <a href="{{ route('barang.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </div>

        </form>
    </div>
</div>
<script src="{{ asset('assets/js/edit-barang.js') }}"></script>
@endsection
