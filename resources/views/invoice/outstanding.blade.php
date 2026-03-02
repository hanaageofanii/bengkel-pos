@extends('dashboard')

@section('title','Tagihan Outstanding')

@section('content')

<div class="w-full space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                Tagihan Outstanding
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Monitoring piutang pelanggan
            </p>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="grid md:grid-cols-2 gap-8">

        <!-- TOTAL INVOICE -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <p class="text-xs uppercase tracking-widest text-gray-400 font-semibold">
                Total Nominal Invoice
            </p>

            <h2 class="text-3xl font-bold text-gray-900 mt-3">
                Rp {{ number_format($totalAll,0,',','.') }}
            </h2>

            <div class="mt-4 h-1 w-12 bg-gray-900 rounded-full"></div>
        </div>

        <!-- TOTAL SISA -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-3xl shadow-lg p-8 text-white">
            <p class="text-xs uppercase tracking-widest opacity-80 font-semibold">
                Total Sisa Tagihan
            </p>

            <h2 class="text-4xl font-bold mt-3">
                Rp {{ number_format($totalOutstanding,0,',','.') }}
            </h2>

            <div class="mt-4 h-1 w-12 bg-white/70 rounded-full"></div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-8 py-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">
                Invoice Belum Lunas
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead>
                    <tr class="text-xs uppercase tracking-wider text-gray-400 bg-gray-50">
                        <th class="px-8 py-4 text-left font-medium">Tanggal</th>
                        <th class="px-8 py-4 text-left font-medium">Customer</th>
                        <th class="px-8 py-4 text-left font-medium">Mobil</th>
                        <th class="px-8 py-4 text-left font-medium">No Polisi</th>
                        <th class="px-8 py-4 text-right font-medium">Total</th>
                        <th class="px-8 py-4 text-right font-medium">DP</th>
                        <th class="px-8 py-4 text-right font-medium">Sisa</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @foreach($invoices as $inv)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-8 py-5 text-gray-700">
                            {{ \Carbon\Carbon::parse($inv->tanggal)->format('d M Y') }}
                        </td>

                        <td class="px-8 py-5 font-semibold text-gray-900">
                            {{ $inv->pelanggan->nama }}
                        </td>

                        <td class="px-8 py-5 text-gray-600">
                            {{ $inv->pelanggan->merk_mobil }}
                            {{ $inv->pelanggan->model_mobil }}
                        </td>

                        <td class="px-8 py-5 uppercase font-medium text-gray-700">
                            {{ $inv->pelanggan->plat_nomor }}
                        </td>

                        <td class="px-8 py-5 text-right text-gray-800">
                            Rp {{ number_format($inv->grand_total,0,',','.') }}
                        </td>

                        <td class="px-8 py-5 text-right text-gray-500">
                            Rp {{ number_format($inv->payment_awal,0,',','.') }}
                        </td>

                        <td class="px-8 py-5 text-right">
                            <span class="inline-block bg-red-50 text-red-600 font-bold px-4 py-2 rounded-xl">
                                Rp {{ number_format($inv->sisa,0,',','.') }}
                            </span>
                        </td>

                    </tr>
                    @endforeach

                    @if($invoices->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-400">
                            Tidak ada tagihan outstanding
                        </td>
                    </tr>
                    @endif

                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection
