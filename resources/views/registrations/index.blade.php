@extends('layouts.app')

@section('title', 'All Registrations - AAB Event Planner')

@section('content')
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">All Registrations</h1>
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

        <!-- Registrations Table Card -->
        <div class="events-table-card">
            <div class="table-header">
                <h2 class="table-title">Registrations</h2>
            </div>

            <div class="table-container">
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Event</th>
                            <th>Category</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                            <tr>
                                <td>
                                    <span class="event-name">{{ $registration->user->name }}</span>
                                    <div style="font-size: 12px; color: #667085;">{{ $registration->user->email }}</div>
                                </td>
                                <td>
                                    <span class="event-name">{{ $registration->event->title }}</span>
                                    <div style="font-size: 12px; color: #667085;">{{ $registration->event->start_date->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    @if($registration->event->category)
                                        <span style="background: #F2F4F7; color: #344054; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                            {{ $registration->event->category->name }}
                                        </span>
                                    @else
                                        <span style="color: #667085;">-</span>
                                    @endif
                                </td>
                                <td>{{ $registration->created_at->format('M d, Y g:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <p>No registrations found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($registrations->hasPages())
                <div style="padding: 20px; display: flex; justify-content: center;">
                    {{ $registrations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

