@extends('layouts.app')

@section('title', 'Edit Pendonor')

@section('content')

<div class="admin-dashboard">

    {{-- SIDEBAR --}}
    @include('components.admin-sidebar')


    {{-- MAIN CONTENT --}}
    <main class="admin-main">

        {{-- TOPBAR --}}
        <div class="admin-topbar">

            <div>

                <span class="admin-page-label">
                    DATA MASTER
                </span>

                <h1>
                    Edit Pendonor
                </h1>

                <p>
                    Perbarui informasi data pendonor.
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
                    {{ strtoupper(
                        substr(
                            Auth::user()->username ?? 'A',
                            0,
                            1
                        )
                    ) }}
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


            {{-- FORM --}}
            <form
                action="{{ route('pendonor.update', $pendonor->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="row g-4">

                    {{-- NIK --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="nik"
                                class="admin-form-label"
                            >
                                NIK
                            </label>

                            <input
                                type="text"
                                id="nik"
                                name="nik"
                                class="admin-form-control @error('nik') is-invalid @enderror"
                                value="{{ old('nik', $pendonor->nik) }}"
                                required
                            >

                            @error('nik')
                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- NAMA LENGKAP --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="nama_lengkap"
                                class="admin-form-label"
                            >
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                id="nama_lengkap"
                                name="nama_lengkap"
                                class="admin-form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $pendonor->nama_lengkap) }}"
                                required
                            >

                            @error('nama_lengkap')
                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- NOMOR HP --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="no_hp"
                                class="admin-form-label"
                            >
                                Nomor HP
                            </label>

                            <input
                                type="text"
                                id="no_hp"
                                name="no_hp"
                                class="admin-form-control @error('no_hp') is-invalid @enderror"
                                value="{{ old('no_hp', $pendonor->no_hp) }}"
                                required
                            >

                            @error('no_hp')
                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- BERAT BADAN --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="berat_badan"
                                class="admin-form-label"
                            >
                                Berat Badan
                            </label>

                            <div class="admin-input-unit">

                                <input
                                    type="number"
                                    id="berat_badan"
                                    name="berat_badan"
                                    class="admin-form-control @error('berat_badan') is-invalid @enderror"
                                    value="{{ old('berat_badan', $pendonor->berat_badan) }}"
                                    min="1"
                                    required
                                >

                                <span class="admin-input-unit-label">
                                    kg
                                </span>

                            </div>

                            @error('berat_badan')
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
                                id="golongan_darah"
                                name="golongan_darah"
                                class="admin-form-select @error('golongan_darah') is-invalid @enderror"
                                required
                            >

                                <option value="A"
                                    {{ old('golongan_darah', $pendonor->golongan_darah) === 'A' ? 'selected' : '' }}>
                                    A
                                </option>

                                <option value="B"
                                    {{ old('golongan_darah', $pendonor->golongan_darah) === 'B' ? 'selected' : '' }}>
                                    B
                                </option>

                                <option value="AB"
                                    {{ old('golongan_darah', $pendonor->golongan_darah) === 'AB' ? 'selected' : '' }}>
                                    AB
                                </option>

                                <option value="O"
                                    {{ old('golongan_darah', $pendonor->golongan_darah) === 'O' ? 'selected' : '' }}>
                                    O
                                </option>

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
                                id="rhesus"
                                name="rhesus"
                                class="admin-form-select @error('rhesus') is-invalid @enderror"
                                required
                            >

                                <option value="+"
                                    {{ old('rhesus', $pendonor->rhesus) === '+' ? 'selected' : '' }}>
                                    Positif (+)
                                </option>

                                <option value="-"
                                    {{ old('rhesus', $pendonor->rhesus) === '-' ? 'selected' : '' }}>
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
                                id="alamat"
                                name="alamat"
                                class="admin-form-textarea @error('alamat') is-invalid @enderror"
                                rows="4"
                                required
                            >{{ old('alamat', $pendonor->alamat) }}</textarea>

                            @error('alamat')
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
                        href="{{ route('pendonor.index') }}"
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
                Blodora Admin Panel
            </span>

            <span>
                Sistem Donor Darah
            </span>

        </div>

    </main>

</div>

@endsection