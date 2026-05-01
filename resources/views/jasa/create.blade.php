@extends('dashboard')

@section('title', 'Tambah Jasa')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/create-jasa.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="tj-wrap">

    {{-- ── Header ── --}}
    <div class="tj-header">
        <div class="tj-breadcrumb">
            <a href="{{ route('jasa.index') }}">Jasa Pekerjaan</a>
            <span>/</span>
            <span style="color:var(--mz-text)">Tambah</span>
        </div>
        <div class="tj-title">Tambah Jasa</div>
        <div class="tj-subtitle">Tambahkan data jasa pekerjaan beserta harga modal</div>
    </div>

    {{-- ── Card ── --}}
    <div class="tj-card">
        <div class="tj-card-bar"></div>

        <div class="tj-card-head">
            <div class="tj-card-icon">
                <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
            </div>
            <div class="tj-card-head-text">
                <div class="cht">Data Jasa Baru</div>
                <div class="chs">Isi semua field yang diperlukan</div>
            </div>
        </div>

        <form method="POST" action="{{ route('jasa.store') }}">
            @csrf

            <div class="tj-form-body">

                <div class="tj-tip">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Pastikan nama jasa jelas dan harga sudah sesuai tarif terbaru sebelum disimpan.
                </div>

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
                                value="{{ old('nama') }}"
                                placeholder="Contoh: Servis Ringan, Ganti Oli"
                                class="mz-input"
                            >
                        </div>
                    </div>
                </div>

                <hr class="tj-sep">

                {{-- ── Harga ── --}}
                <div class="tj-section-label">Harga</div>

                <div class="tj-grid">
                    <div class="mz-field col-full">
                        <label class="mz-label">Harga Modal</label>
                        <div class="mz-input-wrap">
<svg class="mz-input-icon" viewBox="0 0 24 24">
    <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
</svg>                            <input type="hidden" name="harga_pribadi" id="harga_pribadi_val">
                            <input
                                type="text"
                                id="harga_pribadi"
                                value="{{ old('harga_pribadi') ? 'Rp. ' . number_format(old('harga_pribadi'),0,',','.') : '' }}"
                                placeholder="Rp. 150.000"
                                class="mz-input"
                                oninput="formatRupiah(this, 'harga_pribadi_val', 'prev_pribadi')"
                            >
                        </div>
                        <div class="price-preview" id="prev_pribadi"></div>
                    </div>

                    {{-- <div class="mz-field">
                        <label class="mz-label">Harga Perusahaan</label>
                        <div class="mz-input-wrap">
<svg class="mz-input-icon" viewBox="0 0 24 24">
    <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
</svg>                            <input type="hidden" name="harga_perusahaan" id="harga_perusahaan_val">
                            <input
                                type="text"
                                id="harga_perusahaan"
                                value="{{ old('harga_perusahaan') ? 'Rp. ' . number_format(old('harga_perusahaan'),0,',','.') : '' }}"
                                placeholder="Rp. 120.000"
                                class="mz-input"
                                oninput="formatRupiah(this, 'harga_perusahaan_val', 'prev_perusahaan')"
                            >
                        </div>
                        <div class="price-preview" id="prev_perusahaan"></div>
                    </div> --}}
                </div>

                <hr class="tj-sep">

                {{-- ── Keterangan ── --}}
                <div class="tj-section-label">Keterangan</div>

                <div class="tj-grid">
                    <div class="mz-field col-full">
                        <label class="mz-label">Keterangan <span style="color:var(--mz-muted);font-weight:400;text-transform:none;letter-spacing:0">(Opsional)</span></label>
                        <div class="mz-textarea-wrap">
                            <svg class="mz-textarea-icon" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                            <textarea
                                name="keterangan"
                                rows="4"
                                placeholder="Catatan tambahan tentang jasa, contoh: estimasi waktu pengerjaan"
                                class="mz-textarea"
                                id="keterangan"
                                oninput="countChars()"
                            >{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="char-count" id="charCount">0 karakter</div>
                    </div>
                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="tj-footer">
                <div class="tj-footer-hint">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Data jasa tersimpan langsung ke database
                </div>
                <div class="tj-actions">
                    <a href="{{ route('jasa.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Jasa</button>
                </div>
            </div>

        </form>
    </div>
</div>
<script src="{{ asset('assets/js/create-jasa.js') }}"></script>
@endsection
