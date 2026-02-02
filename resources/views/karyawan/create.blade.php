@extends('dashboard')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="w-full">

    <!-- HEADER -->
    <div class="mb-10">
        <h2 class="text-3xl font-semibold text-gray-800 leading-tight">
            Tambah Karyawan
        </h2>
        <p class="text-gray-500 mt-2">
            Lengkapi data karyawan dengan benar sebelum menyimpan
        </p>
    </div>

    <!-- FORM -->
    <form method="POST" action="{{ route('karyawan.store') }}"
          class="w-full bg-white rounded-2xl shadow px-16 py-12">
        @csrf

        <!-- FORM GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-12">

            <!-- Nama -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-3">
                    Nama Karyawan
                </label>
                <input type="text" name="nama" required
                       placeholder="Contoh: Budi Santoso"
                       class="h-14 w-full border border-gray-300 rounded-xl px-5
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <!-- Jabatan -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-3">
                    Jabatan
                </label>
                <input type="text" name="jabatan"
                       placeholder="Contoh: Mekanik"
                       class="h-14 w-full border border-gray-300 rounded-xl px-5
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <!-- Status -->
            {{-- <div class="flex flex-col md:col-span-2">
                <label class="text-sm font-medium text-gray-700 mb-3">
                    Status Karyawan
                </label>
                <select name="status"
                        class="h-14 w-full border border-gray-300 rounded-xl px-5
                               bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="aktif">Aktif</option>
                    <option value="cuti">Cuti</option>
                    <option value="resign">Resign</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div> --}}

        </div>

        <!-- FOOTER ACTION -->
        <div class="flex items-center justify-end gap-8 mt-16 pt-8 border-t">
            <a href="{{ route('karyawan.index') }}"
               class="text-gray-600 hover:text-gray-800 font-medium">
                Batal
            </a>

            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white
                           px-14 py-4 rounded-xl text-sm font-semibold shadow-md">
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
