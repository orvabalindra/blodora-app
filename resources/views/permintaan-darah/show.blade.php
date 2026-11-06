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
                        Detail Permintaan Darah
                    </h3>

                    <p>
                        Detail permintaan darah fasilitas kesehatan.
                    </p>

                </div>

                <div class="admin-page-actions">

                    <a
                        href="{{ route('permintaan-darah.index') }}"
                        class="admin-btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </div>

            @if (session('success'))
                <div class="admin-form-alert admin-form-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="admin-form-alert admin-form-alert-danger">
                    {{ session('warning') }}
                </div>
            @endif

            @if (session('info'))
                <div class="admin-form-alert">
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="admin-form-alert admin-form-alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($permintaanDarah->status !== 'ditolak' && $sisaPermintaan > $stokCocokTersedia)
                <div class="admin-stock-shortage-notice" role="status">
                    <strong>Stok belum mencukupi.</strong>
                    Untuk {{ $permintaanDarah->golongan_darah }}{{ $permintaanDarah->rhesus }} tersedia {{ $stokCocokTersedia }} kantong layak, sementara masih dibutuhkan {{ $sisaPermintaan }} kantong. Hubungi rumah sakit untuk menyampaikan ketersediaan dan perkiraan tindak lanjut.
                    @if ($permintaanDarah->rumahSakit?->no_telp)
                        <div class="mt-2"><a class="admin-btn-secondary" href="tel:{{ preg_replace('/[^0-9+]/', '', $permintaanDarah->rumahSakit->no_telp) }}"><i class="bi bi-telephone" aria-hidden="true"></i> Hubungi {{ $permintaanDarah->rumahSakit->no_telp }}</a></div>
                    @endif
                </div>
            @elseif ($permintaanDarah->status === 'diterima')
                <div class="admin-stock-available-notice" role="status">
                    Stok cocok tersedia: {{ $stokCocokTersedia }} kantong. Kebutuhan yang tersisa {{ $sisaPermintaan }} kantong.
                </div>
            @endif


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
                        <a
                            href="{{ route('rumah-sakit.show', $permintaanDarah->rumahSakit) }}"
                            class="admin-table-link"
                        >
                            Lihat data faskes
                        </a>
                    @endif

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
                        @if ($permintaanDarah->rumahSakit->no_telp)
                            <a class="admin-table-link" href="tel:{{ preg_replace('/[^0-9+]/', '', $permintaanDarah->rumahSakit->no_telp) }}">Telepon rumah sakit</a>
                        @endif

                    @endif

                </div>

                <div class="admin-detail-item">
                    <span class="admin-detail-label">Tingkat Kebutuhan</span>
                    <span class="badge {{ $permintaanDarah->tingkat_kebutuhan === 'darurat' ? 'text-bg-danger' : ($permintaanDarah->tingkat_kebutuhan === 'mendesak' ? 'text-bg-warning' : 'text-bg-light') }}">{{ ucfirst($permintaanDarah->tingkat_kebutuhan === 'normal' ? 'terencana' : $permintaanDarah->tingkat_kebutuhan) }}</span>
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

                <div class="admin-detail-item">

                    <span class="admin-detail-label">
                        Progres Penyerahan
                    </span>

                    <strong class="admin-detail-value">
                        {{ $permintaanDarah->jumlah_terpenuhi }} dari
                        {{ $permintaanDarah->jumlah_minta }} kantong
                    </strong>

                    <span class="admin-detail-sub-label">
                        Sisa {{ max($permintaanDarah->jumlah_minta - $permintaanDarah->jumlah_terpenuhi, 0) }} kantong
                    </span>

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

                    @elseif ($permintaanDarah->status === 'ditolak')

                        <span class="admin-status-pill danger">
                            Ditolak
                        </span>

                    @elseif ($permintaanDarah->jumlah_terpenuhi >= $permintaanDarah->jumlah_minta)

                        <span class="admin-status-pill success">
                            Terpenuhi
                        </span>

                    @elseif ($permintaanDarah->jumlah_terpenuhi > 0)

                        <span class="admin-status-pill warning">
                            Sebagian disalurkan
                        </span>

                    @else

                        <span class="admin-status-pill success">
                            Disetujui, menunggu penyerahan
                        </span>

                    @endif

                </div>

            </div>

            @if ($permintaanDarah->penyalurans->isNotEmpty())
                <div class="admin-panel-header">
                    <div>
                        <span class="admin-section-label">RIWAYAT</span>
                        <h3>Penyerahan Darah</h3>
                        <p>Batch stok yang digunakan untuk memenuhi permintaan ini.</p>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table class="admin-data-table">
                        <thead>
                            <tr>
                                <th>Tanggal Penyerahan</th>
                                <th>Batch Stok</th>
                                <th>Kedaluwarsa</th>
                                <th>Jumlah</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permintaanDarah->penyalurans as $penyaluran)
                                <tr>
                                    <td>{{ $penyaluran->diserahkan_pada->format('d M Y H:i') }}</td>
                                    <td>
                                        @if ($penyaluran->stokDarah)
                                            <a
                                                href="{{ route('stok-darah.show', $penyaluran->stokDarah) }}"
                                                class="admin-table-link"
                                            >
                                                Stok {{ $penyaluran->stokDarah->golongan_darah }}{{ $penyaluran->stokDarah->rhesus }}
                                            </a>
                                        @else
                                            Stok tidak tersedia
                                        @endif
                                    </td>
                                    <td>{{ $penyaluran->stokDarah?->tanggal_kadaluarsa?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $penyaluran->jumlah_kantong }} kantong</td>
                                    <td>{{ $penyaluran->diserahkanOleh?->username ?? 'Petugas tidak tersedia' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif


            {{-- ACTION --}}
            <div class="admin-form-actions admin-request-actions {{ $permintaanDarah->status === 'diterima' ? 'is-delivery' : 'is-decision' }}">

                <a
                    href="{{ route('permintaan-darah.index') }}"
                    class="admin-btn-secondary"
                >
                    Kembali
                </a>


                @if ($permintaanDarah->status === 'diproses')
                    <form action="{{ route('permintaan-darah.status', $permintaanDarah) }}" method="POST" data-swal-confirm="Permintaan akan disetujui. Stok berkurang setelah penyerahan dicatat." data-swal-title="Setujui permintaan darah?" data-swal-icon="question" data-swal-confirm-button="Ya, setujui">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="diterima">
                        <button type="submit" class="admin-btn-primary">
                            Setujui Permintaan
                        </button>
                    </form>

                    <form
                        action="{{ route('permintaan-darah.status', $permintaanDarah) }}"
                        method="POST"
                        data-swal-confirm="Permintaan darah ini akan ditolak." data-swal-title="Tolak permintaan darah?" data-swal-confirm-button="Ya, tolak"
                    >
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" class="admin-btn-secondary">
                            Tolak
                        </button>
                    </form>
                @elseif ($permintaanDarah->status === 'diterima' && $permintaanDarah->jumlah_terpenuhi < $permintaanDarah->jumlah_minta)
                    <form
                        action="{{ route('permintaan-darah.penyaluran', $permintaanDarah) }}"
                        method="POST"
                        data-swal-confirm="Penyerahan dicatat dan stok akan dikurangi sesuai jumlah kantong." data-swal-title="Catat penyerahan darah?" data-swal-icon="question" data-swal-confirm-button="Ya, catat penyerahan"
                    >
                        @csrf
                        <div class="admin-form-group">
                            <label for="jumlah_kantong" class="admin-form-label">
                                Jumlah yang benar-benar diserahkan
                            </label>
                            <div class="admin-input-unit">
                                <input
                                    type="number"
                                    name="jumlah_kantong"
                                    id="jumlah_kantong"
                                    class="admin-form-control"
                                    min="1"
                                    max="{{ $permintaanDarah->jumlah_minta - $permintaanDarah->jumlah_terpenuhi }}"
                                    value="{{ old('jumlah_kantong', $permintaanDarah->jumlah_minta - $permintaanDarah->jumlah_terpenuhi) }}"
                                    required
                                >
                                <span class="admin-input-unit-label">kantong</span>
                            </div>
                        </div>
                        <p>Stok yang dipakai harus memiliki golongan ABO dan rhesus yang sama serta belum kedaluwarsa.</p>
                        <button type="submit" class="admin-btn-primary">
                            Catat Penyerahan
                        </button>
                    </form>
                @endif

                @if ($permintaanDarah->status === 'diproses' && $permintaanDarah->penyalurans->isEmpty())
                    <form
                        action="{{ route('permintaan-darah.destroy', $permintaanDarah) }}"
                        method="POST"
                        data-swal-confirm="Permintaan darah ini akan dihapus." data-swal-title="Hapus permintaan darah?" data-swal-confirm-button="Ya, hapus"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn-secondary">
                            Hapus
                        </button>
                    </form>
                @endif

            </div>

        </div>


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
