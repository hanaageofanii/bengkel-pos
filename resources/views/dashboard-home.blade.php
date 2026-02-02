@extends('dashboard')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-semibold mb-2">Dashboard 5A Auto Service</h1>

<p class="text-gray-600 mb-6">
    Halo, {{ auth()->user()->name }}
</p>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-sm text-gray-500">Total Karyawan</p>
        <p class="text-xl font-semibold">
            {{ $totalKaryawan }}
        </p>
    </div>
</div>
@endsection
