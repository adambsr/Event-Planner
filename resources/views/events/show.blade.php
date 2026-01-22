@extends('layouts.app')

@section('title', $event->title . ' - Event Planner')

@section('content')
<div class="container">
    <div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
        
        <!-- Hero Section -->
        <div class="event-hero">
            <img 
                src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80' }}" 
                alt="{{ $event->title }}"
                class="event-hero-image"
            >
            <div class="event-hero-overlay"></div>
            
            <!-- Back Button -->
            <a href="{{ route('home') }}" class="back-btn">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </a>
            
            @if($event->is_free)
                <div class="free-badge-hero">FREE</div>
            @endif
            
            <!-- Hero Content -->
            <div class="event-hero-content">
                <h1 class="event-hero-title">{{ $event->title }}</h1>
                <p class="event-hero-place">{{ $event->place }}</p>
                <p class="event-hero-description">{{ Str::limit($event->description, 200) }}</p>
                
                @if($event->isArchived())
                    <span class="archived-notice">This event has been archived</span>
                @else
                    @auth
                        @if($isRegistered)
                            <form action="{{ route('registrations.destroy', $event) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="book-now-btn unregister">Unregister</button>
                            </form>
                        @else
                            @if(!$event->isFull())
                                <form action="{{ route('registrations.store', $event) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="book-now-btn">Book now</button>
                                </form>
                            @else
                                <button disabled class="book-now-btn">Event Full</button>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="book-now-btn">Book now</a>
                    @endauth
                @endif
                
                @auth
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('events.edit', $event) }}" class="edit-event-btn">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.333 2.00004C11.5081 1.82494 11.716 1.68605 11.9447 1.59129C12.1735 1.49653 12.4187 1.44775 12.6663 1.44775C12.914 1.44775 13.1592 1.49653 13.3879 1.59129C13.6167 1.68605 13.8246 1.82494 13.9997 2.00004C14.1748 2.17513 14.3137 2.383 14.4084 2.61178C14.5032 2.84055 14.552 3.08575 14.552 3.33337C14.552 3.58099 14.5032 3.82619 14.4084 4.05497C14.3137 4.28374 14.1748 4.49161 13.9997 4.66671L4.99967 13.6667L1.33301 14.6667L2.33301 11L11.333 2.00004Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        
        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif
        
        <!-- Details Grid -->
        <div class="event-details-grid">
            <!-- Description -->
            <div class="detail-section">
                <h2>Description</h2>
                <p>{{ $event->description }}</p>
            </div>
            
            <!-- Hours & Capacity -->
            <div>
                <div class="hours-section">
                    <h2>Hours</h2>
                    
                    @php
                        $startDate = $event->start_date;
                        $endDate = $event->end_date;
                        $durationDays = $endDate ? $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) + 1 : 1;
                    @endphp
                    
                    @if($durationDays > 1)
                        <div class="hour-item">
                            <span class="hour-label">Duration:</span>
                            <span class="hour-value">{{ $durationDays }} days</span>
                        </div>
                        @for($i = 0; $i < $durationDays; $i++)
                            @php
                                $currentDay = $startDate->copy()->addDays($i);
                                $isFirstDay = $i === 0;
                                $isLastDay = $i === $durationDays - 1;
                                
                                if ($isFirstDay) {
                                    $dayStart = $startDate->format('g:iA');
                                    $dayEnd = '11:59PM';
                                } elseif ($isLastDay) {
                                    $dayStart = '12:00AM';
                                    $dayEnd = $endDate->format('g:iA');
                                } else {
                                    $dayStart = '12:00AM';
                                    $dayEnd = '11:59PM';
                                }
                            @endphp
                            <div class="hour-item">
                                <span class="hour-label">{{ $currentDay->format('l, M d') }}:</span>
                                <span class="hour-value">{{ $dayStart }} - {{ $dayEnd }}</span>
                            </div>
                        @endfor
                    @else
                        <div class="hour-item">
                            <span class="hour-label">Event hours:</span>
                            <span class="hour-value">{{ $event->start_date->format('g:iA') }} - {{ $event->end_date ? $event->end_date->format('g:iA') : 'TBD' }}</span>
                        </div>
                    @endif
                </div>
                
                @if($event->capacity)
                <div class="capacity-section">
                    <h2>Capacity</h2>
                    <div class="capacity-item">
                        <span class="capacity-label">Seats number :</span>
                        <span class="capacity-value">{{ $event->capacity }} persons</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Other Events Section -->
        @if($otherEvents->count() > 0)
        <div class="other-events-section">
            <h2>Other events you may like</h2>
            
            <div class="other-events-grid">
                @foreach($otherEvents as $otherEvent)
                <div class="other-event-card" onclick="window.location.href='{{ route('events.show', $otherEvent) }}'">
                    <div class="other-event-card-image">
                        <img 
                            src="{{ $otherEvent->image ? asset('storage/' . $otherEvent->image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                            alt="{{ $otherEvent->title }}"
                        >
                        @if($otherEvent->is_free)
                            <span class="other-event-card-badge free">FREE</span>
                        @else
                            <span class="other-event-card-badge">TND {{ number_format($otherEvent->price, 0) }}</span>
                        @endif
                    </div>
                    <div class="other-event-card-content">
                        <h3 class="other-event-card-title">
                            <a href="{{ route('events.show', $otherEvent) }}">{{ $otherEvent->title }}</a>
                        </h3>
                        <p class="other-event-card-date">{{ $otherEvent->start_date->format('l, F d, g:iA') }}</p>
                        <p class="other-event-card-meta">
                            @if($otherEvent->is_free)
                                ONLINE EVENT
                            @else
                                {{ Str::limit($otherEvent->place, 30) }}
                            @endif
                            · Attend anywhere
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
    </div>
</div>
@endsection

