<aside class="donor-sidebar" id="app-sidebar">
    <div class="donor-sidebar-brand">
        <button class="sidebar-brand-toggle" type="button" aria-label="Tutup sidebar" aria-expanded="true">
            <img src="{{ asset('images/Blodora2.png') }}" alt="Blodora" class="donor-sidebar-logo">
        </button>
        <span class="donor-brand-subtitle">
            Blood Donation System
        </span>
    </div>

    <nav class="donor-sidebar-menu">
        <div class="donor-menu-title">
            <span class="sidebar-menu-label">MENU</span>
        </div>

        <a href="{{ route('dashboard.pendonor') }}"
            class="donor-menu-item {{ request()->routeIs('dashboard.pendonor') ? 'active' : '' }}">
            <span class="donor-menu-icon">⌂</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('pendonor.profile') }}"
            class="donor-menu-item {{ request()->routeIs('pendonor.profile') ? 'active' : '' }}">
            <span class="donor-menu-icon">♙</span>
            <span>Profil Saya</span>
        </a>

        <a href="{{ route('donor.jadwal') }}"
            class="donor-menu-item {{ request()->routeIs('donor.jadwal') ? 'active' : '' }}">
            <span class="donor-menu-icon"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
            <span>Jadwal Donor</span>
        </a>

        <a href="{{ route('donor.pendaftaran.index') }}"
            class="donor-menu-item {{ request()->routeIs('donor.pendaftaran.*') ? 'active' : '' }}">
            <span class="donor-menu-icon">↻</span>
            <span>Pendaftaran Saya</span>
        </a>
        <a href="{{ route('donor.notifikasi.index') }}"
            class="donor-menu-item {{ request()->routeIs('donor.notifikasi.*') ? 'active' : '' }}">
            <span class="donor-menu-icon"><i class="bi bi-bell" aria-hidden="true"></i></span>
            <span>Notifikasi @php($unreadNotificationCount = Auth::user()->unreadNotifications()->count()) @if ($unreadNotificationCount > 0)<small class="donor-notification-count">{{ $unreadNotificationCount }}</small>@endif</span>
        </a>
    </nav>

    <div class="donor-sidebar-bottom">
        <div class="donor-profile-mini">
            <div class="donor-avatar">
                {{ strtoupper(substr(Auth::user()->username ?? 'P', 0, 1)) }}
            </div>
            <div class="donor-profile-info">
                <strong>{{ Auth::user()->username ?? 'Pendonor' }}</strong>
                <span>Pendonor</span>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="donor-logout-btn">
                <span>↪</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
<button class="sidebar-mobile-backdrop" type="button" data-sidebar-close aria-label="Tutup menu"></button>
