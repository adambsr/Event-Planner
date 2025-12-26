@auth
<div class="user-dropdown" id="userDropdown">
    <button class="user-dropdown-toggle" onclick="toggleUserDropdown()">
        <div class="user-avatar-wrapper">
            <div class="user-avatar">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <span class="user-status-indicator"></span>
        </div>
        <div class="user-info">
            <span class="user-name">{{ auth()->user()->name }}</span>
            <span class="user-email">{{ auth()->user()->email }}</span>
        </div>
        <span class="user-dropdown-arrow"></span>
    </button>
    <div class="user-dropdown-menu">
        <!-- Profile Header in Dropdown -->
        <div class="user-dropdown-header">
            <div class="user-avatar-wrapper">
                <div class="user-avatar">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <span class="user-status-indicator"></span>
            </div>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="user-email">{{ auth()->user()->email }}</span>
            </div>
        </div>
        
        <!-- Dropdown Items -->
        <div class="user-dropdown-divider"></div>
        <a href="{{ route('profile.index') }}" class="user-dropdown-item">View profile</a>
        <a href="{{ route('registrations.my') }}" class="user-dropdown-item">Registrations</a>
        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('events.list') }}" class="user-dropdown-item admin">Admin Panel</a>
        @elseif(auth()->user()->hasRole('manager'))
            <a href="{{ route('events.list') }}" class="user-dropdown-item admin">Manager Panel</a>
        @endif
        <div class="user-dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="user-dropdown-item logout" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; padding: 12px 16px;">Log out</button>
        </form>
    </div>
</div>

<script>
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown && !dropdown.contains(event.target)) {
        dropdown.classList.remove('active');
    }
});
</script>
@endauth
