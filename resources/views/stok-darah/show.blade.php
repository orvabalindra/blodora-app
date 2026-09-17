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
                    <a
                        href="{{ route('stok-darah.edit', $stokDarah) }}"
                        class="admin-btn-primary"
                    >
                        Edit
                    </a>


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


                {{-- PENDONOR --}}
                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Pendonor
                    </span>

                    <strong class="admin-detail-value">
                        {{ $stokDarah->pendonor?->nama_lengkap ?? 'Tidak ditemukan' }}
                    </strong>

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


            </div>


            {{-- DELETE --}}
            <div class="admin-form-actions">

                <form
                    action="{{ route('stok-darah.destroy', $stokDarah) }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus data stok darah ini?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="admin-btn-secondary"
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