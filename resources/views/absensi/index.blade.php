@extends('dashboard')

@section('title', 'Absensi Karyawan')

@section('content')
<style>
    /* ── Mazer dark theme variables ── */
body {
    color: var(--mz-text);
}
    /* DEFAULT DARK */
:root {
    --mz-bg:       #0f1117;
    --mz-surface:  #181c27;
    --mz-surface2: #1e2333;
    --mz-border:   #262c3d;
    --mz-text:     #e4e8f0;
    --mz-muted:    #6b7694;
}

/* LIGHT MODE */
[data-theme="light"] {
    --mz-bg:       #ffffff;
    --mz-surface:  #f9fafb;
    --mz-surface2: #f1f5f9;
    --mz-border:   #e5e7eb;
    --mz-text:     #111827;
    --mz-muted:    #6b7280;
  --mz-accent:    #4f8ef7;
        --mz-muted:     #6b7694;
        --mz-green:     #3ef08a;
        --mz-red:       #f26c6c;
        --mz-yellow:    #f5c542;
        --mz-orange:    #f5923e;
        --mz-blue:      #4f8ef7;
}


    .absensi-wrap        { font-family: 'Inter', sans-serif; color: var(--mz-text); }

    .absensi-header      { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; color:var(--mz-accent); }
    .absensi-title       { font-family: 'Rajdhani', sans-serif; font-size:22px; font-weight:700; letter-spacing:.4px; }
    .absensi-subtitle    { font-size:12px; color:var(--mz-accent); margin-top:2px; }

    /* ── filter form ── */
    .absensi-filter      { display:flex; gap:8px; }
    .mz-select {
        height:34px; padding:0 10px; border-radius:5px;
        background:var(--mz-surface2); border:1px solid var(--mz-border);
        color:var(--mz-text); font-size:12px; cursor:pointer; outline:none;
        transition:border-color .15s;
    }
    .mz-select:focus     { border-color:var(--mz-accent); }

    /* ── table card ── */
    .absensi-card {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:8px; overflow-x:auto;
        box-shadow:0 4px 24px rgba(0,0,0,.35);
    }

    .absensi-table       { width:100%; border-collapse:collapse; font-size:11.5px; }

    .absensi-table thead tr {
        background:var(--mz-surface2);
        color:var(--mz-muted);
        text-transform:uppercase;
        letter-spacing:.5px;
        font-size:10px;
    }

    .absensi-table th,
    .absensi-table td    { border:1px solid var(--mz-border); padding:5px 6px; white-space:nowrap; }

    .absensi-table th    { font-weight:600; text-align:center; }
    .absensi-table th.col-nama,
    .absensi-table td.col-nama {
        text-align:left; padding-left:12px;
        position:sticky; left:0; z-index:10;
    }
    .absensi-table thead th.col-nama { background:var(--mz-surface2); }
    .absensi-table tbody td.col-nama { background:var(--mz-surface); font-weight:500; }

    .absensi-table tbody tr:hover td { background:var(--mz-surface2); }
    .absensi-table tbody tr:hover td.col-nama { background:var(--mz-surface2); }

    .th-total            { background:rgba(79,142,247,.08) !important; color:var(--mz-accent) !important; }
    .td-total            { background:rgba(79,142,247,.06); color:var(--mz-accent); font-weight:700; text-align:center; }

    /* ── status badges ── */
    .badge {
        display:inline-flex; align-items:center; justify-content:center;
        width:22px; height:22px; border-radius:4px;
        font-size:9.5px; font-weight:700; letter-spacing:.3px;
    }
    .badge-H    { background:rgba(62,240,138,.15); color:#3ef08a; }
    .badge-L    { background:rgba(242,108,108,.15); color:#f26c6c; }
    .badge-S    { background:rgba(245,197,66,.15);  color:#f5c542; }
    .badge-K    { background:rgba(245,146,62,.15);  color:#f5923e; }
    .badge-UM   { background:rgba(79,142,247,.15);  color:#4f8ef7; }
    .badge-none { background:rgba(107,118,148,.1);  color:var(--mz-muted); }
    .badge-off  { background:rgba(107,118,148,.08); color:#3a4059; }

    .td-click   { cursor:pointer; text-align:center; }

    /* ── modal backdrop ── */
    .mz-backdrop {
        position:fixed; inset:0; z-index:50;
        display:flex; align-items:center; justify-content:center;
        background:rgba(0,0,0,.6); backdrop-filter:blur(3px);
    }

    /* ── modal card ── */
    .mz-modal {
        background:var(--mz-surface); border:1px solid var(--mz-border);
        border-radius:10px; box-shadow:0 0 0 1px rgba(79,142,247,.1), 0 24px 48px rgba(0,0,0,.5);
        width:100%; max-width:300px; overflow:hidden;
    }

    .mz-modal-bar { height:3px; background:linear-gradient(90deg,#1e90ff,var(--mz-accent),#8ab6ff); }

    .mz-modal-body { padding:20px; }

    .mz-modal-title {
        font-family:'Rajdhani', sans-serif; font-size:15px; font-weight:700;
        text-align:center; color:var(--mz-text); margin-bottom:16px;
        letter-spacing:.4px;
    }

    /* ── status buttons grid ── */
    .status-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }

    .status-btn {
        background:var(--mz-surface2); border:1px solid var(--mz-border);
        border-radius:6px; padding:10px 6px; text-align:center;
        cursor:pointer; transition:border-color .15s, background .15s;
    }
    .status-btn:hover   { border-color:var(--mz-accent); background:rgba(79,142,247,.08); }
    .status-btn .sk     { font-size:13px; font-weight:700; color:var(--mz-text); }
    .status-btn .sv     { font-size:9.5px; color:var(--mz-muted); margin-top:2px; }

    /* ── modal footer ── */
    .mz-modal-footer {
        display:flex; justify-content:space-between;
        margin-top:16px; padding-top:14px; border-top:1px solid var(--mz-border);
    }

    .mz-link-danger  { font-size:12px; color:var(--mz-red);  background:none; border:none; cursor:pointer; }
    .mz-link-danger:hover  { text-decoration:underline; }
    .mz-link-cancel  { font-size:12px; color:var(--mz-muted); background:none; border:none; cursor:pointer; }
    .mz-link-cancel:hover  { text-decoration:underline; }

    /* ── info modal ── */
    .mz-info-body   { padding:24px 20px; text-align:center; }
    .mz-info-icon   { font-size:28px; margin-bottom:8px; }
    .mz-info-title  { font-family:'Rajdhani',sans-serif; font-size:15px; font-weight:700; color:var(--mz-text); margin-bottom:8px; }
    .mz-info-text   { font-size:12px; color:var(--mz-muted); line-height:1.6; }
    .mz-info-text strong { color:var(--mz-yellow); text-transform:uppercase; }
    .mz-btn-ok {
        margin-top:16px; background:var(--mz-accent); color:#fff;
        border:none; border-radius:5px; padding:8px 24px;
        font-size:12px; font-weight:600; cursor:pointer; transition:opacity .15s;
    }
    .mz-btn-ok:hover { opacity:.85; }

    /* ── summary cells ── */
    .td-sum { text-align:center; color:var(--mz-muted); font-weight:600; }
</style>

<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="absensi-wrap" x-data="absensiModal()">

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

<script>
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

function absensiModal() {
    return {
        show: false,
        infoShow: false,
        infoStatus: '',
        karyawan_id: null,
        tanggal: null,
        absensi_id: null,

        open(data) {
            this.karyawan_id = data.karyawan_id
            this.tanggal = data.tanggal
            this.absensi_id = data.absensi_id
            this.show = true
        },
        openInfo(status) {
            this.infoStatus = status
            this.infoShow = true
        },
        close() {
            this.show = false
        },
        save(status) {
            fetch('{{ route('absensi.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    karyawan_id: this.karyawan_id,
                    tanggal: this.tanggal,
                    status: status
                })
            }).then(() => location.reload())
        },
        remove() {
            if (!this.absensi_id) return
            fetch(`/absensi/${this.absensi_id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload())
        }
    }
}
</script>
@endsection
