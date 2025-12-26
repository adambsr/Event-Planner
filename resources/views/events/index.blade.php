@extends('layouts.app')

@section('title', 'Home - Event Planner')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-overlay"></div>
        <button class="hero-nav prev" onclick="previousSlide()">‹</button>
        <button class="hero-nav next" onclick="nextSlide()">›</button>
        <div class="hero-content">
            <h1 class="hero-title">Made for those who do</h1>
        </div>
    </div>

    <!-- Events Section -->
    <section class="events-section">
        <div class="events-header">
            <h2 class="events-title">Events</h2>
            <form method="GET" action="{{ route('home') }}" class="filters" id="filterForm">
                <div class="search-box">
                    <input 
                        type="text" 
                        name="search" 
                        id="searchInput"
                        placeholder="Search" 
                        value="{{ request('search') }}"
                        onkeypress="if(event.key === 'Enter') { this.form.submit(); }"
                        oninput="handleSearchInput(this)"
                    >
                    <span class="search-icon">🔍</span>
                </div>
                <select name="weekday" class="filter-select" onchange="this.form.submit()">
                    <option value="">Weekdays</option>
                    <option value="monday" {{ request('weekday') == 'monday' ? 'selected' : '' }}>Monday</option>
                    <option value="tuesday" {{ request('weekday') == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="wednesday" {{ request('weekday') == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="thursday" {{ request('weekday') == 'thursday' ? 'selected' : '' }}>Thursday</option>
                    <option value="friday" {{ request('weekday') == 'friday' ? 'selected' : '' }}>Friday</option>
                    <option value="saturday" {{ request('weekday') == 'saturday' ? 'selected' : '' }}>Saturday</option>
                    <option value="sunday" {{ request('weekday') == 'sunday' ? 'selected' : '' }}>Sunday</option>
                </select>
                <select name="category_id" id="categoryFilter" onchange="this.form.submit()" class="filter-select">
                    <option value="">Any category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Events Grid -->
        <div class="events-grid">
            @forelse($events as $event)
                <div class="event-card" onclick="window.location.href='{{ route('events.show', $event) }}'">
                    <div class="event-image" style="background-image: url('{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}');">
                        @if($event->is_free)
                            <div class="event-free-badge">FREE</div>
                        @endif
                    </div>
                    <div class="event-details">
                        <h3 class="event-title">{{ $event->title }}</h3>
                        <p class="event-meta">{{ $event->start_date->format('l, F d, g:i A') }}</p>
                        <p class="event-meta">{{ $event->place }}</p>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #000000;">
                    <p style="font-size: 18px;">No events found. Check back later!</p>
                </div>
            @endforelse
        </div>

        <!-- Load More Button -->
        @if($events->hasMorePages())
        <div class="load-more">
            <a href="{{ $events->nextPageUrl() }}" class="load-more-btn">Load more...</a>
        </div>
        @endif
    </section>
</div>

@push('scripts')
<script>
    function previousSlide() {
        console.log('Previous slide');
    }
    
    function nextSlide() {
        console.log('Next slide');
    }

    function handleSearchInput(input) {
        // If search input is cleared, submit form to reset filters
        if (input.value.trim() === '') {
            const form = document.getElementById('filterForm');
            const url = new URL(form.action);
            url.searchParams.delete('search');
            // Preserve category filter if it exists
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter && categoryFilter.value) {
                url.searchParams.set('category_id', categoryFilter.value);
            }
            window.location.href = url.toString();
        }
    }
</script>
@endpush
@endsection
