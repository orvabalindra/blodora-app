@extends('layouts.app')

@section('title', 'Jadwal Donor')

@section('content')
<div class="admin-dashboard">
    @include('components.admin-sidebar')

    <main class="admin-main">
        <div class="admin-topbar">
            <div>
                <span class="admin-page-label">KEGIATAN DONOR</span>
                <h1>Jadwal Donor</h1>
                <p>Kelola jadwal, lokasi, dan kuota kegiatan donor.</p>
            </div>
            <div class="admin-topbar-right">
                <div class="admin-date">
                    <span>Hari ini</span>
                    <strong>{{ now()->translatedFormat('d F Y') }}</strong>
                </div>
                <div class="admin-top-avatar">
                    {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3">{{ session('success') }}</div>
        @endif

        <div class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <span class="admin-section-label">KEGIATAN DONOR</span>
                    <h3>Daftar Jadwal Donor</h3>
                    <p>Total {{ $jadwalDonors->total() }} jadwal kegiatan.</p>
                </div>
                <a href="{{ route('jadwal-donor.create') }}" class="admin-btn-primary">+ Tambah Jadwal</a>
            </div>

            <div class="table-responsive">
                <table class="table admin-data-table align-middle">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Waktu</th>
                            <th>Lokasi</th>
                            <th>Pendaftar</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalDonors as $jadwalDonor)
                            <tr>
                                <td><strong>{{ $jadwalDonor->nama_kegiatan }}</strong></td>
                                <td>
                                    {{ $jadwalDonor->tanggal->translatedFormat('d M Y') }}<br>
                                    {{ substr($jadwalDonor->waktu_mulai, 0, 5) }}–{{ substr($jadwalDonor->waktu_selesai, 0, 5) }}
                                </td>
                                <td>{{ $jadwalDonor->lokasi }}</td>
                                <td>{{ $jadwalDonor->pendaftar_aktif_count }} / {{ $jadwalDonor->kuota }}</td>
                                <td class="text-end">
                                    <a href="{{ route('jadwal-donor.edit', $jadwalDonor) }}" class="admin-btn-secondary">Ubah</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="admin-empty-state">
                                        <strong>Belum ada jadwal donor</strong>
                                        <span>Buat jadwal donor baru agar pendonor dapat mendaftar.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $jadwalDonors->links() }}
        </div>

        <div class="admin-footer">
            <span>Blodora Admin Panel</span>
            <span>Sistem Donor Darah</span>
        </div>
    </main>
</div>
@endsection
