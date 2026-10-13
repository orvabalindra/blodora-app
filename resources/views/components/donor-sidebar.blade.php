<aside class="donor-sidebar">
    <div class="donor-sidebar-brand">
        <img src="{{ asset('images/Blodora2.png') }}" alt="Blodora" class="donor-sidebar-logo">
        <span class="donor-brand-subtitle">
            Blood Donation System
        </span>
    </div>

    <nav class="donor-sidebar-menu">
        <div class="donor-menu-title">MENU</div>

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

        <div class="donor-menu-separator"></div>
        <div class="donor-menu-title">LAINNYA</div>

        <a href="{{ route('pendonor.profile') }}"
            class="donor-menu-item {{ request()->routeIs('pendonor.profile') ? 'active' : '' }}">
            <span class="donor-menu-icon">⚙</span>
            <span>Akun Saya</span>
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
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
