<!DOCTYPE html>
<html lang="id" data-theme="dark" class="no-transition">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') | 5a Auto Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.11/dist/cdn.min.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
</head>

<body>

    {{-- Overlay mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- Hamburger (mobile only) --}}
    <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()" aria-label="Toggle menu">
        <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
    </button>

    <aside class="sidebar" id="sidebar">

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

            {{-- Transaksi group --}}
            @php
                $transaksiActive = request()->routeIs(['invoice.*','estimasi.*','selfbilling.*']);
            @endphp
            <div class="nav-group">
                <div class="nav-group-toggle {{ $transaksiActive ? 'active open' : '' }}"
                     onclick="toggleNavGroup(this)"
                     role="button"
                     aria-expanded="{{ $transaksiActive ? 'true' : 'false' }}">
                    <div class="nav-group-left">
                        <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1H8.3c.12 2.19 1.76 3.42 3.7 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                        Transaksi
                    </div>
                    <svg class="chevron" viewBox="0 0 24 24"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/></svg>
                </div>

                <div class="nav-sub {{ $transaksiActive ? 'open' : '' }}">
                    <a href="{{ route('estimasi.index') }}"
                       class="nav-sub-item {{ request()->routeIs('estimasi.*') ? 'active' : '' }}">
                        <span class="sub-dot"></span>
                        Estimasi Harga
                    </a>
                    <a href="{{ route('invoice.index') }}"
                       class="nav-sub-item {{ request()->routeIs('invoice.index') || request()->routeIs('invoice.create') ? 'active' : '' }}">
                        <span class="sub-dot"></span>
                        Invoice
                    </a>
                    <a href="{{ route('invoice.outstanding') }}"
                       class="nav-sub-item {{ request()->routeIs('invoice.outstanding') ? 'active' : '' }}">
                        <span class="sub-dot"></span>
                        Outstanding
                    </a>
                    <a href="{{ route('selfbilling.index') }}"
                       class="nav-sub-item {{ request()->routeIs('selfbilling.*') ? 'active' : '' }}">
                        <span class="sub-dot"></span>
                        Self Billing
                    </a>
                </div>
            </div>

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

        {{-- Theme toggle --}}
        <div class="sidebar-theme">
            <div class="theme-label">Tampilan</div>
            <div class="theme-toggle">
                <button class="theme-btn" id="btnDark" onclick="setTheme('dark')" title="Dark mode">
                    <svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 1 0 9 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 0 1-4.4 2.26 5.403 5.403 0 0 1-3.14-9.8c-.44-.06-.9-.1-1.36-.1z"/></svg>
                    Gelap
                </button>
                <button class="theme-btn" id="btnLight" onclick="setTheme('light')" title="Light mode">
                    <svg viewBox="0 0 24 24"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0a.996.996 0 0 0 0-1.41l-1.06-1.06zm1.06-12.37l-1.06 1.06a.996.996 0 0 0 0 1.41c.39.39 1.03.39 1.41 0l1.06-1.06a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0zM7.05 18.36l-1.06 1.06a.996.996 0 0 0 0 1.41c.39.39 1.03.39 1.41 0l1.06-1.06a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0z"/></svg>
                    Terang
                </button>
            </div>
        </div>

        {{-- Footer: profile + logout --}}
        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}"
               class="sidebar-profile {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <div class="profile-avatar">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <span class="profile-name">{{ Auth::user()->name }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout-icon" title="Logout">
                    <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                </button>
            </form>
        </div>

    </aside>

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
    /* ══════════════════════════════════════
       THEME — init sebelum paint pertama
    ══════════════════════════════════════ */
    (function () {
        const saved = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', saved);

        // Sync tombol setelah DOM siap
        document.addEventListener('DOMContentLoaded', function () {
            syncThemeButtons(saved);
            // Hapus no-transition setelah 1 frame agar tidak flash
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    document.documentElement.classList.remove('no-transition');
                });
            });
        });
    })();

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        syncThemeButtons(theme);
    }

    function syncThemeButtons(theme) {
        var btnDark  = document.getElementById('btnDark');
        var btnLight = document.getElementById('btnLight');
        if (!btnDark || !btnLight) return;
        btnDark.classList.toggle('active',  theme === 'dark');
        btnLight.classList.toggle('active', theme === 'light');
    }

    /* ══════════════════════════════════════
       NAV GROUP DROPDOWN
    ══════════════════════════════════════ */
    function toggleNavGroup(toggleEl) {
        var isOpen = toggleEl.classList.contains('open');
        toggleEl.classList.toggle('open', !isOpen);
        toggleEl.setAttribute('aria-expanded', !isOpen);

        var sub = toggleEl.nextElementSibling;
        if (sub && sub.classList.contains('nav-sub')) {
            sub.classList.toggle('open', !isOpen);
        }
    }

    /* ══════════════════════════════════════
       MOBILE SIDEBAR
    ══════════════════════════════════════ */
    function toggleSidebar() {
        var sidebar  = document.getElementById('sidebar');
        var overlay  = document.getElementById('sidebarOverlay');
        var isOpen   = sidebar.classList.contains('open');
        sidebar.classList.toggle('open', !isOpen);
        overlay.classList.toggle('show', !isOpen);
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    // Tutup sidebar mobile saat resize ke desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 769) {
            closeSidebar();
        }
    });
    </script>
</body>
</html>
