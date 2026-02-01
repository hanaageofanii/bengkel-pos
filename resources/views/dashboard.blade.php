<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | 5A Auto Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex bg-gray-100">

    <aside class="w-64 bg-[#1B3C53] text-white flex flex-col">
        <div class="px-6 py-4 text-lg font-semibold border-b border-white/10">
        5A Auto Service
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-white/10">
                Dashboard
            </a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-white/10">
                Absen Karyawan
            </a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-white/10">
                Stok Barang
            </a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-white/10">
                Invoice
            </a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-white/10">
                History Invoice
            </a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-white/10">
                Estimasi
            </a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-white/10">
                Tagihan Outstanding
            </a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-white/10">
                Pelanggan
            </a>
            <a href="{{ route('karyawan.index') }}"
            class="block px-3 py-2 rounded hover:bg-white/10">
                Karyawan
            </a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-white/10">
                Harga Jasa
            </a>
        </nav>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}" class="p-4 border-t border-white/10">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded hover:bg-red-500/20 text-red-300">
                Logout
            </button>
        </form>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">
        <h1 class="text-2xl font-semibold mb-2">
            Dashboard 5A Auto Service
        </h1>

        <p class="text-gray-600 mb-6">
            Halo, {{ auth()->user()->name }}
        </p>

        <!-- contoh konten -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Total Pelanggan</p>
                <p class="text-xl font-semibold">0</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Invoice Hari Ini</p>
                <p class="text-xl font-semibold">0</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <p class="text-sm text-gray-500">Outstanding</p>
                <p class="text-xl font-semibold">Rp 0</p>
            </div>
        </div>
    </main>

</body>
</html>
