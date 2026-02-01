@extends('dashboard')

@section('content')
<h2 class="text-xl font-semibold mb-4">Tambah Karyawan</h2>

<form method="POST" action="{{ route('karyawan.store') }}"
      class="bg-white p-4 rounded shadow max-w-md">
    @csrf

    <div class="mb-3">
        <label class="text-sm">Nama Karyawan</label>
        <input type="text" name="nama" required
               class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-3">
        <label class="text-sm">Jabatan</label>
        <input type="text" name="jabatan"
               class="w-full border rounded px-3 py-2">
    </div>

    <button class="bg-green-600 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>
@endsection
