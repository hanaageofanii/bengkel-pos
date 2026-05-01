@extends('dashboard')

@section('title', 'Tagihan Outstanding')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/outstanding-inv.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div>

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <div class="page-title-text">Tagihan Outstanding</div>
            <div class="page-subtitle">Monitoring piutang pelanggan aktif</div>
            <div class="count-chip">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                {{ $invoices->count() }} invoice belum lunas
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px">

        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            </div>
            <div class="stat-label">Total Nominal Invoice</div>
            <div class="stat-value">Rp {{ number_format($totalAll,0,',','.') }}</div>
        </div>

        <div class="stat-card red">
            <div class="stat-icon red">
                <svg class="mz-input-icon" viewBox="0 0 24 24">
    <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
</svg>
            </div>
            <div class="stat-label">Total Sisa Tagihan</div>
            <div class="stat-value">Rp {{ number_format($totalOutstanding,0,',','.') }}</div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="table-card">

        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span>
                Invoice Belum Lunas
            </div>

            <div class="search-wrap">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input
                    type="text"
                    class="search-input"
                    id="tableSearch"
                    placeholder="Cari customer..."
                    onkeyup="filterTable()"
                >
            </div>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invoiceTable">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Kendaraan</th>
                        <th>No Polisi</th>
                        <th class="text-right">Total Invoice</th>
                        <th class="text-right">DP</th>
                        <th class="text-right">Sisa Tagihan</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($invoices as $inv)
                    <tr>

                        <td>
                            <span class="date-badge">
                                {{ \Carbon\Carbon::parse($inv->tanggal)->format('d M Y') }}
                            </span>
                        </td>

                        <td>
                            <div class="customer-cell">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($inv->pelanggan->nama, 0, 1)) }}
                                </div>
                                <span class="customer-name">{{ $inv->pelanggan->nama }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="car-cell">
                                {{ $inv->pelanggan->merk_mobil }}
                                {{ $inv->pelanggan->model_mobil }}
                            </span>
                        </td>

                        <td>
                            <span class="plat-badge">{{ $inv->pelanggan->plat_nomor }}</span>
                        </td>

                        <td class="text-right">
                            <span class="amount-total">
                                Rp {{ number_format($inv->grand_total,0,',','.') }}
                            </span>
                        </td>

                        <td class="text-right">
                            <span class="amount-dp">
                                Rp {{ number_format($inv->payment_awal,0,',','.') }}
                            </span>
                        </td>

                        <td class="text-right">
                            <span class="sisa-badge">
                                Rp {{ number_format($inv->sisa,0,',','.') }}
                            </span>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                <p>Semua tagihan telah lunas</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>

<script src="{{ asset('assets/js/outstanding-inv.js') }}"></script>
@endsection
