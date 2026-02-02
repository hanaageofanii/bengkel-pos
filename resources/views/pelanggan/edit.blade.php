@extends('dashboard')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="w-full">

    <div class="mb-10">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Edit Pelanggan
        </h2>
        <p class="text-sm text-gray-500 mt-2">
            Perbarui data pelanggan dan kendaraan
        </p>
    </div>

    <form method="POST"
          action="{{ route('pelanggan.update', $pelanggan->id) }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 px-14 py-12">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">

            <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-semibold text-gray-700">
                    Nama Pelanggan / Perusahaan
                </label>
                <input name="nama"
                       required
                       value="{{ old('nama', $pelanggan->nama) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    No. HP
                </label>
                <input name="no_hp"
                       value="{{ old('no_hp', $pelanggan->no_hp) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Tipe Pelanggan
                </label>
                <select name="tipe"
                        class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               outline-none transition">
                    <option value="pribadi"
                        {{ old('tipe', $pelanggan->tipe) === 'pribadi' ? 'selected' : '' }}>
                        Pribadi
                    </option>
                    <option value="perusahaan"
                        {{ old('tipe', $pelanggan->tipe) === 'perusahaan' ? 'selected' : '' }}>
                        Perusahaan
                    </option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Plat Nomor
                </label>
                <input name="plat_nomor"
                       required
                       value="{{ old('plat_nomor', $pelanggan->plat_nomor) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition uppercase">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Merk Mobil
                </label>
                <input name="merk_mobil"
                       required
                       value="{{ old('merk_mobil', $pelanggan->merk_mobil) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Model Mobil
                </label>
                <input name="model_mobil"
                       required
                       value="{{ old('model_mobil', $pelanggan->model_mobil) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">
                    Tahun Mobil <span class="text-gray-400">(opsional)</span>
                </label>
                <input name="tahun_mobil"
                       value="{{ old('tahun_mobil', $pelanggan->tahun_mobil) }}"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              outline-none transition">
            </div>

        </div>

        <div class="flex items-center justify-end gap-6 mt-16 pt-8 border-t border-gray-100">
            <a href="{{ route('pelanggan.index') }}"
               class="px-6 py-3 rounded-lg text-sm font-medium text-gray-600
                      hover:text-gray-900 hover:bg-gray-100 transition">
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
