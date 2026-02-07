@extends('dashboard')

@section('title','Buat Invoice')

@section('content')
<div x-data="invoiceForm()" class="w-full space-y-6">

    <!-- HEADER -->
    <div class="border-b-2 border-gray-300 pb-4">
        <h2 class="text-3xl font-bold text-gray-700">
            Buat Invoice
        </h2>
        <p class="text-sm text-gray-500 mt-2">
            Input transaksi servis & perbaikan kendaraan
        </p>
    </div>

    <!-- CARD FORM -->
    <form method="POST"
          action="{{ route('invoice.store') }}"
          class="bg-white border-2 border-gray-300 rounded-2xl shadow-lg overflow-hidden">
        @csrf

        <!-- ================= DATA PELANGGAN ================= -->
        <div class="px-8 py-6 border-b-2 border-gray-200">
            <h3 class="text-base font-bold text-gray-600 mb-6 pb-2 border-b border-gray-200">
                DATA PELANGGAN & KENDARAAN
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">Pelanggan</label>
                    <select name="pelanggan_id"
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400"
                            @change="setPelanggan($event)">
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}"
                                    data-tipe="{{ $p->tipe }}">
                                {{ $p->nama }} — {{ strtoupper($p->plat_nomor) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2 font-medium">
                        Tipe: <span x-text="tipePelanggan" class="font-bold uppercase text-gray-600"></span>
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           value="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">KM</label>
                    <input name="km"
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">No Telp</label>
                    <input name="no_telp"
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">No Chasis</label>
                    <input name="no_chasis"
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">No Mesin</label>
                    <input name="no_mesin"
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">
                </div>
            </div>
        </div>

        <!-- ================= KELUHAN ================= -->
        <div class="px-8 py-6 border-b-2 border-gray-200">
            <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-600">
                    KELUHAN
                </h3>
                <button type="button"
                        @click="keluhan.push('')"
                        class="px-4 py-2 text-xs font-bold text-gray-600 border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 transition-all">
                    + TAMBAH
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(k,index) in keluhan" :key="index">
                    <div class="flex gap-3 items-start">
                        <textarea name="keluhan[]"
                                  x-model="keluhan[index]"
                                  rows="2"
                                  class="flex-1 px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 resize-none"></textarea>
                        <button type="button"
                                @click="keluhan.splice(index,1)"
                                class="px-3 py-2 text-lg font-bold text-gray-500 border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 transition-all">
                            ×
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- ================= JASA ================= -->
        <div class="px-8 py-6 border-b-2 border-gray-200">
            <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-600">
                    JASA
                </h3>
                <button type="button"
                        @click="addJasa()"
                        class="px-4 py-2 text-xs font-bold text-gray-600 border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 transition-all">
                    + TAMBAH
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(j,index) in jasa" :key="index">
                    <div class="grid grid-cols-12 gap-3 items-center text-sm">
                        <select class="col-span-7 px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400"
                                @change="setJasa($event,index)">
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

                        <input name="jasa_harga[]"
                               x-model="j.harga"
                               placeholder="Harga"
                               class="col-span-3 px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white text-right focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">

                        <button type="button"
                                @click="jasa.splice(index,1)"
                                class="col-span-2 px-2 py-2 text-xs font-bold text-gray-500 border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 transition-all">
                                HAPUS
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- ================= BARANG ================= -->
        <div class="px-8 py-6 border-b-2 border-gray-200">
            <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-600">
                    SPARE PART
                </h3>
                <button type="button"
                        @click="addBarang()"
                        class="px-4 py-2 text-xs font-bold text-gray-600 border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 transition-all">
                    + TAMBAH
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(b,index) in barang" :key="index">
                    <div class="grid grid-cols-12 gap-3 items-center text-sm">
                        <select class="col-span-5 px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400"
                                @change="setBarang($event,index)">
                            <option value="">— Pilih Barang —</option>
                            @foreach($barangs as $br)
                                <option value="{{ $br->id }}"
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
                               placeholder="Qty"
                               class="col-span-2 px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white text-center focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">

                        <input name="barang_harga[]"
                               x-model="b.harga"
                               placeholder="Harga"
                               class="col-span-3 px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white text-right focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">

                        <button type="button"
                                @click="barang.splice(index,1)"
                                class="col-span-2 px-2 py-2 text-xs font-bold text-gray-500 border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 transition-all">
                                HAPUS
                        </button>
                    </div>
                </template>
            </div>
        </div>

<!-- ================= PEMBAYARAN ================= -->
        <div class="px-8 py-6 border-b-2 border-gray-200">
            <h3 class="text-base font-bold text-gray-600 mb-6 pb-2 border-b border-gray-200">
                PEMBAYARAN
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">Status Pembayaran</label>
                    <select name="status_bayar"
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">
                        <option value="belum">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase">Metode Pembayaran</label>
                    <select name="metode_bayar"
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400">
                        <option value="">— Pilih Metode —</option>
                        <option value="cash">Cash</option>
                        <option value="bca">Transfer BCA</option>
                        <option value="mandiri">Transfer Mandiri</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ================= ACTION ================= -->
        <div class="px-8 py-6 flex justify-end gap-4 bg-gray-50">
            <a href="{{ route('invoice.index') }}"
               class="px-8 py-3 text-sm font-bold text-gray-600 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-100 hover:border-gray-400 transition-all shadow-sm">
                BATAL
            </a>
            <button type="submit"
                class="px-8 py-3 text-sm font-bold bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                SIMPAN & PRINT
            </button>
        </div>

    </form>
</div>
<script>
function invoiceForm() {
    return {
        tipePelanggan: 'pribadi',
        keluhan: [''],
        jasa: [],
        barang: [],

        setPelanggan(e) {
            this.tipePelanggan =
                e.target.selectedOptions[0].dataset.tipe || 'pribadi'
            this.updateAllPrices()
        },

        updateAllPrices() {
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

        addJasa() {
            this.jasa.push({id:'', nama:'', harga:0, harga_pribadi:0, harga_perusahaan:0})
        },

        setJasa(e,i) {
            let o = e.target.selectedOptions[0]
            this.jasa[i] = {
                id: o.value,
                nama: o.dataset.nama,
                harga_pribadi: +o.dataset.pribadi,
                harga_perusahaan: +o.dataset.perusahaan,
                harga: this.tipePelanggan === 'perusahaan'
                    ? +o.dataset.perusahaan
                    : +o.dataset.pribadi
            }
        },

        addBarang() {
            this.barang.push({id:'', nama:'', qty:1, stock:0, harga:0, harga_pribadi:0, harga_perusahaan:0})
        },

        setBarang(e,i) {
            let o = e.target.selectedOptions[0]
            let stock = +o.dataset.stock

            if (stock <= 0) {
                alert('STOCK HABIS')
                return
            }

            if (stock <= 5) {
                alert(`PERINGATAN: Stock ${o.dataset.nama} tinggal ${stock}`)
            }

            this.barang[i] = {
                id: o.value,
                nama: o.dataset.nama,
                qty: 1,
                stock,
                harga_pribadi: +o.dataset.pribadi,
                harga_perusahaan: +o.dataset.perusahaan,
                harga: this.tipePelanggan === 'perusahaan'
                    ? +o.dataset.perusahaan
                    : +o.dataset.pribadi
            }
        },

        updateQty(i) {
            let b = this.barang[i]
            if (b.qty > b.stock) {
                alert('Qty melebihi stock')
                b.qty = b.stock
            }
        }
    }
}
</script>
@endsection
