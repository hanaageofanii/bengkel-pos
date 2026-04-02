<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') | 5a Auto Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════════
           THEME VARIABLES
        ══════════════════════════════════════ */
        :root {
            --sidebar-w: 240px;
        }

        [data-theme="dark"] {
            --bg:         #0f1117;
            --surface:    #181c27;
            --surface2:   #1e2333;
            --border:     #262c3d;
            --accent:     #4f8ef7;
            --accent2:    #1e90ff;
            --text:       #e4e8f0;
            --text-soft:  #b0b8d0;
            --muted:      #6b7694;
            --red:        #f26c6c;
            --green:      #3ef08a;
            --sidebar-bg: #13172100;
            --sidebar-surface: #181c27;
            --sidebar-border:  #262c3d;
            --sidebar-text:    #c8d0e8;
            --sidebar-muted:   #6b7694;
            --sidebar-active-bg: rgba(79,142,247,.15);
            --sidebar-active-border: #4f8ef7;
            --sidebar-hover-bg:  rgba(255,255,255,.05);
            --logo-filter: none;
        }

        [data-theme="light"] {
            --bg:         #f0f4f8;
            --surface:    #ffffff;
            --surface2:   #f5f7fa;
            --border:     #dde3ed;
            --accent:     #2563eb;
            --accent2:    #1d4ed8;
            --text:       #1a202c;
            --text-soft:  #4a5568;
            --muted:      #718096;
            --red:        #e53e3e;
            --green:      #38a169;
            --sidebar-bg: #1b2a3b;
            --sidebar-surface: #1b2a3b;
            --sidebar-border:  rgba(255,255,255,.1);
            --sidebar-text:    #cbd5e1;
            --sidebar-muted:   #94a3b8;
            --sidebar-active-bg: rgba(255,255,255,.15);
            --sidebar-active-border: #ffffff;
            --sidebar-hover-bg: rgba(255,255,255,.07);
            --logo-filter: none;
        }

        /* ══════════════════════════════════════
           BASE
        ══════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: background .25s, color .25s;
        }

        /* ══════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-w);
            background: var(--sidebar-surface);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: background .25s, border-color .25s;
            box-shadow: 4px 0 24px rgba(0,0,0,.3);
        }

        /* top accent line */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1e90ff, #4f8ef7, #8ab6ff);
        }

        /* ── logo area ── */
        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo img {
            height: 40px;
            width: 40px;
            object-fit: contain;
            border-radius: 8px;
            filter: var(--logo-filter);
        }

        .sidebar-logo-text .brand {
            font-family: 'Rajdhani', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            letter-spacing: .4px;
            line-height: 1;
        }

        .sidebar-logo-text .sub {
            font-size: 10px;
            color: var(--sidebar-muted);
            margin-top: 3px;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        /* ── nav section label ── */
        .nav-label {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--sidebar-muted);
            padding: 16px 16px 6px;
        }

        /* ── nav links ── */
        .sidebar-nav {
            flex: 1;
            padding: 8px 12px;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 7px;
            font-size: 13px;
            color: var(--sidebar-text);
            text-decoration: none;
            margin-bottom: 2px;
            border-left: 3px solid transparent;
            transition: background .15s, color .15s, border-color .15s;
            position: relative;
        }

        .nav-item:hover {
            background: var(--sidebar-hover-bg);
            color: #fff;
        }

        .nav-item.active {
            background: var(--sidebar-active-bg);
            border-left-color: var(--sidebar-active-border);
            color: #fff;
            font-weight: 600;
        }

        .nav-item svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
            flex-shrink: 0;
            opacity: .75;
        }

        .nav-item.active svg { opacity: 1; }

        /* ── theme toggle in sidebar ── */
        .sidebar-theme {
            padding: 10px 12px 4px;
        }
        .theme-label {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--sidebar-muted);
            margin-bottom: 7px;
            padding-left: 2px;
        }
        .theme-toggle {
            display: flex;
            background: rgba(255,255,255,.06);
            border: 1px solid var(--sidebar-border);
            border-radius: 8px;
            padding: 3px;
            gap: 3px;
        }
        .theme-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 6px 4px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            background: transparent;
            color: var(--sidebar-muted);
            font-size: 11px;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            transition: background .18s, color .18s;
        }
        .theme-btn svg { width: 13px; height: 13px; fill: currentColor; flex-shrink: 0; }
        .theme-btn.active {
            background: rgba(79,142,247,.2);
            color: #4f8ef7;
        }

        /* ── logout ── */
        .sidebar-footer {
            padding: 8px 12px 12px;
            border-top: 1px solid var(--sidebar-border);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 12px;
            border-radius: 7px;
            font-size: 13px;
            color: #f26c6c;
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: left;
            font-family: 'Inter', sans-serif;
            transition: background .15s;
        }

        .btn-logout svg { width: 15px; height: 15px; fill: #f26c6c; flex-shrink: 0; }
        .btn-logout:hover { background: rgba(242,108,108,.1); }

        /* ══════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════ */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        .content-inner {
            padding: 28px 28px;
        }

        /* ══════════════════════════════════════
           GRID BACKGROUND (subtle)
        ══════════════════════════════════════ */
        /* [data-theme="dark"] body {
            background-image:
                repeating-linear-gradient(0deg,   transparent, transparent 39px, rgba(38,44,61,.5) 39px, rgba(38,44,61,.5) 40px),
                repeating-linear-gradient(90deg,  transparent, transparent 39px, rgba(38,44,61,.5) 39px, rgba(38,44,61,.5) 40px);
        } */
    </style>
</head>

<body>

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    <aside class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="5A Auto Service">
            <div class="sidebar-logo-text">
                <div class="brand">5A AUTO SERVICE</div>
                <div class="sub">Admin Portal</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="sidebar-nav">

            <div class="nav-label">Menu Utama</div>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                Dashboard
            </a>

            <a href="{{ route('invoice.index') }}"
               class="nav-item {{ request()->routeIs('invoice.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                Invoice
            </a>

            <a href="{{ route('invoice.outstanding') }}"
               class="nav-item {{ request()->routeIs('invoice.outstanding') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1H8.3c.12 2.19 1.76 3.42 3.7 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                Tagihan Outstanding
            </a>

            <div class="nav-label">SDM</div>

            <a href="{{ route('karyawan.index') }}"
               class="nav-item {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                Karyawan
            </a>

            <a href="{{ route('absensi.index') }}"
               class="nav-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                Absensi
            </a>

            <div class="nav-label">Data</div>

            <a href="{{ route('pelanggan.index') }}"
               class="nav-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                Pelanggan
            </a>

            <a href="{{ route('barang.index') }}"
               class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8 14H6v-2h2v2zm0-3H6V9h2v2zm0-3H6V6h2v2zm9 6H9v-2h8v2zm0-3H9V9h8v2zm0-3H9V6h8v2z"/></svg>
                Stok Barang
            </a>

            <a href="{{ route('jasa.index') }}"
               class="nav-item {{ request()->routeIs('jasa.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/></svg>
                Jasa Pekerjaan
            </a>

        </nav>

        {{-- Theme Toggle --}}
        <div class="sidebar-theme">
            <div class="theme-label">Tampilan</div>
            <div class="theme-toggle">
                <button class="theme-btn active" id="btnDark" onclick="setTheme('dark')" title="Dark mode">
                    <svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 1 0 9 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 0 1-4.4 2.26 5.403 5.403 0 0 1-3.14-9.8c-.44-.06-.9-.1-1.36-.1z"/></svg>
                    Gelap
                </button>
                <button class="theme-btn" id="btnLight" onclick="setTheme('light')" title="Light mode">
                    <svg viewBox="0 0 24 24"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0a.996.996 0 0 0 0-1.41l-1.06-1.06zm1.06-12.37l-1.06 1.06a.996.996 0 0 0 0 1.41c.39.39 1.03.39 1.41 0l1.06-1.06a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0zM7.05 18.36l-1.06 1.06a.996.996 0 0 0 0 1.41c.39.39 1.03.39 1.41 0l1.06-1.06a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0z"/></svg>
                    Terang
                </button>
            </div>
        </div>

        {{-- Logout --}}
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                    Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- ══════════════ MAIN ══════════════ --}}
    <main class="main-content">
        <div class="content-inner">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('error'))
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Stok Tidak Cukup',
        text: "{{ session('error') }}",
        confirmButtonColor: '#ef4444'
    });
    </script>
    @endif

    <script>
    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        document.getElementById('btnDark').classList.toggle('active', theme === 'dark');
        document.getElementById('btnLight').classList.toggle('active', theme === 'light');
    }

    // Load saved theme on page load
    (function() {
        const saved = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', saved);
        if (saved === 'light') {
            document.getElementById('btnDark').classList.remove('active');
            document.getElementById('btnLight').classList.add('active');
        }
    })();
    </script>
</body>
</html>
