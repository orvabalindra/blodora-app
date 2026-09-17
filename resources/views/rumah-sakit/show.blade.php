@extends('layouts.app')

@section('title', 'Detail Faskes')

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
                    Detail Faskes
                </h1>

                <p>
                    Informasi lengkap fasilitas kesehatan.
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
                        DETAIL FASKES
                    </span>

                    <h3>
                        {{ $rumahSakit->nama_rs }}
                    </h3>

                    <p>
                        ID Faskes #{{ $rumahSakit->id_rs }}
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('rumah-sakit.edit', $rumahSakit) }}"
                        class="admin-btn-primary"
                    >
                        Edit
                    </a>

                    <a
                        href="{{ route('rumah-sakit.index') }}"
                        class="admin-btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </div>


            {{-- DETAIL --}}
            <div class="admin-detail-grid">

                {{-- NAMA --}}
                <div class="admin-detail-item full-width">

                    <span class="admin-detail-label">
                        Nama Faskes
                    </span>

                    <strong class="admin-detail-value">
                        {{ $rumahSakit->nama_rs }}
                    </strong>

                </div>


                {{-- ALAMAT --}}
                <div class="admin-detail-item full-width">

                    <span class="admin-detail-label">
                        Alamat
                    </span>

                    <strong class="admin-detail-value">
                        {{ $rumahSakit->alamat }}
                    </strong>

                </div>


                {{-- TELEPON --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Nomor Telepon
                    </span>

                    <strong class="admin-detail-value">
                        {{ $rumahSakit->no_telp }}
                    </strong>

                </div>


                {{-- ID --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        ID Faskes
                    </span>

                    <strong class="admin-detail-value">
                        #{{ $rumahSakit->id_rs }}
                    </strong>

                </div>

            </div>


            {{-- ACTION --}}
            <div class="admin-form-actions">

                <a
                    href="{{ route('rumah-sakit.index') }}"
                    class="admin-btn-secondary"
                >
                    Kembali
                </a>


                <form
                    action="{{ route('rumah-sakit.destroy', $rumahSakit) }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus data Faskes ini?')"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="admin-btn-primary"
                    >
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