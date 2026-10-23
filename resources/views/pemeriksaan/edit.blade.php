@extends('layouts.app')

@section('title', 'Ubah Pemeriksaan')

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
                    PEMERIKSAAN
                </span>

                <h1>
                    Ubah Pemeriksaan
                </h1>

                <p>
                    Ubah data hasil pemeriksaan pendonor.
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
                        EDIT PEMERIKSAAN
                    </span>

                    <h3>
                        Ubah Data Pemeriksaan
                    </h3>

                    <p>
                        Perbarui informasi pemeriksaan pendonor.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('pemeriksaan.show', $pemeriksaan) }}"
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
                action="{{ route('pemeriksaan.update', $pemeriksaan) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="row g-4">

                    {{-- PENDONOR --}}
                    <div class="col-12">

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
                                        {{ old('pendonor_id', $pemeriksaan->pendonor_id) == $pendonor->id ? 'selected' : '' }}
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


                    {{-- TANGGAL PEMERIKSAAN --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="tanggal_pemeriksaan"
                                class="admin-form-label"
                            >
                                Tanggal Pemeriksaan
                            </label>

                            <x-admin-date-parts name="tanggal_pemeriksaan" :value="old('tanggal_pemeriksaan', optional($pemeriksaan->tanggal_pemeriksaan)->format('Y-m-d'))" />

                            @error('tanggal_pemeriksaan')

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
                                    name="berat_badan"
                                    id="berat_badan"
                                    class="admin-form-control @error('berat_badan') is-invalid @enderror"
                                    value="{{ old('berat_badan', $pemeriksaan->berat_badan) }}"
                                    min="1"
                                    step="1"
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


                    {{-- TEKANAN DARAH --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="tekanan_darah"
                                class="admin-form-label"
                            >
                                Tekanan Darah
                            </label>

                            <input
                                type="text"
                                name="tekanan_darah"
                                id="tekanan_darah"
                                class="admin-form-control @error('tekanan_darah') is-invalid @enderror"
                                value="{{ old('tekanan_darah', $pemeriksaan->tekanan_darah) }}"
                                placeholder="Contoh: 120/80"
                                required
                            >

                            @error('tekanan_darah')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- HEMOGLOBIN --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="hemoglobin"
                                class="admin-form-label"
                            >
                                Hemoglobin (g/dL)
                            </label>

                            <input
                                type="number"
                                name="hemoglobin"
                                id="hemoglobin"
                                class="admin-form-control @error('hemoglobin') is-invalid @enderror"
                                value="{{ old('hemoglobin', $pemeriksaan->hemoglobin) }}"
                                min="0"
                                max="30"
                                step="0.1"
                                placeholder="Contoh: 13.5"
                                required
                            >

                            @error('hemoglobin')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- HASIL PEMERIKSAAN --}}
                    <div class="col-12">

                        <div class="admin-form-group">

                            <label
                                for="hasil"
                                class="admin-form-label"
                            >
                                Hasil Pemeriksaan
                            </label>

                            <select
                                name="hasil"
                                id="hasil"
                                class="admin-form-select @error('hasil') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih hasil
                                </option>

                                <option
                                    value="layak"
                                    {{ old('hasil', $pemeriksaan->hasil) === 'layak' ? 'selected' : '' }}
                                >
                                    Layak
                                </option>

                                <option
                                    value="tidak layak"
                                    {{ old('hasil', $pemeriksaan->hasil) === 'tidak layak' ? 'selected' : '' }}
                                >
                                    Tidak Layak
                                </option>

                            </select>

                            @error('hasil')

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
                        href="{{ route('pemeriksaan.index') }}"
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
