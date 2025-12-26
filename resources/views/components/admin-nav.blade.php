<nav class="admin-nav">
    <a href="{{ route('users.index') }}" class="admin-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
        Users
    </a>
    <a href="{{ route('registrations.index') }}" class="admin-nav-link {{ request()->routeIs('registrations.*') ? 'active' : '' }}">
        Registrations
    </a>
    <a href="{{ route('categories.index') }}" class="admin-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
        Categories
    </a>
    <a href="{{ route('events.list') }}" class="admin-nav-link {{ request()->routeIs('events.*') || request()->routeIs('events.create') || request()->routeIs('events.edit') ? 'active' : '' }}">
        Events
    </a>
</nav>

