<aside class="admin-sidebar" id="app-sidebar">
    <div class="admin-sidebar-brand">
        <button class="sidebar-brand-toggle" type="button" aria-label="Tutup menu" aria-expanded="true">
            <img src="{{ asset('images/Blodora2.png') }}" alt="Blodora" class="admin-sidebar-logo">
        </button>
    </div>
    <nav class="admin-sidebar-menu">
        <div class="admin-menu-title"><span class="sidebar-menu-label">PORTAL FASKES</span></div>
        <a href="{{ route('faskes.permintaan.index') }}" class="admin-menu-item {{ request()->routeIs('faskes.permintaan.index') ? 'active' : '' }}">
            <span class="admin-menu-icon"><i class="bi bi-droplet" aria-hidden="true"></i></span>
            <span>Permintaan Darah</span>
        </a>
        <a href="{{ route('faskes.permintaan.create') }}" class="admin-menu-item {{ request()->routeIs('faskes.permintaan.create') ? 'active' : '' }}">
            <span class="admin-menu-icon">+</span>
            <span>Ajukan Permintaan</span>
        </a>
    </nav>
    <div class="admin-sidebar-bottom">
        <div class="admin-profile-mini">
            <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->username ?? 'R', 0, 1)) }}</div>
            <div class="admin-profile-info">
                <strong>{{ Auth::user()->rumahSakit?->nama_rs ?? 'Rumah Sakit' }}</strong>
                <small>{{ Auth::user()->username }}</small>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" data-swal-confirm="Anda akan keluar dari portal rumah sakit." data-swal-title="Keluar dari akun?">
            @csrf
            <button type="submit" class="admin-logout-btn"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar</button>
        </form>
    </div>
</aside>
