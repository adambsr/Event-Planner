@extends('layouts.app')

@section('title', 'Create Event - AAB Event Planner')

@section('content')
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">Create Event</h1>
            @include('components.admin-nav')
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('events._form', ['event' => $event, 'categories' => $categories])
    </div>
</div>
@endsection
