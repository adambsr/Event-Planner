@extends('layouts.app')

@section('title', 'Events List - AAB Event Planner')

@section('content')
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">Events List</h1>
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

        <!-- Events Table Card -->
        <div class="events-table-card">
            <div class="table-header">
                <h2 class="table-title">Events</h2>
                @can('edit events')
                    <a href="{{ route('events.create') }}" class="btn-create-event">Create event</a>
                @endcan
            </div>

            <div class="table-container">
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Event name</th>
                            <th>Start date</th>
                            <th>End Date</th>
                            <th>Pricing</th>
                            <th>Capacity</th>
                            <th>Place</th>
                            <th class="actions-column"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>
                                    <span class="event-name">{{ $event->title }}</span>
                                </td>
                                <td>{{ $event->start_date->format('M d, Y, g') }}{{ strtolower($event->start_date->format('a')) }}</td>
                                <td>{{ $event->end_date->format('M d, Y, g') }}{{ strtolower($event->end_date->format('a')) }}</td>
                                <td>
                                    @if($event->is_free)
                                        <span class="pricing-badge free">Free</span>
                                    @else
                                        <span class="pricing-badge paid">{{ number_format($event->price, 0) }} TND</span>
                                    @endif
                                </td>
                                <td>{{ $event->capacity }}</td>
                                <td>{{ $event->place }}</td>
                                <td>
                                    @can('edit events')
                                        <div class="action-menu" id="actionMenu{{ $event->id }}">
                                            <button class="action-menu-toggle" onclick="toggleActionMenu({{ $event->id }})">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 9.5C8.82843 9.5 9.5 8.82843 9.5 8C9.5 7.17157 8.82843 6.5 8 6.5C7.17157 6.5 6.5 7.17157 6.5 8C6.5 8.82843 7.17157 9.5 8 9.5Z" fill="#667085"/>
                                                    <path d="M8 4.5C8.82843 4.5 9.5 3.82843 9.5 3C9.5 2.17157 8.82843 1.5 8 1.5C7.17157 1.5 6.5 2.17157 6.5 3C6.5 3.82843 7.17157 4.5 8 4.5Z" fill="#667085"/>
                                                    <path d="M8 14.5C8.82843 14.5 9.5 13.8284 9.5 13C9.5 12.1716 8.82843 11.5 8 11.5C7.17157 11.5 6.5 12.1716 6.5 13C6.5 13.8284 7.17157 14.5 8 14.5Z" fill="#667085"/>
                                                </svg>
                                            </button>
                                            <div class="action-menu-dropdown">
                                                    <a href="{{ route('events.edit', $event) }}" class="action-menu-item">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3333 2.00004C11.5084 1.82493 11.7163 1.68605 11.9444 1.5913C12.1726 1.49655 12.4166 1.44775 12.6629 1.44775C12.9092 1.44775 13.1532 1.49655 13.3814 1.5913C13.6095 1.68605 13.8174 1.82493 13.9925 2.00004C14.1676 2.17515 14.3065 2.3831 14.4012 2.61124C14.496 2.83938 14.5448 3.08341 14.5448 3.32971C14.5448 3.57601 14.496 3.82004 14.4012 4.04818C14.3065 4.27632 14.1676 4.48427 13.9925 4.65938L5.17583 13.476L1.33333 14.6667L2.524 10.8242L11.3333 2.00004Z" stroke="#344054" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @can('delete events')
                                                    <form action="{{ route('events.destroy', $event) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to archive this event?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-menu-item archive">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M2.66667 4H13.3333M12.6667 4V12.6667C12.6667 13.0203 12.5262 13.3594 12.2761 13.6095C12.026 13.8596 11.6869 14 11.3333 14H4.66667C4.31305 14 3.97391 13.8596 3.72381 13.6095C3.47371 13.3594 3.33333 13.0203 3.33333 12.6667V4M5.33333 4V2.66667C5.33333 2.31305 5.47371 1.97391 5.72381 1.72381C5.97391 1.47371 6.31305 1.33333 6.66667 1.33333H9.33333C9.68696 1.33333 10.0261 1.47371 10.2762 1.72381C10.5263 1.97391 10.6667 2.31305 10.6667 2.66667V4" stroke="#D92D20" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                            Archive
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
                                <td colspan="7" class="empty-state">
                                    <p>No events found. @can('edit events')<a href="{{ route('events.create') }}">Create your first event</a>@endcan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
            <div class="table-pagination">
                {{ $events->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleActionMenu(eventId) {
    // Close all other menus
    document.querySelectorAll('.action-menu-dropdown').forEach(menu => {
        if (menu.id !== 'actionMenu' + eventId + 'Dropdown') {
            menu.classList.remove('active');
        }
    });
    
    // Toggle current menu
    const menu = document.getElementById('actionMenu' + eventId).querySelector('.action-menu-dropdown');
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

