@extends('dashboard')

@section('title','Edit Invoice')

@section('content')
<div x-data="invoiceEditForm()" class="w-full space-y-8">

    <!-- HEADER -->
    <div class="border-b pb-4">
        <h2 class="text-3xl font-bold text-gray-800">
            Edit Invoice
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            {{ $invoice->invoice_no }}
        </p>
    </div>

    <!-- FORM -->
    <form method="POST"
          action="{{ route('invoice.update',$invoice) }}"
          class="bg-white rounded-2xl shadow border overflow-hidden">
        @csrf
        @method('PUT')

        <!-- ================= DATA PELANGGAN ================= -->
        <div class="p-6 border-b">
            <h3 class="font-bold text-gray-600 mb-4">
                Data Pelanggan & Kendaraan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <label class="label">Pelanggan</label>
                   <select name="pelanggan_id"
                            class="input"
                            @change="setPelanggan($event)">
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}"
                                data-tipe="{{ $p->tipe }}"
                                {{ $invoice->pelanggan_id == $p->id ? 'selected':'' }}>
                                {{ $p->nama }} — {{ strtoupper($p->plat_nomor) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label">Tanggal</label>
                    <input type="date" name="tanggal"
                           value="{{ $invoice->tanggal }}"
                           class="input">
                </div>

                <div>
                    <label class="label">KM</label>
                    <input name="km" value="{{ $invoice->km }}" class="input">
                </div>

                <div>
                    <label class="label">No Telp</label>
                    <input name="no_telp" value="{{ $invoice->no_telp }}" class="input">
                </div>

                <div>
                    <label class="label">No Chasis</label>
                    <input name="no_chasis" value="{{ $invoice->no_chasis }}" class="input">
                </div>

                <div>
                    <label class="label">No Mesin</label>
                    <input name="no_mesin" value="{{ $invoice->no_mesin }}" class="input">
                </div>
            </div>
        </div>

        <!-- ================= KELUHAN ================= -->
        <div class="p-6 border-b">
            <div class="flex justify-between mb-4">
                <h3 class="font-bold text-gray-600">Keluhan</h3>
                <button type="button"
                        @click="keluhan.push('')"
                        class="btn-secondary">
                    + Tambah
                </button>
            </div>

            <template x-for="(k,i) in keluhan" :key="i">
                <div class="flex gap-3 mb-2">
                    <textarea name="keluhan[]"
                              x-model="keluhan[i]"
                              class="input flex-1"></textarea>
                    <button type="button"
                            @click="keluhan.splice(i,1)"
                            class="btn-danger">×</button>
                </div>
            </template>
        </div>

        <!-- ================= JASA ================= -->
<div class="p-6 border-b">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-gray-600">Jasa</h3>
        <button type="button"
                @click="addJasa()"
                class="btn-secondary">
            + Tambah
        </button>
    </div>

    <template x-for="(j,i) in jasa" :key="i">
        <div class="grid grid-cols-12 gap-3 items-center mb-2 text-sm">

            <!-- SELECT JASA -->
            <select class="col-span-6 input h-10"
                    @change="setJasa($event,i)">
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

            <!-- HIDDEN -->
            <input type="hidden" name="jasa_id[]" :value="j.id">
            <input type="hidden" name="jasa_nama[]" :value="j.nama">

            <!-- HARGA -->
            <input name="jasa_harga[]"
                   x-model="j.harga"
                   class="col-span-5 input h-10 text-right">

            <!-- HAPUS -->
            <button type="button"
                    @click="jasa.splice(i,1)"
                    class="btn-danger h-10 flex items-center justify-center">
                Hapus
            </button>
        </div>
    </template>
</div>

        <!-- ================= BARANG ================= -->
        <div class="p-6 border-b">
            <div class="flex justify-between mb-4">
                <h3 class="font-bold text-gray-600">Spare Part</h3>
                <button type="button"
                        @click="addBarang()"
                        class="btn-secondary">
                    + Tambah
                </button>
            </div>

            <template x-for="(b,i) in barang" :key="i">
                <div class="grid grid-cols-12 gap-3 mb-2 text-sm">
                    <select class="col-span-5 input"
                            @change="setBarang($event,i)">
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

                    <input type="number"
                           min="1"
                           name="barang_qty[]"
                           x-model="b.qty"
                           class="col-span-2 input text-center">

                    <input name="barang_harga[]"
                           x-model="b.harga"
                           class="col-span-4 input text-right">

                    <button type="button"
                            @click="barang.splice(i,1)"
                            class="btn-danger">
                        Hapus
                    </button>
                </div>
            </template>
        </div>

<!-- ================= PEMBAYARAN ================= -->
<div class="px-8 py-6 border-b-2 border-gray-200">

    <h3 class="text-base font-bold text-gray-600 mb-6 pb-2 border-b border-gray-200">
        PEMBAYARAN
    </h3>

    <!-- STATUS & METODE -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-6">

        <!-- STATUS -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">
                Status Pembayaran
            </label>
            <select name="status_bayar"
                x-model="statusBayar"
                @change="if(statusBayar==='sudah'){ paymentAwal = grandTotal }"
                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg">

                <option value="belum">Belum Lunas</option>
                <option value="sudah">Lunas</option>

            </select>
        </div>

        <!-- METODE -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">
                Metode Pembayaran
            </label>

            <select name="metode_bayar"
                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg">

                <option value="">— Pilih Metode —</option>

                <option value="cash"
                    {{ $invoice->metode_bayar == 'cash' ? 'selected' : '' }}>
                    Cash
                </option>

                <option value="bca"
                    {{ $invoice->metode_bayar == 'bca' ? 'selected' : '' }}>
                    Transfer BCA
                </option>

                <option value="mandiri"
                    {{ $invoice->metode_bayar == 'mandiri' ? 'selected' : '' }}>
                    Transfer Mandiri
                </option>

            </select>
        </div>
    </div>


    <!-- DP & SISA -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

        <!-- PAYMENT AWAL -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">
                Payment Awal (DP)
            </label>

            <!-- BELUM -->
            <template x-if="statusBayar === 'belum'">
                <input type="number"
                    name="payment_awal"
                    x-model="paymentAwal"
                    min="0"
                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg">
            </template>

            <!-- SUDAH -->
            <template x-if="statusBayar === 'sudah'">
                <div>
                    <div class="w-full px-4 py-2.5 border-2 border-green-400 bg-green-100 text-green-700 rounded-lg font-bold text-center">
                        LUNAS
                    </div>
                    <input type="hidden" name="payment_awal" :value="grandTotal">
                </div>
            </template>
        </div>


        <!-- SISA (DISPLAY ONLY - TIDAK DIKIRIM) -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">
                Sisa Tagihan
            </label>

            <template x-if="statusBayar === 'belum'">
                <input type="text"
                    :value="formatRupiah(grandTotal - paymentAwal)"
                    readonly
                    class="w-full px-4 py-2.5 border-2 border-gray-200 bg-gray-100 rounded-lg font-semibold">
            </template>

            <template x-if="statusBayar === 'sudah'">
                <div class="w-full px-4 py-2.5 border-2 border-green-400 bg-green-100 text-green-700 rounded-lg font-bold text-center">
                    LUNAS
                </div>
            </template>
        </div>

    </div>
</div>
</div>
        <!-- ACTION -->
        <div class="p-6 flex justify-end gap-4 bg-gray-50">
            <a href="{{ route('invoice.index') }}" class="btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn-primary">
                Update Invoice
            </button>
        </div>
    </form>
</div>

<script>
function invoiceEditForm() {
    return {

        /* ================= MASTER DATA ================= */
        jasaMaster: @json($jasas),
        barangMaster: @json($barangs),

        /* ================= DATA DARI DB ================= */
        keluhan: @json($invoice->keluhan ?? []),
        jasa: @json($invoice->jasa ?? []),
        barang: @json($invoice->barang ?? []),

        statusBayar: @json($invoice->status_bayar ?? 'belum'),
        paymentAwal: @json($invoice->payment_awal ?? 0),

        tipePelanggan: 'pribadi',

        /* ================= INIT ================= */
        init() {

            const select = document.querySelector('[name="pelanggan_id"]')
            if (select) {
                this.tipePelanggan =
                    select.selectedOptions[0].dataset.tipe || 'pribadi'
            }

            this.injectHargaAwal()
        },

        /* ================= INJECT HARGA SAAT LOAD ================= */
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

        /* ================= GANTI TIPE ================= */
        setPelanggan(e) {
            this.tipePelanggan =
                e.target.selectedOptions[0].dataset.tipe || 'pribadi'

            this.updateHargaByTipe()
        },

        updateHargaByTipe() {
            this.jasa.forEach(j => {
                j.harga = this.tipePelanggan === 'perusahaan'
                    ? j.harga_perusahaan
                    : j.harga_pribadi
            })

            this.barang.forEach(b => {
                b.harga = this.tipePelanggan === 'perusahaan'
                    ? b.harga_perusahaan
                    : b.harga_pribadi
            })
        },

        /* ================= SET JASA ================= */
        setJasa(e, i) {
            const master = this.jasaMaster.find(x => x.id == e.target.value)
            if (!master) return

            this.jasa[i] = {
                id: master.id,
                nama: master.nama,
                harga_pribadi: master.harga_pribadi,
                harga_perusahaan: master.harga_perusahaan,
                harga: this.tipePelanggan === 'perusahaan'
                    ? master.harga_perusahaan
                    : master.harga_pribadi
            }
        },

        /* ================= SET BARANG ================= */
        setBarang(e, i) {
            const master = this.barangMaster.find(x => x.id == e.target.value)
            if (!master) return

            this.barang[i] = {
                id: master.id,
                nama: master.nama,
                qty: this.barang[i]?.qty ?? 1,
                harga_pribadi: master.harga_pribadi,
                harga_perusahaan: master.harga_perusahaan,
                harga: this.tipePelanggan === 'perusahaan'
                    ? master.harga_perusahaan
                    : master.harga_pribadi
            }
        },

        /* ================= GRAND TOTAL ================= */
        get grandTotal() {

            const totalJasa = this.jasa.reduce(
                (t, j) => t + Number(j.harga || 0), 0
            )

            const totalPart = this.barang.reduce(
                (t, b) => t + (Number(b.harga || 0) * Number(b.qty || 0)), 0
            )

            return totalJasa + totalPart
        },

        /* ================= SISA ================= */
        get sisa() {
            return this.grandTotal - Number(this.paymentAwal || 0)
        },

        /* ================= FORMAT RUPIAH ================= */
        formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID')
        }
    }
}
</script>
<style>
.label{font-weight:600;font-size:12px;color:#555}
.input{width:100%;padding:8px;border:1px solid #ccc;border-radius:8px}
.btn-primary{background:#16a34a;color:#fff;padding:10px 20px;border-radius:10px}
.btn-secondary{background:#e5e7eb;padding:8px 16px;border-radius:8px}
.btn-danger{background:#fee2e2;color:#991b1b;padding:8px 12px;border-radius:8px}
</style>
@endsection
