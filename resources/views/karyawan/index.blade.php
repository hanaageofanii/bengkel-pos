@extends('dashboard')

@section('title', 'Data Karyawan')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/index-karyawan.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div x-data="deleteModal()" class="ky-wrap">

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="mz-alert-success">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="ky-header">
        <div>
            <div class="ky-title">Data Karyawan</div>
            <div class="ky-subtitle">Daftar karyawan yang terdaftar di sistem</div>
        </div>
        <a href="{{ route('karyawan.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Karyawan
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="ky-stats">
        <div class="stat-card">
            <div class="stat-icon emerald">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $karyawans->count() }}</div>
                <div class="stat-lbl">Total Karyawan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $karyawans->where('status','aktif')->count() }}</div>
                <div class="stat-lbl">Aktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $karyawans->where('status','cuti')->count() }}</div>
                <div class="stat-lbl">Cuti</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $karyawans->whereIn('status',['resign','nonaktif'])->count() }}</div>
                <div class="stat-lbl">Nonaktif / Resign</div>
            </div>
        </div>
    </div>

    {{-- ── Table Card ── --}}
    <div class="ky-card">
        <div class="ky-card-bar"></div>

        <table class="ky-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Kontak</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th class="th-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $k)
                <tr>
                    {{-- Nama --}}
                    <td>
                        <div class="td-nama-wrap">
                            <div class="avatar-mini">
                                {{ collect(explode(' ', $k->nama))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('') }}
                            </div>
                            <div class="td-nama-text">
                                <div class="tn">{{ $k->nama }}</div>
                                <div class="tid">#{{ $k->id }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Jabatan --}}
                    <td>
                        @if($k->jabatan)
                            <span class="jabatan-pill">
                                <svg viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.36C18 2.06 15.94 0 13.36 0c-1.31 0-2.44.55-3.31 1.41L9 2.5 7.95 1.41C7.08.55 5.95 0 4.64 0 2.06 0 0 2.06 0 4.64c0 .48.1.92.18 1.36H0v2h20c1.1 0 2-.9 2-2s-.9-2-2-2zM4.64 4c-.75 0-1.36-.61-1.36-1.36S3.89 1.28 4.64 1.28s1.36.61 1.36 1.36S5.39 4 4.64 4zm8.72 0c-.75 0-1.36-.61-1.36-1.36s.61-1.36 1.36-1.36 1.36.61 1.36 1.36S14.11 4 13.36 4zM2 22h20v-2H2v2zm0-4h20v-2H2v2zm0-4h20v-2H2v2z"/></svg>
                                {{ $k->jabatan }}
                            </span>
                        @else
                            <span style="color:var(--mz-muted);font-size:12px">—</span>
                        @endif
                    </td>

                    {{-- Kontak --}}
                    <td>
                        <div class="kontak-row">
                            <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.25 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.61 21 3 13.39 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            {{ $k->no_hp ?? '—' }}
                        </div>
                        <div class="kontak-row">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            {{ $k->email ?? '—' }}
                        </div>
                    </td>

                    {{-- Salary --}}
                    <td>
                        @if($k->salary)
                            <span class="salary-badge">
                                Rp {{ number_format($k->salary, 0, ',', '.') }}
                            </span>
                        @else
                            <span style="color:var(--mz-muted);font-size:12px">—</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        @switch($k->status)
                            @case('aktif')
                                <span class="status-badge st-aktif">
                                    <span class="status-dot"></span>Aktif
                                </span>
                                @break
                            @case('cuti')
                                <span class="status-badge st-cuti">
                                    <span class="status-dot"></span>Cuti
                                </span>
                                @break
                            @case('resign')
                                <span class="status-badge st-resign">
                                    <span class="status-dot"></span>Resign
                                </span>
                                @break
                            @case('nonaktif')
                                <span class="status-badge st-nonaktif">
                                    <span class="status-dot"></span>Nonaktif
                                </span>
                                @break
                        @endswitch
                    </td>

                    {{-- Aksi --}}
                    <td class="td-aksi">
                        <div class="action-group">
                            <a href="{{ route('karyawan.edit', $k->id) }}" class="btn-edit">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            <button @click="open({{ $k->id }}, '{{ $k->nama }}')" class="btn-del">
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
                            <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            <p>Belum ada data karyawan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Delete Modal ── --}}
    <div x-show="show" x-transition class="mz-backdrop" style="display:none">
        <div @click.away="show = false" class="mz-modal">
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
                    <button @click="show = false" class="del-btn-cancel">Batal</button>
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
<script src="{{ asset('assets/js/index-karyawan.js') }}"></script>
@endsection
