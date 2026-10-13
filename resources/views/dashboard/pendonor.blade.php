@extends('layouts.app')

@section('title', 'Dashboard Pendonor')

@section('content')

    <div class="donor-dashboard">

        @include('components.donor-sidebar')


        {{-- =========================================================
        MAIN
        ========================================================== --}}
        <main class="donor-main">

            {{-- TOPBAR --}}
            <div class="donor-topbar">

                <div>

                    <span class="donor-page-label">
                        DASHBOARD PENDONOR
                    </span>

                    <h1>
                        Selamat datang,
                        {{ $pendonor->nama_lengkap ?? Auth::user()->username ?? 'Pendonor' }}
                        👋
                    </h1>

                    <p>
                        Pantau profil, pemeriksaan, dan aktivitas donor kamu di Blodora.
                    </p>

                </div>


                <div class="donor-topbar-right">

                    <div class="donor-date">

                        <span>
                            Hari ini
                        </span>

                        <strong>
                            {{ now()->translatedFormat('d F Y') }}
                        </strong>

                    </div>


                    <div class="donor-top-avatar">
                        {{ strtoupper(substr(Auth::user()->username ?? 'P', 0, 1)) }}
                    </div>

                </div>

            </div>


            {{-- =====================================================
            STAT CARDS
            ====================================================== --}}
            <div class="row g-4 donor-stat-row">

                {{-- GOLONGAN DARAH --}}
                <div class="col-xl-3 col-md-6">

                    <div class="donor-stat-card">

                        <div class="donor-stat-top">

                            <div class="donor-stat-icon blood">
                                +
                            </div>

                            <span class="donor-stat-tag">
                                Profil
                            </span>

                        </div>

                        <div class="donor-stat-content">

                            <span>
                                Golongan Darah
                            </span>

                            <h2>
                                {{ $pendonor->golongan_darah ?? '-' }}{{ $pendonor->rhesus ?? '' }}
                            </h2>

                            <small>
                                Data golongan darah kamu
                            </small>

                        </div>

                    </div>

                </div>


                {{-- TOTAL DONOR --}}
                <div class="col-xl-3 col-md-6">

                    <div class="donor-stat-card">

                        <div class="donor-stat-top">

                            <div class="donor-stat-icon donation">
                                ↻
                            </div>

                            <span class="donor-stat-tag">
                                Aktivitas
                            </span>

                        </div>

                        <div class="donor-stat-content">

                            <span>
                                Total Donor
                            </span>

                            <h2>
                                {{ $totalDonor ?? 0 }}
                            </h2>

                            <small>
                                Kali donor berhasil
                            </small>

                        </div>

                    </div>

                </div>


                {{-- PEMERIKSAAN TERAKHIR --}}
                <div class="col-xl-3 col-md-6">

                    <div class="donor-stat-card">

                        <div class="donor-stat-top">

                            <div class="donor-stat-icon exam">
                                ♡
                            </div>

                            <span class="donor-stat-tag">
                                Pemeriksaan
                            </span>

                        </div>

                        <div class="donor-stat-content">

                            <span>
                                Pemeriksaan Terakhir
                            </span>

                            <h2 class="donor-stat-date">

                                {{
        isset($pemeriksaanTerakhir) && $pemeriksaanTerakhir
        ? $pemeriksaanTerakhir->tanggal_pemeriksaan?->format('d M Y')
        : '-'
                                }}

                            </h2>

                            <small>
                                Tanggal pemeriksaan terbaru
                            </small>

                        </div>

                    </div>

                </div>


                {{-- STATUS --}}
                <div class="col-xl-3 col-md-6">

                    <div class="donor-stat-card">

                        <div class="donor-stat-top">

                            <div class="donor-stat-icon status">
                                ✓
                            </div>

                            <span class="donor-stat-tag">
                                Status
                            </span>

                        </div>

                        <div class="donor-stat-content">

                            <span>
                                Status Pemeriksaan
                            </span>


                            @if (
                                    isset($pemeriksaanTerakhir) &&
                                    $pemeriksaanTerakhir &&
                                    $pemeriksaanTerakhir->hasil === 'layak'
                                )

                                <h2 class="donor-status-text success">
                                    Layak
                                </h2>

                                <small>
                                    Layak untuk donor
                                </small>

                            @elseif (
                                    isset($pemeriksaanTerakhir) &&
                                    $pemeriksaanTerakhir
                                )

                                <h2 class="donor-status-text danger">
                                    Tidak Layak
                                </h2>

                                <small>
                                    Periksa kembali hasil pemeriksaan
                                </small>

                            @else

                                <h2 class="donor-status-text muted">
                                    -
                                </h2>

                                <small>
                                    Belum ada pemeriksaan
                                </small>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
            MAIN CONTENT
            ====================================================== --}}
            <div class="row g-4 donor-content-row">


                {{-- PEMERIKSAAN TERAKHIR --}}
                <div class="col-lg-8">

                    <div class="donor-panel">

                        <div class="donor-panel-header">

                            <div>

                                <span class="donor-section-label">
                                    PEMERIKSAAN TERAKHIR
                                </span>

                                <h3>
                                    Hasil Pemeriksaan
                                </h3>

                                <p>
                                    Informasi pemeriksaan kesehatan terbaru kamu.
                                </p>

                            </div>


                            @if (
                                    isset($pemeriksaanTerakhir) &&
                                    $pemeriksaanTerakhir
                                )

                                <a href="{{ route('donor.pendaftaran.index') }}"
                                    class="donor-btn-secondary">
                                    Lihat Detail
                                </a>

                            @endif

                        </div>


                        @if (
                                            isset($pemeriksaanTerakhir) &&
                                            $pemeriksaanTerakhir
                                        )

                                        <div class="donor-exam-date">

                                            <span>
                                                Tanggal Pemeriksaan
                                            </span>

                                            <strong>
                                                {{ $pemeriksaanTerakhir->tanggal_pemeriksaan?->translatedFormat('d F Y') ?? '-' }}
                                            </strong>

                                        </div>


                                        <div class="row g-3">

                                            {{-- BERAT --}}
                                            <div class="col-md-6">

                                                <div class="donor-detail-card">

                                                    <span>
                                                        Berat Badan
                                                    </span>

                                                    <strong>
                                                        {{ $pemeriksaanTerakhir->berat_badan ?? '-' }}
                                                        <small>kg</small>
                                                    </strong>

                                                </div>

                                            </div>


                                            {{-- TEKANAN --}}
                                            <div class="col-md-6">

                                                <div class="donor-detail-card">

                                                    <span>
                                                        Tekanan Darah
                                                    </span>

                                                    <strong>
                                                        {{ $pemeriksaanTerakhir->tekanan_darah ?? '-' }}
                                                    </strong>

                                                </div>

                                            </div>


                                            {{-- HEMOGLOBIN --}}
                                            <div class="col-md-6">

                                                <div class="donor-detail-card">

                                                    <span>
                                                        Hemoglobin
                                                    </span>

                                                    <strong>
                                                        {{ isset($pemeriksaanTerakhir->hemoglobin)
                            ? number_format((float) $pemeriksaanTerakhir->hemoglobin, 1)
                            : '-' }}
                                                        <small>g/dL</small>
                                                    </strong>

                                                </div>

                                            </div>


                                            {{-- HASIL --}}
                                            <div class="col-md-6">

                                                <div class="donor-detail-card">

                                                    <span>
                                                        Hasil Pemeriksaan
                                                    </span>

                                                    @if ($pemeriksaanTerakhir->hasil === 'layak')

                                                        <span class="donor-status-pill success">
                                                            Layak Donor
                                                        </span>

                                                    @else

                                                        <span class="donor-status-pill danger">
                                                            Tidak Layak Donor
                                                        </span>

                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                        @else

                            <div class="donor-empty-state">

                                <div class="donor-empty-icon">
                                    ♡
                                </div>

                                <strong>
                                    Belum ada pemeriksaan
                                </strong>

                                <span>
                                    Data pemeriksaan kamu belum tersedia.
                                </span>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- PROFIL SINGKAT --}}
                <div class="col-lg-4">

                    <div class="donor-panel">

                        <div class="donor-panel-header">

                            <div>

                                <span class="donor-section-label">
                                    PROFIL SAYA
                                </span>

                                <h3>
                                    Data Pendonor
                                </h3>

                            </div>

                        </div>


                        <div class="donor-profile-card">

                            <div class="donor-profile-large-avatar">
                                {{ strtoupper(substr($pendonor->nama_lengkap ?? Auth::user()->username ?? 'P', 0, 1)) }}
                            </div>

                            <strong class="donor-profile-name">
                                {{ $pendonor->nama_lengkap ?? Auth::user()->username ?? 'Pendonor' }}
                            </strong>

                            <span class="donor-profile-role">
                                Pendonor
                            </span>

                        </div>


                        <div class="donor-info-list">

                            <div class="donor-info-row">

                                <span>
                                    No. HP
                                </span>

                                <strong>
                                    {{ $pendonor->no_hp ?? '-' }}
                                </strong>

                            </div>


                            <div class="donor-info-row">

                                <span>
                                    Golongan
                                </span>

                                <strong>
                                    {{ $pendonor->golongan_darah ?? '-' }}{{ $pendonor->rhesus ?? '' }}
                                </strong>

                            </div>


                            <div class="donor-info-row">

                                <span>
                                    Berat Badan
                                </span>

                                <strong>
                                    {{ $pendonor->berat_badan ?? '-' }}
                                    @if (!empty($pendonor->berat_badan))
                                        kg
                                    @endif
                                </strong>

                            </div>

                        </div>


                        <a href="{{ route('pendonor.profile') }}" class="donor-btn-primary donor-full-btn">
                            Lihat Profil Saya
                        </a>

                    </div>

                </div>

            </div>


            <div class="donor-panel mb-4">
                <div class="donor-panel-header">
                    <div>
                        <span class="donor-section-label">PENDAFTARAN</span>
                        <h3>Pendaftaran Donor Terakhir</h3>
                        <p>Status jadwal dan hasil screening terbaru yang terhubung dengan pendaftaran.</p>
                    </div>
                    <a href="{{ route('donor.pendaftaran.index') }}" class="donor-btn-secondary">
                        Lihat Semua
                    </a>
                </div>

                @if ($pendaftaranTerakhir)
                    @php
                        $jadwalTerakhir = $pendaftaranTerakhir->jadwalDonor;
                        $screeningTerakhir = $pendaftaranTerakhir->pemeriksaans->first();
                    @endphp
                    <div class="donor-history-row">
                        <div class="donor-history-icon">◷</div>
                        <div class="donor-history-content">
                            <strong>{{ $jadwalTerakhir?->nama_kegiatan ?? 'Jadwal donor' }}</strong>
                            <span>
                                {{ $jadwalTerakhir?->tanggal?->translatedFormat('d F Y') ?? '-' }}
                                @if ($jadwalTerakhir?->lokasi)
                                    · {{ $jadwalTerakhir->lokasi }}
                                @endif
                            </span>
                            @if ($screeningTerakhir)
                                <span>Hasil screening: {{ ucfirst($screeningTerakhir->hasil) }}</span>
                            @endif
                        </div>
                        <div class="donor-history-status">
                            @if ($pendaftaranTerakhir->status === 'selesai')
                                <span class="donor-status-pill success">Selesai donor</span>
                            @elseif (in_array($pendaftaranTerakhir->status, ['gagal_screening', 'tidak_hadir', 'dibatalkan'], true))
                                <span class="donor-status-pill danger">
                                    {{ ucwords(str_replace('_', ' ', $pendaftaranTerakhir->status)) }}
                                </span>
                            @elseif ($pendaftaranTerakhir->status === 'proses_donor')
                                <span class="donor-status-pill success">Lolos screening</span>
                            @else
                                <span class="donor-status-pill muted">
                                    {{ ucwords(str_replace('_', ' ', $pendaftaranTerakhir->status)) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="donor-empty-state compact">
                        <strong>Belum ada pendaftaran donor</strong>
                        <span>Pilih jadwal donor untuk memulai pendaftaran dan screening.</span>
                        <a href="{{ route('donor.jadwal') }}" class="donor-btn-primary">Lihat Jadwal Donor</a>
                    </div>
                @endif
            </div>

            {{-- =====================================================
            RIWAYAT + STATUS PROFIL
            ====================================================== --}}
            <div class="row g-4 donor-content-row">


                {{-- RIWAYAT TERBARU --}}
                <div class="col-lg-8">

                    <div class="donor-panel">

                        <div class="donor-panel-header">

                            <div>

                                <span class="donor-section-label">
                                    AKTIVITAS
                                </span>

                                <h3>
                                    Riwayat Donor Terbaru
                                </h3>

                                <p>
                                    Aktivitas donor terakhir kamu.
                                </p>

                            </div>


                            <a href="{{ route('donor.pendaftaran.index') }}" class="donor-btn-secondary">
                                Lihat Semua
                            </a>

                        </div>


                        @if (
                                isset($riwayatDonors) &&
                                $riwayatDonors->count()
                            )

                            <div class="donor-history-list">

                                @foreach ($riwayatDonors->take(3) as $riwayat)

                                    <div class="donor-history-row">

                                        <div class="donor-history-icon">
                                            ↻
                                        </div>


                                        <div class="donor-history-content">

                                            <strong>
                                                Donor Darah
                                            </strong>

                                            <span>
                                                {{ $riwayat->tanggal_donor?->translatedFormat('d F Y') ?? '-' }}
                                                ·
                                                {{ $riwayat->jumlah_donor ?? 0 }}
                                                kantong
                                            </span>

                                        </div>


                                        <div class="donor-history-status">

                                            @if ($riwayat->status === 'sukses')

                                                <span class="donor-status-pill success">
                                                    Sukses
                                                </span>

                                            @else

                                                <span class="donor-status-pill danger">
                                                    Gagal
                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="donor-empty-state compact">

                                <div class="donor-empty-icon">
                                    ↻
                                </div>

                                <strong>
                                    Belum ada riwayat donor
                                </strong>

                                <span>
                                    Aktivitas donor kamu akan muncul di sini.
                                </span>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- STATUS PROFIL --}}
                <div class="col-lg-4">

                    <div class="donor-panel">

                        <div class="donor-panel-header">

                            <div>

                                <span class="donor-section-label">
                                    STATUS PROFIL
                                </span>

                                <h3>
                                    Kelengkapan Data
                                </h3>

                            </div>

                        </div>


                        @php
                            $profileComplete = !empty($pendonor);
                            $profileName = !empty($pendonor?->nama_lengkap);
                            $profilePhone = !empty($pendonor?->no_hp);
                            $profileBlood = !empty($pendonor?->golongan_darah);
                            $profileWeight = !empty($pendonor?->berat_badan);
                        @endphp


                        <div class="donor-check-list">

                            <div class="donor-check-row">

                                <span class="donor-check-icon {{ $profileComplete ? 'success' : 'danger' }}">
                                    {{ $profileComplete ? '✓' : '!' }}
                                </span>

                                <div>
                                    <strong>
                                        Profil tersedia
                                    </strong>

                                    <span>
                                        Data pendonor
                                    </span>
                                </div>

                            </div>


                            <div class="donor-check-row">

                                <span class="donor-check-icon {{ $profileName ? 'success' : 'danger' }}">
                                    {{ $profileName ? '✓' : '!' }}
                                </span>

                                <div>
                                    <strong>
                                        Nama lengkap
                                    </strong>

                                    <span>
                                        Identitas pendonor
                                    </span>
                                </div>

                            </div>


                            <div class="donor-check-row">

                                <span class="donor-check-icon {{ $profilePhone ? 'success' : 'danger' }}">
                                    {{ $profilePhone ? '✓' : '!' }}
                                </span>

                                <div>
                                    <strong>
                                        Nomor HP
                                    </strong>

                                    <span>
                                        Data kontak
                                    </span>
                                </div>

                            </div>


                            <div class="donor-check-row">

                                <span class="donor-check-icon {{ $profileBlood ? 'success' : 'danger' }}">
                                    {{ $profileBlood ? '✓' : '!' }}
                                </span>

                                <div>
                                    <strong>
                                        Golongan darah
                                    </strong>

                                    <span>
                                        Data darah
                                    </span>
                                </div>

                            </div>


                            <div class="donor-check-row">

                                <span class="donor-check-icon {{ $profileWeight ? 'success' : 'danger' }}">
                                    {{ $profileWeight ? '✓' : '!' }}
                                </span>

                                <div>
                                    <strong>
                                        Berat badan
                                    </strong>

                                    <span>
                                        Data pemeriksaan
                                    </span>
                                </div>

                            </div>

                        </div>


                        @unless ($profileComplete && $profileName && $profilePhone && $profileBlood && $profileWeight)

                            <a href="{{ route('pendonor.profile') }}" class="donor-btn-primary donor-full-btn">
                                Lengkapi Profil
                            </a>

                        @endunless

                    </div>

                </div>

            </div>


            {{-- =====================================================
            QUICK ACTIONS
            ====================================================== --}}
            <div class="donor-panel donor-quick-panel">

                <div class="donor-panel-header">

                    <div>

                        <span class="donor-section-label">
                            AKSI CEPAT
                        </span>

                        <h3>
                            Menu Untuk Kamu
                        </h3>

                        <p>
                            Akses cepat ke fitur yang paling sering digunakan.
                        </p>

                    </div>

                </div>


                <div class="row g-3">

                    <div class="col-md-4">

                        <a href="{{ route('pendonor.profile') }}" class="donor-quick-card">

                            <div class="donor-quick-icon">
                                ♙
                            </div>

                            <div class="donor-quick-content">

                                <h4>
                                    Profil Saya
                                </h4>

                                <p>
                                    Kelola informasi pribadi pendonor.
                                </p>

                                <span>
                                    Buka Profil →
                                </span>

                            </div>

                        </a>

                    </div>


                    <div class="col-md-4">

                        <a href="{{ route('donor.jadwal') }}" class="donor-quick-card">

                            <div class="donor-quick-icon">
                                ♡
                            </div>

                            <div class="donor-quick-content">

                                <h4>
                                    Pemeriksaan
                                </h4>

                                <p>
                                    Lihat hasil pemeriksaan kesehatan.
                                </p>

                                <span>
                                    Lihat Pemeriksaan →
                                </span>

                            </div>

                        </a>

                    </div>


                    <div class="col-md-4">

                        <a href="{{ route('donor.pendaftaran.index') }}" class="donor-quick-card">

                            <div class="donor-quick-icon">
                                ↻
                            </div>

                            <div class="donor-quick-content">

                                <h4>
                                    Riwayat Donor
                                </h4>

                                <p>
                                    Lihat seluruh aktivitas donor.
                                </p>

                                <span>
                                    Lihat Riwayat →
                                </span>

                            </div>

                        </a>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="donor-footer">

                <span>
                    Blodora Donor Panel
                </span>

                <span>
                    Sistem Donor Darah
                </span>

            </div>

        </main>

    </div>

@endsection
