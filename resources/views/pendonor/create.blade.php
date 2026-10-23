@extends('layouts.app')

@section('title', 'Tambah Pendonor')

@section('content')
<div class="admin-dashboard">
    @include('components.admin-sidebar')

    <main class="admin-main">
        <div class="admin-topbar">
            <div>
                <span class="admin-page-label">DATA MASTER</span>
                <h1>Tambah Pendonor</h1>
                <p>Lengkapi profil untuk akun pendonor yang sudah terdaftar.</p>
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

        <div class="admin-panel">
            @if ($errors->any())
                <div class="admin-form-alert admin-form-alert-danger">
                    <strong>Data belum valid.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($users->isEmpty())
                <div class="alert alert-info">
                    Belum ada akun pendonor yang belum memiliki profil. Minta pendonor untuk mendaftar terlebih dahulu.
                </div>
            @else
                <form action="{{ route('pendonor.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="admin-form-group">
                                <label for="user_id" class="admin-form-label">Akun Pendonor</label>
                                <select id="user_id" name="user_id"
                                    class="admin-form-select @error('user_id') is-invalid @enderror" required>
                                    <option value="">Pilih akun pendonor</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>
                                            {{ $user->username }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="nik" class="admin-form-label">NIK</label>
                                <input id="nik" name="nik" type="text"
                                    class="admin-form-control @error('nik') is-invalid @enderror"
                                    value="{{ old('nik') }}" required>
                                @error('nik')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="nama_lengkap" class="admin-form-label">Nama Lengkap</label>
                                <input id="nama_lengkap" name="nama_lengkap" type="text"
                                    class="admin-form-control @error('nama_lengkap') is-invalid @enderror"
                                    value="{{ old('nama_lengkap') }}" required>
                                @error('nama_lengkap')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="no_hp" class="admin-form-label">Nomor HP</label>
                                <input id="no_hp" name="no_hp" type="text"
                                    class="admin-form-control @error('no_hp') is-invalid @enderror"
                                    value="{{ old('no_hp') }}" required>
                                @error('no_hp')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="berat_badan" class="admin-form-label">Berat Badan</label>
                                <div class="admin-input-unit">
                                    <input id="berat_badan" name="berat_badan" type="number" min="1"
                                        class="admin-form-control @error('berat_badan') is-invalid @enderror"
                                        value="{{ old('berat_badan') }}" required>
                                    <span class="admin-input-unit-label">kg</span>
                                </div>
                                @error('berat_badan')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="tinggi_badan" class="admin-form-label">Tinggi Badan (opsional)</label>
                                <div class="admin-input-unit">
                                    <input id="tinggi_badan" name="tinggi_badan" type="number" step="0.1" min="50" max="250"
                                        class="admin-form-control @error('tinggi_badan') is-invalid @enderror" value="{{ old('tinggi_badan') }}">
                                    <span class="admin-input-unit-label">cm</span>
                                </div>
                                @error('tinggi_badan')<div class="admin-form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="golongan_darah" class="admin-form-label">Golongan Darah</label>
                                <select id="golongan_darah" name="golongan_darah" class="admin-form-select" required>
                                    <option value="">Pilih golongan darah</option>
                                    @foreach (['A', 'B', 'AB', 'O'] as $golongan)
                                        <option value="{{ $golongan }}" @selected(old('golongan_darah') === $golongan)>
                                            {{ $golongan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('golongan_darah')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="rhesus" class="admin-form-label">Rhesus</label>
                                <select id="rhesus" name="rhesus" class="admin-form-select" required>
                                    <option value="">Pilih rhesus</option>
                                    <option value="+" @selected(old('rhesus') === '+')>Positif (+)</option>
                                    <option value="-" @selected(old('rhesus') === '-')>Negatif (-)</option>
                                </select>
                                @error('rhesus')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="admin-form-group">
                                <label for="alamat" class="admin-form-label">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="3"
                                    class="admin-form-control @error('alamat') is-invalid @enderror"
                                    required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="admin-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-danger">Simpan Pendonor</button>
                        <a href="{{ route('pendonor.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            @endif
        </div>

        <div class="admin-footer">
            <span>Panel Admin Blodora</span>
            <span>Sistem Donor Darah</span>
        </div>
    </main>
</div>
@endsection
