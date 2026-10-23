@extends('layouts.auth')

@section('title', 'Daftar - Blodora')

@section('content')

    <div class="auth-topbar">

        <a href="/" class="auth-back">
            ← Kembali
        </a>

        <div class="auth-switch">

            <span>
                Sudah punya akun?
            </span>

            <a
                href="{{ route('login') }}"
                class="auth-login-link"
            >
                Masuk
            </a>

        </div>

    </div>


    <div class="auth-card">

        <div class="auth-header">

            <img
                src="{{ asset('images/Blodora2.png') }}"
                alt="Blodora"
                class="auth-logo"
            >

            <h1 class="auth-title">
                Buat Akun
            </h1>

            <p class="auth-subtitle">
                Daftar untuk memulai
            </p>

        </div>


        @if ($errors->any())

            <div class="auth-error">

                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach

            </div>

        @endif


        <form
            action="{{ route('register.process') }}"
            method="POST"
            class="auth-form"
        >

            @csrf


            <!-- Nama pengguna -->

            <div class="auth-field">

                <label
                    for="username"
                    class="auth-label"
                >
                    Nama pengguna
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    class="auth-input"
                    value="{{ old('username') }}"
                    placeholder="Pilih nama pengguna"
                    required
                    autofocus
                >

            </div>


            <!-- Alamat email -->

            <div class="auth-field">

                <label
                    for="email"
                    class="auth-label"
                >
                    Alamat email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input"
                    value="{{ old('email') }}"
                    placeholder="Masukkan alamat email"
                    required
                >

            </div>


            <!-- Kata sandi -->

            <div class="auth-field">

                <label
                    for="password"
                    class="auth-label"
                >
                    Kata sandi
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input"
                    placeholder="Buat kata sandi"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-password-toggle="password"
                        aria-label="Tampilkan password"
                    >
                        <i class="bi bi-eye"></i>
                    </button>

                </div>

            </div>


            <!-- Confirm Kata sandi -->

            <div class="auth-field">

                <label
                    for="password_confirmation"
                    class="auth-label"
                >
                    Konfirmasi kata sandi
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="auth-input"
                        placeholder="Masukkan kembali kata sandi"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-password-toggle="password_confirmation"
                        aria-label="Tampilkan konfirmasi password"
                    >
                        <i class="bi bi-eye"></i>
                    </button>

                </div>

            </div>


            <!-- Submit -->

            <button
                type="submit"
                class="btn-blodora"
            >
                <span class="btn-text">
                    Daftar
                </span>

                <span class="btn-loading d-none">
                    <span class="auth-spinner"></span>
                    Creating account...
                </span>
            </button>

        </form>


        <div class="auth-footer">
            Dengan mendaftar, Anda menyetujui ketentuan yang berlaku.
        </div>

    </div>

@endsection
