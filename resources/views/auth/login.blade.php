@extends('layouts.auth')

@section('title', 'Login - Blodora')

@section('content')

    <div class="auth-topbar">

        <a href="/" class="auth-back">
            ← Back
        </a>

        <div class="auth-switch">
            <span>Belum punya akun?</span>

            <a href="{{ route('register') }}" class="auth-register-link">
                Register
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
                Welcome
            </h1>

            <p class="auth-subtitle">
                Login to your account
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


            <!-- Username -->

            <div class="auth-field">

                <label
                    for="username"
                    class="auth-label"
                >
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    class="auth-input"
                    value="{{ old('username') }}"
                    placeholder="Enter your username"
                    required
                    autofocus
                >

            </div>


            <!-- Password -->

            <div class="auth-field">

                <label
                    for="password"
                    class="auth-label"
                >
                    Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input"
                        placeholder="Enter your password"
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
                        Remember me
                    </span>

                </label>

                <a
                    href="{{ route('password.request') }}"
                    class="auth-forgot"
                >
                    Forgot password?
                </a>

            </div>


            <!-- Submit -->

            <button
                type="submit"
                class="btn-blodora"
            >
                <span class="btn-text">
                    Login
                </span>

                <span class="btn-loading d-none">
                    <span class="auth-spinner"></span>
                    Logging in...
                </span>
            </button>

        </form>


        <div class="auth-divider">
            OR
        </div>


        <button
            type="button"
            class="btn-google"
        >
            <span class="google-icon">
                G
            </span>

            Continue with Google
        </button>


        <div class="auth-footer">
            Need help?
        </div>

    </div>

@endsection