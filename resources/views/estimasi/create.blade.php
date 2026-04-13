@extends('dashboard')

@section('title','Buat Estimasi')

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
        <span style="color:var(--mz-text)">Estimasi</span>
    </div>
    {{-- ✅ Badge ESTIMASI --}}
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:4px;">
        <div class="inv-title">Buat Estimasi</div>
        <span style="
            background: rgba(245,197,66,0.12);
            border: 1px solid rgba(245,197,66,0.35);
            color: var(--mz-yellow, #f5c542);
            font-family: 'Rajdhani', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 20px;
        ">Tidak disimpan ke database</span>
    </div>
    <div class="inv-subtitle">Preview estimasi biaya servis & perbaikan kendaraan</div>

    <form method="POST" action="{{ route('estimasi.preview') }}">
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
                        <select name="pelanggan_id" class="mz-select select2" @change="setPelanggan($event)">
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
                <template x-for="(j,index) in jasa" :key="j.id + '-' + index">
                    <div class="row-item row-12" style="margin-bottom:10px">
                        <select class="mz-select select2-jasa" x-model="j.id" @change="setJasa($event,index)">
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
                <template x-for="(b,index) in barang" :key="b.id + '-' + index">
                    <div class="row-item row-part" style="margin-bottom:10px">
                        <select class="mz-select select2-barang" @change="setBarang($event,index)">
                            <option value="">— Pilih Barang —</option>
                            @foreach($barangs as $br)
                                <option value="{{ $br->id }}"
                                        data-nama="{{ $br->nama }}"
                                        data-pribadi="{{ $br->harga_pribadi }}"
                                        data-perusahaan="{{ $br->harga_perusahaan }}"
                                        data-stock="{{ $br->stok }}">
                                    {{ $br->nama }}
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
                    <div class="sum-label">Estimasi Total</div>
                    <div class="sum-val grand" x-text="formatRupiah(grandTotal)"></div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="inv-footer">
                <a href="{{ route('invoice.index') }}" class="btn-cancel">Batal</a>
                {{-- ✅ tombol preview, warna kuning --}}
                <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #d97706, #f5c542);">
                    Lihat Estimasi
                </button>
            </div>
        </div>
    </form>
</div>
<script src="{{ asset('assets/js/create.invoice.js') }}"></script>
@endsection
