@extends('layouts.app')

@section('title', 'Lengkapi Profil Pendonor')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-md-5">

                    <div class="mb-4">
                        <div class="text-danger fw-bold small text-uppercase mb-2">
                            BLODORA
                        </div>

                        <h2 class="fw-bold mb-2">
                            Lengkapi Profil Pendonor
                        </h2>

                        <p class="text-muted mb-0">
                            Lengkapi data diri sebelum menggunakan layanan pendonor.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Data belum valid.</strong>

                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pendonor.profile.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                NIK
                            </label>

                            <input
                                type="text"
                                name="nik"
                                class="form-control"
                                value="{{ old('nik') }}"
                                placeholder="Masukkan NIK"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="nama_lengkap"
                                class="form-control"
                                value="{{ old('nama_lengkap') }}"
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nomor HP
                            </label>

                            <input
                                type="text"
                                name="no_hp"
                                class="form-control"
                                value="{{ old('no_hp') }}"
                                placeholder="Masukkan nomor HP"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Alamat
                            </label>

                            <textarea
                                name="alamat"
                                class="form-control"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                                required
                            >{{ old('alamat') }}</textarea>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Golongan Darah
                                </label>

                                <select
                                    name="golongan_darah"
                                    class="form-select"
                                    required
                                >
                                    <option value="">Pilih golongan</option>
                                    <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>
                                        A
                                    </option>
                                    <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>
                                        B
                                    </option>
                                    <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>
                                        AB
                                    </option>
                                    <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>
                                        O
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Rhesus
                                </label>

                                <select
                                    name="rhesus"
                                    class="form-select"
                                    required
                                >
                                    <option value="">Pilih rhesus</option>

                                    <option value="+"
                                        {{ old('rhesus') == '+' ? 'selected' : '' }}>
                                        Positif (+)
                                    </option>

                                    <option value="-"
                                        {{ old('rhesus') == '-' ? 'selected' : '' }}>
                                        Negatif (-)
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Berat Badan
                            </label>

                            <div class="input-group">
                                <input
                                    type="number"
                                    name="berat_badan"
                                    class="form-control"
                                    value="{{ old('berat_badan') }}"
                                    placeholder="Contoh: 60"
                                    min="1"
                                    required
                                >

                                <span class="input-group-text">
                                    kg
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="tinggi_badan">Tinggi Badan (opsional)</label>
                            <div class="input-group">
                                <input id="tinggi_badan" type="number" step="0.1" min="50" max="250" name="tinggi_badan"
                                    class="form-control @error('tinggi_badan') is-invalid @enderror" value="{{ old('tinggi_badan') }}" placeholder="Contoh: 165">
                                <span class="input-group-text">cm</span>
                            </div>
                            @error('tinggi_badan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <button
                            type="submit"
                            class="btn btn-danger w-100 py-3 rounded-3 fw-semibold"
                        >
                            Simpan Profil Pendonor
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection
