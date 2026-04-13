<!DOCTYPE html>
<html>
<head>
    <title>Estimasi — {{ $data['pelanggan']->nama }}</title>
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
    <a href="{{ route('estimasi.create') }}" class="btn-back-est">← Ubah</a>
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

{{-- ✅ Judul ESTIMASI, bukan INVOICE --}}
<div class="invoice-title">ESTIMASI</div>

<table class="outer-table">
    <colgroup>
        <col style="width: 47%">
        <col style="width: 6%">
        <col style="width: 47%">
    </colgroup>
    <tbody>
        <tr>
            <td style="padding:0;border:none;vertical-align:top;">
                <table class="inner-table">
                    <tr>
                        <td>Nama</td>
                        <td>{{ $data['pelanggan']->nama }}</td>
                    </tr>
                    <tr>
                        <td>Merk / Type</td>
                        <td>{{ $data['pelanggan']->merk_mobil }}</td>
                    </tr>
                    <tr>
                        <td>No. Polisi</td>
                        <td>{{ strtoupper($data['pelanggan']->plat_nomor) }}</td>
                    </tr>
                    <tr>
                        <td>KM</td>
                        <td>{{ $data['km'] }}</td>
                    </tr>
                </table>
            </td>
            <td class="spacer-col"></td>
            <td style="padding:0;border:none;vertical-align:top;">
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
<table>
    <tr class="bold text-center">
        <td width="5%">No</td>
        <td width="45%">Keluhan</td>
        <td width="5%">No</td>
        <td width="30%">Pekerjaan</td>
        <td width="15%">Harga Jasa</td>
    </tr>
    @php $no = 1; @endphp
    @foreach($data['jasa'] as $j)
    <tr>
        <td class="text-center">{{ $no }}</td>
        <td>{{ $data['keluhan'][$no-1] ?? '' }}</td>
        <td class="text-center">{{ $no }}</td>
        <td>{{ $j['nama'] }}</td>
        <td class="text-right">Rp {{ number_format($j['harga']) }}</td>
    </tr>
    @php $no++; @endphp
    @endforeach
    <tr class="bold">
        <td colspan="3" style="border:none;"></td>
        <td class="text-right total-label">Total Jasa</td>
        <td class="text-right total-amount">Rp {{ number_format($data['total_jasa']) }}</td>
    </tr>
</table>

<!-- SPARE PART -->
<table>
    <tr class="bold text-center">
        <td>No</td>
        <td>Spare Part</td>
        <td>Qty</td>
        <td>Harga</td>
        <td>Total</td>
    </tr>
    @foreach($data['barang'] as $i => $b)
    <tr>
        <td class="text-center">{{ $i+1 }}</td>
        <td>{{ $b['nama'] }}</td>
        <td class="text-center">{{ $b['qty'] }}</td>
        <td class="text-right">Rp {{ number_format($b['harga']) }}</td>
        <td class="text-right">Rp {{ number_format($b['total']) }}</td>
    </tr>
    @endforeach
    <tr class="bold">
        <td colspan="3" rowspan="2" class="note-cell" style="border:none;vertical-align:top;padding:4px 6px;">NOTE:</td>
        <td class="text-right total-label">Total Part</td>
        <td class="text-right total-amount">Rp {{ number_format($data['total_part']) }}</td>
    </tr>
    <tr class="bold">
        <td class="text-right total-label double-line">Estimasi Total</td>
        <td class="text-right total-amount double-line" style="color:#d97706;">
            Rp {{ number_format($data['grand_total']) }}
        </td>
    </tr>
</table>

<!-- FOOTER TTD + Rekening -->
<table class="no-border" style="margin-top:2px;">
    <tr>
        <td style="vertical-align:top;padding-top:0;width:50%;padding-left:40px;text-align:left;">
            <div style="font-weight:bold;">
                Bekasi, {{ \Carbon\Carbon::parse($data['tanggal'])->format('d F Y') }}
            </div>
            <div style="margin-left:20px;">Hormat Kami,</div>
            <div style="position:relative;height:60px;">
                <img src="{{ asset('assets/images/ttd.png') }}"
                     style="position:absolute;top:-80px;left:0;height:220px;">
            </div>
        </td>
        <td style="vertical-align:top;width:25%;padding:0 0 0 10px;">
            <div class="rekening-box">
                Pembayaran melalui Rekening :
                <table class="rek-table">
                    <tr>
                        <td class="rek-label">Mandiri</td>
                        <td>: </td>
                        <td class="rek-num">1560010520965</td>
                    </tr>
                    <tr>
                        <td class="rek-label">BCA</td>
                        <td>: </td>
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
