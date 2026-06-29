<aside class="sidebar" id="sidebar">
    <div class="logo">
        <div class="sidebar-logo-bg">
            <img src="{{ asset('images/majubersamalogo.png') }}" alt="Maju Bersama" class="sidebar-logo-img">
        </div>
    </div>

    <nav class="nav-section">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>
    </nav>

    @php $role = strtolower(auth()->user()->role ?? ''); @endphp

    @if($role === 'admin')
    <nav class="nav-section">
        <div class="nav-label">Master Data</div>
        <a href="{{ route('barns.index') }}" class="nav-item {{ request()->routeIs('barns.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Kandang
        </a>
        <a href="{{ route('egg-categories.index') }}" class="nav-item {{ request()->routeIs('egg-categories.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2a8 8 0 00-8 8c0 5 8 12 8 12s8-7 8-12a8 8 0 00-8-8z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            Kategori Telur
        </a>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') || request()->routeIs('roles') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Pengaturan
        </a>
    </nav>
    @endif

    <nav class="nav-section">
        <div class="nav-label">Transaksi</div>
        @if(in_array($role, ['admin', 'keuangan']))
        <a href="{{ route('daily-prices.index') }}" class="nav-item {{ request()->routeIs('daily-prices.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
            </svg>
            Harga Berlaku
        </a>
        @endif
        @if(in_array($role, ['admin', 'peternak']))
        <a href="{{ route('productions.index') }}" class="nav-item {{ request()->routeIs('productions.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Input Produksi
        </a>
        @endif
        @if(in_array($role, ['admin', 'keuangan']))
        <a href="{{ route('sales.index') }}" class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            Penjualan Manual
        </a>
        @endif
    </nav>

    <nav class="nav-section">
        <div class="nav-label">Laporan</div>
        <a href="{{ route('laporan.produksi-harian') }}" class="nav-item {{ request()->routeIs('laporan.produksi-harian') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <line x1="3" y1="9" x2="21" y2="9"/>
                <line x1="9" y1="21" x2="9" y2="9"/>
            </svg>
            Produksi Harian
        </a>
        <a href="{{ route('laporan.produksi-mingguan') }}" class="nav-item {{ request()->routeIs('laporan.produksi-mingguan') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <line x1="3" y1="9" x2="21" y2="9"/>
                <line x1="3" y1="15" x2="21" y2="15"/>
            </svg>
            Produksi Mingguan
        </a>
        <a href="{{ route('laporan.produksi-bulanan') }}" class="nav-item {{ request()->routeIs('laporan.produksi-bulanan') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Produksi Bulanan
        </a>
        @if(in_array($role, ['admin', 'keuangan']))
        <a href="{{ route('laporan.penjualan-kontrak') }}" class="nav-item {{ request()->routeIs('laporan.penjualan-kontrak') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Penjualan
        </a>
        <a href="{{ route('laporan.penjualan') }}" class="nav-item {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            Penjualan Manual
        </a>
        @endif
        <a href="{{ route('laporan.telur-pecah') }}" class="nav-item {{ request()->routeIs('laporan.telur-pecah') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2a8 8 0 00-8 8c0 5 8 12 8 12s8-7 8-12a8 8 0 00-8-8z"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Telur Pecah
        </a>
        <a href="{{ route('laporan.stock-gudang') }}" class="nav-item {{ request()->routeIs('laporan.stock-gudang') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2"/>
                <path d="M8 21h8"/>
                <path d="M12 17v4"/>
            </svg>
            Stok Gudang
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="theme-toggle">
            <div class="theme-toggle-label">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3"/>
                    <line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/>
                    <line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                Mode Terang
            </div>
            <div class="theme-switch" id="themeSwitch"></div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
