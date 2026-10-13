@extends('layouts.app')

@section('title', 'Pendaftaran Donor Saya')

@section('content')
<div class="donor-dashboard">
    @include('components.donor-sidebar')

    <main class="donor-main">
        <div class="donor-topbar">
            <div>
                <span class="donor-page-label">PENDAFTARAN DONOR</span>
                <h1>Pendaftaran Saya</h1>
                <p>Informasi ini hanya menampilkan pendaftaran milik akun Anda.</p>
            </div>
            <div class="donor-topbar-right">
                <div class="donor-date">
                    <span>Hari ini</span>
                    <strong>{{ now()->translatedFormat('d F Y') }}</strong>
                </div>
                <div class="donor-top-avatar">
                    {{ strtoupper(substr(Auth::user()->username ?? 'P', 0, 1)) }}
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3">{{ $errors->first() }}</div>
        @endif

        <section class="donor-panel">
            <div class="donor-panel-header">
                <div>
                    <span class="donor-section-label">RIWAYAT PENDAFTARAN</span>
                    <h3>Daftar pendaftaran donor</h3>
                    <p>Pantau status pendaftaran dan batalkan selama masih menunggu screening.</p>
                </div>
                <a href="{{ route('donor.jadwal') }}" class="donor-btn-primary">Lihat Jadwal</a>
            </div>

            <div class="table-responsive">
                <table class="table admin-data-table align-middle">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftarans as $pendaftaran)
                            <tr>
                                <td><strong>{{ $pendaftaran->jadwalDonor?->nama_kegiatan ?? 'Jadwal dihapus' }}</strong></td>
                                <td>
                                    {{ $pendaftaran->jadwalDonor?->tanggal?->translatedFormat('d M Y') ?? '—' }}
                                    @if ($pendaftaran->jadwalDonor)
                                        <br>
                                        {{ substr($pendaftaran->jadwalDonor->waktu_mulai, 0, 5) }}–{{ substr($pendaftaran->jadwalDonor->waktu_selesai, 0, 5) }}
                                    @endif
                                </td>
                                <td>
                                    <span class="donor-stat-tag">
                                        {{ str_replace('_', ' ', ucfirst($pendaftaran->status)) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if ($pendaftaran->status === 'menunggu_screening')
                                        <form method="POST" action="{{ route('donor.pendaftaran.destroy', $pendaftaran) }}" onsubmit="return confirm('Batalkan pendaftaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="donor-btn-secondary" type="submit">Batalkan</button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="donor-empty-state compact">
                                        <div class="donor-empty-icon">↻</div>
                                        <strong>Belum ada pendaftaran</strong>
                                        <span>Pendaftaran kegiatan donor yang kamu ikuti akan muncul di sini.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (method_exists($pendaftarans, 'links'))
                {{ $pendaftarans->links() }}
            @endif
        </section>

        <div class="donor-footer">
            <span>Blodora</span>
            <span>Sistem Donor Darah</span>
        </div>
    </main>
</div>
@endsection
