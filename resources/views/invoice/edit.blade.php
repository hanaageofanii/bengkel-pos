@extends('dashboard')

@section('title','Edit Invoice')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/edit-invoice.css') }}">
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

<form method="POST" action="{{ route('invoice.update',$invoice) }}"
      @submit.prevent="submitForm($el)">
    @csrf
    @method('PUT')

    {{-- Hidden input untuk payment_awal — nilainya di-set oleh submitForm() --}}
    <input type="hidden" name="payment_awal" x-ref="paymentAwalHidden" :value="paymentAwal">

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
                            <option value="{{ $p->id }}"
                                    data-tipe="{{ $p->tipe }}"
                                    data-notelp="{{ $p->no_hp }}"
                                    data-nochasis="{{ $p->no_chasis }}"
                                    data-nomesin="{{ $p->no_mesin }}"
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
                    <input type="hidden" name="jasa_harga[]" :value="j.harga">
                    <input
                        type="text"
                        :value="j.harga ? 'Rp. ' + Number(j.harga).toLocaleString('id-ID') : ''"
                        placeholder="Rp. 0"
                        class="mz-input"
                        style="text-align:right"
                        @focus="$event.target.value = j.harga || ''"
                        @input="j.harga = $event.target.value.replace(/[^0-9]/g,''); $event.target.value = j.harga"
                        @blur="$event.target.value = j.harga ? 'Rp. ' + Number(j.harga).toLocaleString('id-ID') : ''"
                    >
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
                    <input type="hidden" name="barang_harga[]" :value="b.harga">
                    <input type="number" min="1" name="barang_qty[]" x-model="b.qty" class="mz-input" style="text-align:center">
                    <input
                        type="text"
                        :value="b.harga ? 'Rp. ' + Number(b.harga).toLocaleString('id-ID') : ''"
                        placeholder="Rp. 0"
                        class="mz-input"
                        style="text-align:right"
                        @focus="$event.target.value = b.harga || ''"
                        @input="b.harga = $event.target.value.replace(/[^0-9]/g,''); $event.target.value = b.harga"
                        @blur="$event.target.value = b.harga ? 'Rp. ' + Number(b.harga).toLocaleString('id-ID') : ''"
                    >
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
                {{-- Select Status Bayar --}}
                <div class="mz-field">
                    <label class="mz-label">Status Pembayaran</label>
                    <select name="status_bayar" class="mz-select" x-model="statusBayar"
                            @change="statusBayar === 'sudah' ? paymentAwal = grandTotal : paymentAwal = {{ $invoice->payment_awal ?? 0 }}">
                        <option value="belum" {{ $invoice->status_bayar == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="sudah" {{ $invoice->status_bayar == 'sudah' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="mz-field">
                    <label class="mz-label">Metode Pembayaran</label>
                    <select name="metode_bayar" class="mz-select">
                        <option value="">— Pilih Metode —</option>
                        <option value="cash"    {{ $invoice->metode_bayar == 'cash'    ? 'selected' : '' }}>Cash</option>
                        <option value="bca"     {{ $invoice->metode_bayar == 'bca'     ? 'selected' : '' }}>Debit</option>
                        <option value="mandiri" {{ $invoice->metode_bayar == 'mandiri' ? 'selected' : '' }}>Transfer Bank</option>
                    </select>
                </div>
            </div>

            <div class="inv-grid">
                {{-- Payment Awal --}}
                <div class="mz-field">
                    <label class="mz-label">Payment Awal (DP)</label>

                    {{-- Lunas: tampil box LUNAS, hidden = grandTotal --}}
                    <template x-if="statusBayar === 'sudah'">
                        <div>
                            <div class="lunas-box">LUNAS</div>
                            <input type="hidden" name="payment_awal" :value="grandTotal">
                        </div>
                    </template>

                    {{-- Belum Lunas: input DP bebas --}}
                    <template x-if="statusBayar === 'belum'">
                        <input
                            type="text"
                            placeholder="Rp. 0"
                            class="mz-input"
                            :value="paymentAwal ? 'Rp. ' + Number(paymentAwal).toLocaleString('id-ID') : ''"
                            @focus="$event.target.value = paymentAwal || ''"
                            @input="paymentAwal = $event.target.value.replace(/[^0-9]/g,''); $refs.paymentAwalHidden.value = paymentAwal"
                            @blur="$event.target.value = paymentAwal ? 'Rp. ' + Number(paymentAwal).toLocaleString('id-ID') : ''"
                        >
                    </template>
                </div>

                {{-- ✅ FIX: Sisa Tagihan pakai rumus yang sama dengan summary --}}
                <div class="mz-field">
                    <label class="mz-label">Sisa Tagihan</label>
                    <template x-if="statusBayar === 'sudah'">
                        <div class="lunas-box">LUNAS</div>
                    </template>
                    <template x-if="statusBayar === 'belum'">
                        <div class="sisa-display" style="color:var(--mz-yellow)"
                             x-text="formatRupiah(Math.max(0, grandTotal - Number(paymentAwal || 0) - totalCicilan))">
                        </div>
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
                <textarea name="notes" rows="3" class="mz-textarea"
                          placeholder="Catatan tambahan...">{{ old('notes', $invoice->notes) }}</textarea>
            </div>
        </div>

        {{-- ✅ FIX: Summary — hapus x-data wrapper duplikat, pakai totalCicilan dari invoiceEditForm() --}}
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
            <div class="sum-item">
                <div class="sum-label">DP</div>
                <div class="sum-val" style="color:#22c55e" x-text="formatRupiah(paymentAwal)"></div>
            </div>
            @if($invoice->payments->sum('jumlah') > 0)
            <div class="sum-item">
                <div class="sum-label">Total Cicilan</div>
                <div class="sum-val" style="color:#22c55e">
                    Rp {{ number_format($invoice->payments->sum('jumlah'), 0, ',', '.') }}
                </div>
            </div>
            @endif
            {{-- ✅ FIX: Sisa di summary pakai totalCicilan dari state Alpine, bukan x-data terpisah --}}
            <div class="sum-item">
                <div class="sum-label">Sisa (setelah update)</div>
                <div class="sum-val" style="color:var(--mz-yellow)"
                     x-text="statusBayar === 'sudah' ? 'LUNAS' : formatRupiah(Math.max(0, grandTotal - Number(paymentAwal || 0) - totalCicilan))">
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
        <form method="POST" action="{{ route('invoice.cicilan.store',$invoice) }}"
            class="cicilan-form"
            onsubmit="this.querySelectorAll('.rupiah-field').forEach(i=>i.value=i.value.replace(/[^0-9]/g,''))">
            @csrf
            <input
                type="text"
                name="jumlah"
                required
                placeholder="Rp. 0"
                class="mz-input rupiah-field"
                onfocus="this.value=this.value.replace(/[^0-9]/g,'')"
                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                onblur="if(this.value) this.value='Rp. '+Number(this.value).toLocaleString('id-ID')"
            >
            <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required class="mz-input">
            <select name="metode" class="mz-select">
                <option value="cash">Cash</option>
                <option value="bca">Debit</option>
                <option value="mandiri">Transfer Bank</option>
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
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Metode</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $p)
                <tr>
                    <td style="color:var(--mz-muted)">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</td>
                    <td style="font-weight:600">Rp {{ number_format($p->jumlah) }}</td>
                    <td style="font-size:11px;color:var(--mz-muted)">
                        @if($p->metode == 'bca') Debit
                        @elseif($p->metode == 'mandiri') Transfer Bank
                        @elseif($p->metode == 'cash') Cash
                        @else {{ strtoupper($p->metode) }}
                        @endif
                    </td>
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
function invoiceEditForm() {
    return {
        jasaMaster:   @json($jasas),
        barangMaster: @json($barangs),
        keluhan:      @json($invoice->keluhan ?? []),
        jasa:         @json($invoice->jasa ?? []),
        barang:       @json($invoice->barang ?? []),
        paymentAwal:  @json($invoice->payment_awal ?? 0),
        statusBayar:  @json($invoice->status_bayar ?? 'belum'),
        tipePelanggan: 'pribadi',

        // ✅ FIX: totalCicilan dipindah ke sini supaya bisa dipakai di semua template
        totalCicilan: {{ $invoice->payments->sum('jumlah') }},

        init() {
            const select = document.querySelector('[name="pelanggan_id"]')
            if (select) {
                this.tipePelanggan = select.selectedOptions[0].dataset.tipe || 'pribadi'
            }
            this.injectHargaAwal()
        },

        injectHargaAwal() {
            this.jasa.forEach(j => {
                const master = this.jasaMaster.find(x => x.id == j.id)
                if (!master) return
                j.harga_pribadi    = master.harga_pribadi
                j.harga_perusahaan = master.harga_perusahaan
            })
            this.barang.forEach(b => {
                const master = this.barangMaster.find(x => x.id == b.id)
                if (!master) return
                b.harga_pribadi    = master.harga_pribadi
                b.harga_perusahaan = master.harga_perusahaan
            })
        },

        setPelanggan(e) {
            const opt = e.target.selectedOptions[0]
            this.tipePelanggan = opt.dataset.tipe || 'pribadi'
            this.updateHargaByTipe()

            // Autofill field kendaraan
            const noTelp   = document.querySelector('[name="no_telp"]')
            const noChasis = document.querySelector('[name="no_chasis"]')
            const noMesin  = document.querySelector('[name="no_mesin"]')
            if (noTelp)   noTelp.value   = opt.dataset.notelp   || ''
            if (noChasis) noChasis.value = opt.dataset.nochasis || ''
            if (noMesin)  noMesin.value  = opt.dataset.nomesin  || ''
        },

        updateHargaByTipe() {
            this.jasa.forEach(j => {
                j.harga = this.tipePelanggan === 'perusahaan' ? j.harga_perusahaan : j.harga_pribadi
            })
            this.barang.forEach(b => {
                b.harga = this.tipePelanggan === 'perusahaan' ? b.harga_perusahaan : b.harga_pribadi
            })
        },

        setJasa(e, i) {
            const master = this.jasaMaster.find(x => x.id == e.target.value)
            if (!master) return
            this.jasa[i] = {
                id: master.id,
                nama: master.nama,
                harga_pribadi: master.harga_pribadi,
                harga_perusahaan: master.harga_perusahaan,
                harga: this.tipePelanggan === 'perusahaan' ? master.harga_perusahaan : master.harga_pribadi
            }
        },

        setBarang(e, i) {
            const master = this.barangMaster.find(x => x.id == e.target.value)
            if (!master) return
            this.barang[i] = {
                id: master.id,
                nama: master.nama,
                qty: this.barang[i]?.qty ?? 1,
                harga_pribadi: master.harga_pribadi,
                harga_perusahaan: master.harga_perusahaan,
                harga: this.tipePelanggan === 'perusahaan' ? master.harga_perusahaan : master.harga_pribadi
            }
        },

        addJasa()   { this.jasa.push({id:'', nama:'', harga:0, harga_pribadi:0, harga_perusahaan:0}) },
        addBarang() { this.barang.push({id:'', nama:'', qty:1, harga_pribadi:0, harga_perusahaan:0, harga:0}) },

        get grandTotal() {
            const totalJasa = this.jasa.reduce((t, j) => t + Number(j.harga || 0), 0)
            const totalPart = this.barang.reduce((t, b) => t + (Number(b.harga || 0) * Number(b.qty || 0)), 0)
            return totalJasa + totalPart
        },

        // ✅ FIX: getter sisaTagihan — single source of truth
        get sisaTagihan() {
            if (this.statusBayar === 'sudah') return 0
            return Math.max(0, this.grandTotal - Number(this.paymentAwal || 0) - this.totalCicilan)
        },

        formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID')
        },

        submitForm(form) {
            if (this.statusBayar === 'sudah') {
                this.$refs.paymentAwalHidden.value = this.grandTotal
            } else {
                this.$refs.paymentAwalHidden.value = this.paymentAwal || 0
            }
            form.submit()
        }
    }
}
</script>
@endsection
