@extends('dashboard')

@section('title', 'Absensi Karyawan')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/absensi.css') }}">
<link href="[fonts.googleapis.com](https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap)" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="absensi-wrap"
    x-data="absensiModal()"
    data-store-url="{{ route('absensi.store') }}"
    data-csrf="{{ csrf_token() }}">

    {{-- ── Header ── --}}
    <div class="absensi-header">
        <div>
            <div class="absensi-title">Absensi Karyawan</div>
            <div class="absensi-subtitle">
                {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
            </div>
        </div>

        <form method="GET" action="{{ route('absensi.index') }}" class="absensi-filter">
            <select name="bulan" class="mz-select" onchange="this.form.submit()">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun" class="mz-select" onchange="this.form.submit()">
                @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    {{-- ── Table ── --}}
    <div class="absensi-card">
        <table class="absensi-table">
            <thead>
                <tr>
                    <th class="col-nama">Nama</th>
                    @for ($d = 1; $d <= $jumlahHari; $d++)
                        <th>{{ $d }}</th>
                    @endfor
                    <th>H</th>
                    <th>L</th>
                    <th>S</th>
                    <th>K</th>
                    <th>UM</th>
                    <th class="th-total">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($karyawans as $karyawan)
                    @php
                        $isAktif  = $karyawan->status === 'aktif';
                        $totalH   = $karyawan->absensis->where('status','H')->count();
                        $totalL   = $karyawan->absensis->where('status','L')->count();
                        $totalS   = $karyawan->absensis->where('status','S')->count();
                        $totalK   = $karyawan->absensis->where('status','K')->count();
                        $totalUM  = $karyawan->absensis->where('status','UM')->count();
                        $totalAll = $totalH + $totalL + $totalS + $totalK + $totalUM;
                    @endphp

                    <tr>
                        <td class="col-nama">{{ $karyawan->nama }}</td>

                        @for ($d = 1; $d <= $jumlahHari; $d++)
                            @php
                                $tgl   = \Carbon\Carbon::create($tahun, $bulan, $d)->toDateString();
                                $absen = $karyawan->absensis->firstWhere('tanggal', $tgl);
                            @endphp

                            <td class="td-click"
                                @click="
                                    {{ $isAktif
                                        ? "open({
                                            karyawan_id: {$karyawan->id},
                                            tanggal: '{$tgl}',
                                            absensi_id: ".($absen->id ?? 'null')."
                                        })"
                                        : "openInfo('{$karyawan->status}')"
                                    }}
                                ">
                                <span class="badge
                                    @if(!$isAktif)                  badge-off
                                    @elseif($absen?->status==='H')  badge-H
                                    @elseif($absen?->status==='L')  badge-L
                                    @elseif($absen?->status==='S')  badge-S
                                    @elseif($absen?->status==='K')  badge-K
                                    @elseif($absen?->status==='UM') badge-UM
                                    @else                           badge-none
                                    @endif
                                ">
                                    {{ $isAktif ? ($absen->status ?? '·') : '—' }}
                                </span>
                            </td>
                        @endfor

                        <td class="td-sum">{{ $totalH }}</td>
                        <td class="td-sum">{{ $totalL }}</td>
                        <td class="td-sum">{{ $totalS }}</td>
                        <td class="td-sum">{{ $totalK }}</td>
                        <td class="td-sum">{{ $totalUM }}</td>
                        <td class="td-total">{{ $totalAll }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ── Modal Pilih Status ── --}}
    <div x-show="show" x-transition class="mz-backdrop" style="display:none">
        <div @click.away="close" class="mz-modal">
            <div class="mz-modal-bar"></div>
            <div class="mz-modal-body">
                <div class="mz-modal-title">Pilih Status Absensi</div>

                <div class="status-grid">
                    @foreach (['H'=>'Hadir','L'=>'Libur','S'=>'Sakit','K'=>'Kuliah','UM'=>'Uang Makan'] as $k => $v)
                        <button @click="save('{{ $k }}')" class="status-btn">
                            <div class="sk">{{ $k }}</div>
                            <div class="sv">{{ $v }}</div>
                        </button>
                    @endforeach
                </div>

                <div class="mz-modal-footer">
                    <button @click="remove" x-show="absensi_id" class="mz-link-danger">
                        Hapus
                    </button>
                    <button @click="close" class="mz-link-cancel">Batal</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal Info Status Non-Aktif ── --}}
    <div x-show="infoShow" x-transition class="mz-backdrop" style="display:none">
        <div @click.away="infoShow = false" class="mz-modal">
            <div class="mz-modal-bar"></div>
            <div class="mz-info-body">
                <div class="mz-info-icon">⚠️</div>
                <div class="mz-info-title">Tidak Bisa Absen</div>
                <p class="mz-info-text">
                    Karyawan sedang
                    <strong x-text="infoStatus"></strong>,
                    sehingga tidak bisa melakukan absensi.
                </p>
                <button @click="infoShow = false" class="mz-btn-ok">Mengerti</button>
            </div>
        </div>
    </div>

</div>
<script src="{{ asset('assets/js/absensi.js') }}"></script>
@endsection
