@extends('dashboard')

@section('title', 'Detail Self Billing')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/create-invoice.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="inv-wrap">

    {{-- Breadcrumb --}}
    <div class="inv-breadcrumb">
        <a href="{{ route('selfbilling.index') }}">Self Billing</a>
        <span>/</span>
        <span style="color:var(--mz-text)">Detail Tagihan</span>
    </div>

    <div class="inv-title">{{ $selfbilling->nama_vendor }}</div>
    <div class="inv-subtitle">Detail tagihan untuk: {{ $selfbilling->jenis_barang }}</div>

    <div class="inv-summary" style="background: var(--mz-surface); border-radius: 12px; margin-bottom: 25px; border: 1px solid var(--mz-border); justify-content: space-around;">
        <div class="sum-item" style="text-align: center">
            <div class="sum-label">TOTAL TAGIHAN</div>
            <div class="sum-val">Rp {{ number_format($selfbilling->total_tagihan, 0, ',', '.') }}</div>
        </div>
        <div style="color:var(--mz-border);font-size:24px">/</div>
        <div class="sum-item" style="text-align: center">
            <div class="sum-label" style="color:var(--mz-green)">TOTAL DIBAYAR</div>
            <div class="sum-val" style="color:var(--mz-green)">Rp {{ number_format($selfbilling->payments->sum('nominal_bayar'), 0, ',', '.') }}</div>
        </div>
        <div style="color:var(--mz-border);font-size:24px">=</div>
        <div class="sum-item" style="text-align: center">
            <div class="sum-label" style="color:var(--mz-red)">SISA TAGIHAN</div>
            <div class="sum-val grand" style="color:var(--mz-red)">Rp {{ number_format($selfbilling->sisa_tagihan, 0, ',', '.') }}</div>
        </div>
    </div>

    @if($selfbilling->sisa_tagihan > 0)
    <div class="inv-card" style="margin-bottom: 20px;">
        <div class="inv-card-bar" style="background: var(--mz-accent)"></div>
        <div class="inv-section">
            <div class="inv-section-title">
                <svg style="width:16px; height:16px; fill:var(--mz-accent); margin-right:8px" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.39 2.1-1.39 1.47 0 2.01.59 2.1 1.58h1.79c-.07-1.62-1.07-2.55-2.52-2.9V5h-2v1.73c-1.54.34-2.72 1.32-2.72 2.91 0 1.9 1.58 2.85 3.89 3.42 2.1.51 2.54 1.19 2.54 2.04 0 1.18-1.13 1.75-2.38 1.75-1.61 0-2.28-.73-2.39-1.91h-1.79c.11 2.06 1.55 2.89 2.76 3.18V19h2v-1.7c1.63-.29 2.82-1.17 2.82-2.73 0-2.15-1.62-3.04-3.88-3.63z"/></svg>
                Input Pembayaran Baru
            </div>

            <form action="{{ route('selfbilling.pay', $selfbilling->id) }}" method="POST"
                onsubmit="document.querySelectorAll('.rupiah-field').forEach(i=>i.value=i.value.replace(/[^0-9]/g,''))">
                @csrf
                <div class="inv-grid" style="margin-top: 15px;">
                    <div class="mz-field">
                        <label class="mz-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" class="mz-input" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mz-field">
                    <label class="mz-label">Nominal Bayar (Rp)</label>
                    <input
                        type="text"
                        name="nominal_bayar"
                        class="mz-input rupiah-field"
                        placeholder="Rp. 0"
                        required
                        onfocus="this.value=this.value.replace(/[^0-9]/g,'')"
                        oninput="let r=this.value.replace(/[^0-9]/g,''); this.value=r?'Rp. '+Number(r).toLocaleString('id-ID'):'';"
                        onblur="if(this.value) this.value='Rp. '+Number(this.value.replace(/[^0-9]/g,'')).toLocaleString('id-ID')"
                    >
                </div>
                    <div class="mz-field">
                        <label class="mz-label">Metode</label>
                        <select name="metode_bayar" class="mz-select" required>
                            <option value="cash">Cash</option>
                            <option value="bca">Debit</option>
                            <option value="mandiri">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="mz-field">
                        <label class="mz-label">&nbsp;</label>
                        <button type="submit" class="btn-submit" style="width: 100%;">Proses Bayar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="lunas-box" style="margin-bottom: 25px; padding: 30px; border-width: 2px;">
        <div style="font-size: 24px;">LUNAS TERBAYAR</div>
        <div style="font-size: 12px; font-weight: 400; opacity: 0.8;">Seluruh tagihan vendor telah diselesaikan.</div>
    </div>
    @endif

    <div class="inv-card">
        <div class="inv-section">
            <div class="inv-section-title">Riwayat Pembayaran Vendor</div>
            <div style="margin-top: 15px;">
                @forelse($selfbilling->payments as $pay)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid var(--mz-border);">
                    <div>
                        <div style="font-size: 13px; font-weight: 600;">{{ date('d F Y', strtotime($pay->tanggal_bayar)) }}</div>
                        <div style="font-size: 11px; color: var(--mz-muted);">
                            Metode:
                            @if($pay->metode_bayar == 'bca')
                                Debit
                            @elseif($pay->metode_bayar == 'mandiri')
                                Transfer Bank
                            @elseif($pay->metode_bayar == 'cash')
                                Cash
                            @else
                                {{ strtoupper($pay->metode_bayar) }}
                            @endif
                        </div>
                    </div>
                    <div style="font-family: 'Rajdhani'; font-weight: 700; color: var(--mz-green); font-size: 18px;">
                        + Rp {{ number_format($pay->nominal_bayar, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 40px; color: var(--mz-muted); font-size: 12px;">
                    Belum ada riwayat pembayaran untuk tagihan ini.
                </div>
                @endforelse
            </div>
        </div>

        <div class="inv-footer">
            <a href="{{ route('selfbilling.index') }}" class="btn-cancel">Kembali ke Daftar</a>
        </div>
    </div>
</div>

@endsection
