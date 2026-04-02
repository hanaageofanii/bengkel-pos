@extends('dashboard')

@section('title', 'Tagihan Outstanding')

@section('content')

<style>
    /* ── stat cards ── */
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(0,0,0,.25);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
    }
    .stat-card.blue::before  { background: linear-gradient(90deg, #1e90ff, #4f8ef7); }
    .stat-card.red::before   { background: linear-gradient(90deg, #f26c6c, #ff4444); }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 16px;
    }
    .stat-icon.blue { background: rgba(79,142,247,.15); }
    .stat-icon.red  { background: rgba(242,108,108,.15); }
    .stat-icon svg  { width: 22px; height: 22px; fill: currentColor; }
    .stat-icon.blue svg { color: #4f8ef7; }
    .stat-icon.red  svg { color: #f26c6c; }

    .stat-label {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 1.1px;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 8px;
    }
    .stat-value {
        font-family: 'Rajdhani', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: var(--text);
        line-height: 1;
    }
    .stat-card.red .stat-value { color: #f26c6c; }

    /* ── table card ── */
    .table-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .table-card-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
        letter-spacing: .3px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-card-title span.dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #f26c6c;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(242,108,108,.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 3px rgba(242,108,108,.2); }
        50%       { box-shadow: 0 0 0 6px rgba(242,108,108,.0); }
    }

    /* ── search ── */
    .search-wrap {
        position: relative;
    }
    .search-wrap svg {
        position: absolute;
        left: 11px; top: 50%;
        transform: translateY(-50%);
        width: 14px; height: 14px;
        fill: var(--muted);
        pointer-events: none;
    }
    .search-input {
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: 'Inter', sans-serif;
        font-size: 12.5px;
        padding: 7px 12px 7px 32px;
        width: 200px;
        outline: none;
        transition: border-color .2s;
    }
    .search-input::placeholder { color: var(--muted); }
    .search-input:focus { border-color: var(--accent); }

    /* ── table ── */
    .inv-table {
        width: 100%;
        border-collapse: collapse;
    }

    .inv-table thead tr {
        background: var(--surface2);
    }

    .inv-table th {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--muted);
        padding: 12px 20px;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .inv-table th.text-right { text-align: right; }

    .inv-table td {
        padding: 14px 20px;
        font-size: 13px;
        color: var(--text-soft);
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }

    .inv-table tbody tr:last-child td { border-bottom: none; }

    .inv-table tbody tr:hover td { background: var(--surface2); }

    .inv-table td.text-right { text-align: right; }

    /* ── cell components ── */
    .date-badge {
        font-size: 11.5px;
        color: var(--muted);
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 3px 9px;
        white-space: nowrap;
        display: inline-block;
    }

    .customer-cell {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .avatar-circle {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: rgba(79,142,247,.15);
        color: #4f8ef7;
        font-family: 'Rajdhani', sans-serif;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid rgba(79,142,247,.2);
    }

    .customer-name {
        font-weight: 600;
        font-size: 13px;
        color: var(--text);
    }

    .car-cell {
        font-size: 12.5px;
        color: var(--text-soft);
    }

    .plat-badge {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: #4f8ef7;
        background: rgba(79,142,247,.1);
        border: 1px solid rgba(79,142,247,.2);
        border-radius: 6px;
        padding: 3px 10px;
        display: inline-block;
    }

    .amount-total {
        font-family: 'Rajdhani', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }

    .amount-dp {
        font-size: 12.5px;
        color: var(--muted);
    }

    .sisa-badge {
        font-family: 'Rajdhani', sans-serif;
        font-size: 13.5px;
        font-weight: 700;
        color: #f26c6c;
        background: rgba(242,108,108,.1);
        border: 1px solid rgba(242,108,108,.2);
        border-radius: 8px;
        padding: 5px 12px;
        display: inline-block;
        white-space: nowrap;
    }

    /* ── empty state ── */
    .empty-state {
        text-align: center;
        padding: 56px 20px;
    }
    .empty-state svg {
        width: 48px; height: 48px;
        fill: var(--muted);
        margin: 0 auto 14px;
        display: block;
        opacity: .4;
    }
    .empty-state p {
        color: var(--muted);
        font-size: 13px;
    }

    /* ── page header ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-title-text {
        font-family: 'Rajdhani', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: var(--text);
        letter-spacing: .3px;
        line-height: 1;
    }
    .page-subtitle {
        font-size: 12px;
        color: var(--muted);
        margin-top: 5px;
        letter-spacing: .2px;
    }
    .count-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(242,108,108,.12);
        border: 1px solid rgba(242,108,108,.25);
        border-radius: 20px;
        padding: 5px 13px;
        font-size: 12px;
        font-weight: 600;
        color: #f26c6c;
        margin-top: 10px;
    }
    .count-chip svg {
        width: 12px; height: 12px;
        fill: #f26c6c;
    }
</style>

<di>

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
                <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1H8.3c.12 2.19 1.76 3.42 3.7 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
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

<script>
    function filterTable() {
        const input = document.getElementById('tableSearch').value.toLowerCase();
        const rows  = document.querySelectorAll('#invoiceTable tbody tr');
        rows.forEach(row => {
            const cell = row.querySelector('td:nth-child(2)');
            if (!cell) return;
            row.style.display = cell.textContent.toLowerCase().includes(input) ? '' : 'none';
        });
    }
</script>

@endsection
