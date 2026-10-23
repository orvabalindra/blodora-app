@extends('layouts.app')

@section('title', 'Notifikasi Permintaan Darah')

@section('content')
<div class="admin-dashboard">
    @include('components.admin-sidebar')

    <main class="admin-main">
        <div class="admin-topbar">
            <div>
                <span class="admin-page-label">PERMINTAAN DARAH</span>
                <h1>Notifikasi</h1>
                <p>Pembaruan permintaan darah dari rumah sakit dan penyalurannya.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <section class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <span class="admin-section-label">AKTIVITAS</span>
                    <h3>Permintaan terbaru</h3>
                    <p>{{ $unreadCount }} notifikasi belum dibaca.</p>
                </div>
                @if ($notifications->total() > 0)
                    <div class="notification-bulk-actions">
                        @if ($unreadCount > 0)
                            <form method="POST" action="{{ route('admin.permintaan-notifikasi.read-all') }}">
                                @csrf
                                <button class="admin-btn-secondary" type="submit"><i class="bi bi-check2-all" aria-hidden="true"></i> Tandai semua dibaca</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.permintaan-notifikasi.destroy-all') }}" onsubmit="return confirm('Hapus semua notifikasi permintaan darah?')">
                            @csrf
                            @method('DELETE')
                            <button class="admin-btn-secondary notification-delete-button" type="submit"><i class="bi bi-trash3" aria-hidden="true"></i> Hapus semua</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="admin-notification-list">
                @forelse ($notifications as $notification)
                    <article class="admin-notification-item {{ $notification->read_at ? '' : 'is-unread' }}">
                        <div>
                            <strong>{{ $notification->data['title'] ?? 'Pembaruan permintaan darah' }}</strong>
                            <p>{{ $notification->data['message'] ?? 'Status permintaan darah diperbarui.' }}</p>
                            <small>{{ $notification->created_at->translatedFormat('d M Y, H:i') }}</small>
                        </div>
                        <div class="admin-notification-actions">
                            <a class="admin-btn-secondary" href="{{ $notification->data['url'] ?? route('permintaan-darah.index') }}">Buka permintaan</a>
                            @if (! $notification->read_at)
                                <form method="POST" action="{{ route('admin.permintaan-notifikasi.read', $notification->id) }}">
                                    @csrf
                                    <button class="admin-btn-primary" type="submit">Tandai dibaca</button>
                                </form>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="admin-empty-state">
                        <div class="admin-empty-icon"><i class="bi bi-bell" aria-hidden="true"></i></div>
                        <strong>Belum ada notifikasi permintaan</strong>
                        <span>Permintaan baru dan perubahan status akan muncul di sini.</span>
                    </div>
                @endforelse
            </div>

            {{ $notifications->links() }}
        </section>

        <div class="admin-footer"><span>Panel Admin Blodora</span><span>Notifikasi Permintaan Darah</span></div>
    </main>
</div>
@endsection
