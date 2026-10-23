@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="donor-dashboard">
    @include('components.donor-sidebar')

    <main class="donor-main">
        <div class="donor-topbar">
            <div>
                <span class="donor-page-label">PEMBARUAN AKUN</span>
                <h1>Notifikasi</h1>
                <p>Informasi terbaru tentang pengajuan dan proses donor kamu.</p>
            </div>
            <div class="donor-topbar-right">
                <div class="donor-top-avatar">{{ strtoupper(substr(Auth::user()->username ?? 'P', 0, 1)) }}</div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3">{{ session('success') }}</div>
        @endif

        <section class="donor-panel">
            <div class="donor-panel-header">
                <div>
                    <span class="donor-section-label">NOTIFIKASI STATUS</span>
                    <h3>Aktivitas terbaru</h3>
                    <p>{{ $unreadCount }} notifikasi belum dibaca.</p>
                </div>
                @if ($notifications->total() > 0)
                    <div class="notification-bulk-actions">
                        @if ($unreadCount > 0)
                            <form method="POST" action="{{ route('donor.notifikasi.read-all') }}">
                                @csrf
                                <button class="donor-btn-secondary" type="submit"><i class="bi bi-check2-all" aria-hidden="true"></i> Tandai semua dibaca</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('donor.notifikasi.destroy-all') }}" onsubmit="return confirm('Hapus semua notifikasi?')">
                            @csrf
                            @method('DELETE')
                            <button class="donor-btn-secondary notification-delete-button" type="submit"><i class="bi bi-trash3" aria-hidden="true"></i> Hapus semua</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="donor-notification-list">
                @forelse ($notifications as $notification)
                    <article class="donor-notification-item {{ $notification->read_at ? '' : 'is-unread' }}">
                        <div class="donor-notification-content">
                            <strong>{{ $notification->data['title'] ?? 'Pembaruan pendaftaran' }}</strong>
                            <p>{{ $notification->data['message'] ?? 'Status pendaftaran donor diperbarui.' }}</p>
                            <small>{{ $notification->created_at->translatedFormat('d M Y, H:i') }}</small>
                        </div>
                        <div class="donor-notification-actions">
                            <a class="donor-btn-secondary" href="{{ $notification->data['url'] ?? route('donor.pendaftaran.index') }}">Lihat pendaftaran</a>
                            @if (! $notification->read_at)
                                <form method="POST" action="{{ route('donor.notifikasi.read', $notification->id) }}">
                                    @csrf
                                    <button class="donor-btn-primary" type="submit">Tandai dibaca</button>
                                </form>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="donor-empty-state compact">
                        <div class="donor-empty-icon"><i class="bi bi-bell" aria-hidden="true"></i></div>
                        <strong>Belum ada notifikasi</strong>
                        <span>Pembaruan status pengajuan donor akan muncul di sini.</span>
                    </div>
                @endforelse
            </div>

            {{ $notifications->links() }}
        </section>

        <div class="donor-footer"><span>Blodora</span><span>Sistem Donor Darah</span></div>
    </main>
</div>
@endsection
