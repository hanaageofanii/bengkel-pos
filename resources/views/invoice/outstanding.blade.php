@extends('dashboard')

@section('title','Tagihan Outstanding')

@section('content')

<div class="w-full space-y-8">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 p-6 rounded-2xl shadow-lg text-white">
        <h1 class="text-3xl font-bold tracking-wide">
            Tagihan Outstanding
        </h1>
        <p class="text-sm opacity-80 mt-1">
            5A Auto Service
        </p>
    </div>

    <!-- SUMMARY BOX -->
    <div class="grid md:grid-cols-2 gap-6">

        <!-- TOTAL NOMINAL BELUM LUNAS -->
        <div class="bg-white rounded-2xl shadow-md p-6 border">
            <p class="text-xs uppercase text-gray-500 font-semibold">
                Total Nominal Invoice Belum Lunas
            </p>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">
                Rp {{ number_format($totalAll,0,',','.') }}
            </h2>
        </div>

        <!-- TOTAL SISA -->
        <div class="bg-red-50 rounded-2xl shadow-md p-6 border border-red-200">
            <p class="text-xs uppercase text-red-500 font-semibold">
                Total Sisa Yang Harus Dibayar
            </p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">
                Rp {{ number_format($totalOutstanding,0,',','.') }}
            </h2>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border">

        <div class="border-b px-6 py-4">
            <h3 class="font-semibold text-gray-700">
                Daftar Invoice Belum Lunas
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Mobil</th>
                        <th class="px-6 py-3 text-left">No Polisi</th>
                        <th class="px-6 py-3 text-right">Total</th>
                        <th class="px-6 py-3 text-right">DP</th>
                        <th class="px-6 py-3 text-right">Sisa</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach($invoices as $inv)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($inv->tanggal)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $inv->pelanggan->nama }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $inv->pelanggan->merk_mobil }}
                            {{ $inv->pelanggan->model_mobil }}
                        </td>

                        <td class="px-6 py-4 font-semibold uppercase text-gray-700">
                            {{ $inv->pelanggan->plat_nomor }}
                        </td>

                        <td class="px-6 py-4 text-right text-gray-800">
                            Rp {{ number_format($inv->grand_total,0,',','.') }}
                        </td>

                        <td class="px-6 py-4 text-right text-gray-600">
                            Rp {{ number_format($inv->payment_awal,0,',','.') }}
                        </td>

                        <td class="px-6 py-4 text-right font-bold text-red-600">
                            Rp {{ number_format($inv->sisa,0,',','.') }}
                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection
