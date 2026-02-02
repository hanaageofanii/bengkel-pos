@extends('dashboard')

@section('title', 'Data Karyawan')

@section('content')
<div x-data="deleteModal()" class="w-full">

    {{-- Flash message --}}
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700
                    px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Data Karyawan
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Daftar karyawan yang terdaftar di sistem
            </p>
        </div>

        <a href="{{ route('karyawan.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white
                  px-5 py-2.5 rounded-lg text-sm font-medium shadow-sm">
            + Tambah Karyawan
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-left font-medium">Nama</th>
                    <th class="p-4 text-left font-medium">Jabatan</th>
                    <th class="p-4 text-left font-medium">Kontak</th>
                    <th class="p-4 text-left font-medium">Status</th>
                    <th class="p-4 text-right font-medium">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($karyawans as $k)
                <tr class="border-t hover:bg-gray-50 transition">

                    <!-- NAMA -->
                    <td class="p-4 font-medium text-gray-800">
                        {{ $k->nama }}
                    </td>

                    <!-- JABATAN -->
                    <td class="p-4 text-gray-600">
                        {{ $k->jabatan ?? '-' }}
                    </td>

                    <!-- KONTAK -->
                    <td class="p-4 text-gray-600">
                        <div class="space-y-0.5 text-xs">
                            <div>
                                📱 {{ $k->no_hp ?? '-' }}
                            </div>
                            <div>
                                ✉️ {{ $k->email ?? '-' }}
                            </div>
                        </div>
                    </td>

                    <!-- STATUS -->
                    <td class="p-4">
                        @switch($k->status)
                            @case('aktif')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                             bg-green-100 text-green-700">
                                    Aktif
                                </span>
                                @break
                            @case('cuti')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                             bg-yellow-100 text-yellow-700">
                                    Cuti
                                </span>
                                @break
                            @case('resign')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                             bg-gray-200 text-gray-700">
                                    Resign
                                </span>
                                @break
                            @case('nonaktif')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                             bg-red-100 text-red-700">
                                    Nonaktif
                                </span>
                                @break
                        @endswitch
                    </td>

                    <!-- AKSI -->
                    <td class="p-4">
                        <div class="flex justify-end gap-6">

                            <a href="{{ route('karyawan.edit', $k->id) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Edit
                            </a>

                            <button
                                @click="open({{ $k->id }}, '{{ $k->nama }}')"
                                class="text-red-600 hover:text-red-800 font-medium">
                                Hapus
                            </button>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        Belum ada data karyawan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- DELETE MODAL -->
    <div x-show="show"
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         style="display: none">

        <div @click.away="show = false"
             class="bg-white rounded-2xl shadow-xl w-full max-w-md px-8 py-10 text-center">

            <div class="mx-auto mb-6 flex h-12 w-12 items-center justify-center
                        rounded-full bg-red-100 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-gray-800">
                Konfirmasi Hapus
            </h3>

            <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                Apakah kamu yakin ingin menghapus
                <span class="font-semibold text-gray-800 block mt-1"
                      x-text="nama"></span>
                <span class="block mt-2 text-xs text-gray-500">
                    Data yang dihapus tidak bisa dikembalikan.
                </span>
            </p>

            <div class="mt-8 flex items-center justify-center gap-4">
                <button
                    @click="show = false"
                    class="px-6 py-2.5 rounded-lg text-sm font-medium
                           text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>

                <form :action="url" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-6 py-2.5 rounded-lg text-sm font-semibold
                                   bg-red-600 hover:bg-red-700 text-white
                                   shadow shadow-red-200 transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function deleteModal() {
    return {
        show: false,
        url: '',
        nama: '',
        open(id, nama) {
            this.url = `/karyawan/${id}`;
            this.nama = nama;
            this.show = true;
        }
    }
}
</script>
@endsection
