@php($isEdit = $jadwalDonor !== null)
@php($tanggalJadwalForm = old('tanggal', $jadwalDonor?->tanggal?->format('Y-m-d') ?? today()->format('Y-m-d')))

<div class="admin-panel">
    <div class="admin-panel-header">
        <div>
            <span class="admin-section-label">INFORMASI KEGIATAN</span>
            <h3>{{ $isEdit ? 'Perbarui detail jadwal' : 'Detail jadwal baru' }}</h3>
            <p>Pastikan waktu selesai lebih akhir daripada waktu mulai.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-4">
            <strong>Data jadwal belum valid.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('jadwal-donor.update', $jadwalDonor) : route('jadwal-donor.store') }}">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <div class="admin-form-group">
                    <label for="nama_kegiatan" class="admin-form-label">Nama kegiatan</label>
                    <input id="nama_kegiatan" name="nama_kegiatan" class="admin-form-control @error('nama_kegiatan') is-invalid @enderror" value="{{ old('nama_kegiatan', $jadwalDonor?->nama_kegiatan) }}" required maxlength="255">
                    @error('nama_kegiatan')<div class="admin-form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="admin-form-group">
                    <label for="lokasi" class="admin-form-label">Lokasi</label>
                    <input id="lokasi" name="lokasi" class="admin-form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $jadwalDonor?->lokasi) }}" required maxlength="255">
                    @error('lokasi')<div class="admin-form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="admin-form-group">
                    <label class="admin-form-label d-block">Tanggal kegiatan</label>
                    <x-admin-date-parts name="tanggal" :value="$tanggalJadwalForm" :min-year="now()->year" />
                    @error('tanggal')<div class="admin-form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-form-group">
                    <label for="waktu_mulai" class="admin-form-label">Waktu mulai</label>
                    <input type="time" id="waktu_mulai" name="waktu_mulai" class="admin-form-control @error('waktu_mulai') is-invalid @enderror" value="{{ old('waktu_mulai', $jadwalDonor ? substr($jadwalDonor->waktu_mulai, 0, 5) : '') }}" required>
                    @error('waktu_mulai')<div class="admin-form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-form-group">
                    <label for="waktu_selesai" class="admin-form-label">Waktu selesai</label>
                    <input type="time" id="waktu_selesai" name="waktu_selesai" class="admin-form-control @error('waktu_selesai') is-invalid @enderror" value="{{ old('waktu_selesai', $jadwalDonor ? substr($jadwalDonor->waktu_selesai, 0, 5) : '') }}" required>
                    @error('waktu_selesai')<div class="admin-form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-form-group">
                    <label for="kuota" class="admin-form-label">Kuota pendaftar</label>
                    <input type="number" id="kuota" name="kuota" class="admin-form-control @error('kuota') is-invalid @enderror" value="{{ old('kuota', $jadwalDonor?->kuota) }}" min="1" required>
                    @error('kuota')<div class="admin-form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('jadwal-donor.index') }}" class="admin-btn-secondary">Batal</a>
            <button class="admin-btn-primary" type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Buat Jadwal' }}</button>
        </div>
    </form>
</div>
