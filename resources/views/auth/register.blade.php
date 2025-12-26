@extends('layouts.app')

@section('title', 'Register - Event Planner')

@section('content')
<div class="auth-container">
    <!-- Left Side - Image Background -->
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Hello friend</h1>
            <p>To keep connected with us provide us with your information</p>
            <a href="{{ route('login') }}" class="auth-left-btn">Login</a>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="auth-right">
        <div class="auth-form-container">
            <!-- Brand Name -->
            <div class="auth-brand">
                <span>AAB</span>
                <span>Event Planner</span>
            </div>

            <!-- Title -->
            <h2 class="auth-title">Sign Up to Event Planner</h2>

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

            <!-- Register Form -->
            <form action="{{ route('toRegister') }}" method="POST">
                @csrf

                <!-- Name Field -->
                <div class="form-group">
                    <label class="form-label">Your name</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}"
                        placeholder="Enter your name" 
                        required
                        class="form-input"
                    >
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

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

                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label">Password</label>
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

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label class="form-label">Confirm password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        placeholder="Enter your password" 
                        required
                        class="form-input"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">Register</button>
            </form>
        </div>
    </div>
</div>
@endsection
