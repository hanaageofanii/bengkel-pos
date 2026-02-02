@extends('dashboard')

@section('title', 'Jasa Pekerjaan')

@section('content')
<div class="w-full" x-data="deleteModal()">

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700
                    px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
                Jasa Pekerjaan
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Daftar jasa servis dan pekerjaan
            </p>
        </div>

        <a href="{{ route('jasa.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white
                  px-5 py-2.5 rounded-lg text-sm font-medium shadow-sm">
            + Tambah Jasa
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left">Nama Jasa</th>
                    <th class="px-6 py-4 text-right">Harga Pribadi</th>
                    <th class="px-6 py-4 text-right">Harga Perusahaan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jasas as $j)
                <tr class="border-t hover:bg-gray-50 transition">

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $j->nama }}
                        @if($j->keterangan)
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $j->keterangan }}
                            </div>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        Rp {{ number_format($j->harga_pribadi) }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        Rp {{ number_format($j->harga_perusahaan) }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-6 text-sm font-medium">
                            <a href="{{ route('jasa.edit', $j->id) }}"
                               class="text-blue-600 hover:text-blue-800 transition">
                                Edit
                            </a>

                            <button
                                @click="open({{ $j->id }}, '{{ $j->nama }}')"
                                class="text-red-600 hover:text-red-800 transition">
                                Hapus
                            </button>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        Belum ada data jasa
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
         style="display:none">

        <div @click.away="close"
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

            <p class="text-sm text-gray-600 mt-3">
                Yakin ingin menghapus jasa
                <span class="font-semibold block mt-1" x-text="nama"></span>
                <span class="block mt-2 text-xs text-gray-500">
                    Data yang dihapus tidak bisa dikembalikan.
                </span>
            </p>

            <div class="mt-8 flex justify-center gap-4">
                <button
                    @click="close"
                    class="px-6 py-2.5 rounded-lg text-sm text-gray-600
                           hover:bg-gray-100 transition">
                    Batal
                </button>

                <form :action="url" method="POST">
                    @csrf
                    @method('DELETE')
                    <button
                        class="px-6 py-2.5 rounded-lg text-sm
                               bg-red-600 hover:bg-red-700 text-white
                               shadow shadow-red-200 transition">
                        Hapus
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
            this.url = `/jasa/${id}`
            this.nama = nama
            this.show = true
        },
        close() {
            this.show = false
        }
    }
}
</script>
@endsection
