@extends('dashboard')

@section('title', 'Edit Karyawan')

@section('content')
<div class="w-full">

    <!-- HEADER -->
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Edit Karyawan
        </h2>
        <p class="text-gray-500 mt-2 text-sm">
            Perbarui dan sesuaikan informasi karyawan
        </p>
    </div>

    <!-- FORM CARD -->
    <form method="POST"
          action="{{ route('karyawan.update', $karyawan->id) }}"
          class="w-full bg-white rounded-2xl shadow-sm border border-gray-100 px-16 py-12">
        @csrf
        @method('PUT')

        <!-- FORM GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-14 gap-y-10">

            <!-- Nama -->
            <div class="space-y-3">
                <label class="text-sm font-semibold text-gray-700">
                    Nama Karyawan
                </label>
                <input type="text"
                       name="nama"
                       value="{{ old('nama', $karyawan->nama) }}"
                       placeholder="Masukkan nama karyawan"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition outline-none">
            </div>

            <!-- Jabatan -->
            <div class="space-y-3">
                <label class="text-sm font-semibold text-gray-700">
                    Jabatan
                </label>
                <input type="text"
                       name="jabatan"
                       value="{{ old('jabatan', $karyawan->jabatan) }}"
                       placeholder="Contoh: Mekanik, Admin"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition outline-none">
            </div>

            <!-- Status -->
            <div class="space-y-3 md:col-span-2">
                <label class="text-sm font-semibold text-gray-700">
                    Status Karyawan
                </label>
                <select name="status"
                        class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               transition outline-none">
                    <option value="aktif"
                        {{ old('status', $karyawan->status) === 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="cuti"
                        {{ old('status', $karyawan->status) === 'cuti' ? 'selected' : '' }}>
                        Cuti
                    </option>
                    <option value="resign"
                        {{ old('status', $karyawan->status) === 'resign' ? 'selected' : '' }}>
                        Resign
                    </option>
                    <option value="nonaktif"
                        {{ old('status', $karyawan->status) === 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

        </div>

        <!-- ACTION FOOTER -->
        <div class="flex items-center justify-end gap-6 mt-16 pt-8 border-t border-gray-100">
            <a href="{{ route('karyawan.index') }}"
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
