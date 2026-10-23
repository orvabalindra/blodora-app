@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')

<div class="admin-dashboard">

    {{-- SIDEBAR --}}
    @include('components.admin-sidebar')


    {{-- MAIN --}}
    <main class="admin-main">

        {{-- TOPBAR --}}
        <div class="admin-topbar">

            <div>

                <span class="admin-page-label">
                    PENGATURAN
                </span>

                <h1>
                    Pengaturan
                </h1>

                <p>
                    Kelola profil administrator dan informasi sistem Blodora.
                </p>

            </div>


            <div class="admin-topbar-right">

                <div class="admin-date">

                    <span>
                        Hari ini
                    </span>

                    <strong>
                        {{ now()->translatedFormat('d F Y') }}
                    </strong>

                </div>

                <div class="admin-top-avatar">
                    {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                </div>

            </div>

        </div>


        {{-- PROFIL ADMIN --}}
        <div class="admin-panel">

            <div class="admin-panel-header">

                <div>

                    <span class="admin-section-label">
                        PROFIL ADMINISTRATOR
                    </span>

                    <h3>
                        Profil Akun
                    </h3>

                    <p>
                        Informasi administrator yang sedang masuk ke sistem.
                    </p>

                </div>

            </div>


            <div class="admin-detail-grid">

                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Nama pengguna
                    </span>

                    <strong class="admin-detail-value">
                        {{ Auth::user()->username ?? 'Administrator' }}
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Role
                    </span>

                    <strong class="admin-detail-value">
                        {{ ucfirst(Auth::user()->role ?? 'admin') }}
                    </strong>

                </div>

            </div>


            <div class="admin-form-actions">

                <button
                    type="button"
                    class="admin-btn-secondary"
                    disabled
                >
                    Ubah Profil
                </button>

                <button
                    type="button"
                    class="admin-btn-primary"
                    disabled
                >
                    Ubah Kata sandi
                </button>

            </div>

        </div>


        {{-- PENGATURAN SISTEM --}}
        <div class="admin-panel">

            <div class="admin-panel-header">

                <div>

                    <span class="admin-section-label">
                        PENGATURAN SISTEM
                    </span>

                    <h3>
                        Informasi Sistem
                    </h3>

                    <p>
                        Konfigurasi umum aplikasi Blodora.
                    </p>

                </div>

            </div>


            <div class="admin-detail-grid">

                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Nama Sistem
                    </span>

                    <strong class="admin-detail-value">
                        BLODORA
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Deskripsi
                    </span>

                    <strong class="admin-detail-value">
                        Blood Donation System
                    </strong>

                </div>


                <div class="admin-detail-item full-width">

                    <span class="admin-detail-label">
                        Status Sistem
                    </span>

                    <span class="admin-status-pill success">
                        Sistem Aktif
                    </span>

                </div>

            </div>

        </div>


        {{-- PREFERENSI TAMPILAN --}}
        <div class="admin-panel">

            <div class="admin-panel-header">

                <div>

                    <span class="admin-section-label">
                        PREFERENSI TAMPILAN
                    </span>

                    <h3>
                        Tampilan Dasbor
                    </h3>

                    <p>
                        Preferensi visual yang digunakan oleh panel administrator.
                    </p>

                </div>

            </div>


            <div class="admin-detail-grid">

                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Tema
                    </span>

                    <strong class="admin-detail-value">
                        Terang
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Warna Utama
                    </span>

                    <strong class="admin-detail-value blood">
                        Merah Blodora
                    </strong>

                </div>

            </div>

        </div>


        {{-- INFORMASI SISTEM --}}
        <div class="admin-panel">

            <div class="admin-panel-header">

                <div>

                    <span class="admin-section-label">
                        INFORMASI SISTEM
                    </span>

                    <h3>
                        Tentang Blodora
                    </h3>

                </div>

            </div>


            <div class="admin-detail-grid">

                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Nama Aplikasi
                    </span>

                    <strong class="admin-detail-value">
                        Blodora
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Versi
                    </span>

                    <strong class="admin-detail-value">
                        1.0.0
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Framework
                    </span>

                    <strong class="admin-detail-value">
                        Laravel
                    </strong>

                </div>


                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Status Database
                    </span>

                    <span class="admin-status-pill success">
                        Terhubung
                    </span>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="admin-footer">

            <span>
                Panel Admin Blodora
            </span>

            <span>
                Sistem Donor Darah
            </span>

        </div>

    </main>

</div>

@endsection
