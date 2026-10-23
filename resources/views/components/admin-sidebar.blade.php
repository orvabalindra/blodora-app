<aside class="admin-sidebar" id="app-sidebar">

    {{-- BRAND --}}
    <div class="admin-sidebar-brand">
        <button class="sidebar-brand-toggle" type="button" aria-label="Tutup sidebar" aria-expanded="true">
            <img src="{{ asset('images/Blodora2.png') }}" alt="Blodora" class="admin-sidebar-logo">
        </button>
    </div>


    {{-- MENU --}}
    <nav class="admin-sidebar-menu">

        <div class="admin-menu-title">
            <span class="sidebar-menu-label">MENU UTAMA</span>
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

        <a href="{{ route('jadwal-donor.index') }}"
            class="admin-menu-item {{ request()->routeIs('jadwal-donor.*') ? 'active' : '' }}">
            <span class="admin-menu-icon"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
            <span>Jadwal Donor</span>
        </a>

        <a href="{{ route('pendaftaran-donor.admin.index') }}"
            class="admin-menu-item {{ request()->routeIs('pendaftaran-donor.*') ? 'active' : '' }}">
            <span class="admin-menu-icon">&#10003;</span>
            <span>Pendaftaran &amp; Screening</span>
        </a>

        {{-- PERMINTAAN DARAH --}}
        <a href="{{ route('permintaan-darah.index') }}"
            class="admin-menu-item {{ request()->routeIs('permintaan-darah.*') ? 'active' : '' }}">
            <span class="admin-menu-icon">▣</span>
            <span>Permintaan Darah</span>
        </a>

        <a href="{{ route('admin.permintaan-notifikasi.index') }}"
            class="admin-menu-item {{ request()->routeIs('admin.permintaan-notifikasi.*') ? 'active' : '' }}">
            <span class="admin-menu-icon"><i class="bi bi-bell" aria-hidden="true"></i></span>
            <span>Notifikasi @php($unreadPermintaanNotifications = Auth::user()->unreadNotifications()->where('type', \App\Notifications\PermintaanDarahStatusNotification::class)->count()) @if ($unreadPermintaanNotifications > 0)<small class="admin-notification-count">{{ $unreadPermintaanNotifications }}</small>@endif</span>
        </a>

        <a href="{{ route('laporan.bulanan') }}"
            class="admin-menu-item {{ request()->routeIs('laporan.bulanan') ? 'active' : '' }}">
            <span class="admin-menu-icon"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i></span>
            <span>Laporan Bulanan</span>
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
                <span>Keluar</span>
            </button>

        </form>

    </div>

</aside>
<button class="sidebar-mobile-backdrop" type="button" data-sidebar-close aria-label="Tutup menu"></button>
