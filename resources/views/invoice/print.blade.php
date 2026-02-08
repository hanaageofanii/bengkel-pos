<!DOCTYPE html>
<html>
<head>
    <title>{{ $invoice->invoice_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 20px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .text-center { text-align: center }
        .text-right { text-align: right }
        .bold { font-weight: bold }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        .no-border td {
            border: none;
            padding: 2px;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #c00;
        }

        .invoice-title {
            letter-spacing: 6px;
            font-size: 18px;
            margin: 10px 0;
        }
    </style>
</head>

<body onload="window.print()">

<!-- ================= HEADER ================= -->
<div class="text-center">
    <img src="{{ asset('assets/images/logo.png') }}"
         style="
            height: 150px;
            display: block;
            margin: 0 auto -40px auto;
         ">

    <div class="header-title"
         style="margin: 0; padding: 0;">
        5a AUTO SERVICE
    </div>

    <div class="text-sm"
         style="margin-top: 4px;">
        Ruko Permata Harapan Baru Blok H No.17 Jl. Raya Pejuang Harapan Indah Medan Satria Bekasi<br>
        Telp : 0878-7880-6657
    </div>
</div>

<hr>

<div class="invoice-title text-center bold">INVOICE</div>

<!-- ================= DATA ================= -->
<table class="no-border">
    <tr>
        <td width="55%">
            <table>
                <tr><td class="bold">Nama</td><td>{{ $invoice->pelanggan->nama }}</td></tr>
                <tr><td class="bold">Merk / Type</td><td>{{ $invoice->pelanggan->merk_mobil }}</td></tr>
                <tr><td class="bold">No. Polisi</td><td>{{ strtoupper($invoice->pelanggan->plat_nomor) }}</td></tr>
                <tr><td class="bold">KM</td><td>{{ $invoice->km }}</td></tr>
            </table>
        </td>
        <td width="45%">
            <table>
                <tr><td class="bold">Date</td><td>{{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}</td></tr>
                <tr><td class="bold">No. Chasis</td><td>{{ $invoice->no_chasis }}</td></tr>
                <tr><td class="bold">No. Mesin</td><td>{{ $invoice->no_mesin }}</td></tr>
                <tr><td class="bold">No. Telp</td><td>{{ $invoice->no_telp }}</td></tr>
            </table>
        </td>
    </tr>
</table>

<!-- ================= KELUHAN & JASA ================= -->
<table>
    <tr class="bold text-center">
        <td width="5%">No</td>
        <td width="45%">Keluhan</td>
        <td width="5%">No</td>
        <td width="30%">Pekerjaan</td>
        <td width="15%">Harga Jasa</td>
    </tr>

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

    <tr class="bold">
        <td colspan="4" class="text-right">Total Jasa</td>
        <td class="text-right">Rp {{ number_format($invoice->total_jasa) }}</td>
    </tr>
</table>

<!-- ================= SPARE PART ================= -->
<table>
    <tr class="bold text-center">
        <td>No</td>
        <td>Spare Part</td>
        <td>Qty</td>
        <td>Harga</td>
        <td>Total</td>
    </tr>

    @foreach($invoice->barang as $i => $b)
    <tr>
        <td class="text-center">{{ $i+1 }}</td>
        <td>{{ $b['nama'] }}</td>
        <td class="text-center">{{ $b['qty'] }}</td>
        <td class="text-right">Rp {{ number_format($b['harga']) }}</td>
        <td class="text-right">Rp {{ number_format($b['total']) }}</td>
    </tr>
    @endforeach

    <tr class="bold">
        <td colspan="4" class="text-right">Total Part</td>
        <td class="text-right">Rp {{ number_format($invoice->total_part) }}</td>
    </tr>

    <tr class="bold">
        <td colspan="4" class="text-right">Total Jasa + Part</td>
        <td class="text-right">Rp {{ number_format($invoice->grand_total) }}</td>
    </tr>
</table>

<br>

<!-- ================= FOOTER ================= -->
<table class="no-border">
    <tr>
<td style="vertical-align:top; padding-top:0">
    Bekasi, {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}<br>
    Hormat Kami,

    <div style="height:0; position:relative;">
        <img src="{{ asset('assets/images/ttd.png') }}"
             style="
                position: relative;
                top: -90px;
                height: 210px;
                display: block;
             ">
    </div>
</td>

<td class="text-right">
            Pembayaran melalui Rekening:<br>
            <b>Mandiri :</b> 1560010520965<br>
            <b>BCA :</b> 5315064497<br>
            a.n Hari Kuswanto
        </td>
    </tr>
</table>

</body>
</html>
