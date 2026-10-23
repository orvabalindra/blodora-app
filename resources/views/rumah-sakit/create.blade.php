@extends('layouts.app')

@section('title', 'Tambah Faskes')

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
                    DATA FASKES
                </span>

                <h1>
                    Tambah Faskes
                </h1>

                <p>
                    Tambahkan data rumah sakit atau fasilitas kesehatan.
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
                        DATA RUMAH SAKIT / FASKES
                    </span>

                    <h3>
                        Tambah Faskes
                    </h3>

                    <p>
                        Masukkan informasi fasilitas kesehatan.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('rumah-sakit.index') }}"
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
                action="{{ route('rumah-sakit.store') }}"
                method="POST"
            >

                @csrf


                <div class="row g-4">

                    {{-- NAMA FASKES --}}
                    <div class="col-12">

                        <div class="admin-form-group">

                            <label
                                for="nama_rs"
                                class="admin-form-label"
                            >
                                Nama Faskes
                            </label>

                            <input
                                type="text"
                                name="nama_rs"
                                id="nama_rs"
                                class="admin-form-control @error('nama_rs') is-invalid @enderror"
                                value="{{ old('nama_rs') }}"
                                placeholder="Contoh: RSUD Kota Malang"
                                required
                            >

                            @error('nama_rs')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- ALAMAT --}}
                    <div class="col-12">

                        <div class="admin-form-group">

                            <label
                                for="alamat"
                                class="admin-form-label"
                            >
                                Alamat
                            </label>

                            <textarea
                                name="alamat"
                                id="alamat"
                                class="admin-form-textarea @error('alamat') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap faskes"
                                required
                            >{{ old('alamat') }}</textarea>

                            @error('alamat')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- NOMOR TELEPON --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="no_telp"
                                class="admin-form-label"
                            >
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                name="no_telp"
                                id="no_telp"
                                class="admin-form-control @error('no_telp') is-invalid @enderror"
                                value="{{ old('no_telp') }}"
                                placeholder="Contoh: 0341123456"
                                required
                            >

                            @error('no_telp')

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
                        href="{{ route('rumah-sakit.index') }}"
                        class="admin-btn-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="admin-btn-primary"
                    >
                        Simpan Faskes
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
