@extends('layouts.app')

@section('title', 'Detail Stok Darah')

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
                    STOK DARAH
                </span>

                <h1>
                    Detail Stok Darah
                </h1>

                <p>
                    Informasi lengkap data stok darah.
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
                        DETAIL STOK DARAH
                    </span>

                    <h3>
                        {{ $stokDarah->golongan_darah }}{{ $stokDarah->rhesus }}
                    </h3>

                    <p>
                        ID Stok #{{ $stokDarah->id_stok_darah }}
                    </p>

                </div>


                <div class="admin-page-actions">

                    {{-- EDIT --}}
                    @if ($stokDarah->pendaftaran_id === null && $stokDarah->penyalurans->isEmpty())
                        <a
                            href="{{ route('stok-darah.edit', $stokDarah) }}"
                            class="admin-btn-primary"
                        >
                            Edit
                        </a>
                    @endif


                    {{-- KEMBALI --}}
                    <a
                        href="{{ route('stok-darah.index') }}"
                        class="admin-btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </div>


            {{-- DETAIL DATA --}}
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

                {{-- PENDONOR --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Pendonor
                    </span>

                    <strong class="admin-detail-value">
                        {{ $stokDarah->pendonor?->nama_lengkap ?? 'Tidak ditemukan' }}
                    </strong>
                    @if ($stokDarah->pendonor)
                        <a href="{{ route('pendonor.show', $stokDarah->pendonor) }}" class="admin-table-link">
                            Lihat profil pendonor
                        </a>
                    @endif

                </div>


                {{-- GOLONGAN DARAH --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Golongan Darah
                    </span>

                    <strong class="admin-detail-value blood">
                        {{ $stokDarah->golongan_darah }}{{ $stokDarah->rhesus }}
                    </strong>

                </div>


                {{-- JUMLAH KANTONG --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Jumlah Kantong
                    </span>

                    <strong class="admin-detail-value">
                        {{ $stokDarah->jumlah_kantong }} kantong
                    </strong>

                </div>


                {{-- TANGGAL MASUK --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Tanggal Masuk
                    </span>

                    <strong class="admin-detail-value">
                        {{ $stokDarah->tanggal_masuk?->format('d F Y') ?? '-' }}
                    </strong>

                </div>


                {{-- TANGGAL KADALUARSA --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Tanggal Kadaluarsa
                    </span>

                    <strong class="admin-detail-value">
                        {{ $stokDarah->tanggal_kadaluarsa?->format('d F Y') ?? '-' }}
                    </strong>

                </div>

                @if ($stokDarah->pendaftaran)
                    <div class="admin-detail-item">
                        <span class="admin-detail-label">Sumber Batch</span>
                        <a href="{{ route('pendaftaran-donor.admin.index') }}" class="admin-table-link">
                            Pendaftaran donor #{{ $stokDarah->pendaftaran_id }}
                        </a>
                    </div>
                @endif


            </div>

            @if ($stokDarah->penyalurans->isNotEmpty())
                <div class="admin-panel-header">
                    <div>
                        <span class="admin-section-label">JEJAK STOK</span>
                        <h3>Riwayat Penyaluran</h3>
                        <p>Permintaan faskes yang mengambil stok dari batch ini.</p>
                    </div>
                </div>
                <div style="overflow-x: auto;">
                    <table class="admin-data-table">
                        <thead>
                            <tr>
                                <th>Permintaan</th>
                                <th>Faskes</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokDarah->penyalurans as $penyaluran)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('permintaan-darah.show', $penyaluran->permintaanDarah) }}"
                                            class="admin-table-link"
                                        >
                                            #{{ $penyaluran->permintaan_darah_id }}
                                        </a>
                                    </td>
                                    <td>{{ $penyaluran->permintaanDarah?->rumahSakit?->nama_rs ?? '-' }}</td>
                                    <td>{{ $penyaluran->diserahkan_pada->format('d M Y H:i') }}</td>
                                    <td>{{ $penyaluran->jumlah_kantong }} kantong</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif


            {{-- DELETE --}}
            <div class="admin-form-actions">

                @if ($stokDarah->pendaftaran_id === null && $stokDarah->penyalurans->isEmpty())
                    <form
                        action="{{ route('stok-darah.destroy', $stokDarah) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data stok darah ini?')"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn-secondary">Hapus</button>
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