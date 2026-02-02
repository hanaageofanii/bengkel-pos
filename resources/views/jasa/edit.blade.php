@extends('dashboard')

@section('title', 'Edit Jasa')

@section('content')
<div class="w-full">

    <!-- HEADER -->
    <div class="mb-14">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Edit Jasa
        </h2>
        <p class="text-sm text-gray-500 mt-2 max-w-xl">
            Perbarui informasi jasa pekerjaan, harga pelanggan pribadi dan perusahaan.
        </p>
    </div>

    <!-- FORM CARD -->
    <form method="POST"
          action="{{ route('jasa.update', $jasa->id) }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 px-16 py-14">
        @csrf
        @method('PUT')

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-14 gap-y-10">

            <!-- NAMA JASA -->
            <div class="space-y-3 md:col-span-2">
                <label class="text-sm font-semibold text-gray-700">
                    Nama Jasa
                </label>
                <input name="nama"
                       required
                       value="{{ old('nama', $jasa->nama) }}"
                       placeholder="Contoh: Servis Ringan, Ganti Oli"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <!-- HARGA PRIBADI -->
            <div class="space-y-3">
                <label class="text-sm font-semibold text-gray-700">
                    Harga Pribadi
                </label>
                <input name="harga_pribadi"
                       type="number"
                       required
                       value="{{ old('harga_pribadi', $jasa->harga_pribadi) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <!-- HARGA PERUSAHAAN -->
            <div class="space-y-3">
                <label class="text-sm font-semibold text-gray-700">
                    Harga Perusahaan
                </label>
                <input name="harga_perusahaan"
                       type="number"
                       required
                       value="{{ old('harga_perusahaan', $jasa->harga_perusahaan) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <!-- KETERANGAN -->
            <div class="space-y-3 md:col-span-2">
                <label class="text-sm font-semibold text-gray-700">
                    Keterangan (Opsional)
                </label>
                <textarea name="keterangan"
                          rows="4"
                          placeholder="Catatan tambahan tentang jasa, contoh: estimasi waktu pengerjaan"
                          class="w-full rounded-xl border border-gray-300 px-5 py-4 text-sm
                                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                 outline-none transition">{{ old('keterangan', $jasa->keterangan) }}</textarea>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="flex items-center justify-end gap-6 mt-16 pt-10 border-t border-gray-100">
            <a href="{{ route('jasa.index') }}"
               class="px-6 py-3 rounded-lg text-sm font-medium text-gray-600
                      hover:bg-gray-100 hover:text-gray-900 transition">
                Batal
            </a>

            <button type="submit"
                    class="px-12 py-3 rounded-xl text-sm font-semibold text-white
                           bg-green-600 hover:bg-green-700
                           shadow-md shadow-green-200 transition">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection
