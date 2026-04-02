@extends('dashboard')

@section('title','Buat Invoice')

@section('content')
<style>
    body {
    color: var(--mz-text);
}
    /* DEFAULT DARK */
:root {
    --mz-bg:       #0f1117;
    --mz-surface:  #181c27;
    --mz-surface2: #1e2333;
    --mz-border:   #262c3d;
    --mz-text:     #e4e8f0;
    --mz-muted:    #6b7694;
}

/* LIGHT MODE */
[data-theme="light"] {
    --mz-bg:       #ffffff;
    --mz-surface:  #f9fafb;
    --mz-surface2: #f1f5f9;
    --mz-border:   #e5e7eb;
    --mz-text:     #111827;
    --mz-muted:    #6b7280;
   --mz-accent:   #4f8ef7;
        --mz-accent2:  #1e90ff;
        --mz-muted:    #6b7694;
        --mz-green:    #3ef08a;
        --mz-red:      #f26c6c;
        --mz-yellow:   #f5c542;
        --mz-orange:   #f5923e;
}


    .inv-wrap  { font-family: 'Inter', sans-serif; color: var(--mz-text); }

    /* breadcrumb & header */
    .inv-breadcrumb { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:10px; }
    .inv-breadcrumb a { color:var(--mz-muted); text-decoration:none; transition:color .15s; }
    .inv-breadcrumb a:hover { color:var(--mz-accent); }
    .inv-breadcrumb span { color:var(--mz-border); }
    .inv-title    { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .inv-subtitle { font-size:12px; color:var(--mz-muted); margin-top:4px; margin-bottom:24px; }

    /* main card */
    .inv-card {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px; overflow:hidden;
        box-shadow:0 0 0 1px rgba(79,142,247,.07), 0 20px 48px rgba(0,0,0,.4);
    }
    .inv-card-bar { height:3px; background:linear-gradient(90deg,#1e90ff,var(--mz-accent),#8ab6ff); }

    /* section */
    .inv-section { padding:24px 28px; border-bottom:1px solid var(--mz-border); }
    .inv-section:last-child { border-bottom:none; }

    .inv-section-head {
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:18px; padding-bottom:10px; border-bottom:1px solid var(--mz-border);
    }
    .inv-section-title {
        display:flex; align-items:center; gap:8px;
        font-family:'Rajdhani',sans-serif; font-size:13px; font-weight:700;
        letter-spacing:.8px; text-transform:uppercase; color:var(--mz-accent);
    }
    .inv-section-title svg { width:14px; height:14px; fill:var(--mz-accent); }

    /* grid */
    .inv-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

    /* field */
    .mz-field { display:flex; flex-direction:column; gap:5px; }
    .mz-label { font-size:10px; font-weight:700; letter-spacing:.7px; text-transform:uppercase; color:var(--mz-muted); }

    .mz-input, .mz-select, .mz-textarea {
        width:100%; background:var(--mz-bg); border:1px solid var(--mz-border);
        border-radius:6px; padding:9px 12px; color:var(--mz-text);
        outline:none; font-family:'Inter',sans-serif; font-size:13px;
        transition:border-color .15s, box-shadow .15s;
    }
    .mz-input::placeholder, .mz-textarea::placeholder { color:#3a4059; }
    .mz-input:focus, .mz-select:focus, .mz-textarea:focus {
        border-color:var(--mz-accent);
        box-shadow:0 0 0 3px rgba(79,142,247,.15);
    }
    .mz-select { appearance:none; cursor:pointer; }
    .mz-textarea { resize:none; }

    /* tipe badge */
    .tipe-text { font-size:11px; margin-top:4px; font-weight:600; }

    /* add button */
    .btn-add-row {
        display:inline-flex; align-items:center; gap:6px;
        padding:6px 14px; border-radius:6px;
        background:rgba(79,142,247,.08); border:1px solid rgba(79,142,247,.25);
        color:var(--mz-accent); font-size:11.5px; font-weight:600;
        cursor:pointer; transition:background .15s; font-family:'Inter',sans-serif;
    }
    .btn-add-row:hover { background:rgba(79,142,247,.18); }
    .btn-add-row svg { width:13px; height:13px; fill:var(--mz-accent); }

    /* row item */
    .row-item { display:grid; gap:10px; align-items:center; margin-bottom:10px; }
    .row-item:last-child { margin-bottom:0; }
    .row-12  { grid-template-columns: 7fr 3fr 2fr; }
    .row-part{ grid-template-columns: 5fr 2fr 3fr 2fr; }
    .row-keluhan { grid-template-columns: 1fr auto; }

    .btn-remove {
        display:inline-flex; align-items:center; justify-content:center;
        padding:8px 14px; border-radius:6px;
        background:rgba(242,108,108,.08); border:1px solid rgba(242,108,108,.25);
        color:var(--mz-red); font-size:12px; font-weight:700;
        cursor:pointer; transition:background .15s; white-space:nowrap;
        font-family:'Inter',sans-serif;
    }
    .btn-remove:hover { background:rgba(242,108,108,.18); }

    /* pembayaran */
    .lunas-box {
        background:rgba(62,240,138,.07); border:1px solid rgba(62,240,138,.25);
        border-radius:6px; padding:9px 12px;
        text-align:center; font-family:'Rajdhani',sans-serif;
        font-size:14px; font-weight:700; color:var(--mz-green); letter-spacing:.5px;
    }
    .sisa-display {
        background:var(--mz-surface2); border:1px solid var(--mz-border);
        border-radius:6px; padding:9px 12px;
        font-size:13px; font-weight:600; color:var(--mz-yellow); text-align:right;
    }

    /* summary bar */
    .inv-summary {
        background:var(--mz-surface2); border-top:2px solid var(--mz-border);
        padding:16px 28px;
        display:flex; align-items:center; justify-content:flex-end; gap:24px;
    }
    .sum-item { text-align:right; }
    .sum-label { font-size:10px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color:var(--mz-muted); }
    .sum-val { font-family:'Rajdhani',sans-serif; font-size:20px; font-weight:700; color:var(--mz-text); line-height:1; margin-top:2px; }
    .sum-val.grand { font-size:24px; color:var(--mz-accent); }

    /* footer */
    .inv-footer {
        padding:18px 28px; background:var(--mz-surface2); border-top:1px solid var(--mz-border);
        display:flex; align-items:center; justify-content:flex-end; gap:10px;
    }
    .btn-cancel {
        padding:9px 20px; border-radius:6px; font-size:12.5px; font-weight:500;
        color:var(--mz-muted); background:transparent; border:1px solid var(--mz-border);
        cursor:pointer; text-decoration:none; display:inline-block;
        transition:border-color .15s, color .15s;
    }
    .btn-cancel:hover { border-color:var(--mz-muted); color:var(--mz-text); }
    .btn-submit {
        padding:9px 28px; border-radius:6px;
        font-family:'Rajdhani',sans-serif; font-size:14px; font-weight:700;
        letter-spacing:.8px; text-transform:uppercase; color:#fff;
        background:linear-gradient(135deg,#1a6fe8,var(--mz-accent));
        border:none; cursor:pointer;
        transition:opacity .15s, transform .1s; position:relative; overflow:hidden;
    }
    .btn-submit::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent); transform:translateX(-100%); transition:transform .45s; }
    .btn-submit:hover::after { transform:translateX(100%); }
    .btn-submit:hover { opacity:.9; } .btn-submit:active { transform:scale(.98); }
</style>

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
                        <select name="pelanggan_id" class="mz-select" @change="setPelanggan($event)">
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
                        <select class="mz-select" @change="setJasa($event,index)">
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
                        <select class="mz-select" @change="setBarang($event,index)">
                            <option value="">— Pilih Barang —</option>
                            @foreach($barangs as $br)
                                <option value="{{ $br->id }}"
                                        data-nama="{{ $br->nama }}"
                                        data-pribadi="{{ $br->harga_pribadi }}"
                                        data-perusahaan="{{ $br->harga_perusahaan }}"
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
</div>

<script>
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

function invoiceForm() {
    return {
        tipePelanggan: 'pribadi',
        keluhan: [''],
        jasa: [],
        barang: [],
        statusBayar: 'belum',
        paymentAwal: 0,

        init() {
            this.$nextTick(() => {
                let select = document.querySelector('[name="pelanggan_id"]')
                if (select && select.selectedOptions.length > 0) {
                    this.tipePelanggan = select.selectedOptions[0].dataset.tipe || 'pribadi'
                }
            })
        },

        setPelanggan(e) {
            this.tipePelanggan = e.target.selectedOptions[0].dataset.tipe || 'pribadi'
            this.updateAllPrices()
        },

        updateAllPrices() {
            this.jasa.forEach(j => {
                j.harga = this.tipePelanggan === 'perusahaan' ? j.harga_perusahaan : j.harga_pribadi
            })
            this.barang.forEach(b => {
                b.harga = this.tipePelanggan === 'perusahaan' ? b.harga_perusahaan : b.harga_pribadi
            })
        },

        addJasa() {
            this.jasa.push({id:'', nama:'', harga:0, harga_pribadi:0, harga_perusahaan:0})
        },

        setJasa(e,i) {
            let o = e.target.selectedOptions[0]
            this.jasa[i] = {
                id: o.value, nama: o.dataset.nama,
                harga_pribadi: +o.dataset.pribadi, harga_perusahaan: +o.dataset.perusahaan,
                harga: this.tipePelanggan === 'perusahaan' ? +o.dataset.perusahaan : +o.dataset.pribadi
            }
        },

        addBarang() {
            this.barang.push({id:'', nama:'', qty:1, stock:0, harga:0, harga_pribadi:0, harga_perusahaan:0})
        },

        setBarang(e,i) {
            let o = e.target.selectedOptions[0]
            let stock = +o.dataset.stock
            if (stock <= 0) { alert('STOCK HABIS'); return }
            if (stock <= 5) { alert(`PERINGATAN: Stock ${o.dataset.nama} tinggal ${stock}`) }
            this.barang[i] = {
                id: o.value, nama: o.dataset.nama, qty: 1, stock,
                harga_pribadi: +o.dataset.pribadi, harga_perusahaan: +o.dataset.perusahaan,
                harga: this.tipePelanggan === 'perusahaan' ? +o.dataset.perusahaan : +o.dataset.pribadi
            }
        },

        updateQty(i) {
            let b = this.barang[i]
            if (b.qty > b.stock) { alert('Qty melebihi stock'); b.qty = b.stock }
        },

        get grandTotal() {
            let totalJasa = this.jasa.reduce((t,j)=>t + Number(j.harga||0),0)
            let totalPart = this.barang.reduce((t,b)=>t + (Number(b.harga||0) * Number(b.qty||0)),0)
            return totalJasa + totalPart
        },

        get sisa() { return this.grandTotal - Number(this.paymentAwal||0) },

        formatRupiah(num) { return 'Rp ' + Number(num).toLocaleString('id-ID') },
    }
}
</script>
@endsection
