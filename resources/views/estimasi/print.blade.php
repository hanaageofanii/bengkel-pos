<!DOCTYPE html>
<html>
<head>
    <title>Estimasi — {{ $estimasi->pelanggan->nama }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/print-inv.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ── Watermark ESTIMASI ── */
        body { position: relative; }
        body::before {
            content: "ESTIMASI";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 96px;
            font-weight: 900;
            font-family: 'Rajdhani', sans-serif;
            color: rgba(245, 197, 66, 0.12);
            letter-spacing: 8px;
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        }
        /* ── Tombol print (tidak ikut tercetak) ── */
        .no-print {
            position: fixed;
            bottom: 24px;
            right: 24px;
            display: flex;
            gap: 10px;
            z-index: 999;
        }
        .btn-print-est {
            padding: 10px 24px;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            cursor: pointer;
        }
        .btn-back-est {
            padding: 10px 20px;
            background: transparent;
            color: #555;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>

<body>

{{-- Tombol aksi (tidak ikut print) --}}
<div class="no-print">
    <a href="{{ route('estimasi.index', $estimasi) }}" class="btn-back-est">← Kembali</a>
    <button class="btn-print-est" onclick="window.print()">🖨 Print Estimasi</button>
</div>

<!-- HEADER -->
<div class="header-container">
    <div class="logo-wrapper">
        <img src="{{ asset('assets/images/logo.png') }}" class="logo">
    </div>
    <div class="header-title">5a AUTO SERVICE</div>
    <div class="header-address">
        Ruko Permata Harapan Baru Blok H No.17 Jl. Raya Pejuang Harapan Indah Medan Satria Bekasi<br>
        Telp : 0878-7880-6657
    </div>
</div>

<hr style="border: 1px solid black;">

<div class="invoice-title">ESTIMASI</div>

<table class="outer-table">
    <colgroup>
        <col style="width: 51%">
        <col style="width: 5%">
        <col style="width: 46%">
    </colgroup>
    <tbody>
        <tr>
            <!-- KIRI -->
            <td style="padding: 0; border: none; vertical-align: top;">
                <table class="inner-table">
                    <tr>
                        <td>Nama</td>
                        <td>{{ $estimasi->pelanggan->nama }}</td>
                    </tr>
                    <tr>
                        <td>Merk / Type</td>
                        <td>
                            {{ $estimasi->pelanggan->merk_mobil }} {{ $estimasi->pelanggan->model_mobil }}
                        </td>
                    </tr>
                    <tr>
                        <td>No. Polisi</td>
                        <td>{{ strtoupper($estimasi->pelanggan->plat_nomor) }}</td>
                    </tr>
                    <tr>
                        <td>KM</td>
                        <td>{{ $data['km'] }}</td>
                    </tr>
                </table>
            </td>

            <td class="spacer-col"></td>

            <!-- KANAN -->
            <td style="padding: 0; border: none; vertical-align: top;">
                <table class="inner-table">
                    <tr>
                        <td>Date</td>
                        <td>{{ \Carbon\Carbon::parse($data['tanggal'])->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>No. Chasis</td>
                        <td>{{ $data['no_chasis'] }}</td>
                    </tr>
                    <tr>
                        <td>No. Mesin</td>
                        <td>{{ $data['no_mesin'] }}</td>
                    </tr>
                    <tr>
                        <td>No. Telp</td>
                        <td>{{ $data['no_telp'] }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<!-- JASA -->
@php
    $minJasaRows   = 10;
    $jasaCount     = count($data['jasa']);
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
    <tr style="{{ !isset($data['jasa'][$i]) ? 'height: 14px;' : '' }}">
        <td class="text-center">{{ isset($data['jasa'][$i]) ? $i + 1 : '' }}</td>
        <td>{{ $data['keluhan'][$i] ?? '' }}</td>
        <td class="text-center">{{ isset($data['jasa'][$i]) ? $i + 1 : '' }}</td>
        <td>{{ $data['jasa'][$i]['nama'] ?? '' }}</td>
        <td class="text-right">
            @if(isset($data['jasa'][$i]))
                Rp {{ number_format($data['jasa'][$i]['harga']) }}
            @endif
        </td>
    </tr>
    @endfor

    <tr class="bold">
        <td colspan="3" style="border: none;"></td>
        <td class="text-right total-label">Total Jasa</td>
        <td class="text-right total-amount">Rp {{ number_format($data['total_jasa']) }}</td>
    </tr>
</table>

<!-- SPARE PART -->
@php
    $minBarangRows   = 10;
    $barangCount     = count($data['barang']);
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
    <tr style="{{ !isset($data['barang'][$i]) ? 'height: 14px;' : '' }}">
        <td class="text-center">{{ isset($data['barang'][$i]) ? $i + 1 : '' }}</td>
        <td>{{ $data['barang'][$i]['nama'] ?? '' }}</td>
        <td class="text-center">{{ isset($data['barang'][$i]) ? $data['barang'][$i]['qty'] ?? '' : '' }}</td>
        <td class="text-right">
            @if(isset($data['barang'][$i]))
                Rp {{ number_format($data['barang'][$i]['harga']) }}
            @endif
        </td>
        <td class="text-right">
            @if(isset($data['barang'][$i]))
                Rp {{ number_format($data['barang'][$i]['total']) }}
            @endif
        </td>
    </tr>
    @endfor

    <tr class="bold">
        <td colspan="3" rowspan="2" class="note-cell" style="border: none; vertical-align: top; padding: 4px 6px;">
            <span style="font-weight: bold;">NOTE:</span><br>
            {{ $data['notes'] ?? '' }}
        </td>
        <td class="text-right total-label">Total Part</td>
        <td class="text-right total-amount">Rp {{ number_format($data['total_part']) }}</td>
    </tr>

    <tr class="bold">
        <td class="text-right total-label double-line">Estimasi Total</td>
        <td class="text-right total-amount double-line" style="color: #d97706;">
            Rp {{ number_format($data['grand_total']) }}
        </td>
    </tr>
</table>

<!-- FOOTER TTD + Rekening -->
<table class="no-border" style="margin-top: 2px;">
    <tr>
        <td style="vertical-align: top; width: 50%; padding-left: 40px; border: none;">

    <div style="width: 150px; margin-left: 0px; text-align: center;">

        <div style="font-weight: bold;">
            Bekasi, {{ \Carbon\Carbon::parse($data['tanggal'])->format('d F Y') }}
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
            <div class="rekening-box">
                Pembayaran Transfer melalui Rekening :
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
