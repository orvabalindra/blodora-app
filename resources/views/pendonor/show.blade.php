@extends('layouts.app')

@section('title', 'Detail Pendonor')

@section('content')

<div class="admin-dashboard">

    {{-- SIDEBAR --}}
    @include('components.admin-sidebar')


    {{-- MAIN CONTENT --}}
    <main class="admin-main">

        {{-- TOPBAR --}}
        <div class="admin-topbar">

            <div>

                <span class="admin-page-label">
                    DATA MASTER
                </span>

                <h1>
                    Detail Pendonor
                </h1>

                <p>
                    Informasi lengkap data pendonor.
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


        {{-- DETAIL PANEL --}}
        <div class="admin-panel">

            {{-- PANEL HEADER --}}
            <div class="admin-panel-header">

                <div>

                    <span class="admin-section-label">
                        PENDONOR
                    </span>

                    <h3>
                        {{ $pendonor->nama_lengkap }}
                    </h3>

                    <p>
                        ID Pendonor #{{ $pendonor->id }}
                    </p>

                </div>


                <div class="admin-page-actions">

                    {{-- EDIT --}}
                    <a
                        href="{{ route('pendonor.edit', $pendonor->id) }}"
                        class="admin-btn-primary"
                    >
                        Edit
                    </a>


                    {{-- KEMBALI --}}
                    <a
                        href="{{ route('pendonor.index') }}"
                        class="admin-btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </div>


            {{-- DETAIL DATA --}}
            <div class="admin-detail-grid">


                {{-- NIK --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        NIK
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->nik }}
                    </strong>

                </div>


                {{-- NAMA LENGKAP --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Nama Lengkap
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->nama_lengkap }}
                    </strong>

                </div>


                {{-- NOMOR HP --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Nomor HP
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->no_hp }}
                    </strong>

                </div>


                {{-- GOLONGAN DARAH --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Golongan Darah
                    </span>

                    <strong class="admin-detail-value blood">
                        {{ $pendonor->golongan_darah }}{{ $pendonor->rhesus }}
                    </strong>

                </div>


                {{-- RHESUS --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Rhesus
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->rhesus }}
                    </strong>

                </div>


                {{-- BERAT BADAN --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Berat Badan
                    </span>

                    <strong class="admin-detail-value">
                        {{ $pendonor->berat_badan }} kg
                    </strong>

                </div>


                {{-- ALAMAT --}}
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


        {{-- FOOTER --}}
        <div class="admin-footer">

            <span>
                Blodora Admin Panel
            </span>

            <span>
                Sistem Donor Darah
            </span>

        </div>

    </main>

</div>

@endsection