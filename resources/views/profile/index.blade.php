@extends('layouts.app')

@section('title', 'My Profile - AAB Event Planner')

@section('content')
@php
    $isAdminOrManager = auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager');
@endphp

@if($isAdminOrManager)
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">My Profile</h1>
            @include('components.admin-nav')
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
@else
<div class="container">
    <div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
        <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 32px;">My Profile</h1>

        @if(session('success'))
            <div style="background:#D1FAE5;padding:12px;border-radius:8px;margin-bottom:24px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#FEE2E2;padding:12px;border-radius:8px;margin-bottom:24px;">
                {{ session('error') }}
            </div>
        @endif
@endif

<!-- TOP ROW: Avatar + Profile Info -->
<div style="display:flex; gap:24px; flex-wrap:wrap; margin-bottom:24px;">

    <!-- Profile Image -->
    <div class="events-table-card" style="flex:1; min-width:320px;">
        <h2 class="table-title" style="padding:24px 24px 0;">Profile Image</h2>
        <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="padding:24px;">
                <div style="display:flex; justify-content:center; margin-bottom:24px;">
                    <div style="width:190px;height:190px;border-radius:50%;overflow:hidden;background:#F2F4F7;display:flex;align-items:center;justify-content:center;">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <span style="font-size:48px;font-weight:600;">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </span>
                        @endif
                    </div>
                    <!-- <div>
                        <p style="font-weight:500;">Current Profile Image</p>
                        <p style="color:#667085;">Max 2MB — JPG, PNG, GIF</p>
                    </div> -->
                </div>

                <input type="file" name="avatar" class="form-input" required>
                <button class="btn btn-primary" style="margin-top:24px;">Update Avatar</button>
            </div>
        </form>
    </div>

    <!-- Profile Information -->
    <div class="events-table-card" style="flex:1; min-width:320px;">
        <h2 class="table-title" style="padding:24px 24px 0;">Profile Information</h2>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div style="padding:24px;">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-input" value="{{ $user->name }}" required>
                </div>

                <div class="form-group" style="margin-top:24px;">
                    <label>Email</label>
                    <input type="email" name="email" class="form-input" value="{{ $user->email }}" required>
                </div>

                <div class="form-group" style="margin-top:24px;">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-input" value="{{ $user->phone }}">
                </div>

                <button class="btn btn-primary" style="margin-top:32px;">Update Profile</button>
            </div>
        </form>
    </div>

</div>

<!-- BOTTOM: Password -->
<div class="events-table-card">
    <h2 class="table-title" style="padding:24px 24px 0;">Change Password</h2>
    <form action="{{ route('profile.password.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div style="padding:24px;">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-input" placeholder="Enter current password" required>
            </div>

            <div class="form-group" style="margin-top:24px;">
                <label>New Password</label>
                <input type="password" name="password" class="form-input" placeholder="Enter new password" required minlength="8">
            </div>

            <div class="form-group" style="margin-top:24px;">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm new password" required>
            </div>

            <button class="btn btn-primary" style="margin-top:32px;">Update Password</button>
        </div>
    </form>
</div>

@if($isAdminOrManager)
    </div>
</div>
@else
    </div>
</div>
@endif
@endsection
