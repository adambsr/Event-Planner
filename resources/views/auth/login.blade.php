@extends('layouts.app')

@section('title', 'Login - Event Planner')

@section('content')
<div class="auth-container">
    <!-- Left Side - Image Background -->
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Welcome Back</h1>
            <p>To keep connected with us provide us with your information</p>
            <a href="{{ route('register') }}" class="auth-left-btn">Register</a>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="auth-right">
        <div class="auth-form-container">
            <!-- Brand Name -->
            <div class="auth-brand">
                <span>AAB</span>
                <span>Event Planner</span>
            </div>

            <!-- Title -->
            <h2 class="auth-title">Sign In to Event Planner</h2>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
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

            <!-- Login Form -->
            <form action="{{ route('toLogin') }}" method="POST">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label">Your email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="Enter your mail" 
                        required
                        class="form-input"
                    >
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <div class="password-header">
                        <label class="form-label">Your password</label>
                        <a href="#" class="forgot-password">Forgot your password?</a>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        required
                        class="form-input"
                    >
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection
