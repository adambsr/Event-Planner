@extends('layouts.app')

@section('title', 'My Registrations - Event Planner')

@section('content')
<div class="container">
    <div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #101828; margin-bottom: 32px;">My Registrations</h1>

        @if(session('success'))
            <div style="background: #D1FAE5; color: #065F46; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #FEE2E2; color: #991B1B; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Events Grid -->
        <div class="events-grid">
            @forelse($registrations as $registration)
                @php
                    $event = $registration->event;
                @endphp
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
                        <p class="event-meta" style="font-size: 12px; color: #667085; margin-top: 8px;">
                            Registered: {{ $registration->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #000000;">
                    <p style="font-size: 18px; margin-bottom: 16px;">You haven't registered for any events yet.</p>
                    <a href="{{ route('events.index') }}" style="color: #10B981; text-decoration: none; font-weight: 600;">
                        Browse Events →
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

