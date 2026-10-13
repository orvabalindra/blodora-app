@extends('layouts.app')

@section('title', 'Data Pendonor')

@section('content')

    <div class="admin-dashboard">

        {{-- =====================================================
        SIDEBAR ADMIN
        ====================================================== --}}
        @include('components.admin-sidebar')


        {{-- =====================================================
        MAIN CONTENT
        ====================================================== --}}
        <main class="admin-main">


            {{-- =================================================
            TOPBAR
            ================================================== --}}
            <div class="admin-topbar">

                <div>

                    <span class="admin-page-label">
                        DATA MASTER
                    </span>

                    <h1>
                        Data Pendonor
                    </h1>

                    <p>
                        Kelola data pendonor yang terdaftar pada Blodora.
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

                    <a href="{{ route('pendonor.create') }}" class="btn btn-danger">
                        Tambah Pendonor
                    </a>

                </div>

            </div>


            {{-- =================================================
            DATA PANEL
            ================================================== --}}
            <div class="admin-panel">


                {{-- PANEL HEADER --}}
                <div class="admin-panel-header">

                    <div>

                        <span class="admin-section-label">
                            PENDONOR
                        </span>

                        <h3>
                            Daftar Pendonor
                        </h3>

                        <p>
                            Total {{ $pendonors->count() }} pendonor terdaftar.
                        </p>

                    </div>

                </div>


                {{-- =================================================
                SUCCESS MESSAGE
                ================================================== --}}
                @if(session('success'))

                    <div class="alert alert-success border-0 rounded-3 mb-4">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- =================================================
                ERROR MESSAGE
                ================================================== --}}
                @if(session('error'))

                    <div class="alert alert-danger border-0 rounded-3 mb-4">
                        {{ session('error') }}
                    </div>

                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif


                {{-- =================================================
                ADA DATA PENDONOR
                ================================================== --}}
                @if($pendonors->count() > 0)

                    <div class="table-responsive">

                        <table class="table admin-data-table align-middle">

                            <thead>

                                <tr>

                                    <th>
                                        NO
                                    </th>

                                    <th>
                                        NIK
                                    </th>

                                    <th>
                                        NAMA LENGKAP
                                    </th>

                                    <th>
                                        NO. HP
                                    </th>

                                    <th>
                                        GOLONGAN
                                    </th>

                                    <th>
                                        RHESUS
                                    </th>

                                    <th>
                                        BERAT BADAN
                                    </th>

                                    <th class="text-end">
                                        AKSI
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($pendonors as $index => $pendonor)

                                                    <tr>

                                                        {{-- NO --}}
                                                        <td>
                                                            {{ $index + 1 }}
                                                        </td>


                                                        {{-- NIK --}}
                                                        <td>
                                                            {{ $pendonor->nik }}
                                                        </td>


                                                        {{-- NAMA --}}
                                                        <td>

                                                            <div class="admin-donor-name">

                                                                <div class="admin-donor-avatar">

                                                                    {{ strtoupper(
                                        substr($pendonor->nama_lengkap, 0, 1)
                                    ) }}

                                                                </div>

                                                                <strong>
                                                                    {{ $pendonor->nama_lengkap }}
                                                                </strong>

                                                            </div>

                                                        </td>


                                                        {{-- NO HP --}}
                                                        <td>
                                                            {{ $pendonor->no_hp }}
                                                        </td>


                                                        {{-- GOLONGAN DARAH --}}
                                                        <td>

                                                            <span class="admin-blood-badge">
                                                                {{ $pendonor->golongan_darah }}
                                                            </span>

                                                        </td>


                                                        {{-- RHESUS --}}
                                                        <td>
                                                            {{ $pendonor->rhesus }}
                                                        </td>


                                                        {{-- BERAT BADAN --}}
                                                        <td>
                                                            {{ $pendonor->berat_badan }} kg
                                                        </td>


                                                        {{-- AKSI --}}
                                                        <td>

                                                            <div class="admin-action-buttons">


                                                                {{-- DETAIL --}}
                                                                <a href="{{ route('pendonor.show', $pendonor->id) }}" class="admin-action-btn view"
                                                                    title="Detail">
                                                                    👁
                                                                </a>


                                                                {{-- EDIT --}}
                                                                <a href="{{ route('pendonor.edit', $pendonor->id) }}" class="admin-action-btn edit"
                                                                    title="Edit">
                                                                    ✎
                                                                </a>


                                                                @if (
                                                                    $pendonor->pendaftarans_count === 0
                                                                    && $pendonor->pemeriksaans_count === 0
                                                                    && $pendonor->riwayat_donor_count === 0
                                                                    && $pendonor->stok_darahs_count === 0
                                                                )
                                                                    {{-- HAPUS --}}
                                                                    <form action="{{ route('pendonor.destroy', $pendonor->id) }}" method="POST"
                                                                        class="d-inline"
                                                                        onsubmit="return confirm('Yakin ingin menghapus data pendonor ini?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="admin-action-btn delete" title="Hapus">
                                                                            ×
                                                                        </button>
                                                                    </form>
                                                                @endif


                                                            </div>

                                                        </td>

                                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                    BELUM ADA DATA
                    ================================================== --}}
                @else

                    <div class="admin-empty-state">

                        <div class="admin-empty-icon">
                            ♙
                        </div>

                        <strong>
                            Belum ada data pendonor
                        </strong>

                        <span>
                            Data pendonor akan muncul di halaman ini.
                        </span>

                    </div>

                @endif

            </div>


            {{-- =================================================
            FOOTER
            ================================================== --}}
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