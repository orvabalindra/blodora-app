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

                </div>


                <div class="admin-page-actions">

                    {{-- EDIT --}}
                    <a
                        href="{{ route('pendonor.edit', $pendonor->id) }}"
                        class="admin-btn-primary"
                    >
                        Ubah
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

        <div class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <span class="admin-section-label">ALUR DONOR</span>
                    <h3>Aktivitas Terhubung</h3>
                    <p>Pendaftaran, hasil pemeriksaan, riwayat, dan batch stok terbaru pendonor ini.</p>
                </div>
            </div>

            <h4>Pendaftaran &amp; Jadwal</h4>
            <div style="overflow-x: auto;">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>Jadwal</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Screening</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftarans as $pendaftaran)
                            <tr>
                                <td>{{ $pendaftaran->jadwalDonor?->nama_kegiatan ?? 'Jadwal tidak tersedia' }}</td>
                                <td>{{ $pendaftaran->jadwalDonor?->tanggal?->format('d M Y') ?? '-' }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $pendaftaran->status)) }}</td>
                                <td>
                                    <a href="{{ route('pendaftaran-donor.admin.index') }}" class="admin-table-link">
                                        Lihat pendaftaran
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Belum ada pendaftaran donor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h4>Pemeriksaan</h4>
            <div style="overflow-x: auto;">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Hasil</th>
                            <th>Terhubung ke</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemeriksaans as $pemeriksaan)
                            <tr>
                                <td>{{ $pemeriksaan->tanggal_pemeriksaan?->format('d M Y') ?? '-' }}</td>
                                <td>{{ ucfirst($pemeriksaan->hasil) }}</td>
                                <td>{{ $pemeriksaan->pendaftaran_id ? 'Pendaftaran donor' : 'Pemeriksaan manual' }}</td>
                                <td>
                                    <a href="{{ route('pemeriksaan.show', $pemeriksaan) }}" class="admin-table-link">
                                        Detail pemeriksaan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Belum ada data pemeriksaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h4>Riwayat Donor</h4>
            <div style="overflow-x: auto;">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatDonors as $riwayat)
                            <tr>
                                <td>{{ $riwayat->tanggal_donor?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $riwayat->jumlah_donor }} kantong</td>
                                <td>{{ ucfirst($riwayat->status) }}</td>
                                <td>
                                    <a href="{{ route('riwayat-donor.show', $riwayat) }}" class="admin-table-link">
                                        Detail riwayat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Belum ada riwayat donor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h4>Stok dari Donasi Pendonor</h4>
            <div style="overflow-x: auto;">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>Golongan</th>
                            <th>Sisa stok</th>
                            <th>Kedaluwarsa</th>
                            <th>Penyaluran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stokDarahs as $stok)
                            <tr>
                                <td>{{ $stok->golongan_darah }}{{ $stok->rhesus }}</td>
                                <td>{{ $stok->jumlah_kantong }} kantong</td>
                                <td>{{ $stok->tanggal_kadaluarsa?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $stok->penyalurans->sum('jumlah_kantong') }} kantong</td>
                                <td>
                                    <a href="{{ route('stok-darah.show', $stok) }}" class="admin-table-link">
                                        Detail stok
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Belum ada stok hasil donor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
