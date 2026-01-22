<header class="header">
    <div class="header-container">
        <a href="{{ route('home') }}" class="logo">
            <span>AAB</span>
            <span>Event Planner</span>
        </a>
        <div class="auth-buttons">
            @auth
                <div class="header-quick-actions">
                    <a href="{{ route('registrations.my') }}" class="header-icon-btn" title="My Registrations">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 2V4M14 2V4M3 8H17M5 4H15C16.1046 4 17 4.89543 17 6V16C17 17.1046 16.1046 18 15 18H5C3.89543 18 3 17.1046 3 16V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="header-icon-label">My Events</span>
                    </a>
                    <a href="{{ route('profile.index') }}" class="header-icon-btn" title="My Profile">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 11C12.2091 11 14 9.20914 14 7C14 4.79086 12.2091 3 10 3C7.79086 3 6 4.79086 6 7C6 9.20914 7.79086 11 10 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 17C3 14.2386 5.68629 12 9 12H11C14.3137 12 17 14.2386 17 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="header-icon-label">Profile</span>
                    </a>
                </div>
                @include('components.user-dropdown')
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Signup</a>
            @endauth
        </div>
    </div>
</header>

