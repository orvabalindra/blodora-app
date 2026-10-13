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

            @if ($errors->any())
                <div class="admin-form-alert admin-form-alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif


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


                @if ($permintaanDarahs->isEmpty())
                    <form
                        action="{{ route('rumah-sakit.destroy', $rumahSakit) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data Faskes ini?')"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn-primary">Hapus</button>
                    </form>
                @endif

            </div>

        </div>

        <div class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <span class="admin-section-label">PERMINTAAN DARAH</span>
                    <h3>Permintaan dari Faskes Ini</h3>
                    <p>Lima permintaan terbaru yang tercatat untuk fasilitas kesehatan ini.</p>
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Golongan</th>
                            <th>Jumlah</th>
                            <th>Disalurkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permintaanDarahs as $permintaan)
                            <tr>
                                <td>{{ $permintaan->tanggal_permintaan?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $permintaan->golongan_darah }}{{ $permintaan->rhesus }}</td>
                                <td>{{ $permintaan->jumlah_minta }} kantong</td>
                                <td>{{ $permintaan->jumlah_terpenuhi }} kantong</td>
                                <td>
                                    <a
                                        href="{{ route('permintaan-darah.show', $permintaan) }}"
                                        class="admin-table-link"
                                    >
                                        Detail permintaan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Faskes ini belum memiliki permintaan darah.</td>
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