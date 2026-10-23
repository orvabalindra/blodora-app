@extends('layouts.app')

@section('title', 'Pendaftaran Donor')

@section('content')
<div class="admin-dashboard">
    @include('components.admin-sidebar')
    <main class="admin-main">
        <div class="admin-topbar"><div><span class="admin-page-label">PENGAJUAN DONOR</span><h1>Pendaftaran Donor</h1><p>Tinjau dan setujui pengajuan sebelum pendonor mengikuti screening.</p></div></div>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        <form method="GET" action="{{ route('pendaftaran-donor.admin.index') }}" class="admin-registration-filters mb-3">
            <div class="input-group">
                <label class="visually-hidden" for="pendaftaran-search">Cari pendaftar</label>
                <input id="pendaftaran-search" class="form-control" type="search" name="q" value="{{ $q }}" placeholder="Cari nama, kegiatan, lokasi, atau status">
                <label class="visually-hidden" for="pendaftaran-status">Filter status</label>
                <select id="pendaftaran-status" class="form-select" name="status" aria-label="Filter status pendaftaran">
                    <option value="">Semua status</option>
                    <option value="menunggu_screening" @selected($status === 'menunggu_screening')>Terdaftar / proses screening</option>
                    <option value="ditunda" @selected($status === 'ditunda')>Ditunda</option>
                    <option value="proses_donor" @selected($status === 'proses_donor')>Lolos screening</option>
                    <option value="selesai" @selected($status === 'selesai')>Donor selesai</option>
                    <option value="gagal_screening" @selected($status === 'gagal_screening')>Tidak layak</option>
                    <option value="tidak_hadir" @selected($status === 'tidak_hadir')>Tidak hadir</option>
                    <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
                </select>
                <button type="submit" class="btn btn-danger">Cari / Filter</button>
                @if ($q !== '' || $status !== '')
                    <a class="btn btn-outline-secondary" href="{{ $status !== '' ? route('pendaftaran-donor.admin.index', ['status' => $status]) : route('pendaftaran-donor.admin.index') }}" aria-label="Hapus pencarian" title="Hapus pencarian"><i class="bi bi-x-lg" aria-hidden="true"></i></a>
                @endif
            </div>
            <small class="text-muted">{{ $pendaftarans->total() }} pendaftaran ditemukan</small>
        </form>

        <details class="admin-dashboard-details mb-3">
                <summary>Alur donor: pengajuan, persetujuan admin, screening, donor, lalu stok bertambah</summary>
        <section class="admin-panel mb-2 mt-2">
            <span class="admin-section-label">ALUR DONOR</span>
            <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                <span class="badge text-bg-light">1. Pendaftaran jadwal</span><span aria-hidden="true">→</span>
                <span class="badge text-bg-light">2. Screening petugas</span><span aria-hidden="true">→</span>
                <span class="badge text-bg-light">3. Donor dan catat kantong</span><span aria-hidden="true">→</span>
                <span class="badge text-bg-success">4. Stok bertambah otomatis</span>
            </div>
            <p class="small text-muted mt-2 mb-0">Pilih “Selesaikan donor dan tambah stok” setelah jadwal berakhir untuk menyimpan riwayat donor dan menambahkan kantong ke stok.</p>
        </section>
        </details>

        @if ($pendaftarans->isEmpty())
            <div class="admin-panel text-center text-muted py-4">Tidak ada pendaftaran yang cocok dengan pencarian atau filter ini.</div>
        @endif

        <div class="row g-3 admin-registration-grid align-items-start">
        @foreach ($pendaftarans as $pendaftaran)
            <div class="col-12 col-md-6 col-xl-4">
            <section class="admin-panel admin-registration-card">
                <div class="admin-registration-card-summary">
                    <div>
                        <h2 class="h6">{{ $pendaftaran->pendonor->nama_lengkap ?? 'Pendonor dihapus' }}</h2>
                        <p class="mb-1">{{ $pendaftaran->jadwalDonor->nama_kegiatan ?? 'Jadwal dihapus' }} · {{ $pendaftaran->jadwalDonor?->tanggal?->translatedFormat('d M Y') ?? '—' }}</p>
                    </div>
                    @php
                        $jadwalDonor = $pendaftaran->jadwalDonor;
                        $waktuMulaiScreening = $jadwalDonor?->tanggal?->copy()->setTimeFromTimeString($jadwalDonor->waktu_mulai);
                        $screeningDimulai = $waktuMulaiScreening && now()->greaterThanOrEqualTo($waktuMulaiScreening);
                        $statusTampilan = match ($pendaftaran->persetujuan_admin) {
                            'menunggu' => 'Menunggu persetujuan admin',
                            'ditolak' => 'Ditolak admin',
                            default => $pendaftaran->status === 'menunggu_screening'
                                ? ($screeningDimulai ? 'Proses screening' : 'Disetujui')
                                : ucwords(str_replace('_', ' ', $pendaftaran->status)),
                        };
                    @endphp
                    <span class="badge rounded-pill {{ $pendaftaran->persetujuan_admin === 'ditolak' || in_array($pendaftaran->status, ['gagal_screening', 'tidak_hadir', 'dibatalkan'], true) ? 'text-bg-secondary' : (in_array($pendaftaran->status, ['selesai', 'proses_donor'], true) || $pendaftaran->persetujuan_admin === 'disetujui' ? 'text-bg-success' : 'text-bg-warning') }}">{{ $statusTampilan }}</span>
                </div>

                <details class="admin-registration-details mt-3" name="detail-pendaftaran">
                    <summary><span>Lihat</span><i class="bi bi-chevron-down" aria-hidden="true"></i></summary>

                @if ($pendaftaran->persetujuan_admin === 'menunggu')
                    <div class="alert alert-info mt-3 mb-3">
                        <strong>Pengajuan menunggu keputusan.</strong> Setujui agar pendonor dapat mengikuti screening pada hari kegiatan, atau tolak dengan menyertakan alasan.
                    </div>
                    <div class="d-flex flex-wrap align-items-end gap-2 mb-3">
                        <form method="POST" action="{{ route('pendaftaran-donor.persetujuan', $pendaftaran) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="keputusan" value="setujui">
                            <button class="admin-btn-primary" type="submit">Setujui pengajuan</button>
                        </form>
                        <form method="POST" action="{{ route('pendaftaran-donor.persetujuan', $pendaftaran) }}" class="flex-grow-1">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="keputusan" value="tolak">
                            <label class="form-label mb-1" for="catatan-admin-{{ $pendaftaran->id }}">Alasan penolakan</label>
                            <div class="d-flex flex-wrap gap-2">
                                <input class="form-control flex-grow-1" id="catatan-admin-{{ $pendaftaran->id }}" name="catatan_admin" maxlength="2000" placeholder="Jelaskan alasan penolakan" required>
                                <button class="btn btn-outline-danger" type="submit">Tolak pengajuan</button>
                            </div>
                        </form>
                    </div>
                @elseif ($pendaftaran->persetujuan_admin === 'ditolak' && $pendaftaran->catatan_admin)
                    <div class="alert alert-secondary mt-3 mb-3"><strong>Alasan penolakan:</strong> {{ $pendaftaran->catatan_admin }}</div>
                @endif

                <div class="alert alert-light border mt-3 mb-3">
                    <strong>Data dari profil:</strong>
                    Berat {{ $pendaftaran->pendonor->berat_badan ?? '—' }} kg ·
                    Tinggi {{ $pendaftaran->pendonor->tinggi_badan ? number_format($pendaftaran->pendonor->tinggi_badan, 1) . ' cm' : 'belum diisi' }} ·
                    Golongan darah {{ $pendaftaran->pendonor->golongan_darah ?? '—' }}{{ $pendaftaran->pendonor->rhesus ?? '' }}.
                    Berat dan tinggi disalin ke catatan screening; golongan darah dipakai saat stok dibuat.
                </div>

                @if ($pendaftaran->keterangan_medis)
                    <details class="mt-2"><summary>Catatan screening (rahasia petugas)</summary>
                        <p class="alert alert-warning mt-2 mb-0">{{ $pendaftaran->keterangan_medis }}</p>
                    </details>
                @endif

                @if ($pendaftaran->persetujuan_admin === 'disetujui' && in_array($pendaftaran->status, ['menunggu_screening', 'ditunda'], true) && ($pendaftaran->status === 'ditunda' || $screeningDimulai))
                    <details class="mt-2">
                    <summary>Isi hasil screening</summary>
                    <form method="POST" action="{{ route('pendaftaran-donor.screening', $pendaftaran) }}" class="mt-3">
                        @csrf
                        @method('PUT')
                        <div class="admin-screening-form-grid">
                            <div class="admin-screening-field">
                                <label class="form-label" for="keputusan-{{ $pendaftaran->id }}">Keputusan petugas</label>
                                <select class="form-select" id="keputusan-{{ $pendaftaran->id }}" name="keputusan" required>
                                    <option value="">Pilih keputusan</option>
                                    <option value="layak">Layak melanjutkan donor</option>
                                    <option value="tidak_layak">Tidak layak saat ini</option>
                                    <option value="ditunda">Tunda dan evaluasi kembali</option>
                                </select>
                            </div>
                            <div class="admin-screening-field admin-screening-field-wide"><label class="form-label d-block">Tanggal pemeriksaan</label><x-admin-date-parts name="tanggal_pemeriksaan" :value="old('tanggal_pemeriksaan')" :required="false" /></div>
                            <div class="admin-screening-field"><label class="form-label" for="tekanan-{{ $pendaftaran->id }}">Tekanan darah</label><input class="form-control" id="tekanan-{{ $pendaftaran->id }}" name="tekanan_darah" placeholder="Contoh: 120/80"></div>
                            <div class="admin-screening-field"><label class="form-label" for="hb-{{ $pendaftaran->id }}">Hemoglobin (g/dL)</label><input class="form-control" type="number" min="0" max="30" step="0.1" id="hb-{{ $pendaftaran->id }}" name="hemoglobin"></div>
                            <div class="admin-screening-field admin-screening-field-wide"><label class="form-label" for="catatan-{{ $pendaftaran->id }}">Catatan petugas (rahasia)</label><textarea class="form-control" id="catatan-{{ $pendaftaran->id }}" name="catatan" rows="2">{{ old('catatan') }}</textarea></div>
                        </div>
                        <button class="admin-btn-primary mt-3" type="submit">Simpan Hasil Screening</button>
                    </form>
                    </details>
                @elseif ($pendaftaran->status === 'proses_donor')
                    @php($waktuSelesai = $pendaftaran->jadwalDonor->tanggal->copy()->setTimeFromTimeString($pendaftaran->jadwalDonor->waktu_selesai))
                    @if (now()->greaterThanOrEqualTo($waktuSelesai))
                        <details class="mt-2">
                        <summary>Catat hasil donor dan penambahan stok</summary>
                        <p class="small text-muted mt-2">Screening dinyatakan layak. Masukkan jumlah kantong untuk menyelesaikan donor dan menambahkannya ke stok.</p>
                        <form method="POST" action="{{ route('pendaftaran-donor.status', $pendaftaran) }}" class="admin-donation-completion-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="selesai">
                            <div>
                                <label class="form-label" for="jumlah-{{ $pendaftaran->id }}">Jumlah kantong terkumpul</label>
                                <input class="form-control" id="jumlah-{{ $pendaftaran->id }}" type="number" name="jumlah_donor" min="1" required>
                            </div>
                            <div class="admin-donation-expiry">
                                <label class="form-label d-block">Tanggal kedaluwarsa stok</label>
                                <x-admin-date-parts name="tanggal_kadaluarsa" :value="old('tanggal_kadaluarsa')" />
                            </div>
                            <button class="admin-btn-primary" type="submit">Selesaikan donor dan tambah stok</button>
                        </form>
                        <form method="POST" action="{{ route('pendaftaran-donor.status', $pendaftaran) }}" class="admin-donation-absence-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="tidak_hadir">
                            <button class="btn btn-outline-secondary" type="submit">Tidak Hadir</button>
                        </form>
                        </details>
                    @else
                        <p class="mt-3">Screening dinyatakan layak. Hasil kehadiran dapat dicatat setelah jadwal selesai.</p>
                    @endif
                @endif

                @if ($pendaftaran->pemeriksaans->isNotEmpty())
                    <details class="mt-3"><summary>Riwayat pemeriksaan petugas</summary>
                        @foreach ($pendaftaran->pemeriksaans as $pemeriksaan)
                            <p class="mb-0 mt-2">{{ $pemeriksaan->tanggal_pemeriksaan->translatedFormat('d M Y') }} · {{ $pemeriksaan->hasil }} · {{ $pemeriksaan->berat_badan }} kg · Hb {{ $pemeriksaan->hemoglobin }} g/dL</p>
                        @endforeach
                    </details>
                @endif
                </details>
            </section>
            </div>
        @endforeach
        </div>
        {{ $pendaftarans->links() }}
    </main>
</div>
@endsection
