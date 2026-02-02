@extends('dashboard')

@section('title', 'Stok Barang')

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
                Stok Barang
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Daftar barang dan harga
            </p>
        </div>

        <a href="{{ route('barang.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white
                  px-5 py-2.5 rounded-lg text-sm font-medium shadow-sm">
            + Tambah Barang
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left">Nama Barang</th>
                    <th class="px-6 py-4 text-right">Harga Pribadi</th>
                    <th class="px-6 py-4 text-right">Harga Perusahaan</th>
                    <th class="px-6 py-4 text-center">Stok</th>
                    <th class="px-6 py-4 text-center">Satuan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($barangs as $b)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $b->nama }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        Rp {{ number_format($b->harga_pribadi) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        Rp {{ number_format($b->harga_perusahaan) }}
                    </td>
                    <td class="px-6 py-4 text-center font-semibold">
                        {{ $b->stok }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ $b->satuan }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-6 text-sm font-medium">
                            <a href="{{ route('barang.edit', $b->id) }}"
                               class="text-blue-600 hover:text-blue-800">
                                Edit
                            </a>
                            <button
                                @click="open({{ $b->id }}, '{{ $b->nama }}')"
                                class="text-red-600 hover:text-red-800">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        Belum ada data barang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- DELETE MODAL -->
    <div x-show="show" x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
         style="display:none">

        <div @click.away="close"
             class="bg-white rounded-2xl shadow-xl w-full max-w-md px-8 py-10 text-center">

            <h3 class="text-lg font-semibold text-gray-800">
                Konfirmasi Hapus
            </h3>

            <p class="text-sm text-gray-600 mt-3">
                Yakin ingin menghapus
                <span class="font-semibold block mt-1" x-text="nama"></span>
            </p>

            <div class="mt-8 flex justify-center gap-4">
                <button @click="close"
                        class="px-6 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100">
                    Batal
                </button>

                <form :action="url" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="px-6 py-2 rounded-lg text-sm
                                   bg-red-600 hover:bg-red-700 text-white">
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
            this.url = `/barang/${id}`
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
