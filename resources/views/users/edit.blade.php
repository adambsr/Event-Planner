@extends('layouts.app')

@section('title', 'Edit User - AAB Event Planner')

@section('content')
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">Edit User</h1>
            @include('components.admin-nav')
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="events-table-card">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="padding: 24px;">

                    <!-- Name & Email -->
                    <div style="display: flex; gap: 24px; margin-bottom: 24px; flex-wrap: wrap;">
                        <div class="form-group" style="flex: 1; min-width: 280px;">
                            <label class="form-label">Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-input" 
                                placeholder="Enter user name"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                        </div>

                        <div class="form-group" style="flex: 1; min-width: 280px;">
                            <label class="form-label">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-input" 
                                placeholder="Enter email address"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password & Confirm Password -->
                    <div style="display: flex; gap: 24px; margin-bottom: 24px; flex-wrap: wrap;">
                        <div class="form-group" style="flex: 1; min-width: 280px;">
                            <label class="form-label">
                                Password (Leave blank to keep current password)
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Enter new password"
                                minlength="8"
                            >
                        </div>

                        <div class="form-group" style="flex: 1; min-width: 280px;">
                            <label class="form-label">Confirm Password</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="form-input" 
                                placeholder="Confirm new password"
                                minlength="8"
                            >
                        </div>
                    </div>

                    <!-- Role & Phone -->
                    <div style="display: flex; gap: 24px; margin-bottom: 24px; flex-wrap: wrap;">
                        <div class="form-group" style="flex: 1; min-width: 280px;">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-input" required>
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="flex: 1; min-width: 280px;">
                            <label class="form-label">Phone (Optional)</label>
                            <input 
                                type="text" 
                                name="phone" 
                                class="form-input" 
                                placeholder="Enter phone number"
                                value="{{ old('phone', $user->phone) }}"
                            >
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        <a href="{{ route('users.index') }}" class="btn btn-outline">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update User
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
