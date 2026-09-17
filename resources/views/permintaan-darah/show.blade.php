@extends('layouts.app')

@section('title', 'Detail Permintaan Darah')

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
                    Detail Permintaan Darah
                </h1>

                <p>
                    Informasi lengkap permintaan darah dari fasilitas kesehatan.
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

            {{-- HEADER --}}
            <div class="admin-panel-header">

                <div>

                    <span class="admin-section-label">
                        DETAIL PERMINTAAN
                    </span>

                    <h3>
                        Permintaan #{{ $permintaanDarah->id_permintaan_darah }}
                    </h3>

                    <p>
                        Detail permintaan darah fasilitas kesehatan.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('permintaan-darah.edit', $permintaanDarah) }}"
                        class="admin-btn-primary"
                    >
                        Edit
                    </a>

                    <a
                        href="{{ route('permintaan-darah.index') }}"
                        class="admin-btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </div>


            {{-- DETAIL --}}
            <div class="admin-detail-grid">


                {{-- FASKES --}}
                <div class="admin-detail-item full-width">

                    <span class="admin-detail-label">
                        FASKES
                    </span>

                    <strong class="admin-detail-value">
                        {{ $permintaanDarah->rumahSakit?->nama_rs ?? 'Faskes tidak ditemukan' }}
                    </strong>

                    @if ($permintaanDarah->rumahSakit)

                        <span class="admin-detail-sub-label">
                            Alamat
                        </span>

                        <strong class="admin-detail-value">
                            {{ $permintaanDarah->rumahSakit->alamat ?? '-' }}
                        </strong>

                        <span class="admin-detail-sub-label">
                            Nomor Telepon
                        </span>

                        <strong class="admin-detail-value">
                            {{ $permintaanDarah->rumahSakit->no_telp ?? '-' }}
                        </strong>

                    @endif

                </div>


                {{-- TANGGAL PERMINTAAN --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Tanggal Permintaan
                    </span>

                    <strong class="admin-detail-value">
                        {{
                            $permintaanDarah->tanggal_permintaan
                                ? $permintaanDarah->tanggal_permintaan->format('d F Y')
                                : '-'
                        }}
                    </strong>

                </div>


                {{-- GOLONGAN DARAH --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Golongan Darah
                    </span>

                    <strong class="admin-detail-value blood">
                        {{ $permintaanDarah->golongan_darah }}{{ $permintaanDarah->rhesus }}
                    </strong>

                </div>


                {{-- JUMLAH --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Jumlah Diminta
                    </span>

                    <strong class="admin-detail-value">
                        {{ $permintaanDarah->jumlah_minta }} kantong
                    </strong>

                </div>


                {{-- STATUS --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Status Permintaan
                    </span>


                    @if ($permintaanDarah->status === 'diproses')

                        <span class="admin-status-pill warning">
                            Diproses
                        </span>

                    @elseif ($permintaanDarah->status === 'diterima')

                        <span class="admin-status-pill success">
                            Diterima
                        </span>

                    @else

                        <span class="admin-status-pill danger">
                            Ditolak
                        </span>

                    @endif

                </div>

            </div>


            {{-- ACTION --}}
            <div class="admin-form-actions">

                <a
                    href="{{ route('permintaan-darah.index') }}"
                    class="admin-btn-secondary"
                >
                    Kembali
                </a>


                <form
                    action="{{ route('permintaan-darah.destroy', $permintaanDarah) }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus permintaan darah ini?')"
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