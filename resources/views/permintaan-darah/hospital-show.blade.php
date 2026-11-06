@extends('layouts.app')

@section('title', 'Detail Permintaan Darah')

@section('content')
<div class="admin-dashboard">
    @include('components.hospital-sidebar')
    <main class="admin-main">
        <div class="admin-topbar"><div><span class="admin-page-label">PORTAL RUMAH SAKIT</span><h1>Detail Permintaan</h1><p>Status dan riwayat penyaluran permintaan rumah sakit Anda.</p></div></div>
        <div class="admin-panel">
            <div class="admin-panel-header"><div><span class="admin-section-label">PERMINTAAN #{{ $permintaanDarah->id_permintaan_darah }}</span><h3>{{ $permintaanDarah->golongan_darah }}{{ $permintaanDarah->rhesus }}</h3></div><a href="{{ route('faskes.permintaan.index') }}" class="admin-btn-secondary">Kembali</a></div>
            <div class="admin-detail-grid">
                <div class="admin-detail-item"><span class="admin-detail-label">Tanggal pengajuan</span><strong class="admin-detail-value">{{ $permintaanDarah->tanggal_permintaan?->translatedFormat('d F Y') ?? '-' }}</strong></div>
                <div class="admin-detail-item"><span class="admin-detail-label">Tingkat kebutuhan</span><strong class="admin-detail-value">{{ ucfirst($permintaanDarah->tingkat_kebutuhan) }}</strong></div>
                <div class="admin-detail-item"><span class="admin-detail-label">Status</span>
                    @if ($permintaanDarah->status === 'diproses')
                        <strong class="admin-detail-value">Menunggu keputusan admin</strong>
                    @elseif ($permintaanDarah->status === 'ditolak')
                        <strong class="admin-detail-value">Ditolak</strong>
                    @elseif ($permintaanDarah->jumlah_terpenuhi >= $permintaanDarah->jumlah_minta)
                        <strong class="admin-detail-value">Terpenuhi</strong>
                    @elseif ($permintaanDarah->jumlah_terpenuhi > 0)
                        <strong class="admin-detail-value">Sebagian terpenuhi</strong>
                    @else
                        <strong class="admin-detail-value">Disetujui, menunggu penyaluran</strong>
                    @endif
                </div>
                <div class="admin-detail-item"><span class="admin-detail-label">Jumlah terpenuhi</span><strong class="admin-detail-value">{{ $permintaanDarah->jumlah_terpenuhi }} dari {{ $permintaanDarah->jumlah_minta }} kantong</strong></div>
            </div>
            @if ($permintaanDarah->penyalurans->isNotEmpty())
                <div class="admin-panel-header mt-4"><div><span class="admin-section-label">RIWAYAT</span><h3>Penyaluran Darah</h3></div></div>
                <div class="table-responsive"><table class="table admin-data-table"><thead><tr><th>Tanggal</th><th>Jumlah</th></tr></thead><tbody>@foreach ($permintaanDarah->penyalurans as $penyaluran)<tr><td>{{ $penyaluran->diserahkan_pada->translatedFormat('d M Y H:i') }}</td><td>{{ $penyaluran->jumlah_kantong }} kantong</td></tr>@endforeach</tbody></table></div>
            @endif
        </div>
        <div class="admin-footer"><span>Portal Rumah Sakit Blodora</span><span>Kontak admin: {{ config('app.name', 'Blodora') }}</span></div>
    </main>
</div>
@endsection
