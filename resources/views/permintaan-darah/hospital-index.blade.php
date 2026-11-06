@extends('layouts.app')

@section('title', 'Permintaan Darah Rumah Sakit')

@section('content')
<div class="admin-dashboard">
    @include('components.hospital-sidebar')
    <main class="admin-main">
        <div class="admin-topbar">
            <div>
                <span class="admin-page-label">PORTAL RUMAH SAKIT</span>
                <h1>{{ $rumahSakit->nama_rs }}</h1>
                <p>Ajukan kebutuhan darah dan pantau proses penanganannya.</p>
            </div>
            <div class="admin-page-actions"><a href="{{ route('faskes.permintaan.create') }}" class="admin-btn-primary">+ Ajukan Permintaan</a></div>
        </div>

        <div class="admin-panel">
            <div class="admin-panel-header"><div><span class="admin-section-label">RIWAYAT PENGAJUAN</span><h3>Permintaan Darah</h3><p>Nomor kontak rumah sakit: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $rumahSakit->no_telp) }}">{{ $rumahSakit->no_telp }}</a></p></div></div>
            @include('components.admin-list-search', ['action' => route('faskes.permintaan.index'), 'items' => $permintaanDarahs, 'placeholder' => 'Cari golongan, tingkat kebutuhan, atau status'])
            @if ($permintaanDarahs->isEmpty())
                <div class="admin-empty-state"><h3>Belum ada permintaan</h3><p>Ajukan kebutuhan darah pertama dari tombol di atas.</p></div>
            @else
                <div class="table-responsive">
                    <table class="table admin-data-table align-middle">
                        <thead><tr><th>Tanggal</th><th>Golongan</th><th>Tingkat kebutuhan</th><th>Diminta</th><th>Terpenuhi</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($permintaanDarahs as $permintaan)
                                <tr>
                                    <td>{{ $permintaan->tanggal_permintaan?->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td><span class="admin-blood-badge">{{ $permintaan->golongan_darah }}{{ $permintaan->rhesus }}</span></td>
                                    <td><span class="badge {{ $permintaan->tingkat_kebutuhan === 'darurat' ? 'text-bg-danger' : ($permintaan->tingkat_kebutuhan === 'mendesak' ? 'text-bg-warning' : 'text-bg-light') }}">{{ ucfirst($permintaan->tingkat_kebutuhan) }}</span></td>
                                    <td>{{ $permintaan->jumlah_minta }} kantong</td>
                                    <td>{{ $permintaan->jumlah_terpenuhi }} kantong</td>
                                    <td>
                                        @if ($permintaan->status === 'diproses')
                                            <span class="admin-status-pill warning">Menunggu keputusan</span>
                                        @elseif ($permintaan->status === 'ditolak')
                                            <span class="admin-status-pill danger">Ditolak</span>
                                        @elseif ($permintaan->jumlah_terpenuhi >= $permintaan->jumlah_minta)
                                            <span class="admin-status-pill success">Terpenuhi</span>
                                        @elseif ($permintaan->jumlah_terpenuhi > 0)
                                            <span class="admin-status-pill warning">Sebagian terpenuhi</span>
                                        @else
                                            <span class="admin-status-pill success">Disetujui</span>
                                        @endif
                                    </td>
                                    <td><a class="admin-action-btn view" href="{{ route('faskes.permintaan.show', $permintaan) }}" aria-label="Lihat permintaan" title="Lihat permintaan"><i class="bi bi-eye" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $permintaanDarahs->links() }}
            @endif
        </div>
        <div class="admin-footer"><span>Portal Rumah Sakit Blodora</span><span>Jika kebutuhan darurat, pastikan nomor telepon kontak selalu aktif.</span></div>
    </main>
</div>
@endsection
