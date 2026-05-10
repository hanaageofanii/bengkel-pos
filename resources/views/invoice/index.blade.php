@extends('dashboard')

@section('title','Invoice')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/index-inv.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="inv-wrap">

    {{-- ── Header ── --}}
    <div class="inv-header">
        <div>
            <div class="inv-title">Invoice</div>
            <div class="inv-subtitle">Daftar transaksi servis & perbaikan kendaraan</div>
        </div>
        <a href="{{ route('invoice.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Buat Invoice
        </a>
    </div>

    {{-- ── Search ── --}}
    <div class="inv-search">
        <form method="GET">
            <div class="search-wrap">
                <input name="q" value="{{ $q }}"
                       placeholder="Cari invoice, pelanggan, plat, atau mobil…"
                       class="search-input">

                <div class="search-date-group">
                    <input type="date" name="dari"   value="{{ $dari }}"   class="search-date" title="Dari tanggal">
                    <span class="date-sep">—</span>
                    <input type="date" name="sampai" value="{{ $sampai }}" class="search-date" title="Sampai tanggal">
                </div>

                <button type="submit" class="search-btn">Cari</button>

                @if($q || $dari || $sampai)
                    <a href="{{ route('invoice.index') }}" class="search-reset">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Table Card ── --}}
    <div class="inv-card">
        <div class="inv-card-bar"></div>

        {{-- Wrapper scroll horizontal --}}
        <div class="table-scroll-hint" id="scrollHint">
            <div class="table-scroll-wrap" id="tableScroll">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th style="text-align:left">Invoice</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>DP</th>
                            <th>Sisa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $i)
                        <tr>
                            <td class="td-inv">{{ $i->invoice_no }}</td>

                            <td class="td-nama-cell">
                                <div class="td-nama">{{ $i->pelanggan->nama }}</div>
                                <div class="td-plat">{{ $i->pelanggan->plat_nomor }}</div>
                                <div class="td-mobil">{{ $i->pelanggan->merk_mobil }} {{ $i->pelanggan->model_mobil }}</div>
                            </td>

                            <td class="td-center" style="color:var(--mz-muted)">
                                {{ \Carbon\Carbon::parse($i->tanggal)->format('d M Y') }}
                            </td>

                            <td class="td-right td-num-bold">
                                Rp {{ number_format($i->grand_total) }}
                            </td>

                            <td class="td-center">
                                @if($i->status_bayar === 'sudah')
                                    <span class="mz-badge badge-lunas">
                                        <span class="badge-dot" style="background:var(--mz-green)"></span>Sudah Bayar
                                    </span>
                                @else
                                    <span class="mz-badge badge-belum">
                                        <span class="badge-dot" style="background:var(--mz-red)"></span>Belum Bayar
                                    </span>
                                @endif
                            </td>

                            <td class="td-right">
                                @if($i->payment_awal == $i->grand_total && $i->grand_total > 0)
                                    <span class="lunas-text">LUNAS</span>
                                @else
                                    Rp {{ number_format($i->payment_awal) }}
                                @endif
                            </td>

                            <td class="td-right">
                                @if($i->sisa == 0)
                                    <span class="lunas-text">LUNAS</span>
                                @else
                                    <span class="sisa-text">Rp {{ number_format($i->sisa) }}</span>
                                @endif
                            </td>

                            <td class="td-center">
                                <div class="act-group">
                                    <a href="{{ route('invoice.show',$i) }}"  class="act-btn act-lihat">Lihat</a>
                                    <a href="{{ route('invoice.edit',$i) }}"  class="act-btn act-edit">Edit</a>
                                    <a href="{{ route('invoice.print',$i) }}" class="act-btn act-print">Print</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                                    <p>Belum ada invoice</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Pagination ── --}}
    <div class="inv-pagination">
        {{ $invoices->links() }}
    </div>

</div>

<script src="{{ asset('assets/js/index-inv.js') }}"></script>
<script>
(function () {
    var wrap = document.getElementById('tableScroll');
    var hint = document.getElementById('scrollHint');
    if (!wrap || !hint) return;

    // Tampilkan hint hanya kalau tabel memang bisa di-scroll
    function checkOverflow() {
        if (wrap.scrollWidth > wrap.clientWidth) {
            hint.classList.add('show-hint');
        } else {
            hint.classList.remove('show-hint');
        }
    }
    checkOverflow();
    window.addEventListener('resize', checkOverflow);

    wrap.addEventListener('scroll', function () {
        hint.classList.toggle('scrolled', wrap.scrollLeft > 10);
    }, { passive: true });
})();
</script>
@endsection
