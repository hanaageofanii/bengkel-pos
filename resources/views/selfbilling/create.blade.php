@extends('dashboard')

@section('title', 'Tambah Self Billing')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/create-invoice.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="inv-wrap">

    {{-- Breadcrumb --}}
    <div class="inv-breadcrumb">
        <a href="{{ route('selfbilling.index') }}">Self Billing</a>
        <span>/</span>
        <span style="color:var(--mz-text)">Buat</span>
    </div>

    {{-- Header --}}
    <div class="inv-title">Buat Self Billing</div>
    <div class="inv-subtitle">Input tagihan catatan dari vendor barang/jasa</div>

    <form action="{{ route('selfbilling.store') }}" method="POST">
        @csrf
        <div class="inv-card">
            <div class="inv-card-bar"></div>

            {{-- ── Detail Transaksi Vendor ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        Detail Transaksi Vendor
                    </div>
                </div>

                <div class="inv-grid">
                    <div class="mz-field">
                        <label class="mz-label">Tanggal</label>
                        <input type="date" name="tanggal" class="mz-input" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">Nama Vendor</label>
                        <input type="text" name="nama_vendor" class="mz-input" placeholder="Masukkan nama PT atau Toko..." required>
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">Jenis Barang/Jasa</label>
                        <input type="text" name="jenis_barang" class="mz-input" placeholder="Contoh: Oli Mesin, Ban Luar..." required>
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">Jumlah Unit</label>
                        <input type="number" name="jumlah_barang" class="mz-input" placeholder="0" required>
                    </div>
                </div>
            </div>

            {{-- ── Nominal Tagihan ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                        Nominal Tagihan
                    </div>
                </div>
                <div class="mz-field">
                    <label class="mz-label">Total Tagihan (Rp)</label>
                    <input type="number" name="total_tagihan" id="input-harga" class="mz-input"
                           style="font-size: 1.5rem; font-weight: 700; color: var(--mz-accent); text-align: left;"
                           placeholder="0" required>
                </div>
            </div>

            {{-- ── Catatan ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        Catatan Tambahan
                    </div>
                </div>
                <div class="mz-field">
                    <textarea name="payment_notes" rows="3" class="mz-textarea" placeholder="Tulis catatan pembayaran atau info lainnya..."></textarea>
                </div>
            </div>

            {{-- Summary Bar
            <div class="inv-summary">
                <div class="sum-item">
                    <div class="sum-label">TOTAL AKUMULASI</div>
                    <div class="sum-val grand" id="total-preview">Rp 0</div>
                </div>
            </div> --}}

            {{-- Footer Buttons --}}
            <div class="inv-footer">
                <a href="{{ route('selfbilling.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan Tagihan</button>
            </div>

        </div>
    </form>
</div>

<script>
    // Script tetap sama untuk update preview rupiah secara live
    document.getElementById('input-harga').addEventListener('input', function(e) {
        const val = e.target.value;
        document.getElementById('total-preview').innerText = val ?
            new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val) : 'Rp 0';
    });
</script>

@endsection
