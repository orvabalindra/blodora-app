@extends('layouts.app')

@section('title', 'Ubah Stok Darah')

@section('content')

<div class="admin-dashboard">

    {{-- SIDEBAR --}}
    @include('components.admin-sidebar')


    {{-- MAIN --}}
    <main class="admin-main">

        {{-- TOPBAR --}}
        <div class="admin-topbar">

            <div>

                <span class="admin-page-label">
                    STOK DARAH
                </span>

                <h1>
                    Ubah Stok Darah
                </h1>

                <p>
                    Ubah informasi data stok darah.
                </p>

            </div>


            <div class="admin-topbar-right">

                <div class="admin-date">

                    <span>
                        Hari ini
                    </span>

                    <strong>
                        {{ now()->translatedFormat('d F Y') }}
                    </strong>

                </div>

                <div class="admin-top-avatar">
                    {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                </div>

            </div>

        </div>


        {{-- PANEL --}}
        <div class="admin-panel">

            <div class="admin-panel-header">

                <div>

                    <span class="admin-section-label">
                        EDIT STOK DARAH
                    </span>

                    <h3>
                        Ubah Data Stok
                    </h3>

                    <p>
                        Perbarui informasi persediaan darah.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('stok-darah.show', $stokDarah) }}"
                        class="admin-btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </div>


            {{-- VALIDATION ERROR --}}
            @if ($errors->any())

                <div class="admin-form-alert admin-form-alert-danger">

                    <strong>
                        Data belum valid.
                    </strong>

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- FORM --}}
            <form
                action="{{ route('stok-darah.update', $stokDarah) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="row g-4">


                    {{-- PENDONOR --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="pendonor_id"
                                class="admin-form-label"
                            >
                                Pendonor
                            </label>

                            <select
                                name="pendonor_id"
                                id="pendonor_id"
                                class="admin-form-select @error('pendonor_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih pendonor
                                </option>

                                @foreach ($pendonors as $pendonor)

                                    <option
                                        value="{{ $pendonor->id }}"
                                        {{ old('pendonor_id', $stokDarah->pendonor_id) == $pendonor->id ? 'selected' : '' }}
                                    >
                                        {{ $pendonor->nama_lengkap }}
                                        — {{ $pendonor->golongan_darah }}{{ $pendonor->rhesus }}
                                    </option>

                                @endforeach

                            </select>

                            @error('pendonor_id')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- LOKASI STOK --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">
                            <label for="lokasi" class="admin-form-label">Lokasi Stok</label>
                            <input
                                type="text"
                                id="lokasi"
                                name="lokasi"
                                maxlength="255"
                                class="admin-form-control @error('lokasi') is-invalid @enderror"
                                value="{{ old('lokasi', $stokDarah->lokasi) }}"
                                placeholder="Contoh: UDD PMI Kota Malang"
                                required
                            >
                            @error('lokasi')
                                <div class="admin-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>


                    {{-- GOLONGAN DARAH --}}
                    <div class="col-md-3">

                        <div class="admin-form-group">

                            <label
                                for="golongan_darah"
                                class="admin-form-label"
                            >
                                Golongan Darah
                            </label>

                            <select
                                name="golongan_darah"
                                id="golongan_darah"
                                class="admin-form-select @error('golongan_darah') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih
                                </option>

                                @foreach (['A', 'B', 'AB', 'O'] as $golongan)

                                    <option
                                        value="{{ $golongan }}"
                                        {{ old('golongan_darah', $stokDarah->golongan_darah) == $golongan ? 'selected' : '' }}
                                    >
                                        {{ $golongan }}
                                    </option>

                                @endforeach

                            </select>

                            @error('golongan_darah')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- RHESUS --}}
                    <div class="col-md-3">

                        <div class="admin-form-group">

                            <label
                                for="rhesus"
                                class="admin-form-label"
                            >
                                Rhesus
                            </label>

                            <select
                                name="rhesus"
                                id="rhesus"
                                class="admin-form-select @error('rhesus') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih
                                </option>

                                <option
                                    value="+"
                                    {{ old('rhesus', $stokDarah->rhesus) == '+' ? 'selected' : '' }}
                                >
                                    Positif (+)
                                </option>

                                <option
                                    value="-"
                                    {{ old('rhesus', $stokDarah->rhesus) == '-' ? 'selected' : '' }}
                                >
                                    Negatif (-)
                                </option>

                            </select>

                            @error('rhesus')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- JUMLAH KANTONG --}}
                    <div class="col-md-4">

                        <div class="admin-form-group">

                            <label
                                for="jumlah_kantong"
                                class="admin-form-label"
                            >
                                Jumlah Kantong
                            </label>

                            <div class="admin-input-unit">

                                <input
                                    type="number"
                                    name="jumlah_kantong"
                                    id="jumlah_kantong"
                                    class="admin-form-control @error('jumlah_kantong') is-invalid @enderror"
                                    value="{{ old('jumlah_kantong', $stokDarah->jumlah_kantong) }}"
                                    min="1"
                                    required
                                >

                                <span class="admin-input-unit-label">
                                    kantong
                                </span>

                            </div>

                            @error('jumlah_kantong')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- TANGGAL MASUK --}}
                    <div class="col-md-4">

                        <div class="admin-form-group">

                            <label
                                for="tanggal_masuk"
                                class="admin-form-label"
                            >
                                Tanggal Masuk
                            </label>

                            <x-admin-date-parts name="tanggal_masuk" :value="old('tanggal_masuk', optional($stokDarah->tanggal_masuk)->format('Y-m-d'))" />

                            @error('tanggal_masuk')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- TANGGAL KADALUARSA --}}
                    <div class="col-md-4">

                        <div class="admin-form-group">

                            <label
                                for="tanggal_kadaluarsa"
                                class="admin-form-label"
                            >
                                Tanggal Kadaluarsa
                            </label>

                            <x-admin-date-parts name="tanggal_kadaluarsa" :value="old('tanggal_kadaluarsa', optional($stokDarah->tanggal_kadaluarsa)->format('Y-m-d'))" />

                            @error('tanggal_kadaluarsa')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="admin-form-actions">

                    <a
                        href="{{ route('stok-darah.index') }}"
                        class="admin-btn-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="admin-btn-primary"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>


        {{-- FOOTER --}}
        <div class="admin-footer">

            <span>
                Panel Admin Blodora
            </span>

            <span>
                Sistem Donor Darah
            </span>

        </div>

    </main>

</div>

@endsection
