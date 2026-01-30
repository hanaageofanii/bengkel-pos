<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | 5a AUTO SERVICE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-[#D2C1B6]">

<div class="w-full max-w-xs bg-white rounded-lg shadow p-6">
    <div class="text-center mb-5">
        <h1 class="text-lg font-semibold text-[#1B3C53]">5a AUTO SERVICE</h1>
        <p class="text-xs text-[#456882]">Admin Login</p>
    </div>

    @if ($errors->any())
        <div class="mb-3 rounded bg-red-50 px-3 py-2 text-xs text-red-600">
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
                   class="w-full rounded-md border border-gray-300 px-2.5 py-1.5 text-sm
                          focus:outline-none focus:ring-1 focus:ring-[#1B3C53]">
        </div>

        <div>
            <label class="block text-xs mb-1 text-[#456882]">Password</label>
            <input type="password"
                   name="password"
                   required
                   class="w-full rounded-md border border-gray-300 px-2.5 py-1.5 text-sm
                          focus:outline-none focus:ring-1 focus:ring-[#1B3C53]">
        </div>

        <button type="submit"
                class="w-full rounded-md bg-[#1B3C53] py-1.5 text-sm font-medium text-white
                       hover:bg-[#234C6A] transition">
            Masuk
        </button>
    </form>
</div>

</body>
</html>
