@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')

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
                        PEMERIKSAAN
                    </span>

                    <h1>
                        Detail Pemeriksaan
                    </h1>

                    <p>
                        Informasi lengkap hasil pemeriksaan pendonor.
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


            {{-- PANEL --}}
            <div class="admin-panel">

                <div class="admin-panel-header">

                    <div>

                        <span class="admin-section-label">
                            DETAIL PEMERIKSAAN
                        </span>

                        <h3>
                            Informasi Pemeriksaan
                        </h3>

                        <p>
                            Data pemeriksaan pendonor secara lengkap.
                        </p>

                    </div>


                    <div class="admin-page-actions">

                        <a href="{{ route('pemeriksaan.edit', $pemeriksaan) }}" class="admin-btn-primary">
                            Edit
                        </a>

                        <a href="{{ route('pemeriksaan.index') }}" class="admin-btn-secondary">
                            Kembali
                        </a>

                    </div>

                </div>


                {{-- DATA PENDONOR --}}
                <div class="admin-detail-grid">

                    <div class="admin-detail-item full-width">

                        <span class="admin-detail-label">
                            PENDONOR
                        </span>

                        <strong class="admin-detail-value">
                            {{ $pemeriksaan->pendonor?->nama_lengkap ?? 'Pendonor tidak ditemukan' }}
                        </strong>

                        @if ($pemeriksaan->pendonor)

                            <span class="admin-detail-sub-label">
                                Golongan Darah
                            </span>

                            <strong class="admin-detail-value blood">
                                {{ $pemeriksaan->pendonor->golongan_darah }}
                                {{ $pemeriksaan->pendonor->rhesus }}
                            </strong>

                        @endif

                    </div>


                    {{-- TANGGAL --}}
                    <div class="admin-detail-item">

                        <span class="admin-detail-label">
                            Tanggal Pemeriksaan
                        </span>

                        <strong class="admin-detail-value">
                            {{ $pemeriksaan->tanggal_pemeriksaan?->format('d F Y') ?? '-' }}
                        </strong>

                    </div>


                    {{-- BERAT BADAN --}}
                    <div class="admin-detail-item">

                        <span class="admin-detail-label">
                            Berat Badan
                        </span>

                        <strong class="admin-detail-value">
                            {{ $pemeriksaan->berat_badan }} kg
                        </strong>

                    </div>


                    {{-- TEKANAN DARAH --}}
                    <div class="admin-detail-item">

                        <span class="admin-detail-label">
                            Tekanan Darah
                        </span>

                        <strong class="admin-detail-value">
                            {{ $pemeriksaan->tekanan_darah }}
                        </strong>

                    </div>


                    {{-- HEMOGLOBIN --}}
                    <div class="admin-detail-item">

                        <span class="admin-detail-label">
                            Hemoglobin
                        </span>

                        <strong class="admin-detail-value">
                            {{ number_format((float) $pemeriksaan->hemoglobin, 1) }}
                            g/dL
                        </strong>

                    </div>


                    {{-- HASIL --}}
                    <div class="admin-detail-item full-width">

                        <span class="admin-detail-label">
                            Hasil Pemeriksaan
                        </span>

                        @if ($pemeriksaan->hasil === 'layak')

                            <span class="admin-status-pill success">
                                Layak Donor
                            </span>

                        @else

                            <span class="admin-status-pill danger">
                                Tidak Layak Donor
                            </span>

                        @endif

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="admin-form-actions">

                    <a href="{{ route('pemeriksaan.index') }}" class="admin-btn-secondary">
                        Kembali
                    </a>


                    <form action="{{ route('pemeriksaan.destroy', $pemeriksaan) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data pemeriksaan ini?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="admin-btn-primary">
                            Hapus
                        </button>

                    </form>

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