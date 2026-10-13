@extends('layouts.app')

@section('title', 'Detail Riwayat Donor')

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
                    RIWAYAT DONOR
                </span>

                <h1>
                    Detail Riwayat Donor
                </h1>

                <p>
                    Informasi lengkap riwayat kegiatan donor.
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

            {{-- PANEL HEADER --}}
            <div class="admin-panel-header">

                <div>

                    <span class="admin-section-label">
                        DETAIL DONOR
                    </span>

                    <h3>
                        Riwayat Donor #{{ $riwayatDonor->id_riwayat_donor }}
                    </h3>

                    <p>
                        Informasi aktivitas donor pendonor.
                    </p>

                </div>


                <div class="admin-page-actions">

                    @if ($riwayatDonor->pendaftaran_id === null)
                        <a
                            href="{{ route('riwayat-donor.edit', $riwayatDonor) }}"
                            class="admin-btn-primary"
                        >
                            Edit
                        </a>
                    @endif

                    <a
                        href="{{ route('riwayat-donor.index') }}"
                        class="admin-btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </div>


            {{-- DETAIL --}}
            <div class="admin-detail-grid">

                @if (session('info'))
                    <div class="admin-form-alert full-width">
                        {{ session('info') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="admin-form-alert admin-form-alert-danger full-width">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if ($riwayatDonor->pendaftaran_id)
                    <div class="admin-detail-item full-width">
                        <span class="admin-detail-label">Pendaftaran</span>
                        <a href="{{ route('pendaftaran-donor.admin.index') }}" class="admin-table-link">
                            Riwayat donor dari pendaftaran #{{ $riwayatDonor->pendaftaran_id }}
                        </a>
                    </div>
                @endif

                {{-- PENDONOR --}}
                <div class="admin-detail-item full-width">

                    <span class="admin-detail-label">
                        PENDONOR
                    </span>

                    <strong class="admin-detail-value">
                        {{ $riwayatDonor->pendonor?->nama_lengkap ?? 'Pendonor tidak ditemukan' }}
                    </strong>

                    @if ($riwayatDonor->pendonor)

                        <span class="admin-detail-sub-label">
                            Golongan Darah
                        </span>

                        <strong class="admin-detail-value blood">
                            {{ $riwayatDonor->pendonor->golongan_darah }}{{ $riwayatDonor->pendonor->rhesus }}
                        </strong>

                    @endif

                </div>

                @if ($riwayatDonor->pendaftaran)
                    <div class="admin-detail-item">
                        <span class="admin-detail-label">Jadwal Donor</span>
                        <strong class="admin-detail-value">
                            {{ $riwayatDonor->pendaftaran->jadwalDonor?->nama_kegiatan ?? '-' }}
                        </strong>
                        <span class="admin-detail-sub-label">
                            {{ $riwayatDonor->pendaftaran->jadwalDonor?->tanggal?->format('d F Y') ?? '-' }}
                        </span>
                    </div>
                @endif


                {{-- TANGGAL DONOR --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Tanggal Donor
                    </span>

                    <strong class="admin-detail-value">
                        {{ $riwayatDonor->tanggal_donor?->format('d F Y') ?? '-' }}
                    </strong>

                </div>


                {{-- JUMLAH DONOR --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Jumlah Donor
                    </span>

                    <strong class="admin-detail-value">
                        {{ $riwayatDonor->jumlah_donor }} kantong
                    </strong>

                </div>


                {{-- PEMERIKSAAN --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Pemeriksaan
                    </span>

                    @if ($riwayatDonor->pemeriksaan)

                        <a
                            href="{{ route('pemeriksaan.show', $riwayatDonor->pemeriksaan) }}"
                            class="admin-table-link"
                        >
                            Pemeriksaan
                            #{{ $riwayatDonor->pemeriksaan->id_pemeriksaan }}
                        </a>

                        <span class="admin-detail-sub-label">
                            Hasil Pemeriksaan
                        </span>

                        <strong class="admin-detail-value">
                            {{ ucfirst($riwayatDonor->pemeriksaan->hasil) }}
                        </strong>

                    @else

                        <span class="admin-detail-value">
                            Tidak ada pemeriksaan terkait.
                        </span>

                    @endif

                </div>


                {{-- STATUS --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Status Donor
                    </span>

                    @if ($riwayatDonor->status === 'sukses')

                        <span class="admin-status-pill success">
                            Sukses
                        </span>

                    @else

                        <span class="admin-status-pill danger">
                            Gagal
                        </span>

                    @endif

                </div>

            </div>


            {{-- ACTION --}}
            <div class="admin-form-actions">

                <a
                    href="{{ route('riwayat-donor.index') }}"
                    class="admin-btn-secondary"
                >
                    Kembali
                </a>


                @if ($riwayatDonor->pendaftaran_id === null)
                    <form
                        action="{{ route('riwayat-donor.destroy', $riwayatDonor) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus riwayat donor ini?')"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn-primary">Hapus</button>
                    </form>
                @endif

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