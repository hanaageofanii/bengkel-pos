<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') | 5a Auto Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}">
</head>

<body class="bg-gray-100">

    <aside class="fixed top-0 left-0 h-screen w-64
                  bg-[#1B3C53] text-white flex flex-col z-50">

<div class="px-6 py-3 border-b border-white/10
            flex flex-col items-center">
    <img src="{{ asset('assets/images/logo.png') }}"
         alt="5A Auto Service"
         class="h-44">

    <div class="text-lg font-semibold tracking-wide -mt-3 leading-none text-red-600">
        5A Auto Service
    </div>
</div>

        <nav class="flex-1 px-4 py-4 space-y-1 text-sm">

            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded transition
               {{ request()->routeIs('dashboard')
                    ? 'bg-white/20 font-semibold border-l-4 border-white'
                    : 'hover:bg-white/10' }}">
                Dashboard
            </a>

            <a href="{{ route('invoice.index') }}"
               class="block px-3 py-2 rounded transition
               {{ request()->routeIs('invoice.*')
                    ? 'bg-white/20 font-semibold border-l-4 border-white'
                    : 'hover:bg-white/10' }}">
                Invoice
            </a>

            <a href="{{ route('karyawan.index') }}"
               class="block px-3 py-2 rounded transition
               {{ request()->routeIs('karyawan.*')
                    ? 'bg-white/20 font-semibold border-l-4 border-white'
                    : 'hover:bg-white/10' }}">
                Karyawan
            </a>

            <a href="{{ route('absensi.index') }}"
               class="block px-3 py-2 rounded transition
               {{ request()->routeIs('absensi.*')
                    ? 'bg-white/20 font-semibold border-l-4 border-white'
                    : 'hover:bg-white/10' }}">
                Absensi
            </a>

            <a href="{{ route('pelanggan.index') }}"
               class="block px-3 py-2 rounded transition
               {{ request()->routeIs('pelanggan.*')
                    ? 'bg-white/20 font-semibold border-l-4 border-white'
                    : 'hover:bg-white/10' }}">
                Pelanggan
            </a>

            <a href="{{ route('barang.index') }}"
               class="block px-3 py-2 rounded transition
               {{ request()->routeIs('barang.*')
                    ? 'bg-white/20 font-semibold border-l-4 border-white'
                    : 'hover:bg-white/10' }}">
                Stok Barang
            </a>

            <a href="{{ route('jasa.index') }}"
               class="block px-3 py-2 rounded transition
               {{ request()->routeIs('jasa.*')
                    ? 'bg-white/20 font-semibold border-l-4 border-white'
                    : 'hover:bg-white/10' }}">
                Jasa Pekerjaan
            </a>



        </nav>

        <form method="POST"
              action="{{ route('logout') }}"
              class="p-4 border-t border-white/10">
            @csrf
            <button
                class="w-full text-left px-3 py-2 rounded
                       text-red-300 hover:bg-red-500/20 transition">
                Logout
            </button>
        </form>
    </aside>

    <main class="ml-64 min-h-screen p-6 overflow-y-auto">
        @yield('content')
    </main>

</body>
</html>
