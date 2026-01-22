@extends('layouts.app')

@section('title', 'Verify Email - Event Planner')

@section('content')
<div class="auth-container">
    <!-- Left Side - Image Background -->
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Check Your Email</h1>
            <p>We've sent you a verification link</p>
        </div>
    </div>

    <!-- Right Side - Verification Notice -->
    <div class="auth-right">
        <div class="auth-form-container">
            <!-- Brand Name -->
            <div class="auth-brand">
                <span>AAB</span>
                <span>Event Planner</span>
            </div>

            <!-- Title -->
            <h2 class="auth-title">Verify Your Email Address</h2>
            
            <!-- Verification Icon -->
            <div class="verification-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#7848F4" stroke-width="1.5">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <p class="auth-subtitle">
                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
            </p>

            <!-- Success/Error Messages -->
            @if(session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            @if(session('status') && session('status') != 'verification-link-sent')
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Resend Form -->
            <form action="{{ route('verification.send') }}" method="POST" class="verification-form">
                @csrf
                <p class="resend-text">Didn't receive the email?</p>
                <button type="submit" class="btn-submit">Resend Verification Email</button>
            </form>

            <!-- Logout Option -->
            <div class="auth-footer">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="auth-link-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.verification-icon {
    text-align: center;
    margin: 30px 0;
}

.verification-form {
    margin-top: 20px;
}

.resend-text {
    text-align: center;
    color: #667085;
    margin-bottom: 15px;
}

.auth-link-btn {
    background: none;
    border: none;
    color: #7848F4;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: color 0.3s;
}

.auth-link-btn:hover {
    color: #5c35c4;
}

.auth-subtitle {
    text-align: center;
    color: #667085;
    line-height: 1.6;
    margin-bottom: 20px;
}
</style>
@endsection
