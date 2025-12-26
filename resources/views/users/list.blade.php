@extends('layouts.app')

@section('title', 'Users - AAB Event Planner')

@section('content')
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">Users List</h1>
            @include('components.admin-nav')
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Users Table Card -->
        <div class="events-table-card">
            <div class="table-header">
                <h2 class="table-title">Users</h2>
                @can('create users')
                    <a href="{{ route('admin.users.create') }}" class="btn-create-event">Create user</a>
                @endcan
            </div>

            <div class="table-container">
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th class="actions-column"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: #F2F4F7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <span style="font-size: 16px; color: #667085; font-weight: 600;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="event-name">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span style="background: #F2F4F7; color: #344054; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-right: 4px;">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span style="color: #667085;">-</span>
                                    @endif
                                </td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    @can('edit users')
                                        <div class="action-menu" id="actionMenu{{ $user->id }}">
                                            <button class="action-menu-toggle" onclick="toggleActionMenu({{ $user->id }})">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 9.5C8.82843 9.5 9.5 8.82843 9.5 8C9.5 7.17157 8.82843 6.5 8 6.5C7.17157 6.5 6.5 7.17157 6.5 8C6.5 8.82843 7.17157 9.5 8 9.5Z" fill="#667085"/>
                                                    <path d="M8 4.5C8.82843 4.5 9.5 3.82843 9.5 3C9.5 2.17157 8.82843 1.5 8 1.5C7.17157 1.5 6.5 2.17157 6.5 3C6.5 3.82843 7.17157 4.5 8 4.5Z" fill="#667085"/>
                                                    <path d="M8 14.5C8.82843 14.5 9.5 13.8284 9.5 13C9.5 12.1716 8.82843 11.5 8 11.5C7.17157 11.5 6.5 12.1716 6.5 13C6.5 13.8284 7.17157 14.5 8 14.5Z" fill="#667085"/>
                                                </svg>
                                            </button>
                                            <div class="action-menu-dropdown">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="action-menu-item">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.3333 2.00004C11.5084 1.82493 11.7163 1.68605 11.9444 1.5913C12.1726 1.49655 12.4166 1.44775 12.6629 1.44775C12.9092 1.44775 13.1532 1.49655 13.3814 1.5913C13.6095 1.68605 13.8174 1.82493 13.9925 2.00004C14.1676 2.17515 14.3065 2.3831 14.4012 2.61124C14.496 2.83938 14.5448 3.08341 14.5448 3.32971C14.5448 3.57601 14.496 3.82004 14.4012 4.04818C14.3065 4.27632 14.1676 4.48427 13.9925 4.65938L5.17583 13.476L1.33333 14.6667L2.524 10.8242L11.3333 2.00004Z" stroke="#344054" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                @can('delete users')
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-menu-item archive">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M2.66667 4H13.3333M12.6667 4V12.6667C12.6667 13.0203 12.5262 13.3594 12.2761 13.6095C12.026 13.8596 11.6869 14 11.3333 14H4.66667C4.31305 14 3.97391 13.8596 3.72381 13.6095C3.47371 13.3594 3.33333 13.0203 3.33333 12.6667V4M5.33333 4V2.66667C5.33333 2.31305 5.47371 1.97391 5.72381 1.72381C5.97391 1.47371 6.31305 1.33333 6.66667 1.33333H9.33333C9.68696 1.33333 10.0261 1.47371 10.2762 1.72381C10.5263 1.97391 10.6667 2.31305 10.6667 2.66667V4" stroke="#D92D20" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <p>No users found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div style="padding: 20px; display: flex; justify-content: center;">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Action Menu Functions
function toggleActionMenu(userId) {
    // Close all other menus
    document.querySelectorAll('.action-menu-dropdown').forEach(menu => {
        if (menu.id !== 'actionMenu' + userId + 'Dropdown') {
            menu.classList.remove('active');
        }
    });
    
    // Toggle current menu
    const menu = document.getElementById('actionMenu' + userId).querySelector('.action-menu-dropdown');
    menu.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.action-menu')) {
        document.querySelectorAll('.action-menu-dropdown').forEach(menu => {
            menu.classList.remove('active');
        });
    }
});
</script>
@endpush
@endsection

