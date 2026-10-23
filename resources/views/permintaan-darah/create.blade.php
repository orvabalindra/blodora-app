@extends('layouts.app')

@section('title', 'Tambah Permintaan Darah')

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
                    PERMINTAAN DARAH
                </span>

                <h1>
                    Tambah Permintaan Darah
                </h1>

                <p>
                    Tambahkan permintaan darah dari fasilitas kesehatan.
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
                        DATA PERMINTAAN
                    </span>

                    <h3>
                        Tambah Permintaan Darah
                    </h3>

                    <p>
                        Masukkan informasi permintaan darah.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('permintaan-darah.index') }}"
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
                action="{{ route('permintaan-darah.store') }}"
                method="POST"
            >

                @csrf


                <div class="row g-4">

                    {{-- FASKES --}}
                    <div class="col-12">

                        <div class="admin-form-group">

                            <label
                                for="rumah_sakit_id"
                                class="admin-form-label"
                            >
                                Faskes
                            </label>

                            <select
                                name="rumah_sakit_id"
                                id="rumah_sakit_id"
                                class="admin-form-select @error('rumah_sakit_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih faskes
                                </option>

                                @foreach ($rumahSakits as $rumahSakit)

                                    <option
                                        value="{{ $rumahSakit->id_rs }}"
                                        {{ old('rumah_sakit_id') == $rumahSakit->id_rs ? 'selected' : '' }}
                                    >
                                        {{ $rumahSakit->nama_rs }}
                                    </option>

                                @endforeach

                            </select>

                            @error('rumah_sakit_id')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror


                            @if ($rumahSakits->isEmpty())

                                <div class="admin-form-error">
                                    Belum ada data Faskes. Tambahkan Faskes terlebih dahulu.
                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- TANGGAL PERMINTAAN --}}
                    <div class="col-12">

                        <div class="admin-form-group">

                            <label
                                for="tanggal_permintaan"
                                class="admin-form-label"
                            >
                                Tanggal Permintaan
                            </label>

                            <x-admin-date-parts name="tanggal_permintaan" :value="old('tanggal_permintaan', now()->format('Y-m-d'))" />

                            @error('tanggal_permintaan')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- GOLONGAN DARAH --}}
                    <div class="col-md-6">

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
                                    Pilih golongan
                                </option>

                                @foreach (['A', 'B', 'AB', 'O'] as $golongan)

                                    <option
                                        value="{{ $golongan }}"
                                        {{ old('golongan_darah') === $golongan ? 'selected' : '' }}
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
                    <div class="col-md-6">

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
                                    Pilih rhesus
                                </option>

                                <option
                                    value="+"
                                    {{ old('rhesus') === '+' ? 'selected' : '' }}
                                >
                                    Positif (+)
                                </option>

                                <option
                                    value="-"
                                    {{ old('rhesus') === '-' ? 'selected' : '' }}
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


                    {{-- JUMLAH MINTA --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="jumlah_minta"
                                class="admin-form-label"
                            >
                                Jumlah Kantong yang Diminta
                            </label>

                            <div class="admin-input-unit">

                                <input
                                    type="number"
                                    name="jumlah_minta"
                                    id="jumlah_minta"
                                    class="admin-form-control @error('jumlah_minta') is-invalid @enderror"
                                    min="1"
                                    value="{{ old('jumlah_minta', 1) }}"
                                    required
                                >

                                <span class="admin-input-unit-label">
                                    kantong
                                </span>

                            </div>

                            @error('jumlah_minta')

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
                        href="{{ route('permintaan-darah.index') }}"
                        class="admin-btn-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="admin-btn-primary"
                    >
                        Simpan Permintaan
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
