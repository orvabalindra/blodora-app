@extends('layouts.app')

@section('title', 'Data Faskes')

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
                    DATA FASKES
                </span>

                <h1>
                    Data Faskes
                </h1>

                <p>
                    Kelola data rumah sakit dan fasilitas kesehatan.
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
                        DATA RUMAH SAKIT / FASKES
                    </span>

                    <h3>
                        Data Faskes
                    </h3>

                    <p>
                        Daftar rumah sakit dan fasilitas kesehatan yang terdaftar.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('rumah-sakit.create') }}"
                        class="admin-btn-primary"
                    >
                        + Tambah Faskes
                    </a>

                </div>

            </div>


            {{-- SUCCESS MESSAGE --}}
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
                            <th>Nama Faskes</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($rumahSakits as $rumahSakit)

                            <tr>

                                {{-- NO --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- NAMA FASKES --}}
                                <td>

                                    <div class="admin-donor-name">

                                        <div class="admin-donor-avatar">

                                            {{
                                                strtoupper(
                                                    substr(
                                                        $rumahSakit->nama_rs,
                                                        0,
                                                        1
                                                    )
                                                )
                                            }}

                                        </div>

                                        <strong>
                                            {{ $rumahSakit->nama_rs }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- ALAMAT --}}
                                <td>
                                    {{ $rumahSakit->alamat }}
                                </td>


                                {{-- TELEPON --}}
                                <td>
                                    {{ $rumahSakit->no_telp }}
                                </td>


                                {{-- AKSI --}}
                                <td>

                                    <div class="admin-action-buttons">

                                        {{-- LIHAT --}}
                                        <a
                                            href="{{ route('rumah-sakit.show', $rumahSakit) }}"
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
                                            href="{{ route('rumah-sakit.edit', $rumahSakit) }}"
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


                                        @if ($rumahSakit->permintaan_darah_count === 0)
                                            {{-- HAPUS --}}
                                            <form
                                                action="{{ route('rumah-sakit.destroy', $rumahSakit) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data Faskes ini?')"
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

                                <td colspan="5">

                                    <div class="admin-empty-state">

                                        <div class="admin-empty-icon">
                                            ▤
                                        </div>

                                        <strong>
                                            Belum ada data Faskes.
                                        </strong>

                                        <span>
                                            Data fasilitas kesehatan akan muncul di sini.
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