@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<div class="donor-dashboard">

    {{-- SIDEBAR --}}
    <aside class="donor-sidebar">

        <div class="donor-sidebar-brand">

            <img
                src="{{ asset('images/Blodora2.png') }}"
                alt="Blodora"
                class="donor-sidebar-logo"
            >

            <span class="donor-brand-subtitle">
                Blood Donation System
            </span>

        </div>


        <nav class="donor-sidebar-menu">

            <div class="donor-menu-title">
                MENU
            </div>


            <a
                href="{{ route('dashboard.pendonor') }}"
                class="donor-menu-item"
            >
                <span class="donor-menu-icon">⌂</span>
                <span>Dashboard</span>
            </a>


            <a
                href="{{ route('pendonor.profile') }}"
                class="donor-menu-item active"
            >
                <span class="donor-menu-icon">♙</span>
                <span>Profil Saya</span>
            </a>


            <a
                href="{{ route('pemeriksaan.index') }}"
                class="donor-menu-item"
            >
                <span class="donor-menu-icon">♡</span>
                <span>Pemeriksaan Saya</span>
            </a>


            <a
                href="{{ route('riwayat-donor.index') }}"
                class="donor-menu-item"
            >
                <span class="donor-menu-icon">↻</span>
                <span>Riwayat Donor</span>
            </a>


            <div class="donor-menu-separator"></div>


            <div class="donor-menu-title">
                LAINNYA
            </div>


            <a
                href="{{ route('pengaturan') }}"
                class="donor-menu-item"
            >
                <span class="donor-menu-icon">⚙</span>
                <span>Pengaturan</span>
            </a>

        </nav>


        <div class="donor-sidebar-bottom">

            <div class="donor-profile-mini">

                <div class="donor-avatar">
                    {{ strtoupper(substr($pendonor->nama_lengkap, 0, 1)) }}
                </div>

                <div class="donor-profile-info">

                    <strong>
                        {{ Auth::user()->username }}
                    </strong>

                    <span>
                        Pendonor
                    </span>

                </div>

            </div>


            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="donor-logout-btn"
                >
                    <span>↪</span>
                    <span>Logout</span>
                </button>

            </form>

        </div>

    </aside>


    {{-- MAIN --}}
    <main class="donor-main">

        {{-- TOPBAR --}}
        <div class="donor-topbar">

            <div>

                <span class="donor-page-label">
                    PROFIL SAYA
                </span>

                <h1>
                    Profil Pendonor
                </h1>

                <p>
                    Informasi pribadi yang terdaftar di sistem Blodora.
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
                    {{ strtoupper(substr($pendonor->nama_lengkap, 0, 1)) }}
                </div>

            </div>

        </div>


        {{-- PROFIL UTAMA --}}
        <div class="donor-panel">

            <div class="donor-panel-header">

                <div>

                    <span class="donor-section-label">
                        DATA PENDONOR
                    </span>

                    <h3>
                        Informasi Pribadi
                    </h3>

                    <p>
                        Data pendonor yang terhubung dengan akun Anda.
                    </p>

                </div>

            </div>


            <div class="donor-profile-detail-head">

                <div class="donor-profile-large-avatar">
                    {{ strtoupper(substr($pendonor->nama_lengkap, 0, 1)) }}
                </div>

                <div>

                    <h2 class="donor-profile-detail-name">
                        {{ $pendonor->nama_lengkap }}
                    </h2>

                    <span class="donor-profile-detail-role">
                        Pendonor Blodora
                    </span>

                </div>

            </div>


            <div class="admin-detail-grid">

                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        NIK
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->nik }}
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Nama Lengkap
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->nama_lengkap }}
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Nomor HP
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->no_hp }}
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Jenis Kelamin
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->jenis_kelamin ?? '-' }}
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Tanggal Lahir
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->tanggal_lahir ?? '-' }}
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Berat Badan
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->berat_badan }} kg
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Golongan Darah
                    </span>

                    <strong class="admin-detail-value blood">
                        {{ $pendonor->golongan_darah }}{{ $pendonor->rhesus }}
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Rhesus
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->rhesus }}
                    </strong>

                </div>


                <div class="admin-detail-item full-width">

                    <span class="admin-detail-label">
                        Alamat
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->alamat }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- INFORMASI AKUN --}}
        <div class="donor-panel donor-content-row">

            <div class="donor-panel-header">

                <div>

                    <span class="donor-section-label">
                        AKUN
                    </span>

                    <h3>
                        Informasi Akun
                    </h3>

                </div>

            </div>


            <div class="admin-detail-grid">

                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Username
                    </span>

                    <strong class="admin-detail-value">
                        {{ Auth::user()->username }}
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Role
                    </span>

                    <strong class="admin-detail-value">
                        {{ ucfirst(Auth::user()->role) }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- BACK --}}
        <div class="donor-form-actions">

            <a
                href="{{ route('dashboard.pendonor') }}"
                class="donor-btn-secondary"
            >
                Kembali ke Dashboard
            </a>

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