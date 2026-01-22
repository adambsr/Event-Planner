@extends('layouts.app')

@section('title', 'Forgot Password - Event Planner')

@section('content')
<div class="auth-container">
    <!-- Left Side - Image Background -->
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Forgot Password?</h1>
            <p>No worries, we'll send you reset instructions</p>
            <a href="{{ route('login') }}" class="auth-left-btn">Back to Login</a>
        </div>
    </div>

    <!-- Right Side - Forgot Password Form -->
    <div class="auth-right">
        <div class="auth-form-container">
            <!-- Brand Name -->
            <div class="auth-brand">
                <span>AAB</span>
                <span>Event Planner</span>
            </div>

            <!-- Title -->
            <h2 class="auth-title">Reset Your Password</h2>
            <p class="auth-subtitle">Enter your email address and we'll send you a link to reset your password.</p>

            <!-- Success/Error Messages -->
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Forgot Password Form -->
            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label">Your email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="Enter your email" 
                        required
                        class="form-input"
                    >
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">Send Reset Link</button>
            </form>

            <!-- Back to Login Link -->
            <div class="auth-footer">
                <a href="{{ route('login') }}" class="auth-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
