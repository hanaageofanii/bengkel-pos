<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') | 5A Auto Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen flex bg-gray-100">

    <aside class="w-64 bg-[#1B3C53] text-white flex flex-col">

        <div class="px-6 py-4 text-lg font-semibold border-b border-white/10 text-center">
            5A Auto Service
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 text-sm">

            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded transition
               {{ request()->routeIs('dashboard')
                    ? 'bg-white/20 font-semibold border-l-4 border-white'
                    : 'hover:bg-white/10' }}">
                Dashboard
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

    <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>

</body>
</html>
