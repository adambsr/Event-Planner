@extends('layouts.app')

@section('title', $event->title . ' - Event Planner')

@section('content')
<div class="container">
    <div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
        <!-- Back Button -->
        <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; margin-bottom: 30px; color: #344054; text-decoration: none; font-weight: 500;">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back to Events
        </a>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px;">
            <!-- Event Image -->
            <div>
                <div style="width: 100%; height: 400px; background-image: url('{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}'); background-size: cover; background-position: center; border-radius: 12px; position: relative;">
                    @if($event->is_free)
                        <div style="position: absolute; top: 20px; right: 20px; background: #10B981; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                            FREE
                        </div>
                    @endif
                </div>
            </div>

            <!-- Event Details -->
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #101828; margin-bottom: 16px;">{{ $event->title }}</h1>
                
                @if($event->category)
                    <div style="margin-bottom: 24px;">
                        <span style="background: #F2F4F7; color: #344054; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: 500;">
                            {{ $event->category->name }}
                        </span>
                        @if($event->isArchived())
                            <span style="background: #FEF3F2; color: #B42318; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: 500; margin-left: 8px;">
                                Archived Event
                            </span>
                        @endif
                    </div>
                @endif

                <div style="margin-bottom: 24px; line-height: 1.8; color: #475467;">
                    <p>{{ $event->description }}</p>
                </div>

                <!-- Event Info -->
                <div style="background: #F9FAFB; padding: 24px; border-radius: 12px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 12px; color: #667085;">
                            <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6024 18.3333 10C18.3333 5.39763 14.6024 1.66667 10 1.66667C5.39763 1.66667 1.66667 5.39763 1.66667 10C1.66667 14.6024 5.39763 18.3333 10 18.3333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 5V10L13.3333 11.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <div style="font-size: 12px; color: #667085; margin-bottom: 4px;">Start Date</div>
                            <div style="font-weight: 600; color: #101828;">{{ $event->start_date->format('l, F d, Y g:i A') }}</div>
                        </div>
                    </div>

                    @if($event->end_date)
                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 12px; color: #667085;">
                            <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6024 18.3333 10C18.3333 5.39763 14.6024 1.66667 10 1.66667C5.39763 1.66667 1.66667 5.39763 1.66667 10C1.66667 14.6024 5.39763 18.3333 10 18.3333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 5V10L13.3333 11.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <div style="font-size: 12px; color: #667085; margin-bottom: 4px;">End Date</div>
                            <div style="font-weight: 600; color: #101828;">{{ $event->end_date->format('l, F d, Y g:i A') }}</div>
                        </div>
                    </div>
                    @endif

                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 12px; color: #667085;">
                            <path d="M17.5 8.33333C17.5 14.1667 10 19.1667 10 19.1667C10 19.1667 2.5 14.1667 2.5 8.33333C2.5 6.34421 3.29018 4.43655 4.6967 3.03004C6.10321 1.62352 8.01088 0.833336 10 0.833336C11.9891 0.833336 13.8968 1.62352 15.3033 3.03004C16.7098 4.43655 17.5 6.34421 17.5 8.33333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 10.8333C11.3807 10.8333 12.5 9.71405 12.5 8.33333C12.5 6.95262 11.3807 5.83333 10 5.83333C8.61929 5.83333 7.5 6.95262 7.5 8.33333C7.5 9.71405 8.61929 10.8333 10 10.8333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <div style="font-size: 12px; color: #667085; margin-bottom: 4px;">Location</div>
                            <div style="font-weight: 600; color: #101828;">{{ $event->place }}</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 12px; color: #667085;">
                            <path d="M10 1.66667V18.3333M1.66667 10H18.3333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <div style="font-size: 12px; color: #667085; margin-bottom: 4px;">Price</div>
                            <div style="font-weight: 600; color: #101828;">
                                @if($event->is_free)
                                    Free
                                @else
                                    TND {{ number_format($event->price, 2) }}
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($event->capacity)
                    <div style="display: flex; align-items: center;">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 12px; color: #667085;">
                            <path d="M16.6667 17.5V15.8333C16.6667 14.9493 16.3155 14.1014 15.6903 13.4763C15.0652 12.8512 14.2174 12.5 13.3333 12.5H6.66667C5.78261 12.5 4.93477 12.8512 4.30964 13.4763C3.68452 14.1014 3.33333 14.9493 3.33333 15.8333V17.5M13.3333 5.83333C13.3333 7.67428 11.841 9.16667 10 9.16667C8.15905 9.16667 6.66667 7.67428 6.66667 5.83333C6.66667 3.99238 8.15905 2.5 10 2.5C11.841 2.5 13.3333 3.99238 13.3333 5.83333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <div style="font-size: 12px; color: #667085; margin-bottom: 4px;">Capacity</div>
                            <div style="font-weight: 600; color: #101828;">
                                {{ $event->registrations()->count() }} / {{ $event->capacity }} registered
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Registration Button -->
                @if($event->isArchived())
                    <div style="background: #FEF3F2; border: 1px solid #FECDCA; color: #B42318; padding: 14px 24px; border-radius: 8px; font-weight: 600; font-size: 16px; text-align: center;">
                        This event has been archived. Registration is closed.
                    </div>
                @else
                    @auth
                        @if($isRegistered)
                            <form action="{{ route('registrations.destroy', $event) }}" method="POST" style="margin-bottom: 16px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width: 100%; background: #DC2626; color: white; padding: 14px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer;">
                                    Unregister from Event
                                </button>
                            </form>
                        @else
                            @if(!$event->isFull())
                                <form action="{{ route('registrations.store', $event) }}" method="POST" style="margin-bottom: 16px;">
                                    @csrf
                                    <button type="submit" style="width: 100%; background: #10B981; color: white; padding: 14px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer;">
                                        Register for Event
                                    </button>
                                </form>
                            @else
                                <button disabled style="width: 100%; background: #D1D5DB; color: #6B7280; padding: 14px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: not-allowed;">
                                    Event Full
                                </button>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" style="display: block; width: 100%; background: #10B981; color: white; padding: 14px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; text-align: center; text-decoration: none;">
                            Login to Rregister
                        </a>
                    @endauth
                @endif

                @if(session('success'))
                    <div style="background: #D1FAE5; color: #065F46; padding: 12px; border-radius: 8px; margin-top: 16px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div style="background: #FEE2E2; color: #991B1B; padding: 12px; border-radius: 8px; margin-top: 16px;">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

