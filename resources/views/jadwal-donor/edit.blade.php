@extends('layouts.app')

@section('title', 'Ubah Jadwal Donor')

@section('content')
<div class="admin-dashboard">
    @include('components.admin-sidebar')

    <main class="admin-main">
        <div class="admin-topbar">
            <div>
                <span class="admin-page-label">KEGIATAN DONOR</span>
                <h1>Ubah Jadwal Donor</h1>
                <p>Perbarui informasi kegiatan, waktu pelaksanaan, dan kuota pendonor.</p>
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

        @include('jadwal-donor.form', ['jadwalDonor' => $jadwalDonor])

        <div class="admin-footer">
            <span>Blodora Admin Panel</span>
            <span>Sistem Donor Darah</span>
        </div>
    </main>
</div>
@endsection
