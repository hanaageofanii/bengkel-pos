@extends('dashboard')

@section('title', 'Self Billing')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/selfbilling.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="sb-wrapper">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <div class="page-title-text">Self Billing</div>
            <div class="page-subtitle">Monitoring hutang bengkel ke vendor</div>
            <div class="count-chip">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                {{ $selfBillings->count() }} Transaksi Aktif
            </div>
        </div>
        <a href="{{ route('selfbilling.create') }}" class="sb-btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Tagihan
        </a>
    </div>

    {{-- STAT CARDS --}}
    <div class="sb-stats">
        <div class="sb-stat-card">
            <div class="sb-stat-icon sb-icon-red">
                <svg class="mz-input-icon" viewBox="0 0 24 24">
    <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
</svg>            </div>

            <div>
                <div class="sb-stat-label">Total Sisa Hutang</div>
                <div class="sb-stat-value sb-val-danger">Rp {{ number_format($totalSisaHutang, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="sb-stat-card">
            <div class="sb-stat-icon sb-icon-purple">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div>
                <div class="sb-stat-label">Mitra Vendor</div>
                <div class="sb-stat-value">{{ $selfBillings->unique('nama_vendor')->count() }} Vendor</div>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div class="sb-alert">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- TABLE --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span>
                Daftar Tagihan & Status Pembayaran
            </div>
            <div class="search-wrap">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input type="text" class="search-input" id="tableSearch" placeholder="Cari vendor atau barang..." oninput="filterTable()">
            </div>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invoiceTable">
                <thead>
                    <tr>
                        <th style="width:40px;text-align:center">No</th>
                        <th>Info Tagihan</th>
                        <th>Vendor</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-right">Sisa Hutang</th>
                        <th class="text-right">Payment Notes</th>
                        <th style="text-align:center">Status</th>
                        <th style="width:110px;text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($selfBillings as $i => $sb)
                    <tr>
                        <td style="text-align:center;color:var(--mz-muted)">{{ $i + 1 }}</td>
                        <td>
                            <div class="info-barang">{{ $sb->jenis_barang }}</div>
                            <div class="info-tanggal">{{ \Carbon\Carbon::parse($sb->tanggal)->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div class="customer-cell">
                                <div class="avatar-circle">{{ strtoupper(substr($sb->nama_vendor, 0, 2)) }}</div>
                                <span class="customer-name">{{ $sb->nama_vendor }}</span>
                            </div>
                        </td>
                        <td class="text-right">
                            <span class="amount-muted">Rp {{ number_format($sb->total_tagihan, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-right">
                            <span class="amount-sisa {{ $sb->sisa_tagihan == 0 ? 'lunas' : 'hutang' }}">
                                Rp {{ number_format($sb->sisa_tagihan, 0, ',', '.') }}
                            </span>
                        </td>
                        <td style="text-align: right; vertical-align: middle;">
                            @if($sb->payment_notes)
                                <div style="font-size: 12px; color: #64748b; line-height: 1.4; max-width: 200px; margin: 0 auto;" title="{{ $sb->payment_notes }}">
                                    {{ Str::limit($sb->payment_notes, 40) }}
                                </div>
                            @else
                                <span style="color: #cbd5e1; font-size: 11px; display: block; text-align: left;">-</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            @if($sb->sisa_tagihan <= 0)
                                <span class="status-badge status-lunas">Lunas</span>
                            @elseif($sb->payments->count() > 0)
                                <span class="status-badge status-cicil">Proses</span>
                            @else
                                <span class="status-badge status-hutang">Tagihan</span>
                            @endif
                        </td>
                        <td>
                            <div class="sb-actions">
                                <a href="{{ route('selfbilling.show', $sb->id) }}"
                                   class="sb-act sb-act-view" title="Detail & Riwayat">
                                    <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                </a>
                                <button class="sb-act sb-act-edit" title="Edit Data"
                                    data-id="{{ $sb->id }}"
                                    data-vendor="{{ addslashes($sb->nama_vendor) }}"
                                    data-tanggal="{{ $sb->tanggal }}"
                                    data-barang="{{ addslashes($sb->jenis_barang) }}"
                                    data-jumlah="{{ $sb->jumlah_barang }}"
                                    data-total="{{ $sb->total_tagihan }}"
                                    data-notes="{{ addslashes($sb->payment_notes ?? '') }}"
                                    onclick="openEdit(this)">
                                    <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                </button>
                                <button class="sb-act sb-act-del" title="Hapus"
                                    data-id="{{ $sb->id }}"
                                    data-vendor="{{ addslashes($sb->nama_vendor) }}"
                                    data-total="{{ number_format($sb->total_tagihan, 0, ',', '.') }}"
                                    data-sisa="{{ number_format($sb->sisa_tagihan, 0, ',', '.') }}"
                                    onclick="openDelete(this)">
                                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24"><path d="M19.5 3.5L18 2l-1.5 1.5L15 2l-1.5 1.5L12 2l-1.5 1.5L9 2 7.5 3.5 6 2v14H3v3c0 1.66 1.34 3 3 3h12c1.66 0 3-1.34 3-3V2l-1.5 1.5zM19 19c0 .55-.45 1-1 1s-1-.45-1-1v-3H8V5h11v14z"/><path d="M9 7h6v2H9zm0 3h6v2H9z"/></svg>                                <p>Belum ada data tagihan vendor.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL EDIT
════════════════════════════════════════════════════════ --}}
<div class="sb-overlay" id="modalEdit" onclick="handleOverlayClick(event, 'modalEdit')">
    <div class="sb-modal">
        <div class="sb-modal-head">
            <div class="sb-modal-title">
                <div class="sb-modal-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:#fff"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                </div>
                Edit Data Tagihan
            </div>
            <button class="sb-modal-x" onclick="closeModal('modalEdit')">
                <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:var(--muted)"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
        <form id="editForm" method="POST"
      onsubmit="document.querySelectorAll('.rupiah-modal').forEach(i=>i.value=i.value.replace(/[^0-9]/g,''))">
    @csrf
    @method('PUT')
            <div class="sb-modal-body">
                <div class="sb-form-grid">
                    <div class="sb-form-group">
                        <label class="sb-form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="e-tanggal" class="sb-input" required>
                    </div>
                    <div class="sb-form-group">
                        <label class="sb-form-label">Nama Vendor</label>
                        <input type="text" name="nama_vendor" id="e-vendor" class="sb-input" placeholder="Nama PT atau Toko" required>
                    </div>
                    <div class="sb-form-group">
                        <label class="sb-form-label">Jenis Barang / Jasa</label>
                        <input type="text" name="jenis_barang" id="e-barang" class="sb-input" placeholder="Oli Mesin, Ban Luar..." required>
                    </div>
                    <div class="sb-form-group">
                        <label class="sb-form-label">Jumlah Unit</label>
                        <input type="number" name="jumlah_barang" id="e-jumlah" class="sb-input" placeholder="0" min="0" required>
                    </div>
                    <div class="sb-form-group" style="grid-column:span 2">
                        <label class="sb-form-label">Total Tagihan (Rp)</label>
                        <input
                            type="text"
                            name="total_tagihan"
                            id="e-total"
                            class="sb-input rupiah-modal"
                            placeholder="Rp. 0"
                            min="0"
                            required
                            onfocus="this.value=this.value.replace(/[^0-9]/g,'')"
                            oninput="let r=this.value.replace(/[^0-9]/g,''); this.value=r?'Rp. '+Number(r).toLocaleString('id-ID'):'';"
                            onblur="if(this.value) this.value='Rp. '+Number(this.value.replace(/[^0-9]/g,'')).toLocaleString('id-ID')"
                        >
                        <span class="sb-input-hint" id="e-total-hint"></span>
                    </div>
                    <div class="sb-form-group" style="grid-column:span 2">
                        <label class="sb-form-label">Catatan <span class="sb-optional">opsional</span></label>
                        <textarea name="payment_notes" id="e-notes" class="sb-input sb-textarea" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>
            <div class="sb-modal-foot">
                <button type="button" class="sb-btn-ghost" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="sb-btn-save">
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:#fff"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL DELETE
════════════════════════════════════════════════════════ --}}
<div class="sb-overlay" id="modalDelete" onclick="handleOverlayClick(event, 'modalDelete')">
    <div class="sb-modal" style="max-width:420px">
        <div class="sb-modal-head">
            <div class="sb-modal-title">
                <div class="sb-modal-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:#fff"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                </div>
                Hapus Tagihan
            </div>
            <button class="sb-modal-x" onclick="closeModal('modalDelete')">
                <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:var(--muted)"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
        <div class="sb-modal-body">
            <div class="sb-del-body">
                <div class="sb-del-icon-wrap">
                    <svg viewBox="0 0 24 24" style="width:28px;height:28px;fill:#f87171"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                </div>
                <p class="sb-del-title">Yakin ingin menghapus?</p>
                <p class="sb-del-desc">
                    Data tagihan dari <strong id="d-vendor" class="sb-del-strong"></strong>
                    senilai <strong id="d-total" class="sb-del-amount"></strong> beserta
                    <span id="d-sisa-info"></span> akan <strong>dihapus permanen</strong>
                    dan tidak dapat dikembalikan.
                </p>
            </div>
        </div>
        <div class="sb-modal-foot">
            <button type="button" class="sb-btn-ghost" onclick="closeModal('modalDelete')">Batal</button>
            <form id="deleteForm" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="sb-btn-del">
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:#fff"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/selfbilling.js') }}"></script>
@endsection
