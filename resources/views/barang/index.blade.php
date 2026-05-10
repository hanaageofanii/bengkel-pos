@extends('dashboard')

@section('title', 'Stok Barang')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/index-barang.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/mz-search.css') }}">
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
                <svg viewBox="0 0 24 24">
                    <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
                </svg>
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

    {{-- ── Search Bar ── --}}
    <div class="mz-search-wrap">
        <div class="mz-search-box">
            <svg class="mz-search-icon" viewBox="0 0 24 24">
                <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <input
                type="text"
                x-model="search"
                class="mz-search-input"
                placeholder="Cari nama barang atau satuan..."
                autocomplete="off"
            >
            <button x-show="search" @click="search = ''" class="mz-search-clear" x-transition>
                <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
        <div class="mz-search-count" x-show="search" x-transition>
            Menampilkan hasil untuk "<span x-text="search" class="mz-search-keyword"></span>"
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="sb-card">
        <div class="sb-card-bar"></div>

        <div class="sb-table-wrap">
            <table class="sb-table">
                <thead>
                    <tr>
                        <th class="th-nama">Nama Barang</th>
                        <th class="th-num">Harga Modal</th>
                        <th class="th-center">Stok</th>
                        <th class="th-center">Satuan</th>
                        <th class="th-aksi">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($barangs as $b)
                    <tr x-show="!search || $el.textContent.toLowerCase().includes(search.toLowerCase())">
                        <td class="td-nama">{{ $b->nama }}</td>

                        <td class="td-price">
                            <span style="font-size:10px;color:var(--mz-muted);margin-right:2px">Rp</span>{{ number_format($b->harga_pribadi) }}
                        </td>

                        <td class="td-stok">
                            <span class="stok-badge
                                @if($b->stok <= 0)    stok-empty
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
                        <td colspan="5">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                                <p>Belum ada data barang</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    {{-- Empty search result --}}
                    <tr x-show="search && document.querySelectorAll('.sb-table tbody tr[style*=\'display: none\']').length === {{ $barangs->count() }}" style="display:none">
                        <td colspan="5">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                                <p>Tidak ada barang yang cocok</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

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
