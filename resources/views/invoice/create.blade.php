@extends('dashboard')

@section('title','Buat Invoice')

@section('content')
<div x-data="invoiceForm()" class="max-w-7xl mx-auto space-y-10">

    <!-- HEADER -->
    <div>
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Buat Invoice
        </h2>
        <p class="text-sm text-gray-500">
            5A Auto Service
        </p>
    </div>

    <form method="POST"
          action="{{ route('invoice.store') }}"
          class="space-y-10">
        @csrf

        <!-- ================= DATA PELANGGAN ================= -->
        <div class="bg-white rounded-2xl shadow-sm border p-8">
            <h3 class="text-lg font-semibold mb-6">
                Data Pelanggan & Kendaraan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

                <div>
                    <label class="font-medium text-gray-700">Pelanggan</label>
                    <select name="pelanggan_id"
                            class="input mt-1"
                            @change="setPelanggan($event)">
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}"
                                    data-tipe="{{ $p->tipe }}">
                                {{ $p->nama }} — {{ strtoupper($p->plat_nomor) }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Tipe:
                        <span x-text="tipePelanggan"
                              class="font-semibold capitalize"></span>
                    </p>
                </div>

                <div>
                    <label class="font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="tanggal" class="input mt-1">
                </div>

                <div>
                    <label class="font-medium text-gray-700">KM</label>
                    <input name="km" class="input mt-1" placeholder="49680">
                </div>

                <div>
                    <label class="font-medium text-gray-700">No Telp</label>
                    <input name="no_telp" class="input mt-1">
                </div>

                <div>
                    <label class="font-medium text-gray-700">No Chasis</label>
                    <input name="no_chasis" class="input mt-1">
                </div>

                <div>
                    <label class="font-medium text-gray-700">No Mesin</label>
                    <input name="no_mesin" class="input mt-1">
                </div>

            </div>
        </div>

        <!-- ================= KELUHAN ================= -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Keluhan</h3>
                <button type="button"
                        @click="keluhan.push('')"
                        class="text-sm text-blue-600 hover:underline">
                    + Tambah Keluhan
                </button>
            </div>

            <template x-for="(k,index) in keluhan" :key="index">
                <div class="flex gap-3">
                    <textarea name="keluhan[]"
                              x-model="keluhan[index]"
                              class="input w-full"
                              placeholder="Keluhan kendaraan"></textarea>

                    <button type="button"
                            @click="keluhan.splice(index,1)"
                            class="text-red-500 text-sm">
                        ✕
                    </button>
                </div>
            </template>
        </div>

        <!-- ================= JASA ================= -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 space-y-5">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Pekerjaan / Jasa</h3>
                <button type="button"
                        @click="addJasa()"
                        class="text-sm text-blue-600 hover:underline">
                    + Tambah Jasa
                </button>
            </div>

            <template x-for="(j,index) in jasa" :key="index">
                <div class="grid grid-cols-12 gap-3 items-center text-sm">

                    <select class="input col-span-7"
                            @change="setJasa($event,index)">
                        <option value="">Pilih Jasa</option>
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
                           class="input col-span-3 text-right">

                    <button type="button"
                            @click="jasa.splice(index,1)"
                            class="text-red-500 col-span-2 text-sm">
                        Hapus
                    </button>
                </div>
            </template>
        </div>

        <!-- ================= BARANG ================= -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 space-y-5">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Spare Part</h3>
                <button type="button"
                        @click="addBarang()"
                        class="text-sm text-blue-600 hover:underline">
                    + Tambah Barang
                </button>
            </div>

            <template x-for="(b,index) in barang" :key="index">
                <div class="grid grid-cols-12 gap-3 items-center text-sm">

                    <select class="input col-span-5"
                            @change="setBarang($event,index)">
                        <option value="">Pilih Barang</option>
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
                           class="input col-span-2 text-center">

                    <input name="barang_harga[]"
                           x-model="b.harga"
                           class="input col-span-3 text-right">

                    <button type="button"
                            @click="barang.splice(index,1)"
                            class="text-red-500 col-span-2 text-sm">
                        Hapus
                    </button>
                </div>
            </template>
        </div>

        <!-- ================= PEMBAYARAN ================= -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="font-medium text-gray-700">Status Bayar</label>
                <select name="status_bayar" class="input mt-1">
                    <option value="belum">Belum Bayar</option>
                    <option value="sudah">Sudah Bayar</option>
                </select>
            </div>

            <div>
                <label class="font-medium text-gray-700">Metode Bayar</label>
                <select name="metode_bayar" class="input mt-1">
                    <option value="">-</option>
                    <option value="cash">Cash</option>
                    <option value="mandiri">Transfer Mandiri</option>
                    <option value="bca">Transfer BCA</option>
                </select>
            </div>
        </div>

        <!-- ================= ACTION ================= -->
        <div class="flex justify-end gap-4 pt-6">
            <a href="{{ route('invoice.index') }}"
               class="px-6 py-3 rounded-lg border text-sm">
                Batal
            </a>

            <button class="px-10 py-3 rounded-lg bg-blue-600
                           hover:bg-blue-700 text-white font-semibold shadow">
                Simpan & Print
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
        },

        addJasa() {
            this.jasa.push({id:'', nama:'', harga:0})
        },
        setJasa(e,i) {
            let opt = e.target.selectedOptions[0]
            this.jasa[i].id = opt.value
            this.jasa[i].nama = opt.dataset.nama
            this.jasa[i].harga =
                this.tipePelanggan === 'perusahaan'
                    ? opt.dataset.perusahaan
                    : opt.dataset.pribadi
        },

        addBarang() {
            this.barang.push({id:'', nama:'', qty:1, harga:0})
        },
        setBarang(e,i) {
            let opt = e.target.selectedOptions[0]
            this.barang[i].id = opt.value
            this.barang[i].nama = opt.dataset.nama
            this.barang[i].harga =
                this.tipePelanggan === 'perusahaan'
                    ? opt.dataset.perusahaan
                    : opt.dataset.pribadi
        }
    }
}
</script>
@endsection
