@extends('layouts.auth')

@section('title', 'Forgot Password - Blodora')

@section('content')

    <div class="auth-topbar">

        <a
            href="{{ route('login') }}"
            class="auth-back"
        >
            ← Back
        </a>

        <div class="auth-switch">

            <span>
                Remember your password?
            </span>

            <a
                href="{{ route('login') }}"
                class="auth-login-link"
            >
                Login
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
                Forgot Password?
            </h1>

            <p class="auth-subtitle">
                Enter your email to receive a reset link
            </p>

        </div>


        @if (session('status'))

            <div class="auth-success">
                {{ session('status') }}
            </div>

        @endif


        @if ($errors->any())

            <div class="auth-error">
                {{ $errors->first() }}
            </div>

        @endif


        <form
            action="{{ route('password.email') }}"
            method="POST"
            class="auth-form"
        >

            @csrf


            <div class="auth-field">

                <label
                    for="email"
                    class="auth-label"
                >
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    autofocus
                >

            </div>


            <button
                type="submit"
                class="btn-blodora"
            >

                <span class="btn-text">
                    Send Reset Link
                </span>

                <span class="btn-loading d-none">
                    <span class="auth-spinner"></span>
                    Sending...
                </span>

            </button>

        </form>


        <div class="auth-footer">

            Remember your password?

            <a href="{{ route('login') }}">
                Login
            </a>

        </div>

    </div>

@endsection