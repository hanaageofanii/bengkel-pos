@extends('dashboard')

@section('title','Estimasi')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/index-inv.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="inv-wrap">

    <div class="inv-header">
        <div>
            <div class="inv-title">Estimasi</div>
            <div class="inv-subtitle">Riwayat estimasi biaya servis & perbaikan kendaraan</div>
        </div>
        <a href="{{ route('estimasi.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Buat Estimasi
        </a>
    </div>

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#22c55e;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
        {{ session('error') }}
    </div>
    @endif

    <div class="inv-search">
        <form method="GET">
            <div class="search-wrap">
                <input name="q" value="{{ $q ?? '' }}"
                       placeholder="Cari pelanggan, plat, atau mobil…"
                       class="search-input">
                <div class="search-date-group">
                    <input type="date" name="dari"   value="{{ $dari ?? '' }}"   class="search-date" title="Dari tanggal">
                    <span class="date-sep">—</span>
                    <input type="date" name="sampai" value="{{ $sampai ?? '' }}" class="search-date" title="Sampai tanggal">
                </div>
                <button type="submit" class="search-btn">Cari</button>
                @if(($q ?? '') || ($dari ?? '') || ($sampai ?? ''))
                    <a href="{{ route('estimasi.index') }}" class="search-reset">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="inv-card">
        <div class="inv-card-bar" style="background:linear-gradient(90deg,#f5c542,#f59e0b,#fbbf24)"></div>
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Tanggal</th>
                    <th>Total Jasa</th>
                    <th>Total Part</th>
                    <th>Grand Total</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($estimasis as $e)
                <tr>
                    <td>
                        <div class="td-nama">{{ $e->pelanggan->nama }}</div>
                        <div class="td-plat">{{ strtoupper($e->pelanggan->plat_nomor) }}</div>
                    </td>
                    <td>
                        <div class="td-mobil">{{ $e->pelanggan->merk_mobil }} {{ $e->pelanggan->model_mobil }}</div>
                    </td>
                    <td class="td-center" style="color:var(--mz-muted)">
                        {{ \Carbon\Carbon::parse($e->tanggal)->format('d M Y') }}
                    </td>
                    <td class="td-right">Rp {{ number_format($e->total_jasa) }}</td>
                    <td class="td-right">Rp {{ number_format($e->total_part) }}</td>
                    <td class="td-right td-num-bold" style="color:var(--mz-yellow,#f5c542)">
                        Rp {{ number_format($e->grand_total) }}
                    </td>
                    <td>
                        <div style="max-width:200px; font-size:12px; color:#666;">
                            {{ $e->notes ?? '-' }}
                        </div>
                    </td>
                    <td class="td-center">
                        <div class="act-group">
                            {{-- TOMBOL APPROVE --}}
                            <button type="button"
                                class="act-btn"
                                style="background:#22c55e;color:#fff"
                                onclick="openApproveModal({{ $e->id }}, '{{ $e->pelanggan->nama }}')">
                                ✓ Approve
                            </button>

                            <a href="{{ route('estimasi.print', $e) }}" class="act-btn act-print">Print</a>
                            <a href="{{ route('estimasi.edit', $e) }}" class="act-btn act-edit">Edit</a>

                            <button type="button"
                                class="act-btn"
                                style="background:#ef4444;color:#fff"
                                onclick="openDeleteModal({{ $e->id }}, '{{ $e->pelanggan->nama }}')">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                            <p>Belum ada data estimasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="inv-pagination">
        {{ $estimasis->links() }}
    </div>

</div>

{{-- ===== MODAL APPROVE ===== --}}
<div id="approveModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:999;">
    <div style="background:#fff; padding:24px; border-radius:12px; width:320px; text-align:center; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
        <div style="font-size:40px; margin-bottom:8px;">✅</div>
        <h3 style="margin:0 0 8px; font-size:16px;">Approve Estimasi?</h3>
        <p id="approveText" style="font-size:13px; color:#555; margin-bottom:20px;"></p>
        <p style="font-size:12px; color:#888; margin-bottom:20px;">
            Estimasi akan <strong>dihapus</strong> dan otomatis dibuatkan <strong>Invoice</strong> baru.
        </p>

        <form id="approveForm" method="POST">
            @csrf
            <div style="display:flex; gap:10px; justify-content:center;">
                <button type="button" onclick="closeApproveModal()"
                    style="padding:8px 18px; border:1px solid #ddd; border-radius:6px; background:#fff; cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="padding:8px 18px; background:#22c55e; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:600;">
                    Ya, Approve
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL HAPUS ===== --}}
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:999;">
    <div style="background:#fff; padding:20px; border-radius:10px; width:300px; text-align:center;">
        <h3>Hapus Data</h3>
        <p id="deleteText" style="font-size:13px; color:#555;"></p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div style="margin-top:15px; display:flex; gap:10px; justify-content:center;">
                <button type="button" onclick="closeDeleteModal()" style="padding:6px 12px;">Batal</button>
                <button type="submit" style="background:#ef4444;color:#fff;padding:6px 12px;border:none;border-radius:6px;">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ===== APPROVE MODAL =====
function openApproveModal(id, nama) {
    document.getElementById('approveModal').style.display = 'flex';
    document.getElementById('approveText').innerText = 'Approve estimasi milik ' + nama + '?';
    document.getElementById('approveForm').action = '/estimasi/' + id + '/approve';
}

function closeApproveModal() {
    document.getElementById('approveModal').style.display = 'none';
}

// ===== DELETE MODAL =====
function openDeleteModal(id, nama) {
    document.getElementById('deleteModal').style.display = 'flex';
    document.getElementById('deleteText').innerText = 'Hapus estimasi milik ' + nama + '?';
    document.getElementById('deleteForm').action = '/estimasi/' + id;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>

@endsection
