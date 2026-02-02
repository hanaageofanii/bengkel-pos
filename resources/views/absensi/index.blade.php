@extends('dashboard')

@section('title', 'Absensi Karyawan')

@section('content')
<div class="space-y-4" x-data="absensiModal()">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Absensi Karyawan
            </h2>
            <p class="text-xs text-gray-500">
                {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
            </p>
        </div>

        <form method="GET"
              action="{{ route('absensi.index') }}"
              class="flex gap-2">
            <select name="bulan"
                    class="h-8 px-2 rounded border text-xs"
                    onchange="this.form.submit()">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun"
                    class="h-8 px-2 rounded border text-xs"
                    onchange="this.form.submit()">
                @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    <div class="bg-white border rounded-lg overflow-x-auto">
        <table class="text-xs w-full border-collapse">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="border px-2 py-1 text-left sticky left-0 bg-gray-50 z-10">
                        Nama
                    </th>

                    @for ($d = 1; $d <= $jumlahHari; $d++)
                        <th class="border px-1 py-1 text-center">{{ $d }}</th>
                    @endfor

                    <th class="border px-1 py-1">H</th>
                    <th class="border px-1 py-1">L</th>
                    <th class="border px-1 py-1">S</th>
                    <th class="border px-1 py-1">K</th>
                    <th class="border px-1 py-1">UM</th>
                    <th class="border px-2 py-1 font-semibold bg-gray-100">
                        Total
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($karyawans as $karyawan)
                    @php
                        $isAktif = $karyawan->status === 'aktif';

                        $totalH  = $karyawan->absensis->where('status','H')->count();
                        $totalL  = $karyawan->absensis->where('status','L')->count();
                        $totalS  = $karyawan->absensis->where('status','S')->count();
                        $totalK  = $karyawan->absensis->where('status','K')->count();
                        $totalUM = $karyawan->absensis->where('status','UM')->count();
                        $totalAll = $totalH + $totalL + $totalS + $totalK + $totalUM;
                    @endphp

                    <tr class="hover:bg-gray-50">
                        <td class="border px-2 py-1 font-medium sticky left-0 bg-white">
                            {{ $karyawan->nama }}
                        </td>

                        @for ($d = 1; $d <= $jumlahHari; $d++)
                            @php
                                $tgl = \Carbon\Carbon::create($tahun, $bulan, $d)->toDateString();
                                $absen = $karyawan->absensis->firstWhere('tanggal', $tgl);
                            @endphp

                            <td class="border px-1 py-1 text-center cursor-pointer"
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

                                <span class="inline-flex items-center justify-center
                                    w-5 h-5 rounded text-[10px] font-semibold
                                    @if(!$isAktif)
                                        bg-gray-300 text-gray-500
                                    @elseif($absen?->status === 'H')
                                        bg-green-200 text-green-800
                                    @elseif($absen?->status === 'L')
                                        bg-red-200 text-red-800
                                    @elseif($absen?->status === 'S')
                                        bg-yellow-200 text-yellow-800
                                    @elseif($absen?->status === 'K')
                                        bg-orange-200 text-orange-800
                                    @elseif($absen?->status === 'UM')
                                        bg-blue-200 text-blue-800
                                    @else
                                        bg-gray-200 text-gray-500
                                    @endif
                                ">
                                    {{ $isAktif ? ($absen->status ?? '-') : '—' }}
                                </span>
                            </td>
                        @endfor

                        <td class="border px-1 py-1 text-center">{{ $totalH }}</td>
                        <td class="border px-1 py-1 text-center">{{ $totalL }}</td>
                        <td class="border px-1 py-1 text-center">{{ $totalS }}</td>
                        <td class="border px-1 py-1 text-center">{{ $totalK }}</td>
                        <td class="border px-1 py-1 text-center">{{ $totalUM }}</td>

                        <td class="border px-2 py-1 text-center font-semibold bg-gray-100">
                            {{ $totalAll }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="show" x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
         style="display:none">
        <div @click.away="close"
             class="bg-white rounded-lg shadow-lg w-full max-w-xs p-4">

            <h3 class="text-sm font-semibold text-center mb-4">
                Pilih Status
            </h3>

            <div class="grid grid-cols-3 gap-2 text-xs">
                @foreach (['H'=>'Hadir','L'=>'Libur','S'=>'Sakit','K'=>'Kuliah','UM'=>'Uang Makan'] as $k => $v)
                    <button @click="save('{{ $k }}')"
                            class="border rounded px-2 py-2 hover:bg-gray-100">
                        <div class="font-semibold">{{ $k }}</div>
                        <div class="text-[10px] text-gray-500">{{ $v }}</div>
                    </button>
                @endforeach
            </div>

            <div class="flex justify-between mt-4 text-xs">
                <button @click="remove"
                        x-show="absensi_id"
                        class="text-red-600 hover:underline">
                    Hapus
                </button>

                <button @click="close"
                        class="text-gray-600 hover:underline">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <div x-show="infoShow" x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
         style="display:none">
        <div @click.away="infoShow = false"
             class="bg-white rounded-lg shadow-lg w-full max-w-xs p-4 text-center">

            <h3 class="text-sm font-semibold mb-2">
                Tidak Bisa Absen
            </h3>

            <p class="text-xs text-gray-600">
                Karyawan sedang
                <span class="font-semibold uppercase" x-text="infoStatus"></span>,
                sehingga tidak bisa melakukan absensi.
            </p>

            <button @click="infoShow = false"
                    class="mt-4 text-xs text-blue-600 hover:underline">
                Mengerti
            </button>
        </div>
    </div>

</div>

<script>
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
