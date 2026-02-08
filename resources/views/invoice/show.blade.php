@extends('dashboard')
@section('title','Detail Invoice')

@section('content')
<div class="w-full space-y-6">

    <!-- ACTION -->
    <div class="flex justify-between items-center">
        <a href="{{ route('invoice.index') }}"
           class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-semibold">
            ← Kembali
        </a>

        <a href="{{ route('invoice.print',$invoice) }}"
           class="px-5 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold">
            Print Invoice
        </a>
    </div>

    <!-- INVOICE CARD -->
    <div class="bg-white p-6 shadow rounded-xl">

        <!-- ================= HEADER ================= -->
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" height="70" class="mx-auto mb-2">
            <div class="text-xl font-bold text-red-500 -mt-40">5a AUTO SERVICE</div>
            <div class="text-sm">
                Ruko Permata Harapan Baru Blok H No.17 Jl. Raya Pejuang  Harapan Indah Medan Satria Bekasi<br>
                Telp : 0878-7880-6657
            </div>
        </div>

        <hr class="my-4">

        <div class="text-center font-bold tracking-widest text-lg mb-4">
            INVOICE
        </div>

        <!-- ================= DATA ================= -->
        <table class="w-full text-sm mb-4">
            <tr>
                <td class="w-1/2 align-top">
                    <table class="w-full">
                        <tr><td class="font-semibold">Nama</td><td>{{ $invoice->pelanggan->nama }}</td></tr>
                        <tr><td class="font-semibold">Merk / Type</td><td>{{ $invoice->pelanggan->merk_mobil }}</td></tr>
                        <tr><td class="font-semibold">No Polisi</td><td>{{ strtoupper($invoice->pelanggan->plat_nomor) }}</td></tr>
                        <tr><td class="font-semibold">KM</td><td>{{ $invoice->km }}</td></tr>
                    </table>
                </td>

                <td class="w-1/2 align-top">
                    <table class="w-full">
                        <tr><td class="font-semibold">Tanggal</td><td>{{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}</td></tr>
                        <tr><td class="font-semibold">No Chasis</td><td>{{ $invoice->no_chasis }}</td></tr>
                        <tr><td class="font-semibold">No Mesin</td><td>{{ $invoice->no_mesin }}</td></tr>
                        <tr><td class="font-semibold">No Telp</td><td>{{ $invoice->no_telp }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- ================= KELUHAN & JASA ================= -->
        <table class="w-full text-sm border mb-4">
            <thead class="bg-gray-100">
                <tr class="text-center font-semibold">
                    <td>No</td>
                    <td>Keluhan</td>
                    <td>No</td>
                    <td>Pekerjaan</td>
                    <td class="text-right">Harga Jasa</td>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($invoice->jasa as $j)
                <tr>
                    <td class="text-center">{{ $no }}</td>
                    <td>{{ $invoice->keluhan[$no-1] ?? '' }}</td>
                    <td class="text-center">{{ $no }}</td>
                    <td>{{ $j['nama'] }}</td>
                    <td class="text-right">Rp {{ number_format($j['harga']) }}</td>
                </tr>
                @php $no++; @endphp
                @endforeach

                <tr class="font-bold">
                    <td colspan="4" class="text-right">Total Jasa</td>
                    <td class="text-right">Rp {{ number_format($invoice->total_jasa) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- ================= SPARE PART ================= -->
        <table class="w-full text-sm border mb-4">
            <thead class="bg-gray-100">
                <tr class="text-center font-semibold">
                    <td>No</td>
                    <td>Spare Part</td>
                    <td>Qty</td>
                    <td class="text-right">Harga</td>
                    <td class="text-right">Total</td>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->barang as $i => $b)
                <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td>{{ $b['nama'] }}</td>
                    <td class="text-center">{{ $b['qty'] }}</td>
                    <td class="text-right">Rp {{ number_format($b['harga']) }}</td>
                    <td class="text-right">Rp {{ number_format($b['total']) }}</td>
                </tr>
                @endforeach

                <tr class="font-bold">
                    <td colspan="4" class="text-right">Total Part</td>
                    <td class="text-right">Rp {{ number_format($invoice->total_part) }}</td>
                </tr>

                <tr class="font-bold">
                    <td colspan="4" class="text-right">Grand Total</td>
                    <td class="text-right">Rp {{ number_format($invoice->grand_total) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- ================= FOOTER ================= -->
        <table class="w-full text-sm">
            <tr>
                <td>
                    Bekasi, {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}<br>
                    Hormat Kami,<br><br><br>
                    <b>HARI KUSWANTO</b>
                </td>
                <td class="text-right align-top">
                    Pembayaran melalui Rekening:<br>
                    <b>Mandiri :</b> 1560010520965<br>
                    <b>BCA :</b> 5315064497<br>
                    a.n Hari Kuswanto
                </td>
            </tr>
        </table>

    </div>
</div>
@endsection
