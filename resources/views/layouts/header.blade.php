<header class="header">
    <div class="header-container">
        <a href="{{ route('home') }}" class="logo">
            <span>AAB</span>
            <span>Event Planner</span>
        </a>
        <div class="auth-buttons">
            @auth
                @include('components.user-dropdown')
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Signup</a>
            @endauth
        </div>
    </div>
</header>

