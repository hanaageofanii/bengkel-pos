@extends('dashboard')

@section('title', 'Data Pelanggan')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/index-pelanggan.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/mz-search.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="pl-wrap" x-data="deleteModal()">

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="mz-alert-success">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="pl-header">
        <div>
            <div class="pl-title">Data Pelanggan</div>
            <div class="pl-subtitle">Daftar pelanggan dan kendaraan yang terdaftar</div>
        </div>
        <a href="{{ route('pelanggan.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Pelanggan
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="pl-stats">
        <div class="stat-card">
            <div class="stat-icon teal">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $pelanggans->count() }}</div>
                <div class="stat-lbl">Total Pelanggan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $pelanggans->where('tipe','pribadi')->count() }}</div>
                <div class="stat-lbl">Pelanggan Umum</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg viewBox="0 0 24 24"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $pelanggans->where('tipe','perusahaan')->count() }}</div>
                <div class="stat-lbl">Pelanggan Perusahaan</div>
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
                placeholder="Cari nama, plat nomor, merk/model mobil..."
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
    <div class="pl-card">
        <div class="pl-card-bar"></div>

        <table class="pl-table">
            <thead>
                <tr>
                    <th class="th-nama">Nama Pelanggan</th>
                    <th class="th-center">Plat Nomor</th>
                    <th class="th-center">No. Chasis</th>
                    <th class="th-center">No. Mesin</th>
                    <th class="th-center">Mobil</th>
                    <th class="th-center">Tipe</th>
                    <th class="th-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggans as $p)
                <tr x-show="!search || $el.textContent.toLowerCase().includes(search.toLowerCase())">
                    {{-- Nama --}}
                    <td class="td-nama" data-label="Pelanggan">
                        {{ $p->nama }}
                        @if($p->no_hp)
                            <div class="td-hp">
                                <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                                {{ $p->no_hp }}
                            </div>
                        @endif
                    </td>

                    {{-- Plat --}}
                    <td data-label="Plat Nomor" style="text-align:center">
                        <span class="plat-badge">{{ $p->plat_nomor }}</span>
                    </td>

                    {{-- Chasis --}}
                    <td data-label="No. Chasis" style="text-align:center">
                        <span class="plat-badge">{{ $p->no_chasis }}</span>
                    </td>

                    {{-- Mesin --}}
                    <td data-label="No. Mesin" style="text-align:center">
                        <span class="plat-badge">{{ $p->no_mesin }}</span>
                    </td>

                    {{-- Mobil --}}
                    <td class="td-mobil" data-label="Mobil">
                        <div class="mobil-name">{{ $p->merk_mobil }} {{ $p->model_mobil }}</div>
                        @if($p->tahun_mobil)
                            <div class="mobil-tahun">{{ $p->tahun_mobil }}</div>
                        @endif
                    </td>

                    {{-- Tipe --}}
                    <td class="td-tipe" data-label="Tipe">
                        @if($p->tipe === 'pribadi')
                            <span class="tipe-pribadi">
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                Umum
                            </span>
                        @else
                            <span class="tipe-perusahaan">
                                <svg viewBox="0 0 24 24"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                                Perusahaan
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="td-aksi" data-label="Aksi">
                        <div class="action-group">
                            <a href="{{ route('pelanggan.edit', $p->id) }}" class="btn-edit">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            <button @click="open({{ $p->id }}, '{{ $p->nama }}')" class="btn-del">
                                <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            <p>Belum ada data pelanggan</p>
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
                    Apakah kamu yakin ingin menghapus
                    <span class="del-name" x-text="nama"></span>
                    <span class="del-sub">Data yang dihapus tidak bisa dikembalikan.</span>
                </p>
                <div class="del-actions">
                    <button @click="close" class="del-btn-cancel">Batal</button>
                    <form :action="url" method="POST" style="flex:1;display:flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="del-btn-confirm" style="width:100%">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('assets/js/index-pelanggan.js') }}"></script>
@endsection
