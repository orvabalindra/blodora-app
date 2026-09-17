@extends('layouts.app')

@section('title', 'Tambah Pemeriksaan')

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
                    Tambah Pemeriksaan
                </h1>

                <p>
                    Tambahkan data pemeriksaan kesehatan pendonor.
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
                        DATA PEMERIKSAAN
                    </span>

                    <h3>
                        Tambah Pemeriksaan
                    </h3>

                    <p>
                        Masukkan hasil pemeriksaan pendonor.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('pemeriksaan.index') }}"
                        class="admin-btn-secondary"
                    >
                        Kembali
                    </a>

                </div>

            </div>


            {{-- ERROR VALIDASI --}}
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
                action="{{ route('pemeriksaan.store') }}"
                method="POST"
            >

                @csrf


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


                            @if ($pendonors->isEmpty())

                                <div class="admin-form-error">
                                    Belum ada data pendonor. Silakan tambahkan pendonor terlebih dahulu.
                                </div>

                            @endif

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

                            <input
                                type="date"
                                name="tanggal_pemeriksaan"
                                id="tanggal_pemeriksaan"
                                class="admin-form-control @error('tanggal_pemeriksaan') is-invalid @enderror"
                                value="{{ old('tanggal_pemeriksaan', now()->format('Y-m-d')) }}"
                                required
                            >

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
                                    value="{{ old('berat_badan') }}"
                                    min="1"
                                    step="1"
                                    placeholder="Contoh: 60"
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
                                value="{{ old('tekanan_darah') }}"
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
                                value="{{ old('hemoglobin') }}"
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


                    {{-- HASIL --}}
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
                                    {{ old('hasil') === 'layak' ? 'selected' : '' }}
                                >
                                    Layak
                                </option>

                                <option
                                    value="tidak layak"
                                    {{ old('hasil') === 'tidak layak' ? 'selected' : '' }}
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
                        Simpan Pemeriksaan
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