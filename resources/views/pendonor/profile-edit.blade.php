@extends('layouts.app')

@section('title', 'Ubah Profil Saya')

@section('content')
<div class="donor-dashboard">
    @include('components.donor-sidebar')
    <main class="donor-main">
        <div class="donor-topbar">
            <div>
                <span class="donor-page-label">PROFIL SAYA</span>
                <h1>Ubah Profil</h1>
                <p>Perbarui data pribadi dan ukuran tubuh Anda.</p>
            </div>
        </div>

        <section class="donor-panel">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
            @endif
            <form action="{{ route('pendonor.profile.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-12 col-md-6">
                    <label class="form-label" for="nik">NIK</label>
                    <input class="form-control" id="nik" name="nik" value="{{ old('nik', $pendonor->nik) }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="nama_lengkap">Nama lengkap</label>
                    <input class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $pendonor->nama_lengkap) }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="no_hp">Nomor HP</label>
                    <input class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $pendonor->no_hp) }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="berat_badan">Berat badan</label>
                    <div class="input-group"><input class="form-control" type="number" id="berat_badan" name="berat_badan" min="1" max="300" value="{{ old('berat_badan', $pendonor->berat_badan) }}" required><span class="input-group-text">kg</span></div>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="tinggi_badan">Tinggi badan</label>
                    <div class="input-group"><input class="form-control" type="number" id="tinggi_badan" name="tinggi_badan" min="50" max="250" step="0.1" value="{{ old('tinggi_badan', $pendonor->tinggi_badan) }}"><span class="input-group-text">cm</span></div>
                    <small class="text-muted">Opsional. Data terbaru akan digunakan sebagai referensi screening berikutnya.</small>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="golongan_darah">Golongan darah</label>
                    <select class="form-select" id="golongan_darah" name="golongan_darah" required>
                        @foreach (['A', 'B', 'AB', 'O'] as $golongan)
                            <option value="{{ $golongan }}" @selected(old('golongan_darah', $pendonor->golongan_darah) === $golongan)>{{ $golongan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="rhesus">Rhesus</label>
                    <select class="form-select" id="rhesus" name="rhesus" required>
                        <option value="+" @selected(old('rhesus', $pendonor->rhesus) === '+')>Positif (+)</option>
                        <option value="-" @selected(old('rhesus', $pendonor->rhesus) === '-')>Negatif (-)</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $pendonor->alamat) }}</textarea>
                </div>
                <div class="col-12 d-flex flex-wrap gap-2 pt-2">
                    <button class="donor-btn-primary" type="submit">Simpan perubahan</button>
                    <a class="donor-btn-secondary" href="{{ route('pendonor.profile') }}">Batal</a>
                </div>
            </form>
        </section>
        <div class="donor-footer"><span>Panel Pendonor Blodora</span><span>Sistem Donor Darah</span></div>
    </main>
</div>
@endsection
