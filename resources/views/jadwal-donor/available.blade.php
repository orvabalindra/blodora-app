@extends('layouts.app')

@section('title', 'Pilih Jadwal Donor')

@section('content')
<div class="donor-dashboard">
    @include('components.donor-sidebar')

    <main class="donor-main">
        <div class="donor-topbar">
            <div>
                <span class="donor-page-label">KEGIATAN DONOR</span>
                <h1>Jadwal Donor</h1>
                <p>Pilih kegiatan untuk mengajukan pendaftaran. Petugas UTD akan melakukan screening sebelum donor.</p>
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

        @if (session('info'))
            <div class="alert alert-info border-0 rounded-3">{{ session('info') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3">{{ $errors->first() }}</div>
        @endif

        @if ($butuhProfil)
            <section class="donor-panel">
                <div class="donor-panel-header">
                    <div>
                        <span class="donor-section-label">PROFIL PENDONOR</span>
                        <h3>Lengkapi profil sebelum mendaftar</h3>
                        <p>Data profil diperlukan oleh petugas untuk memproses pendaftaran dan screening donor.</p>
                    </div>
                </div>
                <a href="{{ route('pendonor.profile.create') }}" class="donor-btn-primary">Lengkapi Profil</a>
            </section>
        @else
            <section class="donor-panel">
                <div class="donor-panel-header">
                    <div>
                        <span class="donor-section-label">JADWAL TERSEDIA</span>
                        <h3>Pilih kegiatan donor</h3>
                        <p>Pastikan waktu dan lokasi sesuai sebelum mengirim pendaftaran.</p>
                    </div>
                    <a href="{{ route('donor.pendaftaran.index') }}" class="donor-btn-secondary">Pendaftaran Saya</a>
                </div>

                <div class="row g-3">
                    @forelse ($jadwalDonors as $jadwalDonor)
                        @php($pendaftaran = $pendaftaranByJadwal->get($jadwalDonor->id))
                        @php($tersedia = $jadwalDonor->kuota - $jadwalDonor->pendaftar_aktif_count)

                        <div class="col-md-6 col-xl-4">
                            <article class="donor-panel h-100">
                                <div class="donor-panel-header">
                                    <div>
                                        <span class="donor-section-label">KEGIATAN DONOR</span>
                                        <h3>{{ $jadwalDonor->nama_kegiatan }}</h3>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <div class="donor-detail-card">
                                        <span>Tanggal dan waktu</span>
                                        <strong>{{ $jadwalDonor->tanggal->translatedFormat('l, d F Y') }}</strong>
                                        <span class="mt-1">
                                            {{ substr($jadwalDonor->waktu_mulai, 0, 5) }}–{{ substr($jadwalDonor->waktu_selesai, 0, 5) }}
                                        </span>
                                    </div>

                                    <div class="donor-detail-card">
                                        <span>Lokasi</span>
                                        <strong>{{ $jadwalDonor->lokasi }}</strong>
                                    </div>

                                    <div class="donor-detail-card">
                                        <span>Kuota tersedia</span>
                                        <strong>{{ max(0, $tersedia) }} <small>dari {{ $jadwalDonor->kuota }} pendaftar</small></strong>
                                    </div>
                                </div>

                                @if ($pendaftaran && $pendaftaran->status !== 'dibatalkan')
                                    <div class="alert alert-secondary mt-3 mb-0">
                                        Status pendaftaran:
                                        <strong>{{ str_replace('_', ' ', ucfirst($pendaftaran->status)) }}</strong>
                                    </div>
                                @elseif ($tersedia > 0)
                                    <form method="POST" action="{{ route('donor.pendaftaran.store', $jadwalDonor) }}">
                                        @csrf
                                        <button class="donor-btn-primary donor-full-btn" type="submit">Daftar Kegiatan</button>
                                    </form>
                                @else
                                    <button class="donor-btn-secondary donor-full-btn opacity-50" type="button" disabled>Kuota penuh</button>
                                @endif
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="donor-empty-state compact">
                                <div class="donor-empty-icon">♡</div>
                                <strong>Belum ada jadwal donor</strong>
                                <span>Jadwal kegiatan donor yang akan datang akan ditampilkan di sini.</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>
        @endif

        <div class="donor-footer">
            <span>Blodora</span>
            <span>Sistem Donor Darah</span>
        </div>
    </main>
</div>
@endsection
