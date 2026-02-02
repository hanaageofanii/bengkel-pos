@extends('dashboard')

@section('title', 'Tambah Barang')

@section('content')
<div class="w-full">

    <!-- HEADER -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Tambah Barang
        </h2>
        <p class="text-sm text-gray-500 mt-2">
            Masukkan data barang, harga, dan stok dengan benar
        </p>
    </div>

    <!-- FORM CARD -->
    <form method="POST"
          action="{{ route('barang.store') }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 px-14 py-12">
        @csrf

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">

            <!-- NAMA BARANG -->
            <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-semibold text-gray-700">
                    Nama Barang
                </label>
                <input name="nama"
                       required
                       value="{{ old('nama') }}"
                       placeholder="Contoh: Oli Mesin, Kampas Rem"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <!-- HARGA PRIBADI -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Harga Pribadi
                </label>
                <input name="harga_pribadi"
                       type="number"
                       required
                       value="{{ old('harga_pribadi') }}"
                       placeholder="Contoh: 75000"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <!-- HARGA PERUSAHAAN -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Harga Perusahaan
                </label>
                <input name="harga_perusahaan"
                       type="number"
                       required
                       value="{{ old('harga_perusahaan') }}"
                       placeholder="Contoh: 65000"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <!-- STOK -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Stok
                </label>
                <input name="stok"
                       type="number"
                       required
                       value="{{ old('stok', 0) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <!-- SATUAN -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Satuan
                </label>
                <input name="satuan"
                       value="{{ old('satuan', 'pcs') }}"
                       placeholder="pcs / liter / set"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

        </div>

        <!-- FOOTER ACTION -->
        <div class="flex items-center justify-end gap-6 mt-16 pt-8 border-t border-gray-100">
            <a href="{{ route('barang.index') }}"
               class="px-6 py-3 rounded-lg text-sm font-medium text-gray-600
                      hover:text-gray-900 hover:bg-gray-100 transition">
                Batal
            </a>

            <button type="submit"
                    class="px-12 py-3 rounded-xl text-sm font-semibold text-white
                           bg-blue-600 hover:bg-blue-700
                           shadow-md shadow-blue-200 transition">
                Simpan Barang
            </button>
        </div>

    </form>
</div>
@endsection
