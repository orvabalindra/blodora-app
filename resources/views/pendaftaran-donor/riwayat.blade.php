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
                <p>Ikuti tahapan pendaftaran sampai donor selesai. Petugas akan memperbarui status setelah screening dan kegiatan.</p>
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
                                    @php
                                        $jadwalDonor = $pendaftaran->jadwalDonor;
                                        $waktuMulaiScreening = $jadwalDonor?->tanggal?->copy()->setTimeFromTimeString($jadwalDonor->waktu_mulai);
                                        $screeningDimulai = $waktuMulaiScreening && now()->greaterThanOrEqualTo($waktuMulaiScreening);
                                        $labelStatus = $pendaftaran->persetujuan_admin === 'menunggu'
                                            ? 'Menunggu persetujuan admin'
                                            : ($pendaftaran->persetujuan_admin === 'ditolak' ? 'Pengajuan ditolak' : ([
                                            'menunggu_screening' => $screeningDimulai ? 'Proses screening' : 'Terdaftar',
                                            'ditunda' => 'Screening ditunda',
                                            'proses_donor' => 'Lolos screening',
                                            'selesai' => 'Donor selesai',
                                            'gagal_screening' => 'Belum layak donor',
                                            'tidak_hadir' => 'Tidak hadir',
                                            'dibatalkan' => 'Dibatalkan',
                                        ][$pendaftaran->status] ?? ucwords(str_replace('_', ' ', $pendaftaran->status))));
                                    @endphp
                                    <span class="donor-stat-tag">{{ $labelStatus }}</span>
                                    @if ($pendaftaran->persetujuan_admin === 'menunggu')
                                        <div class="small text-muted mt-1">Pengajuan sudah dikirim dan sedang menunggu keputusan admin.</div>
                                    @elseif ($pendaftaran->persetujuan_admin === 'ditolak')
                                        <div class="small text-muted mt-1">Alasan admin: {{ $pendaftaran->catatan_admin }}</div>
                                    @elseif ($pendaftaran->status === 'selesai')
                                        <div class="small text-success mt-1">Donor tercatat. Kantong darah sudah ditambahkan ke stok.</div>
                                    @elseif ($pendaftaran->status === 'proses_donor')
                                        <div class="small text-muted mt-1">Datang sesuai jadwal untuk menyelesaikan donor.</div>
                                    @elseif ($pendaftaran->status === 'menunggu_screening')
                                        <div class="small text-muted mt-1">{{ $screeningDimulai ? 'Petugas sedang memproses screening.' : 'Anda terdaftar. Screening dilakukan saat jadwal donor dimulai.' }}</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if (in_array($pendaftaran->persetujuan_admin, ['menunggu', 'disetujui'], true) && $pendaftaran->status === 'menunggu_screening' && ! $screeningDimulai)
                                        <form method="POST" action="{{ route('donor.pendaftaran.destroy', $pendaftaran) }}" onsubmit="return confirm('Batalkan pendaftaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="donor-btn-secondary" type="submit">Batalkan pengajuan</button>
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
