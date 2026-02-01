<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Karyawan | 5A Auto Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex bg-gray-100">

    <!-- SIDEBAR (SAMA PERSIS DENGAN DASHBOARD) -->
    <aside class="w-64 bg-[#1B3C53] text-white flex flex-col">
        <div class="px-6 py-4 text-lg font-semibold border-b border-white/10">
            5A Auto Service
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-white/10">
                Dashboard
            </a>

            <a href="{{ route('karyawan.index') }}"
               class="block px-3 py-2 rounded bg-white/20">
                Karyawan
            </a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="p-4 border-t border-white/10">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded hover:bg-red-500/20 text-red-300">
                Logout
            </button>
        </form>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">
        <h1 class="text-2xl font-semibold mb-4">Data Karyawan</h1>

        <a href="{{ route('karyawan.create') }}"
           class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded text-sm">
            + Tambah Karyawan
        </a>

        <table class="w-full bg-white rounded shadow text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Nama</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($karyawans as $k)
                <tr class="border-t">
                    <td class="p-2">{{ $k->nama }}</td>
                    <td>{{ $k->jabatan }}</td>
                    <td>{{ $k->aktif ? 'Aktif' : 'Nonaktif' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>

</body>
</html>
