@extends('layouts.app')

@section('title', 'Stok Darah')

@section('content')

    <div class="admin-dashboard">

        {{-- SIDEBAR --}}
        @include('components.admin-sidebar')


        {{-- MAIN --}}
        <main class="admin-main">

            {{-- HEADER --}}
            <div class="admin-topbar">

                <div>

                    <span class="admin-page-label">
                        DATA MASTER
                    </span>

                    <h1>
                        Stok Darah
                    </h1>

                    <p>
                        Kelola ketersediaan stok darah Blodora.
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
                            STOK DARAH
                        </span>

                        <h3>
                            Daftar Stok Darah
                        </h3>

                        <p>
                            Total {{ $stokDarah->count() }} data stok.
                        </p>

                    </div>

                    <a href="{{ route('stok-darah.create') }}" class="btn btn-danger">
                        + Tambah Stok
                    </a>

                </div>


                {{-- SUCCESS --}}
                @if(session('success'))

                    <div class="alert alert-success border-0 rounded-3 mb-4">
                        {{ session('success') }}
                    </div>

                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif


                {{-- DATA --}}
                @if($stokDarah->count() > 0)

                    <div class="table-responsive">

                        <table class="table admin-data-table align-middle">

                            <thead>

                                <tr>

                                    <th>NO</th>
                                    <th>PENDONOR</th>
                                    <th>GOLONGAN</th>
                                    <th>RHESUS</th>
                                    <th>JUMLAH</th>
                                    <th>TANGGAL MASUK</th>
                                    <th>KADALUARSA</th>
                                    <th class="text-end">AKSI</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($stokDarah as $index => $stok)

                                                    <tr>

                                                        <td>
                                                            {{ $index + 1 }}
                                                        </td>

                                                        <td>

                                                            <div class="admin-donor-name">

                                                                <div class="admin-donor-avatar">
                                                                    {{ strtoupper(
                                        substr(
                                            $stok->pendonor->nama_lengkap ?? 'P',
                                            0,
                                            1
                                        )
                                    ) }}
                                                                </div>

                                                                <strong>
                                                                    {{ $stok->pendonor->nama_lengkap ?? 'Pendonor tidak ditemukan' }}
                                                                </strong>

                                                            </div>

                                                        </td>

                                                        <td>

                                                            <span class="admin-blood-badge">
                                                                {{ $stok->golongan_darah }}
                                                            </span>

                                                        </td>

                                                        <td>
                                                            {{ $stok->rhesus }}
                                                        </td>

                                                        <td>
                                                            {{ $stok->jumlah_kantong }} kantong
                                                        </td>

                                                        <td>
                                                            {{ $stok->tanggal_masuk->format('d/m/Y') }}
                                                        </td>

                                                        <td>
                                                            {{ $stok->tanggal_kadaluarsa->format('d/m/Y') }}
                                                        </td>

                                                        <td>

                                                            <div class="admin-action-buttons">

                                                                {{-- DETAIL --}}
                                                                <a href="{{ route('stok-darah.show', $stok->id_stok_darah) }}"
                                                                    class="admin-action-btn view" title="Detail">
                                                                    👁
                                                                </a>

                                                                @if ($stok->pendaftaran_id === null && $stok->penyalurans->isEmpty())
                                                                    {{-- EDIT --}}
                                                                    <a href="{{ route('stok-darah.edit', $stok->id_stok_darah) }}"
                                                                        class="admin-action-btn edit" title="Edit">
                                                                        ✎
                                                                    </a>

                                                                    {{-- HAPUS --}}
                                                                    <form action="{{ route('stok-darah.destroy', $stok->id_stok_darah) }}" method="POST"
                                                                        class="d-inline"
                                                                        onsubmit="return confirm('Yakin ingin menghapus stok darah ini?')">
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

                @else

                    <div class="admin-empty-state">

                        <div class="admin-empty-icon">
                            +
                        </div>

                        <strong>
                            Belum ada stok darah
                        </strong>

                        <span>
                            Tambahkan stok darah untuk mulai mengelola persediaan.
                        </span>

                    </div>

                @endif

            </div>


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