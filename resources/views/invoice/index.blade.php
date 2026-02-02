@extends('dashboard')

@section('title','Invoice')

@section('content')
<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
                Invoice
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Daftar transaksi servis & perbaikan kendaraan
            </p>
        </div>

        <a href="{{ route('invoice.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white
                  px-5 py-2.5 rounded-lg text-sm font-medium shadow-sm">
            + Buat Invoice
        </a>
    </div>

    <!-- SEARCH -->
    <div class="bg-white rounded-xl shadow-sm border p-4">
        <form method="GET" class="flex gap-3">
            <input name="q"
                   value="{{ $q }}"
                   placeholder="Cari invoice / nama / plat / merk mobil"
                   class="input w-full md:w-96">

            <button class="px-5 py-2 rounded-lg bg-gray-800 text-white text-sm">
                Cari
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left font-medium">
                        Invoice
                    </th>
                    <th class="px-6 py-4 text-left font-medium">
                        Pelanggan
                    </th>
                    <th class="px-6 py-4 text-center font-medium">
                        Tanggal
                    </th>
                    <th class="px-6 py-4 text-right font-medium">
                        Total
                    </th>
                    <th class="px-6 py-4 text-center font-medium">
                        Status
                    </th>
                    <th class="px-6 py-4 text-right font-medium">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse($invoices as $i)
                <tr class="border-t hover:bg-gray-50 transition">

                    <!-- INVOICE NO -->
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        {{ $i->invoice_no }}
                    </td>

                    <!-- PELANGGAN -->
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">
                            {{ $i->pelanggan->nama }}
                        </div>
                        <div class="text-xs text-gray-500 tracking-wide uppercase">
                            {{ $i->pelanggan->plat_nomor }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $i->pelanggan->merk_mobil }}
                            {{ $i->pelanggan->model_mobil }}
                        </div>
                    </td>

                    <!-- TANGGAL -->
                    <td class="px-6 py-4 text-center text-gray-600">
                        {{ \Carbon\Carbon::parse($i->tanggal)->format('d M Y') }}
                    </td>

                    <!-- TOTAL -->
                    <td class="px-6 py-4 text-right font-semibold">
                        Rp {{ number_format($i->grand_total) }}
                    </td>

                    <!-- STATUS -->
                    <td class="px-6 py-4 text-center">
                        @if($i->status_bayar === 'sudah')
                            <span class="inline-flex px-3 py-1 rounded-full
                                         bg-green-100 text-green-700
                                         text-xs font-semibold">
                                Sudah Bayar
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-full
                                         bg-red-100 text-red-700
                                         text-xs font-semibold">
                                Belum Bayar
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-5 text-sm font-medium">
                            <a href="{{ route('invoice.print',$i) }}"
                               class="text-blue-600 hover:text-blue-800 transition">
                                Print
                            </a>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        class="px-6 py-12 text-center text-gray-500">
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
