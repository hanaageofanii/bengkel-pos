<!DOCTYPE html>
<html>
<head>
    <title>{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: Arial; font-size: 12px }
        table { width:100%; border-collapse: collapse }
        th,td { border:1px solid #000; padding:6px }
        .no-border td { border:none }
    </style>
</head>
<body onload="window.print()">

<h3>5A AUTO SERVICE</h3>
<p>INVOICE: {{ $invoice->invoice_no }}</p>

<table class="no-border">
<tr>
<td>
Nama: {{ $invoice->pelanggan->nama }}<br>
Plat: {{ strtoupper($invoice->pelanggan->plat_nomor) }}<br>
Mobil: {{ $invoice->pelanggan->merk_mobil }} {{ $invoice->pelanggan->model_mobil }}
</td>
<td>
Tanggal: {{ $invoice->tanggal }}<br>
KM: {{ $invoice->km }}<br>
Telp: {{ $invoice->no_telp }}
</td>
</tr>
</table>

<h4>Keluhan</h4>
<ul>
@foreach($invoice->keluhan as $k)
<li>{{ $k }}</li>
@endforeach
</ul>

<h4>Jasa</h4>
<table>
<tr><th>Nama</th><th>Harga</th></tr>
@foreach($invoice->jasa as $j)
<tr>
<td>{{ $j['nama'] }}</td>
<td align="right">{{ number_format($j['harga']) }}</td>
</tr>
@endforeach
</table>

<h4>Spare Part</h4>
<table>
<tr><th>Nama</th><th>Qty</th><th>Harga</th><th>Total</th></tr>
@foreach($invoice->barang as $b)
<tr>
<td>{{ $b['nama'] }}</td>
<td align="center">{{ $b['qty'] }}</td>
<td align="right">{{ number_format($b['harga']) }}</td>
<td align="right">{{ number_format($b['total']) }}</td>
</tr>
@endforeach
</table>

<h3 align="right">
TOTAL: Rp {{ number_format($invoice->grand_total) }}
</h3>

<p>
Status: {{ strtoupper($invoice->status_bayar) }} <br>
Metode: {{ strtoupper($invoice->metode_bayar) }}
</p>

</body>
</html>
