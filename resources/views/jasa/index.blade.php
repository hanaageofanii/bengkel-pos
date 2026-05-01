@extends('dashboard')

@section('title', 'Jasa Pekerjaan')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/index-jasa.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<div class="jasa-wrap" x-data="deleteModal()">

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="mz-alert-success">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5l-4-4 1.41-1.41L10 13.67l6.59-6.59L18 8.5l-8 8z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="jasa-header">
        <div>
            <div class="jasa-title">Jasa Pekerjaan</div>
            <div class="jasa-subtitle">Daftar jasa servis dan pekerjaan</div>
        </div>
        <a href="{{ route('jasa.create') }}" class="btn-add">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Jasa
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="jasa-stats">
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
            </div>
            <div>
                <div class="stat-val">{{ $jasas->count() }}</div>
                <div class="stat-lbl">Total Jenis Jasa</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
<svg class="mz-input-icon" viewBox="0 0 24 24">
    <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
</svg>            </div>
            <div>
                <div class="stat-val">Rp {{ number_format($jasas->avg('harga_pribadi'), 0, ',', '.') }}</div>
                <div class="stat-lbl">Rata-rata Harga Modal</div>
            </div>
        </div>
        {{-- <div class="stat-card">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
            </div>
             <div>
                <div class="stat-val">Rp {{ number_format($jasas->avg('harga_perusahaan'), 0, ',', '.') }}</div>
                <div class="stat-lbl">Rata-rata Harga Perusahaan</div>
            </div>
        </div> --}}
    </div>

    {{-- ── Table Card ── --}}
    <div class="jasa-card">
        <div class="jasa-card-bar"></div>

        <table class="jasa-table">
            <thead>
                <tr>
                    <th class="th-nama">Nama Jasa</th>
                    <th class="th-num">Harga Modal</th>
                    <th class="th-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jasas as $j)
                <tr>
                    <td class="td-nama">
                        {{ $j->nama }}
                        @if($j->keterangan)
                            <div class="desc-tag">
                                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                                {{ $j->keterangan }}
                            </div>
                        @endif
                    </td>

                    <td class="td-price">
                        <span class="price-rp">Rp</span>{{ number_format($j->harga_pribadi) }}
                    </td>

                    {{-- <td class="td-price">
                        <span class="price-rp">Rp</span>{{ number_format($j->harga_perusahaan) }}
                    </td> --}}

                    <td class="td-aksi">
                        <div class="action-group">
                            <a href="{{ route('jasa.edit', $j->id) }}" class="btn-edit">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            <button @click="open({{ $j->id }}, '{{ $j->nama }}')" class="btn-del">
                                <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
                            <p>Belum ada data jasa</p>
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
                    Yakin ingin menghapus jasa ini?
                    <span class="del-name" x-text="nama"></span>
                    <span class="del-sub">Data yang dihapus tidak bisa dikembalikan.</span>
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

<script>
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

function deleteModal() {
    return {
        show: false,
        url: '',
        nama: '',
        open(id, nama) {
            this.url = `/jasa/${id}`
            this.nama = nama
            this.show = true
        },
        close() {
            this.show = false
        }
    }
}
</script>
@endsection
