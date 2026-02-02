@extends('dashboard')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="w-full max-w-6xl mx-auto">

    <!-- HEADER -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Tambah Karyawan
        </h2>
        <p class="text-gray-500 mt-2 text-sm">
            Lengkapi data karyawan dengan benar sebelum menyimpan
        </p>
    </div>

    <!-- FORM CARD -->
    <form method="POST"
          action="{{ route('karyawan.store') }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 px-14 py-12">
        @csrf

        <!-- FORM GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-14 gap-y-10">

            <!-- Nama -->
            <div class="space-y-3">
                <label class="text-sm font-semibold text-gray-700">
                    Nama Karyawan
                </label>
                <input type="text"
                       name="nama"
                       required
                       value="{{ old('nama') }}"
                       placeholder="Contoh: Budi Santoso"
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
                       value="{{ old('jabatan') }}"
                       placeholder="Contoh: Mekanik, Admin"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition outline-none">
            </div>

            <!-- No HP -->
            <div class="space-y-3">
                <label class="text-sm font-semibold text-gray-700">
                    No. HP
                </label>
                <input type="text"
                       name="no_hp"
                       value="{{ old('no_hp') }}"
                       placeholder="Contoh: 08xxxxxxxxxx"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition outline-none">
            </div>

            <!-- Email -->
            <div class="space-y-3">
                <label class="text-sm font-semibold text-gray-700">
                    Email
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Contoh: nama@email.com"
                       class="w-full h-14 rounded-xl border border-gray-300 px-5 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              transition outline-none">
            </div>

            <!-- Status (Hidden, default aktif) -->
            <input type="hidden" name="status" value="aktif">

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
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
