@extends('dashboard')

@section('title', 'Stok Barang')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/index-barang.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="sb-wrap" x-data="deleteModal()">

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="mz-alert-success">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="sb-header">
        <div>
            <div class="sb-title">Stok Barang</div>
            <div class="sb-subtitle">Daftar barang dan harga</div>
        </div>
        <a href="{{ route('barang.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Barang
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="sb-stats">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $barangs->count() }}</div>
                <div class="stat-lbl">Total Jenis Barang</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $barangs->sum('stok') }}</div>
                <div class="stat-lbl">Total Unit Stok</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $barangs->where('stok', '<=', 5)->count() }}</div>
                <div class="stat-lbl">Stok Hampir Habis</div>
            </div>
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="sb-card">
        <div class="sb-card-bar"></div>

        <table class="sb-table">
            <thead>
                <tr>
                    <th class="th-nama">Nama Barang</th>
                    <th class="th-num">Harga Pribadi</th>
                    <th class="th-num">Harga Perusahaan</th>
                    <th class="th-center">Stok</th>
                    <th class="th-center">Satuan</th>
                    <th class="th-aksi">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($barangs as $b)
                <tr>
                    <td class="td-nama">{{ $b->nama }}</td>

                    <td class="td-price">
                        <span style="font-size:10px;color:var(--mz-muted);margin-right:2px">Rp</span>{{ number_format($b->harga_pribadi) }}
                    </td>

                    <td class="td-price">
                        <span style="font-size:10px;color:var(--mz-muted);margin-right:2px">Rp</span>{{ number_format($b->harga_perusahaan) }}
                    </td>

                    <td class="td-stok">
                        <span class="stok-badge
                            @if($b->stok <= 0)   stok-empty
                            @elseif($b->stok <= 5) stok-low
                            @else                  stok-ok
                            @endif
                        ">{{ $b->stok }}</span>
                    </td>

                    <td class="td-satuan">
                        <span class="satuan-pill">{{ $b->satuan }}</span>
                    </td>

                    <td class="td-aksi">
                        <div class="action-group">
                            <a href="{{ route('barang.edit', $b->id) }}" class="btn-edit">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            <button @click="open({{ $b->id }}, '{{ $b->nama }}')" class="btn-del">
                                <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                            <p>Belum ada data barang</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Delete Modal ── --}}
    <div x-show="show" x-transition class="mz-backdrop" style="display:none">
        <div @click.away="close" class="mz-modal">
            <div class="mz-modal-bar-red"></div>
            <div class="mz-modal-body">

                <div class="del-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                </div>

                <div class="del-title">Konfirmasi Hapus</div>
                <p class="del-desc">
                    Yakin ingin menghapus barang ini? Tindakan tidak bisa dibatalkan.
                    <span class="del-name" x-text="nama"></span>
                </p>

                <div class="del-actions">
                    <button @click="close" class="del-btn-cancel">Batal</button>
                    <form :action="url" method="POST" style="flex:1;display:flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="del-btn-confirm" style="width:100%">Hapus</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
<script src="{{ asset('assets/js/index-barang.js') }}"></script>
@endsection
