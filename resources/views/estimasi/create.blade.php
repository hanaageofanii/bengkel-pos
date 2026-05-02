@extends('dashboard')

@section('title','Buat Estimasi')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/create-invoice.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="inv-wrap">

    <div class="inv-breadcrumb">
        <a href="{{ route('estimasi.index') }}">Estimasi</a>
        <span>/</span>
        <span style="color:var(--mz-text)">Buat</span>
    </div>
    <div class="inv-title">Buat Estimasi</div>
    <div class="inv-subtitle">Estimasi biaya servis & perbaikan kendaraan</div>

    <form method="POST" action="{{ route('estimasi.store') }}" id="estimasiForm">
        @csrf
        <div class="inv-card">
            <div class="inv-card-bar" style="background: linear-gradient(90deg, #f5c542, #f59e0b, #fbbf24);"></div>

            {{-- ── Pelanggan ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Data Pelanggan & Kendaraan
                    </div>
                </div>
                <div class="inv-grid">
                    <div class="mz-field">
                        <label class="mz-label">Pelanggan</label>
                        <select name="pelanggan_id" id="pelangganSelect" class="mz-select select2">
                            <option value="">Pilih pelanggan</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}"
                                        data-tipe="{{ $p->tipe }}"
                                        data-notelp="{{ $p->no_hp }}"
                                        data-nochasis="{{ $p->no_chasis }}"
                                        data-nomesin="{{ $p->no_mesin }}">
                                    {{ $p->nama }} — {{ strtoupper($p->plat_nomor) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="tipe-text" style="color:var(--mz-teal,#2dd4bf)">
                            Tipe: <span id="tipePelangganText" style="text-transform:uppercase">-</span>
                        </p>
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="mz-input">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">KM</label>
                        <input name="km" class="mz-input" placeholder="Odometer">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">No Telp</label>
                        <input name="no_telp" id="inputNoTelp" class="mz-input" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">No Chasis</label>
                        <input name="no_chasis" id="inputNoChasis" class="mz-input">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">No Mesin</label>
                        <input name="no_mesin" id="inputNoMesin" class="mz-input">
                    </div>
                </div>
            </div>

            {{-- ── Keluhan ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                        Keluhan
                    </div>
                    <button type="button" onclick="addKeluhan()" class="btn-add-row">
                        <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Tambah
                    </button>
                </div>
                <div id="keluhanContainer"></div>
            </div>

            {{-- ── Jasa ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
                        Jasa
                    </div>
                    <button type="button" onclick="addJasa()" class="btn-add-row">
                        <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Tambah
                    </button>
                </div>
                <div id="jasaContainer"></div>
            </div>

            {{-- ── Spare Part ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                        Spare Part
                    </div>
                    <button type="button" onclick="addBarang()" class="btn-add-row">
                        <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Tambah
                    </button>
                </div>
                <div id="barangContainer"></div>
            </div>

            {{-- ── Catatan ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        Catatan Tambahan
                    </div>
                </div>
                <div class="mz-field">
                    <textarea name="notes"
                              class="mz-textarea"
                              rows="3"
                              placeholder="Tambahkan catatan estimasi..."></textarea>
                </div>
            </div>

            {{-- Summary --}}
            <div class="inv-summary">
                <div class="sum-item">
                    <div class="sum-label">Total Jasa</div>
                    <div class="sum-val" id="totalJasa">Rp 0</div>
                </div>
                <div style="color:var(--mz-border);font-size:20px">+</div>
                <div class="sum-item">
                    <div class="sum-label">Total Part</div>
                    <div class="sum-val" id="totalPart">Rp 0</div>
                </div>
                <div style="color:var(--mz-border);font-size:20px">=</div>
                <div class="sum-item">
                    <div class="sum-label">Estimasi Total</div>
                    <div class="sum-val grand" id="grandTotal">Rp 0</div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="inv-footer">
                <a href="{{ route('estimasi.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #d97706, #f5c542);">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// ── Data dari PHP ─────────────────────────────────────────────────────────────
const JASA_OPTIONS   = @json($jasas);
const BARANG_OPTIONS = @json($barangs);
let tipePelanggan    = 'pribadi';

// ── Counter row ───────────────────────────────────────────────────────────────
let jasaIdx   = 0;
let barangIdx = 0;

// ── Format Rupiah ─────────────────────────────────────────────────────────────
function formatRupiah(num) {
    return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
}

// ── Update summary ────────────────────────────────────────────────────────────
function updateSummary() {
    let totalJasa = 0;
    document.querySelectorAll('.jasa-harga-hidden').forEach(el => {
        totalJasa += Number(el.value || 0);
    });

    let totalPart = 0;
    document.querySelectorAll('.barang-row').forEach(row => {
        const harga = Number(row.querySelector('.barang-harga-hidden').value || 0);
        const qty   = Number(row.querySelector('.barang-qty-input').value   || 0);
        totalPart += harga * qty;
    });

    document.getElementById('totalJasa').textContent  = formatRupiah(totalJasa);
    document.getElementById('totalPart').textContent  = formatRupiah(totalPart);
    document.getElementById('grandTotal').textContent = formatRupiah(totalJasa + totalPart);
}

// ── Keluhan ───────────────────────────────────────────────────────────────────
function addKeluhan(value = '') {
    const container = document.getElementById('keluhanContainer');
    const div = document.createElement('div');
    div.className = 'row-item row-keluhan';
    div.style.marginBottom = '10px';
    div.innerHTML = `
        <textarea name="keluhan[]" rows="2" class="mz-textarea" placeholder="Deskripsi keluhan...">${value}</textarea>
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">×</button>
    `;
    container.appendChild(div);
}

// ── Jasa ──────────────────────────────────────────────────────────────────────
function buildJasaOptions(selectedId = '') {
    let opts = `<option value="">— Pilih Jasa —</option>`;
    JASA_OPTIONS.forEach(js => {
        const sel = js.id == selectedId ? 'selected' : '';
        opts += `<option value="${js.id}"
                    data-nama="${js.nama}"
                    data-pribadi="${js.harga_pribadi}"
                    ${sel}>${js.nama}</option>`;
    });
    return opts;
}

function addJasa(data = null) {
    const idx        = jasaIdx++;
    const container  = document.getElementById('jasaContainer');
    const harga      = data?.harga || 0;

    const div = document.createElement('div');
    div.className    = 'row-item row-12 jasa-row';
    div.dataset.idx  = idx;
    div.style.marginBottom = '10px';
    div.innerHTML = `
        <select class="mz-select select2-jasa" id="jasaSelect_${idx}" data-index="${idx}">
            ${buildJasaOptions(data?.id || '')}
        </select>
        <input type="hidden" name="jasa_id[]"    class="jasa-id-hidden"    value="${data?.id    || ''}">
        <input type="hidden" name="jasa_nama[]"  class="jasa-nama-hidden"  value="${data?.nama  || ''}">
        <input type="hidden" name="jasa_harga[]" class="jasa-harga-hidden" value="${harga}">
        <input type="text"
               class="mz-input jasa-harga-display"
               placeholder="Rp. 0"
               style="text-align:right"
               value="${harga ? 'Rp. ' + Number(harga).toLocaleString('id-ID') : ''}">
        <button type="button" class="btn-remove" onclick="this.closest('.jasa-row').remove(); updateSummary()">Hapus</button>
    `;
    container.appendChild(div);

    const display = div.querySelector('.jasa-harga-display');
    const hidden  = div.querySelector('.jasa-harga-hidden');
    display.addEventListener('focus', () => { display.value = hidden.value || ''; });
    display.addEventListener('input', () => {
        hidden.value = display.value.replace(/[^0-9]/g, '');
        updateSummary();
    });
    display.addEventListener('blur', () => {
        display.value = hidden.value ? 'Rp. ' + Number(hidden.value).toLocaleString('id-ID') : '';
    });

    const $sel = $(`#jasaSelect_${idx}`);
    $sel.select2({ width: '100%' });
    $sel.on('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt.value) return;

        // selalu pakai harga_pribadi
        const price = opt.dataset.pribadi || 0;

        div.querySelector('.jasa-id-hidden').value   = opt.value;
        div.querySelector('.jasa-nama-hidden').value = opt.dataset.nama || '';
        hidden.value  = price;
        display.value = price ? 'Rp. ' + Number(price).toLocaleString('id-ID') : '';
        updateSummary();
    });

    updateSummary();
}

// ── Barang ────────────────────────────────────────────────────────────────────
function buildBarangOptions(selectedId = '') {
    let opts = `<option value="">— Pilih Barang —</option>`;
    BARANG_OPTIONS.forEach(br => {
        const sel      = br.id == selectedId ? 'selected' : '';
        const habis    = br.stok <= 0;
        const disabled = habis ? 'disabled' : '';
        const label    = br.nama + (habis ? ' — Stok Habis' : '');
        opts += `<option value="${br.id}"
                    data-nama="${br.nama}"
                    data-pribadi="${br.harga_pribadi}"
                    data-stock="${br.stok}"
                    ${disabled} ${sel}>${label}</option>`;
    });
    return opts;
}

function addBarang(data = null) {
    const idx        = barangIdx++;
    const container  = document.getElementById('barangContainer');
    const harga      = data?.harga || 0;
    const qty        = data?.qty   || 1;

    const div = document.createElement('div');
    div.className    = 'row-item row-part barang-row';
    div.dataset.idx  = idx;
    div.style.marginBottom = '10px';
    div.innerHTML = `
        <select class="mz-select select2-barang" id="barangSelect_${idx}" data-index="${idx}">
            ${buildBarangOptions(data?.id || '')}
        </select>
        <input type="hidden" name="barang_id[]"    class="barang-id-hidden"    value="${data?.id   || ''}">
        <input type="hidden" name="barang_nama[]"  class="barang-nama-hidden"  value="${data?.nama || ''}">
        <input type="number" min="1" name="barang_qty[]"
               class="mz-input barang-qty-input"
               placeholder="Qty"
               style="text-align:center"
               value="${qty}">
        <input type="hidden" name="barang_harga[]" class="barang-harga-hidden" value="${harga}">
        <input type="text"
               class="mz-input barang-harga-display"
               placeholder="Rp. 0"
               style="text-align:right"
               value="${harga ? 'Rp. ' + Number(harga).toLocaleString('id-ID') : ''}">
        <button type="button" class="btn-remove" onclick="this.closest('.barang-row').remove(); updateSummary()">Hapus</button>
    `;
    container.appendChild(div);

    div.querySelector('.barang-qty-input').addEventListener('input', updateSummary);

    const display = div.querySelector('.barang-harga-display');
    const hidden  = div.querySelector('.barang-harga-hidden');
    display.addEventListener('focus', () => { display.value = hidden.value || ''; });
    display.addEventListener('input', () => {
        hidden.value = display.value.replace(/[^0-9]/g, '');
        updateSummary();
    });
    display.addEventListener('blur', () => {
        display.value = hidden.value ? 'Rp. ' + Number(hidden.value).toLocaleString('id-ID') : '';
    });

    const $sel = $(`#barangSelect_${idx}`);
    $sel.select2({ width: '100%' });
    $sel.on('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt.value) return;

        // selalu pakai harga_pribadi
        const price = opt.dataset.pribadi || 0;

        div.querySelector('.barang-id-hidden').value   = opt.value;
        div.querySelector('.barang-nama-hidden').value = opt.dataset.nama || '';
        hidden.value  = price;
        display.value = price ? 'Rp. ' + Number(price).toLocaleString('id-ID') : '';
        updateSummary();
    });

    updateSummary();
}

// ── Init ──────────────────────────────────────────────────────────────────────
$(document).ready(function () {
    $('#pelangganSelect').select2({ width: '100%' });

    // ── Pelanggan change — autofill no telp, chasis, mesin ───────────────────
    $('#pelangganSelect').on('change', function () {
        const opt = this.options[this.selectedIndex];

        // update tipe (untuk tampilan label saja)
        tipePelanggan = opt.dataset.tipe || 'pribadi';
        document.getElementById('tipePelangganText').textContent = tipePelanggan.toUpperCase();

        // autofill field kendaraan
        document.getElementById('inputNoTelp').value   = opt.dataset.notelp   || '';
        document.getElementById('inputNoChasis').value = opt.dataset.nochasis || '';
        document.getElementById('inputNoMesin').value  = opt.dataset.nomesin  || '';
    });

    // Satu keluhan kosong di awal
    addKeluhan();
});
</script>

@endsection
