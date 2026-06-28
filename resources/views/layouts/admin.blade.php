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
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-style.css') }}?v={{ filemtime(public_path('css/template/templatemo-crypto-style.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-dashboard.css') }}?v={{ filemtime(public_path('css/template/templatemo-crypto-dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-pages.css') }}?v={{ filemtime(public_path('css/template/templatemo-crypto-pages.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-pwa.css') }}?v={{ filemtime(public_path('css/template/templatemo-crypto-pwa.css')) }}">
    <style>.market-table td::before{content:none!important;display:none!important}.market-table{width:100%;border-collapse:collapse}@media(max-width:768px){.market-table>thead{display:table-header-group!important}.market-table>tbody{display:table-row-group!important}.market-table>tbody>tr{display:table-row!important;background:transparent!important;border:none!important;border-radius:0!important;padding:0!important;margin:0!important;overflow:visible!important;cursor:default!important;transform:none!important}.market-table>tbody>tr:active{transform:none!important}.market-table>tbody>tr>td{display:table-cell!important;padding:16px!important;border-bottom:1px solid var(--border)!important;text-align:center!important}.market-table>tbody>tr>td.text-right{text-align:right!important}.market-table>tbody>tr>td::before{content:none!important;display:none!important}}</style>
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
        <a href="{{ route('daily-prices.index') }}" class="bottom-nav-item {{ request()->routeIs('daily-prices.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
            </svg>
            <span>Harga</span>
        </a>
        <a href="{{ route('sales.create') }}" class="bottom-nav-item {{ request()->routeIs('sales.create') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <span>Jual</span>
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

    <script src="{{ asset('js/template/templatemo-crypto-script.js') }}?v={{ filemtime(public_path('js/template/templatemo-crypto-script.js')) }}"></script>
    <script src="{{ asset('js/template/templatemo-crypto-mobile.js') }}?v={{ filemtime(public_path('js/template/templatemo-crypto-mobile.js')) }}"></script>
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
