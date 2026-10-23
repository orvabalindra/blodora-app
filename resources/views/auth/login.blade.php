@extends('layouts.auth')

@section('title', 'Masuk - Blodora')

@section('content')

    <div class="auth-topbar">

        <a href="/" class="auth-back">
            ← Kembali
        </a>

        <div class="auth-switch">
            <span>Belum punya akun?</span>

            <a href="{{ route('register') }}" class="auth-register-link">
                Daftar
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
                Selamat Datang
            </h1>

            <p class="auth-subtitle">
                Masuk ke akun Anda
            </p>

        </div>


        @if ($errors->any())
            <div class="auth-error">
                {{ $errors->first() }}
            </div>
        @endif


        @if (session('success'))
            <div class="auth-success">
                {{ session('success') }}
            </div>
        @endif


        <form
            action="{{ route('login.process') }}"
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
                    placeholder="Masukkan nama pengguna"
                    required
                    autofocus
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
                        placeholder="Masukkan kata sandi"
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


            <!-- Options -->

            <div class="auth-options">

                <label class="auth-remember">

                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                    >

                    <span>
                        Ingat saya
                    </span>

                </label>

                <a
                    href="{{ route('password.request') }}"
                    class="auth-forgot"
                >
                    Lupa kata sandi?
                </a>

            </div>


            <!-- Submit -->

            <button
                type="submit"
                class="btn-blodora"
            >
                <span class="btn-text">
                    Masuk
                </span>

                <span class="btn-loading d-none">
                    <span class="auth-spinner"></span>
                    Sedang masuk...
                </span>
            </button>

        </form>


        <div class="auth-footer">
            Butuh bantuan?
        </div>

    </div>

@endsection
