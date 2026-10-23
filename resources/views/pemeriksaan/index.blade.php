@extends('layouts.app')

@section('title', 'Pemeriksaan Pendonor')

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
                    Pemeriksaan Pendonor
                </h1>

                <p>
                    Kelola data hasil pemeriksaan pendonor.
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
                        DATA PEMERIKSAAN
                    </span>

                    <h3>
                        Pemeriksaan Pendonor
                    </h3>

                    <p>
                        Riwayat pemeriksaan yang tercatat saat petugas melakukan screening pendaftaran.
                    </p>

                </div>


            </div>


            {{-- SUCCESS MESSAGE --}}
            @include('components.admin-list-search', [
                'action' => route('pemeriksaan.index'),
                'items' => $pemeriksaans,
                'placeholder' => 'Cari nama pendonor atau hasil pemeriksaan',
            ])

            @if (session('success'))

                <div class="admin-form-alert admin-form-alert-success">
                    {{ session('success') }}
                </div>

            @endif

            @if ($errors->any())
                <div class="admin-form-alert admin-form-alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif


            {{-- TABLE --}}
            <div style="overflow-x: auto;">

                <table class="admin-data-table">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Pendonor</th>
                            <th>Tanggal</th>
                            <th>Berat Badan</th>
                            <th>Tekanan Darah</th>
                            <th>Hemoglobin</th>
                            <th>Hasil</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($pemeriksaans as $pemeriksaan)

                            <tr>

                                {{-- NO --}}
                                <td>
                                    {{ $loop->iteration + (($pemeriksaans->currentPage() - 1) * $pemeriksaans->perPage()) }}
                                </td>


                                {{-- PENDONOR --}}
                                <td>

                                    <div class="admin-donor-name">

                                        <div class="admin-donor-avatar">

                                            {{
                                                strtoupper(
                                                    substr(
                                                        $pemeriksaan->pendonor?->nama_lengkap ?? 'P',
                                                        0,
                                                        1
                                                    )
                                                )
                                            }}

                                        </div>

                                        <strong>
                                            {{ $pemeriksaan->pendonor?->nama_lengkap ?? 'Pendonor tidak ditemukan' }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- TANGGAL --}}
                                <td>
                                    {{ $pemeriksaan->tanggal_pemeriksaan?->format('d M Y') ?? '-' }}
                                </td>


                                {{-- BERAT BADAN --}}
                                <td>
                                    {{ $pemeriksaan->berat_badan }} kg
                                </td>


                                {{-- TEKANAN DARAH --}}
                                <td>
                                    {{ $pemeriksaan->tekanan_darah }}
                                </td>


                                {{-- HEMOGLOBIN --}}
                                <td>
                                    {{ number_format((float) $pemeriksaan->hemoglobin, 1) }}
                                </td>


                                {{-- HASIL --}}
                                <td>

                                    @if ($pemeriksaan->hasil === 'layak')

                                        <span class="admin-status-pill success">
                                            Layak
                                        </span>

                                    @else

                                        <span class="admin-status-pill danger">
                                            Tidak Layak
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td>

                                    <div class="admin-action-buttons">

                                        {{-- LIHAT --}}
                                        <a
                                            href="{{ route('pemeriksaan.show', $pemeriksaan) }}"
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


                                        @if ($pemeriksaan->pendaftaran_id === null && $pemeriksaan->riwayat_donors_count === 0)
                                            {{-- EDIT --}}
                                            <a
                                                href="{{ route('pemeriksaan.edit', $pemeriksaan) }}"
                                                class="admin-action-btn edit"
                                                title="Ubah"
                                            >
                                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z"></path>
                                                </svg>
                                            </a>
                                        @endif


                                        @if ($pemeriksaan->pendaftaran_id === null && $pemeriksaan->riwayat_donors_count === 0)
                                            {{-- HAPUS --}}
                                            <form
                                                action="{{ route('pemeriksaan.destroy', $pemeriksaan) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data pemeriksaan ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-action-btn delete" title="Hapus">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4h8v2"></path>
                                                        <path d="M19 6l-1 14H6L5 6"></path>
                                                        <path d="M10 11v5"></path>
                                                        <path d="M14 11v5"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="8">

                                    <div class="admin-empty-state">

                                        <div class="admin-empty-icon">
                                            ✓
                                        </div>

                                        <strong>
                                            {{ request()->filled('q') ? 'Tidak ada pemeriksaan yang cocok dengan pencarian.' : 'Belum ada data pemeriksaan.' }}
                                        </strong>

                                        <span>
                                            Hasil screening akan tercatat otomatis setelah petugas memproses pendaftaran donor.
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="mt-4">{{ $pemeriksaans->links() }}</div>

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
