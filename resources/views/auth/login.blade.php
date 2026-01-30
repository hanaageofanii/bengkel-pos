<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Bengkel POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body class="min-h-screen flex items-center justify-center bg-[#D2C1B6]">

<div class="w-full max-w-xs bg-white rounded-xl shadow-md p-5">
    <div class="text-center mb-4">
        <h1 class="text-lg font-semibold text-[#1B3C53]">Bengkel POS</h1>
        <p class="text-xs text-[#456882]">Sistem Bengkel</p>
    </div>

    @if ($errors->any())
        <div class="mb-3 rounded bg-red-100 px-3 py-2 text-xs text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-3">
        @csrf

        <div>
            <label class="block text-xs mb-1 text-[#456882]">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   class="w-full rounded-md border border-[#456882] px-2.5 py-1.5 text-sm
                          focus:border-[#1B3C53] focus:ring-1 focus:ring-[#1B3C53] focus:outline-none">
        </div>

        <div>
            <label class="block text-xs mb-1 text-[#456882]">Password</label>
            <input type="password"
                   name="password"
                   required
                   class="w-full rounded-md border border-[#456882] px-2.5 py-1.5 text-sm
                          focus:border-[#1B3C53] focus:ring-1 focus:ring-[#1B3C53] focus:outline-none">
        </div>

        <button type="submit"
                class="w-full rounded-md bg-[#1B3C53] py-1.5 text-sm font-semibold text-white
                       hover:bg-[#234C6A] transition">
            Masuk
        </button>
    </form>

    {{-- <p class="mt-4 text-center text-[10px] text-[#456882]">
        © {{ date('Y') }} Bengkel POS
    </p> --}}
</div>

<script src="{{ asset('assets/js/auth.js') }}"></script>
</body>
</html>
