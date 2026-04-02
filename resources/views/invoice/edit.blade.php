@extends('dashboard')

@section('title','Edit Invoice')

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

    .inv-breadcrumb { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--mz-muted); margin-bottom:10px; }
    .inv-breadcrumb a { color:var(--mz-muted); text-decoration:none; transition:color .15s; }
    .inv-breadcrumb a:hover { color:var(--mz-accent); }
    .inv-breadcrumb span { color:var(--mz-border); }
    .inv-title    { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; letter-spacing:.4px; line-height:1; }
    .inv-subtitle { font-size:12px; color:var(--mz-muted); margin-top:4px; margin-bottom:24px; }

    .edit-badge {
        display:inline-flex; align-items:center; gap:6px;
        background:rgba(245,146,62,.1); border:1px solid rgba(245,146,62,.25);
        border-radius:20px; padding:4px 12px; margin-top:8px;
        font-size:11px; color:var(--mz-orange); font-weight:500;
    }
    .edit-badge svg { width:11px; height:11px; fill:var(--mz-orange); }

    .inv-card {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px; overflow:hidden;
        box-shadow:0 0 0 1px rgba(245,146,62,.06), 0 20px 48px rgba(0,0,0,.4);
    }
    /* orange bar for edit */
    .inv-card-bar { height:3px; background:linear-gradient(90deg,#e05c00,var(--mz-orange),var(--mz-accent)); }

    .inv-section { padding:24px 28px; border-bottom:1px solid var(--mz-border); }
    .inv-section:last-child { border-bottom:none; }
    .inv-section-head {
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom:18px; padding-bottom:10px; border-bottom:1px solid var(--mz-border);
    }
    .inv-section-title {
        display:flex; align-items:center; gap:8px;
        font-family:'Rajdhani',sans-serif; font-size:13px; font-weight:700;
        letter-spacing:.8px; text-transform:uppercase; color:var(--mz-orange);
    }
    .inv-section-title svg { width:14px; height:14px; fill:var(--mz-orange); }

    .inv-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

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
        border-color:var(--mz-orange);
        box-shadow:0 0 0 3px rgba(245,146,62,.15);
    }
    .mz-select { appearance:none; cursor:pointer; }
    .mz-textarea { resize:none; }

    .btn-add-row {
        display:inline-flex; align-items:center; gap:6px;
        padding:6px 14px; border-radius:6px;
        background:rgba(245,146,62,.08); border:1px solid rgba(245,146,62,.25);
        color:var(--mz-orange); font-size:11.5px; font-weight:600;
        cursor:pointer; transition:background .15s; font-family:'Inter',sans-serif;
    }
    .btn-add-row:hover { background:rgba(245,146,62,.18); }
    .btn-add-row svg { width:13px; height:13px; fill:var(--mz-orange); }

    .row-item { display:grid; gap:10px; align-items:center; margin-bottom:10px; }
    .row-12   { grid-template-columns:7fr 3fr 2fr; }
    .row-part { grid-template-columns:5fr 2fr 3fr 2fr; }
    .row-keluhan { grid-template-columns:1fr auto; }

    .btn-remove {
        display:inline-flex; align-items:center; justify-content:center;
        padding:8px 14px; border-radius:6px;
        background:rgba(242,108,108,.08); border:1px solid rgba(242,108,108,.25);
        color:var(--mz-red); font-size:12px; font-weight:700;
        cursor:pointer; transition:background .15s; white-space:nowrap;
        font-family:'Inter',sans-serif;
    }
    .btn-remove:hover { background:rgba(242,108,108,.18); }

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

    .inv-summary {
        background:var(--mz-surface2); border-top:2px solid var(--mz-border);
        padding:16px 28px;
        display:flex; align-items:center; justify-content:flex-end; gap:24px;
    }
    .sum-item { text-align:right; }
    .sum-label { font-size:10px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color:var(--mz-muted); }
    .sum-val { font-family:'Rajdhani',sans-serif; font-size:20px; font-weight:700; color:var(--mz-text); line-height:1; margin-top:2px; }
    .sum-val.grand { font-size:24px; color:var(--mz-orange); }

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
        background:linear-gradient(135deg,#e05c00,var(--mz-orange));
        border:none; cursor:pointer;
        transition:opacity .15s, transform .1s; position:relative; overflow:hidden;
    }
    .btn-submit::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent); transform:translateX(-100%); transition:transform .45s; }
    .btn-submit:hover::after { transform:translateX(100%); }
    .btn-submit:hover { opacity:.9; } .btn-submit:active { transform:scale(.98); }

    /* cicilan card */
    .cicilan-card {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px; overflow:hidden; margin-top:20px;
    }
    .cicilan-card-bar { height:3px; background:linear-gradient(90deg,#065f46,#10b981,#6ee7b7); }
    .cicilan-head { padding:16px 24px 0; }
    .cicilan-title { font-family:'Rajdhani',sans-serif; font-size:15px; font-weight:700; color:var(--mz-text); }
    .cicilan-sub   { font-size:11px; color:var(--mz-muted); margin-top:2px; margin-bottom:14px; }

    .cicilan-form { display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:10px; padding:0 24px 20px; }
    .btn-cicilan {
        padding:9px 20px; border-radius:6px;
        font-family:'Rajdhani',sans-serif; font-size:13px; font-weight:700;
        letter-spacing:.5px; text-transform:uppercase; color:#fff;
        background:linear-gradient(135deg,#065f46,#10b981);
        border:none; cursor:pointer; transition:opacity .15s;
    }
    .btn-cicilan:hover { opacity:.88; }

    .mz-table { width:100%; border-collapse:collapse; font-size:12px; }
    .mz-table thead tr { background:var(--mz-surface2); }
    .mz-table th { padding:9px 16px; font-size:10px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color:var(--mz-muted); text-align:left; border-bottom:1px solid var(--mz-border); }
    .mz-table td { padding:10px 16px; border-bottom:1px solid var(--mz-border); color:var(--mz-text); }
    .mz-table tbody tr:last-child td { border-bottom:none; }
    .mz-table tbody tr:hover td { background:var(--mz-surface2); }

    .btn-hapus-cicilan {
        background:none; border:none; color:var(--mz-red);
        font-weight:700; cursor:pointer; font-size:12px; font-family:'Inter',sans-serif;
        padding:4px 8px; border-radius:4px; transition:background .15s;
    }
    .btn-hapus-cicilan:hover { background:rgba(242,108,108,.1); }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="inv-wrap" x-data="invoiceEditForm()">

    <div class="inv-breadcrumb">
        <a href="{{ route('invoice.index') }}">Invoice</a>
        <span>/</span>
        <span style="color:var(--mz-text)">Edit</span>
    </div>
    <div class="inv-title">Edit Invoice</div>
    <div class="inv-subtitle" style="margin-bottom:6px">Perbarui data transaksi</div>
    <div class="edit-badge" style="margin-bottom:20px">
        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
        {{ $invoice->invoice_no }}
    </div>

    <form method="POST" action="{{ route('invoice.update',$invoice) }}">
        @csrf
        @method('PUT')

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
                                <option value="{{ $p->id }}" data-tipe="{{ $p->tipe }}"
                                    {{ $invoice->pelanggan_id == $p->id ? 'selected':'' }}>
                                    {{ $p->nama }} — {{ strtoupper($p->plat_nomor) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $invoice->tanggal }}" class="mz-input">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">KM</label>
                        <input name="km" value="{{ $invoice->km }}" class="mz-input">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">No Telp</label>
                        <input name="no_telp" value="{{ $invoice->no_telp }}" class="mz-input">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">No Chasis</label>
                        <input name="no_chasis" value="{{ $invoice->no_chasis }}" class="mz-input">
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">No Mesin</label>
                        <input name="no_mesin" value="{{ $invoice->no_mesin }}" class="mz-input">
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
                <template x-for="(k,i) in keluhan" :key="i">
                    <div class="row-item row-keluhan" style="margin-bottom:10px">
                        <textarea name="keluhan[]" x-model="keluhan[i]" class="mz-textarea" rows="2"></textarea>
                        <button type="button" @click="keluhan.splice(i,1)" class="btn-remove">×</button>
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
                <template x-for="(j,i) in jasa" :key="i">
                    <div class="row-item row-12" style="margin-bottom:10px">
                        <select class="mz-select" @change="setJasa($event,i)">
                            <option value="">Pilih Jasa</option>
                            @foreach($jasas as $js)
                                <option value="{{ $js->id }}"
                                    :selected="j.id == {{ $js->id }}"
                                    data-nama="{{ $js->nama }}"
                                    data-pribadi="{{ $js->harga_pribadi }}"
                                    data-perusahaan="{{ $js->harga_perusahaan }}">
                                    {{ $js->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jasa_id[]" :value="j.id">
                        <input type="hidden" name="jasa_nama[]" :value="j.nama">
                        <input name="jasa_harga[]" x-model="j.harga" class="mz-input" style="text-align:right">
                        <button type="button" @click="jasa.splice(i,1)" class="btn-remove">Hapus</button>
                    </div>
                </template>
            </div>

            {{-- ── Barang ── --}}
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
                <template x-for="(b,i) in barang" :key="i">
                    <div class="row-item row-part" style="margin-bottom:10px">
                        <select class="mz-select" @change="setBarang($event,i)">
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $br)
                                <option value="{{ $br->id }}"
                                    :selected="b.id == {{ $br->id }}"
                                    data-nama="{{ $br->nama }}"
                                    data-pribadi="{{ $br->harga_pribadi }}"
                                    data-perusahaan="{{ $br->harga_perusahaan }}">
                                    {{ $br->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="barang_id[]" :value="b.id">
                        <input type="hidden" name="barang_nama[]" :value="b.nama">
                        <input type="number" min="1" name="barang_qty[]" x-model="b.qty" class="mz-input" style="text-align:center">
                        <input name="barang_harga[]" x-model="b.harga" class="mz-input" style="text-align:right">
                        <button type="button" @click="barang.splice(i,1)" class="btn-remove">Hapus</button>
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
                <option value="belum" {{ $invoice->status_bayar == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                <option value="sudah" {{ $invoice->status_bayar == 'sudah' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>
        <div class="mz-field">
            <label class="mz-label">Metode Pembayaran</label>
            <select name="metode_bayar" class="mz-select">
                <option value="">— Pilih Metode —</option>
                <option value="cash"    {{ $invoice->metode_bayar == 'cash'    ? 'selected' : '' }}>Cash</option>
                <option value="bca"     {{ $invoice->metode_bayar == 'bca'     ? 'selected' : '' }}>Transfer BCA</option>
                <option value="mandiri" {{ $invoice->metode_bayar == 'mandiri' ? 'selected' : '' }}>Transfer Mandiri</option>
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
            <template x-if="statusBayar === 'sudah'">
                <div class="lunas-box">LUNAS</div>
            </template>
            <template x-if="statusBayar === 'belum'">
                {{--
                    Tampilkan sisa dari DB (sudah dikurangi semua cicilan).
                    Ini yang benar karena cicilan sudah tersimpan di DB.
                    Kolom sisa diupdate oleh cicilanStore & cicilanDelete di controller.
                --}}
                <div class="sisa-display">
                    Rp {{ number_format($invoice->sisa, 0, ',', '.') }}
                </div>
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

    {{-- Sisa dari DB — selalu akurat karena sudah dikurangi cicilan --}}
    <div class="sum-item">
        <div class="sum-label">Sisa Tagihan</div>
        <div class="sum-val" style="color:var(--mz-yellow)">
            Rp {{ number_format($invoice->sisa, 0, ',', '.') }}
        </div>
    </div>
</div>

            {{-- Footer --}}
            <div class="inv-footer">
                <a href="{{ route('invoice.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Update Invoice</button>
            </div>
        </div>
    </form>

    {{-- ── Cicilan ── --}}
    @if($invoice->sisa > 0)
    <div class="cicilan-card">
        <div class="cicilan-card-bar"></div>
        <div class="cicilan-head">
            <div class="cicilan-title">Tambah Cicilan</div>
            <div class="cicilan-sub">Sisa tagihan: <strong style="color:var(--mz-red)">Rp {{ number_format($invoice->sisa) }}</strong></div>
        </div>
        <form method="POST" action="{{ route('invoice.cicilan.store',$invoice) }}" class="cicilan-form">
            @csrf
            <input type="number" name="jumlah" required max="{{ $invoice->sisa }}" placeholder="Jumlah" class="mz-input">
            <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required class="mz-input">
            <select name="metode" class="mz-select">
                <option value="cash">Cash</option>
                <option value="bca">BCA</option>
                <option value="mandiri">Mandiri</option>
            </select>
            <button type="submit" class="btn-cicilan">Simpan</button>
        </form>
    </div>
    @endif

    {{-- ── Riwayat Cicilan ── --}}
    @if($invoice->payments->count())
    <div class="cicilan-card" style="margin-top:16px">
        <div class="cicilan-card-bar"></div>
        <div class="cicilan-head">
            <div class="cicilan-title">Riwayat Cicilan</div>
            <div class="cicilan-sub">{{ $invoice->payments->count() }} pembayaran tercatat</div>
        </div>
        <table class="mz-table">
            <thead><tr><th>Tanggal</th><th>Jumlah</th><th>Metode</th><th style="text-align:center">Aksi</th></tr></thead>
            <tbody>
                @foreach($invoice->payments as $p)
                <tr>
                    <td style="color:var(--mz-muted)">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</td>
                    <td style="font-weight:600">Rp {{ number_format($p->jumlah) }}</td>
                    <td style="text-transform:uppercase;font-size:11px;color:var(--mz-muted)">{{ $p->metode }}</td>
                    <td style="text-align:center">
                        <form method="POST" action="{{ route('invoice.cicilan.delete',$p) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-hapus-cicilan">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

function invoiceEditForm() {
    return {
        jasaMaster: @json($jasas),
        barangMaster: @json($barangs),
        keluhan: @json($invoice->keluhan ?? []),
        jasa: @json($invoice->jasa ?? []),
        barang: @json($invoice->barang ?? []),
        statusBayar: @json($invoice->status_bayar ?? 'belum'),
        paymentAwal: @json($invoice->payment_awal ?? 0),
        sisa: @json($invoice->sisa ?? 0),
        tipePelanggan: 'pribadi',

        init() {
            const select = document.querySelector('[name="pelanggan_id"]')
            if (select) { this.tipePelanggan = select.selectedOptions[0].dataset.tipe || 'pribadi' }
            this.injectHargaAwal()
        },

        injectHargaAwal() {
            this.jasa.forEach(j => {
                const master = this.jasaMaster.find(x => x.id == j.id)
                if (!master) return
                j.harga_pribadi = master.harga_pribadi
                j.harga_perusahaan = master.harga_perusahaan
            })
            this.barang.forEach(b => {
                const master = this.barangMaster.find(x => x.id == b.id)
                if (!master) return
                b.harga_pribadi = master.harga_pribadi
                b.harga_perusahaan = master.harga_perusahaan
            })
        },

        setPelanggan(e) {
            this.tipePelanggan = e.target.selectedOptions[0].dataset.tipe || 'pribadi'
            this.updateHargaByTipe()
        },

        updateHargaByTipe() {
            this.jasa.forEach(j => { j.harga = this.tipePelanggan === 'perusahaan' ? j.harga_perusahaan : j.harga_pribadi })
            this.barang.forEach(b => { b.harga = this.tipePelanggan === 'perusahaan' ? b.harga_perusahaan : b.harga_pribadi })
        },

        setJasa(e, i) {
            const master = this.jasaMaster.find(x => x.id == e.target.value)
            if (!master) return
            this.jasa[i] = { id: master.id, nama: master.nama, harga_pribadi: master.harga_pribadi, harga_perusahaan: master.harga_perusahaan, harga: this.tipePelanggan === 'perusahaan' ? master.harga_perusahaan : master.harga_pribadi }
        },

        setBarang(e, i) {
            const master = this.barangMaster.find(x => x.id == e.target.value)
            if (!master) return
            this.barang[i] = { id: master.id, nama: master.nama, qty: this.barang[i]?.qty ?? 1, harga_pribadi: master.harga_pribadi, harga_perusahaan: master.harga_perusahaan, harga: this.tipePelanggan === 'perusahaan' ? master.harga_perusahaan : master.harga_pribadi }
        },

        addJasa() { this.jasa.push({id:'', nama:'', harga:0, harga_pribadi:0, harga_perusahaan:0}) },
        addBarang() { this.barang.push({id:'', nama:'', qty:1, harga_pribadi:0, harga_perusahaan:0, harga:0}) },

        get grandTotal() {
            const totalJasa = this.jasa.reduce((t, j) => t + Number(j.harga || 0), 0)
            const totalPart = this.barang.reduce((t, b) => t + (Number(b.harga || 0) * Number(b.qty || 0)), 0)
            return totalJasa + totalPart
        },

        get sisa() { return this.grandTotal - Number(this.paymentAwal || 0) },

        formatRupiah(num) { return 'Rp ' + Number(num).toLocaleString('id-ID') }
    }
}
</script>
@endsection
