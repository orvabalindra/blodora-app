@extends('layouts.app')

@section('title', 'Permintaan Darah')

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
                    PERMINTAAN DARAH
                </span>

                <h1>
                    Permintaan Darah
                </h1>

                <p>
                    Kelola seluruh permintaan darah dari fasilitas kesehatan.
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
                        DATA PERMINTAAN
                    </span>

                    <h3>
                        Permintaan Darah
                    </h3>

                    <p>
                        Daftar permintaan darah dari fasilitas kesehatan.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('permintaan-darah.create') }}"
                        class="admin-btn-primary"
                    >
                        + Tambah Permintaan
                    </a>

                </div>

            </div>


            {{-- SUCCESS MESSAGE --}}
            @include('components.admin-list-search', [
                'action' => route('permintaan-darah.index'),
                'items' => $permintaanDarahs,
                'placeholder' => 'Cari nama faskes, golongan darah, atau status',
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
                            <th>Faskes</th>
                            <th>Tanggal</th>
                            <th>Golongan</th>
                            <th>Jumlah</th>
                            <th>Disalurkan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($permintaanDarahs as $permintaan)

                            <tr>

                                {{-- NO --}}
                                <td>
                                    {{ $loop->iteration + (($permintaanDarahs->currentPage() - 1) * $permintaanDarahs->perPage()) }}
                                </td>


                                {{-- FASKES --}}
                                <td>

                                    <div class="admin-donor-name">

                                        <div class="admin-donor-avatar">
                                            {{
                                                strtoupper(
                                                    substr(
                                                        $permintaan->rumahSakit?->nama_rs ?? 'F',
                                                        0,
                                                        1
                                                    )
                                                )
                                            }}
                                        </div>

                                        <strong>
                                            {{ $permintaan->rumahSakit?->nama_rs ?? 'Faskes tidak ditemukan' }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- TANGGAL --}}
                                <td>
                                    {{ $permintaan->tanggal_permintaan?->format('d M Y') ?? '-' }}
                                </td>


                                {{-- GOLONGAN --}}
                                <td>

                                    <span class="admin-blood-badge">
                                        {{ $permintaan->golongan_darah }}{{ $permintaan->rhesus }}
                                    </span>

                                </td>


                                {{-- JUMLAH --}}
                                <td>
                                    {{ $permintaan->jumlah_minta }} kantong
                                </td>

                                <td>
                                    {{ $permintaan->jumlah_terpenuhi }} dari
                                    {{ $permintaan->jumlah_minta }} kantong
                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if ($permintaan->status === 'diproses')

                                        <span class="admin-status-pill warning">
                                            Diproses
                                        </span>

                                    @elseif ($permintaan->status === 'ditolak')

                                        <span class="admin-status-pill danger">
                                            Ditolak
                                        </span>

                                    @elseif ($permintaan->jumlah_terpenuhi >= $permintaan->jumlah_minta)

                                        <span class="admin-status-pill success">
                                            Terpenuhi
                                        </span>

                                    @elseif ($permintaan->jumlah_terpenuhi > 0)

                                        <span class="admin-status-pill warning">
                                            Sebagian disalurkan
                                        </span>

                                    @else

                                        <span class="admin-status-pill success">
                                            Disetujui
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td>

                                    <div class="admin-action-buttons">

                                        {{-- LIHAT --}}
                                        <a
                                            href="{{ route('permintaan-darah.show', $permintaan) }}"
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
                                        @if ($permintaan->status === 'diproses')
                                            <a
                                                href="{{ route('permintaan-darah.edit', $permintaan) }}"
                                                class="admin-action-btn edit"
                                                title="Ubah"
                                            >
                                                <svg
                                                    viewBox="0 0 24 24"
                                                    aria-hidden="true"
                                                >
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z"></path>
                                                </svg>
                                            </a>
                                        @endif


                                        {{-- HAPUS --}}
                                        @if ($permintaan->status === 'diproses' && $permintaan->penyalurans_count === 0)
                                            <form
                                                action="{{ route('permintaan-darah.destroy', $permintaan) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus permintaan darah ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="admin-action-btn delete"
                                                    title="Hapus"
                                                >
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
                                            ▣
                                        </div>

                                        <strong>
                                            {{ request()->filled('q') ? 'Tidak ada permintaan yang cocok dengan pencarian.' : 'Belum ada data permintaan darah.' }}
                                        </strong>

                                        <span>
                                            Data permintaan dari fasilitas kesehatan akan muncul di sini.
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="mt-4">{{ $permintaanDarahs->links() }}</div>

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
