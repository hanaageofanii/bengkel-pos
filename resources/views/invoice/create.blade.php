@extends('dashboard')

@section('title','Buat Invoice')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/create-invoice.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="inv-wrap" x-data="invoiceForm()">

    <div class="inv-breadcrumb">
        <a href="{{ route('invoice.index') }}">Invoice</a>
        <span>/</span>
        <span style="color:var(--mz-text)">Buat</span>
    </div>
    <div class="inv-title">Buat Invoice</div>
    <div class="inv-subtitle">Input transaksi servis & perbaikan kendaraan</div>

    <form method="POST" action="{{ route('invoice.store') }}">
        @csrf
        <div class="inv-card">
            <div class="inv-card-bar"></div>

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
                        <select name="pelanggan_id" class="mz-select select2">
                            <option value="">Pilih pelanggan</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}" data-tipe="{{ $p->tipe }}">
                                    {{ $p->nama }} — {{ strtoupper($p->plat_nomor) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="tipe-text" style="color:var(--mz-teal,#2dd4bf)">
                            Tipe: <span x-text="tipePelanggan" style="text-transform:uppercase"></span>
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
                        <input name="no_telp" class="mz-input" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">No Chasis</label>
                        <input name="no_chasis" class="mz-input">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">No Mesin</label>
                        <input name="no_mesin" class="mz-input">
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
                    <button type="button" @click="keluhan.push('')" class="btn-add-row">
                        <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Tambah
                    </button>
                </div>
                <template x-for="(k,index) in keluhan" :key="index">
                    <div class="row-item row-keluhan" style="margin-bottom:10px">
                        <textarea name="keluhan[]" x-model="keluhan[index]" rows="2" class="mz-textarea" placeholder="Deskripsi keluhan..."></textarea>
                        <button type="button" @click="keluhan.splice(index,1)" class="btn-remove">×</button>
                    </div>
                </template>
            </div>

            {{-- ── Jasa ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
                        Jasa
                    </div>
                    <button type="button" @click="addJasa()" class="btn-add-row">
                        <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Tambah
                    </button>
                </div>
                <template x-for="(j,index) in jasa" :key="index">
                    <div class="row-item row-12" style="margin-bottom:10px">
                        <select class="mz-select select2-jasa" :data-index="index">
                            <option value="">— Pilih Jasa —</option>
                            @foreach($jasas as $js)
                                <option value="{{ $js->id }}"
                                        data-nama="{{ $js->nama }}"
                                        data-pribadi="{{ $js->harga_pribadi }}"
                                        data-perusahaan="{{ $js->harga_perusahaan }}">
                                    {{ $js->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jasa_id[]" :value="j.id">
                        <input type="hidden" name="jasa_nama[]" :value="j.nama">
                        <input name="jasa_harga[]" x-model="j.harga" placeholder="Harga" class="mz-input" style="text-align:right">
                        <button type="button" @click="jasa.splice(index,1)" class="btn-remove">Hapus</button>
                    </div>
                </template>
            </div>

            {{-- ── Spare Part ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                        Spare Part
                    </div>
                    <button type="button" @click="addBarang()" class="btn-add-row">
                        <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Tambah
                    </button>
                </div>
                <template x-for="(b,index) in barang" :key="index">
                    <div class="row-item row-part" style="margin-bottom:10px">
                        <select class="mz-select select2-barang" :data-index="index">
                            <option value="">— Pilih Barang —</option>
                            @foreach($barangs as $br)
                                <option value="{{ $br->id }}"
                                        data-nama="{{ $br->nama }}"
                                        data-pribadi="{{ $br->harga_pribadi }}"
                                        data-perusahaan="{{ $br->harga_perusahaan }}"
                                        data-stock="{{ $br->stok }}"
                                        {{ $br->stok <= 0 ? 'disabled' : '' }}>
                                    {{ $br->nama }}{{ $br->stok <= 0 ? ' — Stok Habis' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="barang_id[]" :value="b.id">
                        <input type="hidden" name="barang_nama[]" :value="b.nama">
                        <input type="number" min="1" name="barang_qty[]" x-model="b.qty" placeholder="Qty" class="mz-input" style="text-align:center">
                        <input name="barang_harga[]" x-model="b.harga" placeholder="Harga" class="mz-input" style="text-align:right">
                        <button type="button" @click="barang.splice(index,1)" class="btn-remove">Hapus</button>
                    </div>
                </template>
            </div>

            {{-- ── Pembayaran ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                        Pembayaran
                    </div>
                </div>
                <div class="inv-grid" style="margin-bottom:16px">
                    <div class="mz-field">
                        <label class="mz-label">Status Pembayaran</label>
                        <select name="status_bayar" x-model="statusBayar"
                                @change="if(statusBayar==='sudah'){ paymentAwal = grandTotal }"
                                class="mz-select">
                            <option value="belum">Belum Lunas</option>
                            <option value="sudah">Lunas</option>
                        </select>
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">Metode Pembayaran</label>
                        <select name="metode_bayar" class="mz-select">
                            <option value="">— Pilih Metode —</option>
                            <option value="cash">Cash</option>
                            <option value="bca">Transfer BCA</option>
                            <option value="mandiri">Transfer Mandiri</option>
                        </select>
                    </div>
                </div>
                <div class="inv-grid">
                    <div class="mz-field">
                        <label class="mz-label">Payment Awal (DP)</label>
                        <template x-if="statusBayar === 'belum'">
                            <input type="number" name="payment_awal" x-model="paymentAwal" min="0" class="mz-input">
                        </template>
                        <template x-if="statusBayar === 'sudah'">
                            <div>
                                <div class="lunas-box">LUNAS</div>
                                <input type="hidden" name="payment_awal" :value="grandTotal">
                            </div>
                        </template>
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">Sisa Tagihan</label>
                        <template x-if="statusBayar === 'belum'">
                            <div class="sisa-display" x-text="formatRupiah(sisa)"></div>
                        </template>
                        <template x-if="statusBayar === 'sudah'">
                            <div class="lunas-box">LUNAS</div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ── Notes ── --}}
            <div class="inv-section">
                <div class="inv-section-head">
                    <div class="inv-section-title">
                        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        Notes
                    </div>
                </div>
                <div class="mz-field">
                    <textarea name="notes" rows="3" class="mz-textarea" placeholder="Catatan tambahan untuk invoice ini..."></textarea>
                </div>
            </div>

            {{-- Summary --}}
            <div class="inv-summary">
                <div class="sum-item">
                    <div class="sum-label">Total Jasa</div>
                    <div class="sum-val" x-text="formatRupiah(jasa.reduce((t,j)=>t+Number(j.harga||0),0))"></div>
                </div>
                <div style="color:var(--mz-border);font-size:20px">+</div>
                <div class="sum-item">
                    <div class="sum-label">Total Part</div>
                    <div class="sum-val" x-text="formatRupiah(barang.reduce((t,b)=>t+(Number(b.harga||0)*Number(b.qty||0)),0))"></div>
                </div>
                <div style="color:var(--mz-border);font-size:20px">=</div>
                <div class="sum-item">
                    <div class="sum-label">Grand Total</div>
                    <div class="sum-val grand" x-text="formatRupiah(grandTotal)"></div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="inv-footer">
                <a href="{{ route('invoice.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan & Print</button>
            </div>
        </div>
    </form>

    {{-- Stock Popup — di luar form --}}
    <div id="stock-popup" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.5)">
        <div style="background:var(--mz-surface);border:1px solid var(--mz-border);border-radius:12px;padding:28px 32px;max-width:360px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.5)">
            <div id="stock-popup-icon" style="font-size:40px;margin-bottom:12px"></div>
            <div id="stock-popup-title" style="font-family:'Rajdhani',sans-serif;font-size:20px;font-weight:700;margin-bottom:8px"></div>
            <div id="stock-popup-msg" style="font-size:13px;color:var(--mz-muted);margin-bottom:20px"></div>
            <button onclick="closeStockPopup()" style="padding:8px 28px;border-radius:6px;background:linear-gradient(135deg,#1a6fe8,var(--mz-accent,#4f8ef7));border:none;color:#fff;font-family:'Rajdhani',sans-serif;font-size:14px;font-weight:700;letter-spacing:0.8px;cursor:pointer">OK</button>
        </div>
    </div>

</div>
<script src="{{ asset('assets/js/create.invoice.js') }}"></script>
@endsection
