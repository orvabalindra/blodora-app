@extends('layouts.app')

@section('title', 'Tambah Stok Darah')

@section('content')

<div class="admin-dashboard">

    @include('components.admin-sidebar')

    <main class="admin-main">

        {{-- TOPBAR --}}
        <div class="admin-topbar">

            <div>
                <span class="admin-page-label">
                    STOK DARAH
                </span>

                <h1>
                    Tambah Stok Darah
                </h1>

                <p>
                    Tambahkan persediaan darah baru.
                </p>
            </div>

            <div class="admin-topbar-right">

                <div class="admin-date">
                    <span>Hari ini</span>

                    <strong>
                        {{ now()->translatedFormat('d F Y') }}
                    </strong>
                </div>

                <div class="admin-top-avatar">
                    {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                </div>

            </div>

        </div>


        {{-- FORM PANEL --}}
        <div class="admin-panel">

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


            <form
                action="{{ route('stok-darah.store') }}"
                method="POST"
            >

                @csrf

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
                                id="pendonor_id"
                                name="pendonor_id"
                                class="admin-form-select @error('pendonor_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih pendonor
                                </option>

                                @foreach ($pendonors as $pendonor)

                                    <option
                                        value="{{ $pendonor->id }}"
                                        {{ old('pendonor_id') == $pendonor->id ? 'selected' : '' }}
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
                                id="golongan_darah"
                                name="golongan_darah"
                                class="admin-form-select @error('golongan_darah') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih
                                </option>

                                @foreach (['A', 'B', 'AB', 'O'] as $golongan)

                                    <option
                                        value="{{ $golongan }}"
                                        {{ old('golongan_darah') == $golongan ? 'selected' : '' }}
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
                                id="rhesus"
                                name="rhesus"
                                class="admin-form-select @error('rhesus') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih
                                </option>

                                <option
                                    value="+"
                                    {{ old('rhesus') == '+' ? 'selected' : '' }}
                                >
                                    Positif (+)
                                </option>

                                <option
                                    value="-"
                                    {{ old('rhesus') == '-' ? 'selected' : '' }}
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
                                    id="jumlah_kantong"
                                    type="number"
                                    name="jumlah_kantong"
                                    class="admin-form-control @error('jumlah_kantong') is-invalid @enderror"
                                    value="{{ old('jumlah_kantong') }}"
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

                            <input
                                id="tanggal_masuk"
                                type="date"
                                name="tanggal_masuk"
                                class="admin-form-control @error('tanggal_masuk') is-invalid @enderror"
                                value="{{ old('tanggal_masuk') }}"
                                required
                            >

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

                            <input
                                id="tanggal_kadaluarsa"
                                type="date"
                                name="tanggal_kadaluarsa"
                                class="admin-form-control @error('tanggal_kadaluarsa') is-invalid @enderror"
                                value="{{ old('tanggal_kadaluarsa') }}"
                                required
                            >

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
                        Simpan Stok
                    </button>

                </div>

            </form>

        </div>


        {{-- FOOTER --}}
        <div class="admin-footer">

            <span>
                Blodora Admin Panel
            </span>

            <span>
                Sistem Donor Darah
            </span>

        </div>

    </main>

</div>

@endsection