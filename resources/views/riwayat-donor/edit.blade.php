@extends('layouts.app')

@section('title', 'Ubah Riwayat Donor')

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
                    RIWAYAT DONOR
                </span>

                <h1>
                    Ubah Riwayat Donor
                </h1>

                <p>
                    Ubah data aktivitas donor darah.
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
                        EDIT DATA DONOR
                    </span>

                    <h3>
                        Ubah Riwayat Donor
                    </h3>

                    <p>
                        Perbarui informasi aktivitas donor.
                    </p>

                </div>


                <div class="admin-page-actions">

                    <a
                        href="{{ route('riwayat-donor.show', $riwayatDonor) }}"
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
                action="{{ route('riwayat-donor.update', $riwayatDonor) }}"
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
                                        {{ old(
                                            'pendonor_id',
                                            $riwayatDonor->pendonor_id
                                        ) == $pendonor->id ? 'selected' : '' }}
                                    >
                                        {{ $pendonor->nama_lengkap }}
                                        —
                                        {{ $pendonor->golongan_darah }}{{ $pendonor->rhesus }}
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


                    {{-- PEMERIKSAAN --}}
                    <div class="col-12">

                        <div class="admin-form-group">

                            <label
                                for="pemeriksaan_id"
                                class="admin-form-label"
                            >
                                Pemeriksaan
                            </label>

                            <select
                                name="pemeriksaan_id"
                                id="pemeriksaan_id"
                                class="admin-form-select @error('pemeriksaan_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Tidak terkait pemeriksaan
                                </option>

                                @foreach ($pemeriksaans as $pemeriksaan)

                                    <option
                                        value="{{ $pemeriksaan->id_pemeriksaan }}"
                                        {{ old(
                                            'pemeriksaan_id',
                                            $riwayatDonor->pemeriksaan_id
                                        ) == $pemeriksaan->id_pemeriksaan ? 'selected' : '' }}
                                    >
                                        —
                                        {{ $pemeriksaan->pendonor?->nama_lengkap ?? 'Pendonor tidak ditemukan' }}
                                        —
                                        {{ ucfirst($pemeriksaan->hasil) }}
                                    </option>

                                @endforeach

                            </select>

                            @error('pemeriksaan_id')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- TANGGAL DONOR --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="tanggal_donor"
                                class="admin-form-label"
                            >
                                Tanggal Donor
                            </label>

                            <x-admin-date-parts name="tanggal_donor" :value="old('tanggal_donor', optional($riwayatDonor->tanggal_donor)->format('Y-m-d'))" />

                            @error('tanggal_donor')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- JUMLAH DONOR --}}
                    <div class="col-md-6">

                        <div class="admin-form-group">

                            <label
                                for="jumlah_donor"
                                class="admin-form-label"
                            >
                                Jumlah Donor
                            </label>

                            <div class="admin-input-unit">

                                <input
                                    type="number"
                                    name="jumlah_donor"
                                    id="jumlah_donor"
                                    class="admin-form-control @error('jumlah_donor') is-invalid @enderror"
                                    min="1"
                                    value="{{ old(
                                        'jumlah_donor',
                                        $riwayatDonor->jumlah_donor
                                    ) }}"
                                    required
                                >

                                <span class="admin-input-unit-label">
                                    kantong
                                </span>

                            </div>

                            @error('jumlah_donor')

                                <div class="admin-form-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-12">

                        <div class="admin-form-group">

                            <label
                                for="status"
                                class="admin-form-label"
                            >
                                Status Donor
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="admin-form-select @error('status') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Pilih status
                                </option>

                                <option
                                    value="sukses"
                                    {{ old(
                                        'status',
                                        $riwayatDonor->status
                                    ) === 'sukses' ? 'selected' : '' }}
                                >
                                    Sukses
                                </option>

                                <option
                                    value="gagal"
                                    {{ old(
                                        'status',
                                        $riwayatDonor->status
                                    ) === 'gagal' ? 'selected' : '' }}
                                >
                                    Gagal
                                </option>

                            </select>

                            @error('status')

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
                        href="{{ route('riwayat-donor.show', $riwayatDonor) }}"
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
