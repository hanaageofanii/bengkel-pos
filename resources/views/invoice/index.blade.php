@extends('dashboard')

@section('title','Invoice')

@section('content')
<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                Invoice
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Daftar transaksi servis & perbaikan kendaraan
            </p>
        </div>

        <a href="{{ route('invoice.create') }}"
           class="inline-flex items-center gap-2
                  bg-blue-600 hover:bg-blue-700
                  text-white px-6 py-3
                  rounded-xl text-sm font-semibold shadow-sm">
            + Buat Invoice
        </a>
    </div>

    <div class="flex justify-start">
        <form method="GET" class="w-full md:w-auto">
            <div class="relative w-full md:w-[420px]">
                <input name="q"
                       value="{{ $q }}"
                       placeholder="Cari invoice, pelanggan, plat, atau mobil…"
                       class="w-full pl-5 pr-28 py-2.5
                              border border-gray-300
                              rounded-xl
                              text-sm
                              bg-white
                              focus:outline-none
                              focus:ring-2
                              focus:ring-blue-200
                              focus:border-blue-400">

                <button type="submit"
                        class="absolute right-1 top-1 bottom-1
                               px-5
                               rounded-lg
                               bg-blue-600 hover:bg-blue-700
                               text-white text-sm font-semibold">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Invoice</th>
                    <th class="px-6 py-4 text-left font-semibold">Pelanggan</th>
                    <th class="px-6 py-4 text-center font-semibold">Tanggal</th>
                    <th class="px-6 py-4 text-right font-semibold">Total</th>
                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                    <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($invoices as $i)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-5 font-semibold text-gray-900">
                        {{ $i->invoice_no }}
                    </td>

                    <td class="px-6 py-5">
                        <div class="font-medium text-gray-900">
                            {{ $i->pelanggan->nama }}
                        </div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">
                            {{ $i->pelanggan->plat_nomor }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $i->pelanggan->merk_mobil }}
                            {{ $i->pelanggan->model_mobil }}
                        </div>
                    </td>

                    <td class="px-6 py-5 text-center text-gray-600">
                        {{ \Carbon\Carbon::parse($i->tanggal)->format('d M Y') }}
                    </td>

                    <td class="px-6 py-5 text-right font-bold text-gray-900">
                        Rp {{ number_format($i->grand_total) }}
                    </td>

                    <td class="px-6 py-5 text-center">
                        @if($i->status_bayar === 'sudah')
                            <span class="inline-flex px-3 py-1 rounded-full
                                         bg-green-100 text-green-700
                                         text-xs font-semibold">
                                ● Sudah Bayar
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-full
                                         bg-red-100 text-red-700
                                         text-xs font-semibold">
                                ● Belum Bayar
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-5 text-right space-x-2">

                        <!-- LIHAT -->
                        <a href="{{ route('invoice.show',$i) }}"
                        class="px-3 py-2 rounded-lg
                                bg-gray-100 text-gray-700
                                text-xs font-semibold">
                            Lihat
                        </a>

                        <!-- EDIT -->
                        <a href="{{ route('invoice.edit',$i) }}"
                        class="px-3 py-2 rounded-lg
                                bg-yellow-100 text-yellow-700
                                text-xs font-semibold">
                            Edit
                        </a>

                        <!-- PRINT -->
                        <a href="{{ route('invoice.print',$i) }}"
                        class="px-3 py-2 rounded-lg
                                bg-blue-100 text-blue-700
                                text-xs font-semibold">
                            Print
                        </a>

                    </td>


                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        class="px-6 py-20 text-center text-gray-500">
                        Belum ada invoice
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div>
        {{ $invoices->links() }}
    </div>

</div>
@endsection
