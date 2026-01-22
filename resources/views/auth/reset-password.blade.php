@extends('layouts.app')

@section('title', 'Reset Password - Event Planner')

@section('content')
<div class="auth-container">
    <!-- Left Side - Image Background -->
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Set New Password</h1>
            <p>Create a strong password for your account</p>
            <a href="{{ route('login') }}" class="auth-left-btn">Back to Login</a>
        </div>
    </div>

    <!-- Right Side - Reset Password Form -->
    <div class="auth-right">
        <div class="auth-form-container">
            <!-- Brand Name -->
            <div class="auth-brand">
                <span>AAB</span>
                <span>Event Planner</span>
            </div>

            <!-- Title -->
            <h2 class="auth-title">Create New Password</h2>
            <p class="auth-subtitle">Your new password must be different from previously used passwords.</p>

            <!-- Success/Error Messages -->
            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Reset Password Form -->
            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <!-- Hidden Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label">Your email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $email) }}"
                        placeholder="Enter your email" 
                        required
                        class="form-input"
                    >
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Enter new password" 
                        required
                        class="form-input"
                    >
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        placeholder="Confirm new password" 
                        required
                        class="form-input"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">Reset Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
