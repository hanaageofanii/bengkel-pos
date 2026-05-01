<!DOCTYPE html>
<html>
<head>
    <title>{{ $invoice->invoice_no }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/print-inv.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body onload="window.print()">

<!-- HEADER -->
<div class="header-container">
    <div class="logo-wrapper">
        <img src="{{ asset('assets/images/logo.png') }}" class="logo">
    </div>

    <div class="header-title">
        5a AUTO SERVICE
    </div>

    <div class="header-address">
        Ruko Permata Harapan Baru Blok H No.17 Jl. Raya Pejuang Harapan Indah Medan Satria Bekasi<br>
        Telp : 0878-7880-6657
    </div>
</div>

<hr style="border: 1px solid black;">

<div class="invoice-title">INVOICE</div>

<table class="outer-table">
    <colgroup>
        <col style="width: 51%">
        <col style="width: 5%">   {{-- spacer --}}
        <col style="width: 46%">   {{-- kanan --}}
    </colgroup>
    <tbody>
        <tr>
            <!-- KIRI -->
            <td style="padding: 0; border: none; vertical-align: top;">
                <table class="inner-table">
                    <tr>
                        <td>Nama</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Merk / Type</td>
                        <td>
                            {{ $invoice['pelanggan']->merk_mobil }} {{ $invoice['pelanggan']->model_mobil }}
                        </td>
                    </tr>
                    <tr>
                        <td>No. Polisi</td>
                        <td>{{ strtoupper($invoice->pelanggan->plat_nomor) }}</td>
                    </tr>
                    <tr>
                        <td>KM</td>
                        <td>{{ $invoice->km }}</td>
                    </tr>
                </table>
            </td>

            <td class="spacer-col"></td>

            <!-- KANAN -->
            <td style="padding: 0; border: none; vertical-align: top; ">
                <table class="inner-table">
                    <tr>
                        <td>Date</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>No. Chasis</td>
                        <td>{{ $invoice->no_chasis }}</td>
                    </tr>
                    <tr>
                        <td>No. Mesin</td>
                        <td>{{ $invoice->no_mesin }}</td>
                    </tr>
                    <tr>
                        <td>No. Telp</td>
                        <td>{{ $invoice->no_telp }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<!-- JASA -->
@php
    $minJasaRows = 10;
    $jasaCount   = count($invoice->jasa);
    $totalJasaRows = max($jasaCount, $minJasaRows);
@endphp

<table>
    <tr class="bold text-center">
        <td width="5%">No</td>
        <td width="45%">Keluhan</td>
        <td width="5%">No</td>
        <td width="30%">Pekerjaan</td>
        <td width="15%">Harga Jasa</td>
    </tr>

    @for($i = 0; $i < $totalJasaRows; $i++)
    <tr style="{{ !isset($invoice->jasa[$i]) ? 'height: 14px;' : '' }}">
        <td class="text-center">{{ isset($invoice->jasa[$i]) ? $i + 1 : '' }}</td>
        <td>{{ $invoice->keluhan[$i] ?? '' }}</td>
        <td class="text-center">{{ isset($invoice->jasa[$i]) ? $i + 1 : '' }}</td>
        <td>{{ $invoice->jasa[$i]['nama'] ?? '' }}</td>
        <td class="text-right">
            @if(isset($invoice->jasa[$i]))
                Rp {{ number_format($invoice->jasa[$i]['harga']) }}
            @endif
        </td>
    </tr>
    @endfor

    <tr class="bold">
        <td colspan="3" style="border: none;"></td>
        <td class="text-right total-label">Total Jasa</td>
        <td class="text-right total-amount">Rp {{ number_format($invoice->total_jasa) }}</td>
    </tr>
</table>

<!-- SPAREPART -->
@php
    $minBarangRows = 10;
    $barangCount   = count($invoice->barang);
    $totalBarangRows = max($barangCount, $minBarangRows);
@endphp

<table>
    <tr class="bold text-center">
        <td width="5%">No</td>
        <td width="45%">Spare Part</td>
        <td width="5%">Qty</td>
        <td width="30%">Harga</td>
        <td width="15%">Total</td>
    </tr>

    @for($i = 0; $i < $totalBarangRows; $i++)
    <tr style="{{ !isset($invoice->barang[$i]) ? 'height: 14px;' : '' }}">
        <td class="text-center">{{ isset($invoice->barang[$i]) ? $i + 1 : '' }}</td>
        <td>{{ $invoice->barang[$i]['nama'] ?? '' }}</td>
        <td class="text-center">{{ isset($invoice->barang[$i]) ? $i + 1 : '' }}</td>
        <td class="text-right">
            @if(isset($invoice->barang[$i]))
                Rp {{ number_format($invoice->barang[$i]['harga']) }}
            @endif
        </td>
        <td class="text-right">
            @if(isset($invoice->barang[$i]))
                Rp {{ number_format($invoice->barang[$i]['total']) }}
            @endif
        </td>
    </tr>
    @endfor

    <tr class="bold">
        <td colspan="3" rowspan="2" class="note-cell" style="border:none; vertical-align:top; padding:4px 6px;">
            <span style="font-weight:bold;">NOTE:</span><br>
            {{ $invoice->notes ?? '' }}
        </td>
        <td class="text-right total-label">Total Part</td>
        <td class="text-right total-amount">Rp {{ number_format($invoice->total_part) }}</td>
    </tr>

    <tr class="bold">
        <td class="text-right total-label">Total Jasa + Part</td>
        <td class="text-right total-amount">Rp {{ number_format($invoice->grand_total) }}</td>
    </tr>

    <!-- PEMBAYARAN -->
    @if($invoice->payment_awal > 0)
    <tr>
        <td colspan="3" style="border:none;"></td>
        <td class="text-right bold total-label">Payment Awal (DP)</td>
        <td class="text-right total-amount">Rp {{ number_format($invoice->payment_awal) }}</td>
    </tr>
    @endif

    @foreach($invoice->payments as $i => $pay)
    <tr>
        <td colspan="3" style="border:none;"></td>
        <td class="text-right total-label">
            Pembayaran {{ $i + 1 }}
            ({{ \Carbon\Carbon::parse($pay->tanggal_bayar)->format('d/m/Y') }})
        </td>
        <td class="text-right total-amount">Rp {{ number_format($pay->jumlah) }}</td>
    </tr>
    @endforeach

    <tr class="bold">
        <td colspan="3" style="border:none;"></td>
        <td class="text-right total-label double-line">Total Terbayar</td>
        <td class="text-right total-amount double-line">Rp {{ number_format($invoice->total_terbayar) }}</td>
    </tr>

    @if($invoice->sisa_tagihan > 0)
    <tr>
        <td colspan="3" style="border:none;"></td>
        <td class="text-right bold total-label">Sisa Tagihan</td>
        <td class="text-right total-amount belum">Rp {{ number_format($invoice->sisa_tagihan) }}</td>
    </tr>
    @endif

    @if($invoice->sisa_tagihan <= 0)
    <tr>
        <td colspan="3" style="border:none;"></td>
        <td class="text-right bold total-label">Status</td>
        <td class="text-right total-amount lunas">LUNAS</td>
    </tr>
    @endif
</table>

<!-- TTD FOOTER + Rekening -->
<table class="no-border" style="margin-top: 2px;">
    <tr>
      <td style="vertical-align: top; width: 50%; padding-left: 40px; border: none;">

    <div style="width: 150px; margin-left: 0px; text-align: center;">

        <div style="font-weight: bold;">
            Bekasi, {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d F Y') }}
        </div>

        <div>
            Hormat Kami,
        </div>

    <div style="margin-top: -90px;">
        <img src="{{ asset('assets/images/ttd.png') }}"
             style="height: 210px; width: -30px;">
    </div>

        </td>

        <td style="vertical-align: top; width: 43%; padding: 0 0 0 10px;">
    <div class="rekening-box">Pembayaran Transfer melalalui Rekening :
    <table class="rek-table">
        <tr>
            <td class="rek-label">Mandiri</td>
            <td class="rek-separator">:</td>
            <td class="rek-num">1560010520965</td>
        </tr>
        <tr>
            <td class="rek-label">BCA</td>
            <td class="rek-separator">:</td>
            <td class="rek-num">5315064497</td>
        </tr>
    </table>
    <div class="rek-nama">a.n Hari Kuswanto</div>
</div>
</td>
    </tr>
</table>

</body>
</html>
