@extends('layouts.app')

@section('title', 'Riwayat Donor')

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
                    Riwayat Donor
                </h1>

                <p>
                    Kelola seluruh riwayat kegiatan donor darah.
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
                        DATA DONOR
                    </span>

                    <h3>
                        Riwayat Donor
                    </h3>

                    <p>
                        Daftar seluruh aktivitas donor darah.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('riwayat-donor.create') }}"
                        class="admin-btn-primary"
                    >
                        + Tambah Riwayat
                    </a>

                </div>

            </div>


            {{-- SUCCESS MESSAGE --}}
            @if (session('success'))

                <div class="admin-form-alert admin-form-alert-success">
                    {{ session('success') }}
                </div>

            @endif


            {{-- TABLE --}}
            <div style="overflow-x: auto;">

                <table class="admin-data-table">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Pendonor</th>
                            <th>Tanggal Donor</th>
                            <th>Jumlah</th>
                            <th>Pemeriksaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($riwayatDonors as $riwayat)

                            <tr>

                                {{-- NO --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- PENDONOR --}}
                                <td>

                                    <div class="admin-donor-name">

                                        <div class="admin-donor-avatar">
                                            {{
                                                strtoupper(
                                                    substr(
                                                        $riwayat->pendonor?->nama_lengkap ?? 'P',
                                                        0,
                                                        1
                                                    )
                                                )
                                            }}
                                        </div>

                                        <strong>
                                            {{ $riwayat->pendonor?->nama_lengkap ?? 'Pendonor tidak ditemukan' }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- TANGGAL DONOR --}}
                                <td>
                                    {{ $riwayat->tanggal_donor?->format('d M Y') ?? '-' }}
                                </td>


                                {{-- JUMLAH --}}
                                <td>
                                    {{ $riwayat->jumlah_donor }} kantong
                                </td>


                                {{-- PEMERIKSAAN --}}
                                <td>

                                    @if ($riwayat->pemeriksaan)

                                        <a
                                            href="{{ route('pemeriksaan.show', $riwayat->pemeriksaan) }}"
                                            class="admin-table-link"
                                        >
                                            #{{ $riwayat->pemeriksaan->id_pemeriksaan }}
                                        </a>

                                    @else

                                        <span class="admin-table-muted">
                                            Tidak ada
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if ($riwayat->status === 'sukses')

                                        <span class="admin-status-pill success">
                                            Sukses
                                        </span>

                                    @else

                                        <span class="admin-status-pill danger">
                                            Gagal
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td>

                                    <div class="admin-action-buttons">

                                        {{-- LIHAT --}}
                                        <a
                                            href="{{ route('riwayat-donor.show', $riwayat) }}"
                                            class="admin-action-btn view"
                                            title="Lihat"
                                        >

                                            <svg
                                                viewBox="0 0 24 24"
                                                aria-hidden="true"
                                            >
                                                <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"></path>
                                                <circle cx="12" cy="12" r="2.5"></circle>
                                            </svg>

                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('riwayat-donor.edit', $riwayat) }}"
                                            class="admin-action-btn edit"
                                            title="Edit"
                                        >

                                            <svg
                                                viewBox="0 0 24 24"
                                                aria-hidden="true"
                                            >
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z"></path>
                                            </svg>

                                        </a>


                                        {{-- HAPUS --}}
                                        <form
                                            action="{{ route('riwayat-donor.destroy', $riwayat) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus riwayat donor ini?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="admin-action-btn delete"
                                                title="Hapus"
                                            >

                                                <svg
                                                    viewBox="0 0 24 24"
                                                    aria-hidden="true"
                                                >
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4h8v2"></path>
                                                    <path d="M19 6l-1 14H6L5 6"></path>
                                                    <path d="M10 11v5"></path>
                                                    <path d="M14 11v5"></path>
                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="7">

                                    <div class="admin-empty-state">

                                        <div class="admin-empty-icon">
                                            ↻
                                        </div>

                                        <strong>
                                            Belum ada data riwayat donor.
                                        </strong>

                                        <span>
                                            Data riwayat donor akan muncul di sini.
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

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