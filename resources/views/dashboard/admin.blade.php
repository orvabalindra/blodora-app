@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="admin-dashboard">

        {{-- =====================================================
        SIDEBAR
        ====================================================== --}}
        @include('components.admin-sidebar')



        {{-- =====================================================
        MAIN CONTENT
        ====================================================== --}}
        <main class="admin-main">


            {{-- =================================================
            TOPBAR
            ================================================== --}}
            <div class="admin-topbar">

                <div>

                    <span class="admin-page-label">
                        ADMINISTRATOR
                    </span>

                    <h1>
                        Dashboard
                    </h1>

                    <p>
                        Selamat datang kembali,
                        <strong>
                            {{ Auth::user()->username ?? 'Admin' }}
                        </strong>
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

                        {{ strtoupper(
        substr(
            Auth::user()->username ?? 'A',
            0,
            1
        )
    ) }}

                    </div>

                </div>

            </div>



            {{-- =================================================
            STATISTIK UTAMA
            ================================================== --}}
            <div class="row g-4 admin-stat-row">


                {{-- TOTAL PENDONOR --}}
                <div class="col-12 col-sm-6 col-xl-3">

                    <div class="admin-stat-card">

                        <div class="admin-stat-top">

                            <div class="admin-stat-icon donor-icon">
                                ♙
                            </div>

                            <span class="admin-stat-badge">
                                Pendonor
                            </span>

                        </div>

                        <div class="admin-stat-content">

                            <span>
                                Total Pendonor
                            </span>

                            <h2>
                                {{ $totalPendonor ?? 0 }}
                            </h2>

                            <small>
                                Pendonor terdaftar
                            </small>

                        </div>

                    </div>

                </div>



                {{-- TOTAL RUMAH SAKIT --}}
                <div class="col-12 col-sm-6 col-xl-3">

                    <div class="admin-stat-card">

                        <div class="admin-stat-top">

                            <div class="admin-stat-icon blood-icon">
                                +
                            </div>

                            <span class="admin-stat-badge">
                                Faskes
                            </span>

                        </div>

                        <div class="admin-stat-content">

                            <span>
                                Rumah Sakit / Faskes
                            </span>

                            <h2>
                                {{ $totalRumahSakit ?? 0 }}
                            </h2>

                            <small>
                                Faskes terdaftar
                            </small>

                        </div>

                    </div>

                </div>



                {{-- TOTAL PEMERIKSAAN --}}
                <div class="col-12 col-sm-6 col-xl-3">

                    <div class="admin-stat-card">

                        <div class="admin-stat-top">

                            <div class="admin-stat-icon donation-icon">
                                ✓
                            </div>

                            <span class="admin-stat-badge">
                                Screening
                            </span>

                        </div>

                        <div class="admin-stat-content">

                            <span>
                                Total Pemeriksaan
                            </span>

                            <h2>
                                {{ $totalPemeriksaan ?? 0 }}
                            </h2>

                            <small>
                                Pemeriksaan pendonor
                            </small>

                        </div>

                    </div>

                </div>



                {{-- TOTAL PERMINTAAN --}}
                <div class="col-12 col-sm-6 col-xl-3">

                    <div class="admin-stat-card">

                        <div class="admin-stat-top">

                            <div class="admin-stat-icon request-icon">
                                !
                            </div>

                            <span class="admin-stat-badge">
                                Request
                            </span>

                        </div>

                        <div class="admin-stat-content">

                            <span>
                                Permintaan Darah
                            </span>

                            <h2>
                                {{ $totalPermintaan ?? 0 }}
                            </h2>

                            <small>
                                Permintaan dari faskes
                            </small>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
            PEMERIKSAAN + DONOR + PERMINTAAN
            ================================================== --}}
            <div class="row g-4 mt-1">


                {{-- PEMERIKSAAN --}}
                <div class="col-12 col-lg-4">

                    <div class="admin-panel">

                        <div class="admin-panel-header">

                            <div>

                                <span class="admin-section-label">
                                    PEMERIKSAAN
                                </span>

                                <h3>
                                    Hasil Screening
                                </h3>

                            </div>

                        </div>


                        <div class="admin-summary-list">


                            {{-- LAYAK --}}
                            <div class="admin-summary-row">

                                <div class="admin-summary-left">

                                    <div class="admin-summary-icon success">
                                        ✓
                                    </div>

                                    <div>

                                        <strong>
                                            Layak
                                        </strong>

                                        <span>
                                            Pendonor dapat donor
                                        </span>

                                    </div>

                                </div>

                                <strong class="admin-summary-number">
                                    {{ $screeningLayak ?? 0 }}
                                </strong>

                            </div>


                            {{-- TIDAK LAYAK --}}
                            <div class="admin-summary-row">

                                <div class="admin-summary-left">

                                    <div class="admin-summary-icon danger">
                                        ×
                                    </div>

                                    <div>

                                        <strong>
                                            Tidak Layak
                                        </strong>

                                        <span>
                                            Tidak memenuhi syarat
                                        </span>

                                    </div>

                                </div>

                                <strong class="admin-summary-number">
                                    {{ $screeningTidakLayak ?? 0 }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- RIWAYAT DONOR --}}
                <div class="col-12 col-lg-4">

                    <div class="admin-panel">

                        <div class="admin-panel-header">

                            <div>

                                <span class="admin-section-label">
                                    DONOR
                                </span>

                                <h3>
                                    Status Donor
                                </h3>

                            </div>

                        </div>


                        <div class="admin-summary-list">


                            {{-- SUKSES --}}
                            <div class="admin-summary-row">

                                <div class="admin-summary-left">

                                    <div class="admin-summary-icon success">
                                        ✓
                                    </div>

                                    <div>

                                        <strong>
                                            Donor Sukses
                                        </strong>

                                        <span>
                                            Donor berhasil
                                        </span>

                                    </div>

                                </div>

                                <strong class="admin-summary-number">
                                    {{ $donorSukses ?? 0 }}
                                </strong>

                            </div>


                            {{-- GAGAL --}}
                            <div class="admin-summary-row">

                                <div class="admin-summary-left">

                                    <div class="admin-summary-icon danger">
                                        ×
                                    </div>

                                    <div>

                                        <strong>
                                            Donor Gagal
                                        </strong>

                                        <span>
                                            Donor tidak berhasil
                                        </span>

                                    </div>

                                </div>

                                <strong class="admin-summary-number">
                                    {{ $donorGagal ?? 0 }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- PERMINTAAN --}}
                <div class="col-12 col-lg-4">

                    <div class="admin-panel">

                        <div class="admin-panel-header">

                            <div>

                                <span class="admin-section-label">
                                    PERMINTAAN
                                </span>

                                <h3>
                                    Status Permintaan
                                </h3>

                            </div>

                        </div>


                        <div class="admin-summary-list">


                            {{-- DIPROSES --}}
                            <div class="admin-summary-row">

                                <div class="admin-summary-left">

                                    <div class="admin-summary-icon warning">
                                        …
                                    </div>

                                    <div>

                                        <strong>
                                            Diproses
                                        </strong>

                                        <span>
                                            Menunggu proses
                                        </span>

                                    </div>

                                </div>

                                <strong class="admin-summary-number">
                                    {{ $permintaanDiproses ?? 0 }}
                                </strong>

                            </div>


                            {{-- DITERIMA --}}
                            <div class="admin-summary-row">

                                <div class="admin-summary-left">

                                    <div class="admin-summary-icon success">
                                        ✓
                                    </div>

                                    <div>

                                        <strong>
                                            Diterima
                                        </strong>

                                        <span>
                                            Permintaan diterima
                                        </span>

                                    </div>

                                </div>

                                <strong class="admin-summary-number">
                                    {{ $permintaanDiterima ?? 0 }}
                                </strong>

                            </div>


                            {{-- DITOLAK --}}
                            <div class="admin-summary-row">

                                <div class="admin-summary-left">

                                    <div class="admin-summary-icon danger">
                                        ×
                                    </div>

                                    <div>

                                        <strong>
                                            Ditolak
                                        </strong>

                                        <span>
                                            Permintaan ditolak
                                        </span>

                                    </div>

                                </div>

                                <strong class="admin-summary-number">
                                    {{ $permintaanDitolak ?? 0 }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
            STOK DARAH
            ================================================== --}}
            <div class="row g-4 mt-1">

                <div class="col-12">

                    <div class="admin-panel">

                        <div class="admin-panel-header">

                            <div>

                                <span class="admin-section-label">
                                    INVENTORI
                                </span>

                                <h3>
                                    Stok Darah
                                </h3>

                                <p>
                                    Stok yang masih belum kadaluarsa.
                                </p>

                            </div>

                            <div class="admin-stock-total">

                                <span>
                                    Total
                                </span>

                                <strong>
                                    {{ $totalStok ?? 0 }}
                                </strong>

                                <small>
                                    kantong
                                </small>

                            </div>

                        </div>


                        <div class="row g-3">


                            {{-- A+ --}}
                            <div class="col-6 col-md-3 col-xl-2">

                                <div class="admin-blood-card">

                                    <div class="admin-blood-type">
                                        A+
                                    </div>

                                    <strong>
                                        {{ $stok['A+'] ?? 0 }}
                                    </strong>

                                    <span>
                                        kantong
                                    </span>

                                </div>

                            </div>


                            {{-- A- --}}
                            <div class="col-6 col-md-3 col-xl-2">

                                <div class="admin-blood-card">

                                    <div class="admin-blood-type">
                                        A-
                                    </div>

                                    <strong>
                                        {{ $stok['A-'] ?? 0 }}
                                    </strong>

                                    <span>
                                        kantong
                                    </span>

                                </div>

                            </div>


                            {{-- B+ --}}
                            <div class="col-6 col-md-3 col-xl-2">

                                <div class="admin-blood-card">

                                    <div class="admin-blood-type">
                                        B+
                                    </div>

                                    <strong>
                                        {{ $stok['B+'] ?? 0 }}
                                    </strong>

                                    <span>
                                        kantong
                                    </span>

                                </div>

                            </div>


                            {{-- B- --}}
                            <div class="col-6 col-md-3 col-xl-2">

                                <div class="admin-blood-card">

                                    <div class="admin-blood-type">
                                        B-
                                    </div>

                                    <strong>
                                        {{ $stok['B-'] ?? 0 }}
                                    </strong>

                                    <span>
                                        kantong
                                    </span>

                                </div>

                            </div>


                            {{-- AB+ --}}
                            <div class="col-6 col-md-3 col-xl-2">

                                <div class="admin-blood-card">

                                    <div class="admin-blood-type">
                                        AB+
                                    </div>

                                    <strong>
                                        {{ $stok['AB+'] ?? 0 }}
                                    </strong>

                                    <span>
                                        kantong
                                    </span>

                                </div>

                            </div>


                            {{-- AB- --}}
                            <div class="col-6 col-md-3 col-xl-2">

                                <div class="admin-blood-card">

                                    <div class="admin-blood-type">
                                        AB-
                                    </div>

                                    <strong>
                                        {{ $stok['AB-'] ?? 0 }}
                                    </strong>

                                    <span>
                                        kantong
                                    </span>

                                </div>

                            </div>


                            {{-- O+ --}}
                            <div class="col-6 col-md-3 col-xl-2">

                                <div class="admin-blood-card">

                                    <div class="admin-blood-type">
                                        O+
                                    </div>

                                    <strong>
                                        {{ $stok['O+'] ?? 0 }}
                                    </strong>

                                    <span>
                                        kantong
                                    </span>

                                </div>

                            </div>


                            {{-- O- --}}
                            <div class="col-6 col-md-3 col-xl-2">

                                <div class="admin-blood-card">

                                    <div class="admin-blood-type">
                                        O-
                                    </div>

                                    <strong>
                                        {{ $stok['O-'] ?? 0 }}
                                    </strong>

                                    <span>
                                        kantong
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
            BAWAH
            ================================================== --}}
            <div class="row g-4 mt-1">


                {{-- AKTIVITAS TERBARU --}}
                <div class="col-12 col-xl-7">

                    <div class="admin-panel">

                        <div class="admin-panel-header">

                            <div>

                                <span class="admin-section-label">
                                    AKTIVITAS
                                </span>

                                <h3>
                                    Donor Terbaru
                                </h3>

                                <p>
                                    Lima aktivitas donor terbaru.
                                </p>

                            </div>

                        </div>


                        @if(isset($aktivitas) && $aktivitas->count() > 0)

                            <div class="admin-activity-list">

                                @foreach($aktivitas as $item)

                                                    <div class="admin-activity-row">

                                                        <div class="admin-activity-icon">
                                                            ♥
                                                        </div>

                                                        <div class="admin-activity-content">

                                                            <strong>
                                                                {{ $item->nama_lengkap }}
                                                            </strong>

                                                            <span>

                                                                Golongan
                                                                <b>
                                                                    {{ $item->golongan_darah }}
                                                                </b>

                                                                ·

                                                                {{ \Carbon\Carbon::parse(
                                        $item->tanggal_donor
                                    )->translatedFormat('d M Y') }}

                                                            </span>

                                                        </div>


                                                        <div class="admin-activity-right">

                                                            <strong>
                                                                {{ $item->jumlah_donor }}
                                                            </strong>

                                                            <span class="admin-status-pill
                                                                                                                                                                                                                                                {{ $item->status === 'sukses'
                                        ? 'success'
                                        : 'danger' }}">
                                                                {{ ucfirst($item->status) }}
                                                            </span>

                                                        </div>

                                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="admin-empty-state">

                                <div class="admin-empty-icon">
                                    ♥
                                </div>

                                <strong>
                                    Belum ada aktivitas donor
                                </strong>

                                <span>
                                    Data donor terbaru akan muncul di sini.
                                </span>

                            </div>

                        @endif

                    </div>

                </div>



                {{-- PERMINTAAN PER GOLONGAN --}}
                <div class="col-12 col-xl-5">

                    <div class="admin-panel">

                        <div class="admin-panel-header">

                            <div>

                                <span class="admin-section-label">
                                    ANALISIS
                                </span>

                                <h3>
                                    Permintaan Berdasarkan Golongan
                                </h3>

                                <p>
                                    Jumlah permintaan darah.
                                </p>

                            </div>

                        </div>


                        <div class="admin-request-list">


                            {{-- A --}}
                            <div class="admin-request-row">

                                <div class="admin-request-type">
                                    A
                                </div>

                                <div class="admin-request-progress">

                                    <div class="admin-request-label">

                                        <span>
                                            Golongan A
                                        </span>

                                        <strong>
                                            {{ $permintaanGolongan['A'] ?? 0 }}
                                        </strong>

                                    </div>

                                    <div class="admin-progress">
                                        <div class="admin-progress-bar" style="width:
                                                                        {{ (
        $totalPermintaan ?? 0
    ) > 0
        ? (
            ($permintaanGolongan['A'] ?? 0)
            /
            $totalPermintaan
        ) * 100
        : 0
                                                                        }}%;"></div>
                                    </div>

                                </div>

                            </div>


                            {{-- B --}}
                            <div class="admin-request-row">

                                <div class="admin-request-type">
                                    B
                                </div>

                                <div class="admin-request-progress">

                                    <div class="admin-request-label">

                                        <span>
                                            Golongan B
                                        </span>

                                        <strong>
                                            {{ $permintaanGolongan['B'] ?? 0 }}
                                        </strong>

                                    </div>

                                    <div class="admin-progress">
                                        <div class="admin-progress-bar" style="width:
                                                                        {{ (
        $totalPermintaan ?? 0
    ) > 0
        ? (
            ($permintaanGolongan['B'] ?? 0)
            /
            $totalPermintaan
        ) * 100
        : 0
                                                                        }}%;"></div>
                                    </div>

                                </div>

                            </div>


                            {{-- AB --}}
                            <div class="admin-request-row">

                                <div class="admin-request-type">
                                    AB
                                </div>

                                <div class="admin-request-progress">

                                    <div class="admin-request-label">

                                        <span>
                                            Golongan AB
                                        </span>

                                        <strong>
                                            {{ $permintaanGolongan['AB'] ?? 0 }}
                                        </strong>

                                    </div>

                                    <div class="admin-progress">
                                        <div class="admin-progress-bar" style="width:
                                                                        {{ (
        $totalPermintaan ?? 0
    ) > 0
        ? (
            ($permintaanGolongan['AB'] ?? 0)
            /
            $totalPermintaan
        ) * 100
        : 0
                                                                        }}%;"></div>
                                    </div>

                                </div>

                            </div>


                            {{-- O --}}
                            <div class="admin-request-row">

                                <div class="admin-request-type">
                                    O
                                </div>

                                <div class="admin-request-progress">

                                    <div class="admin-request-label">

                                        <span>
                                            Golongan O
                                        </span>

                                        <strong>
                                            {{ $permintaanGolongan['O'] ?? 0 }}
                                        </strong>

                                    </div>

                                    <div class="admin-progress">
                                        <div class="admin-progress-bar" style="width:
                                                                        {{ (
        $totalPermintaan ?? 0
    ) > 0
        ? (
            ($permintaanGolongan['O'] ?? 0)
            /
            $totalPermintaan
        ) * 100
        : 0
                                                                        }}%;"></div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
            FOOTER
            ================================================== --}}
            <footer class="admin-footer">

                <span>
                    © {{ date('Y') }} Blodora
                </span>

                <span>
                    Sistem Donor Darah
                </span>

            </footer>

        </main>

    </div>

@endsection