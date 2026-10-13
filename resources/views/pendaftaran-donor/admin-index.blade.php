@extends('layouts.app')

@section('title', 'Pendaftaran Donor')

@section('content')
<div class="admin-dashboard">
    @include('components.admin-sidebar')
    <main class="admin-main">
        <div class="admin-topbar"><div><span class="admin-page-label">SCREENING</span><h1>Pendaftaran Donor</h1><p>Catat hasil pemeriksaan sesuai penilaian petugas UTD.</p></div></div>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        @foreach ($pendaftarans as $pendaftaran)
            <section class="admin-panel mb-3">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <h2 class="h5">{{ $pendaftaran->pendonor->nama_lengkap ?? 'Pendonor dihapus' }}</h2>
                        <p class="mb-1">{{ $pendaftaran->jadwalDonor->nama_kegiatan ?? 'Jadwal dihapus' }} · {{ $pendaftaran->jadwalDonor?->tanggal?->translatedFormat('d M Y') ?? '—' }}</p>
                    </div>
                    <strong>{{ str_replace('_', ' ', ucfirst($pendaftaran->status)) }}</strong>
                </div>

                @if ($pendaftaran->keterangan_medis)
                    <div class="alert alert-warning mt-3 mb-0">
                        Catatan screening sebelumnya (rahasia petugas): {{ $pendaftaran->keterangan_medis }}
                    </div>
                @endif

                @if (in_array($pendaftaran->status, ['menunggu_screening', 'ditunda'], true))
                    <form method="POST" action="{{ route('pendaftaran-donor.screening', $pendaftaran) }}" class="mt-3">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="keputusan-{{ $pendaftaran->id }}">Keputusan petugas</label>
                                <select class="form-select" id="keputusan-{{ $pendaftaran->id }}" name="keputusan" required>
                                    <option value="">Pilih keputusan</option>
                                    <option value="layak">Layak melanjutkan donor</option>
                                    <option value="tidak_layak">Tidak layak saat ini</option>
                                    <option value="ditunda">Tunda dan evaluasi kembali</option>
                                </select>
                            </div>
                            <div class="col-md-4"><label class="form-label" for="tanggal-{{ $pendaftaran->id }}">Tanggal pemeriksaan</label><input class="form-control" type="date" id="tanggal-{{ $pendaftaran->id }}" name="tanggal_pemeriksaan"></div>
                            <div class="col-md-4"><label class="form-label" for="berat-{{ $pendaftaran->id }}">Berat badan (kg)</label><input class="form-control" type="number" min="1" id="berat-{{ $pendaftaran->id }}" name="berat_badan"></div>
                            <div class="col-md-4"><label class="form-label" for="tekanan-{{ $pendaftaran->id }}">Tekanan darah</label><input class="form-control" id="tekanan-{{ $pendaftaran->id }}" name="tekanan_darah" placeholder="Contoh: 120/80"></div>
                            <div class="col-md-4"><label class="form-label" for="hb-{{ $pendaftaran->id }}">Hemoglobin (g/dL)</label><input class="form-control" type="number" min="0" max="30" step="0.1" id="hb-{{ $pendaftaran->id }}" name="hemoglobin"></div>
                            <div class="col-md-8"><label class="form-label" for="catatan-{{ $pendaftaran->id }}">Catatan petugas (rahasia)</label><textarea class="form-control" id="catatan-{{ $pendaftaran->id }}" name="catatan" rows="2">{{ old('catatan') }}</textarea></div>
                        </div>
                        <button class="admin-btn-primary mt-3" type="submit">Simpan Hasil Screening</button>
                    </form>
                @elseif ($pendaftaran->status === 'proses_donor')
                    @php($waktuSelesai = $pendaftaran->jadwalDonor->tanggal->copy()->setTimeFromTimeString($pendaftaran->jadwalDonor->waktu_selesai))
                    @if (now()->greaterThanOrEqualTo($waktuSelesai))
                        <p class="mt-3">Screening dinyatakan layak. Catat hasil setelah jadwal selesai.</p>
                        <div class="d-flex gap-2 flex-wrap align-items-end">
                            <form method="POST" action="{{ route('pendaftaran-donor.status', $pendaftaran) }}" class="d-flex gap-2 align-items-end flex-wrap">@csrf @method('PATCH')<input type="hidden" name="status" value="selesai"><div><label class="form-label" for="jumlah-{{ $pendaftaran->id }}">Jumlah kantong terkumpul</label><input class="form-control" id="jumlah-{{ $pendaftaran->id }}" type="number" name="jumlah_donor" min="1" required></div><div><label class="form-label" for="kadaluarsa-{{ $pendaftaran->id }}">Tanggal kedaluwarsa stok</label><input class="form-control" id="kadaluarsa-{{ $pendaftaran->id }}" type="date" name="tanggal_kadaluarsa" min="{{ today()->format('Y-m-d') }}" required></div><button class="btn btn-success" type="submit">Tandai Selesai</button></form>
                            <form method="POST" action="{{ route('pendaftaran-donor.status', $pendaftaran) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="tidak_hadir"><button class="btn btn-outline-secondary" type="submit">Tidak Hadir</button></form>
                        </div>
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
            </section>
        @endforeach
        {{ $pendaftarans->links() }}
    </main>
</div>
@endsection
