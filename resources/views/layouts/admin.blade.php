<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Maju Bersama</title>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <meta name="theme-color" content="#b87333">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Maju Bersama">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-pwa.css') }}">
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="dashboard">
        @include('partials.sidebar')
        <main class="main-content">
            @include('partials.header')
            <div class="content-wrapper" style="grid-template-columns: 1fr;">
                <div class="content-left">
                    @yield('content')
                </div>
            </div>
            <footer class="copyright">
                Hak Cipta &copy; {{ date('Y') }} Maju Bersama. All rights reserved.
            </footer>
        </main>
    </div>

    <div class="top-bar">
        <button class="top-bar-menu" id="topBarMenu" aria-label="Buka menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
        <div class="top-bar-title">@yield('title', 'Dashboard')</div>
    </div>

    <nav class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('productions.index') }}" class="bottom-nav-item {{ request()->routeIs('productions.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span>Produksi</span>
        </a>
        <a href="{{ route('daily-prices.index') }}" class="bottom-nav-item bottom-nav-center {{ request()->routeIs('daily-prices.*') ? 'active' : '' }}">
            <div class="bottom-nav-plus">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
            </div>
            <span>Harga</span>
        </a>
        <a href="{{ route('production-details.index') }}" class="bottom-nav-item {{ request()->routeIs('production-details.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="4" y1="6" x2="20" y2="6"/>
                <line x1="4" y1="12" x2="14" y2="12"/>
                <line x1="4" y1="18" x2="18" y2="18"/>
                <circle cx="18" cy="6" r="1.5" fill="currentColor" stroke="none"/>
                <circle cx="16" cy="12" r="1.5" fill="currentColor" stroke="none"/>
                <circle cx="20" cy="18" r="1.5" fill="currentColor" stroke="none"/>
            </svg>
            <span>Sortir</span>
        </a>
        <a href="{{ route('stock.index') }}" class="bottom-nav-item {{ request()->routeIs('stock.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2"/>
                <path d="M8 21h8"/>
                <path d="M12 17v4"/>
            </svg>
            <span>Stok</span>
        </a>
    </nav>

    <script src="{{ asset('js/template/templatemo-crypto-script.js') }}"></script>
    <script src="{{ asset('js/template/templatemo-crypto-mobile.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var menuBtn = document.getElementById('topBarMenu');
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }

            if (menuBtn) {
                menuBtn.addEventListener('click', toggleSidebar);
            }
            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }
            var savedScroll = sessionStorage.getItem('sidebarScroll');
            if (savedScroll !== null) {
                sidebar.scrollTop = parseInt(savedScroll);
            }

            document.querySelectorAll('.nav-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    sessionStorage.setItem('sidebarScroll', sidebar.scrollTop);
                    if (window.innerWidth <= 1024 && sidebar && sidebar.classList.contains('active')) {
                        toggleSidebar();
                    }
                });
            });
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024 && sidebar && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
            window.addEventListener('pagehide', function() {
                sessionStorage.setItem('sidebarScroll', sidebar.scrollTop);
            });
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset("sw.js") }}')
                .then(function() { console.log('SW registered'); })
                .catch(function(err) { console.log('SW failed:', err); });
        }
    </script>
    @stack('scripts')
</body>
</html>
