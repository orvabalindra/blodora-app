<aside class="admin-sidebar">

    {{-- BRAND --}}
    <div class="admin-sidebar-brand">
        <img src="{{ asset('images/Blodora2.png') }}" alt="Blodora" class="admin-sidebar-logo">
    </div>


    {{-- MENU --}}
    <nav class="admin-sidebar-menu">

        <div class="admin-menu-title">
            MENU UTAMA
        </div>

        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
            class="admin-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="admin-menu-icon">⌂</span>
            <span>Dashboard</span>
        </a>

        {{-- DATA PENDONOR --}}
        <a href="{{ route('pendonor.index') }}"
            class="admin-menu-item {{ request()->routeIs('pendonor.*') ? 'active' : '' }}">
            <span class="admin-menu-icon">♙</span>
            <span>Data Pendonor</span>
        </a>

        {{-- STOK DARAH --}}
        <a href="{{ route('stok-darah.index') }}"
            class="admin-menu-item {{ request()->routeIs('stok-darah.*') ? 'active' : '' }}">
            <span class="admin-menu-icon">+</span>
            <span>Stok Darah</span>
        </a>

        {{-- PEMERIKSAAN --}}
        <a href="{{ route('pemeriksaan.index') }}"
            class="admin-menu-item {{ request()->routeIs('pemeriksaan.*') ? 'active' : '' }}">
            <span class="admin-menu-icon">♡</span>
            <span>Pemeriksaan</span>
        </a>

        {{-- RIWAYAT DONOR --}}
        <a href="{{ route('riwayat-donor.index') }}"
            class="admin-menu-item {{ request()->routeIs('riwayat-donor.*') ? 'active' : '' }}">
            <span class="admin-menu-icon">↻</span>
            <span>Riwayat Donor</span>
        </a>

        {{-- PERMINTAAN DARAH --}}
        <a href="{{ route('permintaan-darah.index') }}"
            class="admin-menu-item {{ request()->routeIs('permintaan-darah.*') ? 'active' : '' }}">
            <span class="admin-menu-icon">▣</span>
            <span>Permintaan Darah</span>
        </a>

        {{-- DATA FASKES --}}
        <a href="{{ route('rumah-sakit.index') }}"
            class="admin-menu-item {{ request()->routeIs('rumah-sakit.*') ? 'active' : '' }}">
            <span class="admin-menu-icon">▤</span>
            <span>Data Faskes</span>
        </a>

        {{-- PEMBATAS --}}
        <div class="menu-separator"></div>

        {{-- PENGATURAN --}}
        <a href="{{ route('pengaturan') }}"
            class="admin-menu-item {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
            <span class="admin-menu-icon">⚙</span>
            <span>Pengaturan</span>
        </a>

    </nav>


    {{-- PROFILE --}}
    <div class="admin-sidebar-bottom">

        <div class="admin-profile-mini">

            <div class="admin-avatar">
                {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
            </div>

            <div class="admin-profile-info">

                <strong>
                    {{ Auth::user()->username ?? 'Administrator' }}
                </strong>

                <span>
                    Administrator
                </span>

            </div>

        </div>


        {{-- LOGOUT --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="admin-logout-btn">
                <span>↪</span>
                <span>Logout</span>
            </button>

        </form>

    </div>

</aside>