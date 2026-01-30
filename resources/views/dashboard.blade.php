<!DOCTYPE html>
<html>
<body>
    <h1>Dashboard 5a AUTO SERVICE
    <p>Halo, {{ auth()->user()->name }}</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
