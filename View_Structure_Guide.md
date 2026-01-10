# Laravel View Structure Guide

This document describes the complete view structure for the AAB Event Planner application. Use this as a template to replicate the same structure in other projects.

## 📁 Directory Structure Overview

```
resources/views/
├── layouts/              # Master layouts and reusable layout components
│   ├── app.blade.php    # Main application layout (master template)
│   ├── header.blade.php # Header partial
│   └── footer.blade.php # Footer partial
│
├── components/          # Reusable UI components
│   ├── admin-nav.blade.php
│   └── user-dropdown.blade.php
│
├── auth/                # Authentication views
│   ├── login.blade.php
│   └── register.blade.php
│
├── events/              # Event management views (CRUD)
│   ├── index.blade.php  # List/display all events (public homepage)
│   ├── list.blade.php   # Admin list view
│   ├── show.blade.php   # Single event detail view
│   ├── create.blade.php # Create new event form
│   ├── edit.blade.php   # Edit existing event form
│   └── _form.blade.php  # Shared form partial (DRY principle)
│
├── categories/          # Category management views
│   └── list.blade.php   # Categories list with inline CRUD
│
├── users/               # User management views
│   ├── list.blade.php   # Admin users list
│   ├── create.blade.php # Create user form
│   └── edit.blade.php   # Edit user form
│
├── registrations/       # Event registration views
│   ├── index.blade.php         # Admin registrations list
│   └── my-registrations.blade.php # User's personal registrations
│
├── profile/             # User profile views
│   └── index.blade.php  # User profile page
│
└── welcome.blade.php    # Landing/welcome page (if different from events.index)
```

---

## 🎨 Layout Architecture Pattern

### 1. Master Layout (`layouts/app.blade.php`)

**Purpose:** Single source of truth for HTML structure, shared across all pages.

**Key Features:**
- HTML boilerplate (DOCTYPE, head, body)
- Meta tags and dynamic title with `@yield('title')`
- Asset loading via Vite: `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- Conditional includes for header/footer (hidden on auth pages)
- Content section: `@yield('content')`
- Stackable sections: `@stack('styles')` and `@stack('scripts')`

**Example Structure:**
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AAB Event Planner')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="page-wrapper">
        @if(!request()->routeIs('login') && !request()->routeIs('register'))
            @include('layouts.header')
        @endif

        <main class="main-content">
            @yield('content')
        </main>

        @if(!request()->routeIs('login') && !request()->routeIs('register'))
            @include('layouts.footer')
        @endif
    </div>
    @stack('scripts')
</body>
</html>
```

### 2. Layout Partials

#### Header (`layouts/header.blade.php`)
- Navigation bar
- Logo/branding
- Conditional authentication buttons
- User dropdown component

#### Footer (`layouts/footer.blade.php`)
- Footer content
- Copyright info
- Additional links

---

## 🔧 Components Pattern

### Purpose
Reusable UI elements that can be included anywhere.

### Examples:

#### `components/admin-nav.blade.php`
Navigation component for admin pages (tabs/links to different admin sections).

#### `components/user-dropdown.blade.php`
User profile dropdown menu with logout, profile links, etc.

**Usage in views:**
```blade
@include('components.admin-nav')
@include('components.user-dropdown')
```

---

## 📋 Resource Views Pattern (CRUD Operations)

Use this pattern for each major resource (Events, Users, Categories, etc.)

### Standard Resource Views:

#### 1. **index.blade.php** - List View (Public)
- **Extends:** `@extends('layouts.app')`
- **Purpose:** Display all resources (public-facing)
- **Common elements:**
  - Hero section (optional)
  - Search/filter forms
  - Cards/table of items
  - Pagination

```blade
@extends('layouts.app')

@section('title', 'Events - AAB Event Planner')

@section('content')
<div class="container">
    <!-- Hero/Banner Section -->
    <div class="hero">
        <h1>Events</h1>
    </div>

    <!-- Search/Filter Section -->
    <form method="GET" action="{{ route('events.index') }}">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <!-- Items Grid/List -->
    <div class="events-grid">
        @foreach($events as $event)
            <div class="event-card">
                <!-- Event content -->
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    {{ $events->links() }}
</div>
@endsection
```

#### 2. **list.blade.php** - Admin List View
- **Extends:** `@extends('layouts.app')`
- **Purpose:** Admin panel view with full CRUD controls
- **Common elements:**
  - Admin navigation component
  - Success/error flash messages
  - Data table with actions (edit, delete)
  - Create button

```blade
@extends('layouts.app')

@section('title', 'Manage Events - AAB Event Planner')

@section('content')
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">Manage Events</h1>
            @include('components.admin-nav')
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Create Button -->
        <a href="{{ route('events.create') }}" class="btn btn-primary">Create New Event</a>

        <!-- Data Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->start_date->format('Y-m-d') }}</td>
                        <td>{{ $event->category->name }}</td>
                        <td>
                            <a href="{{ route('events.edit', $event) }}">Edit</a>
                            <form method="POST" action="{{ route('events.destroy', $event) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
```

#### 3. **show.blade.php** - Detail View
- **Extends:** `@extends('layouts.app')`
- **Purpose:** Display single resource in detail
- **Common elements:**
  - Full resource information
  - Related data
  - Action buttons (edit, delete, back)

```blade
@extends('layouts.app')

@section('title', $event->title . ' - AAB Event Planner')

@section('content')
<div class="container">
    <div class="event-detail">
        <h1>{{ $event->title }}</h1>
        <p>{{ $event->description }}</p>
        <p><strong>Date:</strong> {{ $event->start_date->format('M d, Y') }}</p>
        <p><strong>Category:</strong> {{ $event->category->name }}</p>
        
        @auth
            <a href="{{ route('events.register', $event) }}" class="btn btn-primary">Register</a>
        @endauth
        
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
    </div>
</div>
@endsection
```

#### 4. **create.blade.php** - Create Form
- **Extends:** `@extends('layouts.app')`
- **Purpose:** Form to create new resource
- **Common elements:**
  - Admin header with navigation
  - Error messages display
  - Include form partial: `@include('resource._form')`

```blade
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
                <ul>
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
```

#### 5. **edit.blade.php** - Edit Form
- **Extends:** `@extends('layouts.app')`
- **Purpose:** Form to edit existing resource
- **Common elements:**
  - Admin header with navigation
  - Error messages display
  - Include same form partial as create: `@include('resource._form')`

```blade
@extends('layouts.app')

@section('title', 'Edit Event - AAB Event Planner')

@section('content')
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">Edit Event</h1>
            @include('components.admin-nav')
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
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
```

#### 6. **_form.blade.php** - Shared Form Partial (DRY Pattern)
- **Purpose:** Single form used by both create and edit views
- **Key Feature:** Conditional logic for create vs edit
- **Common elements:**
  - Form with dynamic action (store vs update)
  - CSRF token
  - Method spoofing for PUT (edit only)
  - Pre-filled values using `old()` helper
  - Conditional logic: `{{ $resource->id ? 'Update' : 'Create' }}`

```blade
{{-- Reusable form partial for create and edit --}}

<form action="{{ $event->id ? route('events.update', $event) : route('events.store') }}" 
      method="POST" 
      enctype="multipart/form-data" 
      class="event-form">
    @csrf
    @if($event->id)
        @method('PUT')
    @endif

    <h2>{{ $event->id ? 'Edit Event' : 'Create Event' }}</h2>

    <!-- Title Field -->
    <div class="form-group">
        <label for="title">Event Title</label>
        <input 
            type="text" 
            name="title" 
            id="title"
            value="{{ old('title', $event->title ?? '') }}"
            required
            class="form-input"
        >
    </div>

    <!-- Category Field -->
    <div class="form-group">
        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" required class="form-input">
            <option value="">Select category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" 
                    {{ old('category_id', $event->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Date Fields -->
    <div class="form-group">
        <label for="start_date">Start Date</label>
        <input 
            type="datetime-local" 
            name="start_date" 
            id="start_date"
            value="{{ old('start_date', $event->id && $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}"
            required
            class="form-input"
        >
    </div>

    <!-- Description -->
    <div class="form-group">
        <label for="description">Description</label>
        <textarea 
            name="description" 
            id="description"
            rows="5"
            class="form-input"
        >{{ old('description', $event->description ?? '') }}</textarea>
    </div>

    <!-- Image Upload (if editing, show current image) -->
    @if($event->id && $event->image)
        <div class="form-group">
            <label>Current Image</label>
            <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image" style="max-width: 200px;">
        </div>
    @endif

    <div class="form-group">
        <label for="image">Event Image {{ $event->id ? '(Upload new to replace)' : '' }}</label>
        <input type="file" name="image" id="image" accept="image/*" class="form-input">
    </div>

    <!-- Submit Button -->
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            {{ $event->id ? 'Update Event' : 'Create Event' }}
        </button>
        <a href="{{ route('events.list') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
```

---

## 🔐 Authentication Views Pattern

### `auth/login.blade.php`
```blade
@extends('layouts.app')

@section('title', 'Login - AAB Event Planner')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2>Login</h2>
        
        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </div>
</div>
@endsection
```

### `auth/register.blade.php`
Similar structure to login, with additional fields (name, password confirmation, etc.)

---

## 📊 Special Purpose Views

### Profile Views (`profile/index.blade.php`)
User profile display and edit functionality.

### Registration Views (`registrations/`)
- `index.blade.php` - Admin view of all registrations
- `my-registrations.blade.php` - User's personal registrations view

---

## 🎯 Key Patterns & Best Practices

### 1. **Consistent Naming Convention**
- Resource folders: plural (events, users, categories)
- Views: descriptive names (index, list, create, edit, show)
- Partials: prefix with underscore (_form.blade.php)
- Layouts: singular and descriptive (app, header, footer)

### 2. **DRY Principle (Don't Repeat Yourself)**
- Use `_form.blade.php` partials for shared forms
- Extract reusable components into `components/`
- Use master layout to avoid repeating HTML structure

### 3. **Layout Inheritance Chain**
```
layouts/app.blade.php (master)
    ↓ @extends
resource/index.blade.php (child)
    ↓ @include
components/admin-nav.blade.php (component)
    ↓ @include
resource/_form.blade.php (partial)
```

### 4. **Data Flow Pattern**
```blade
<!-- Controller passes data to view -->
<!-- View: -->
@include('resource._form', [
    'resource' => $resource,
    'relatedData' => $relatedData
])

<!-- Partial: -->
{{ $resource->property }}
```

### 5. **Conditional Rendering**
```blade
<!-- Admin-only sections -->
@auth
    @if(auth()->user()->is_admin)
        <a href="{{ route('admin.panel') }}">Admin Panel</a>
    @endif
@endauth

<!-- Show header/footer conditionally -->
@if(!request()->routeIs('login') && !request()->routeIs('register'))
    @include('layouts.header')
@endif
```

### 6. **Flash Messages Pattern**
```blade
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

### 7. **Old Input Pattern (Forms)**
```blade
<!-- Preserve input on validation errors -->
<input 
    type="text" 
    name="title" 
    value="{{ old('title', $resource->title ?? '') }}"
>

<!-- For selects -->
<option value="{{ $item->id }}" 
    {{ old('field_id', $resource->field_id ?? '') == $item->id ? 'selected' : '' }}>
    {{ $item->name }}
</option>

<!-- For checkboxes -->
<input 
    type="checkbox" 
    name="featured" 
    {{ old('featured', $resource->featured ?? false) ? 'checked' : '' }}
>
```

---

## 📝 Quick Setup Checklist for New Resource

When adding a new resource (e.g., "Products"), create these views:

### Required Structure:
```
resources/views/products/
├── index.blade.php      # Public list view
├── list.blade.php       # Admin list view
├── show.blade.php       # Detail view
├── create.blade.php     # Create form wrapper
├── edit.blade.php       # Edit form wrapper
└── _form.blade.php      # Shared form partial
```

### Steps:
1. ✅ Create resource folder: `resources/views/products/`
2. ✅ Copy `index.blade.php` template - update title and content
3. ✅ Copy `list.blade.php` template - update admin table
4. ✅ Copy `show.blade.php` template - customize detail display
5. ✅ Copy `create.blade.php` template - update includes
6. ✅ Copy `edit.blade.php` template - update includes
7. ✅ Create `_form.blade.php` - build reusable form with all fields
8. ✅ Ensure all views extend `layouts.app`
9. ✅ Use `@include('components.admin-nav')` in admin views
10. ✅ Test create and edit both use the same `_form` partial

---

## 🎨 Common Blade Directives Reference

```blade
{{-- Layout --}}
@extends('layouts.app')
@section('title', 'Page Title')
@section('content')
    <!-- Content here -->
@endsection
@yield('content')

{{-- Including Views --}}
@include('partials.component')
@include('partials.component', ['variable' => $value])

{{-- Control Structures --}}
@if($condition)
    <!-- Content -->
@elseif($otherCondition)
    <!-- Content -->
@else
    <!-- Content -->
@endif

@foreach($items as $item)
    {{ $item->name }}
@endforeach

@forelse($items as $item)
    {{ $item->name }}
@empty
    <p>No items found.</p>
@endforelse

{{-- Authentication --}}
@auth
    <!-- User is authenticated -->
@endauth

@guest
    <!-- User is not authenticated -->
@endguest

{{-- Displaying Data --}}
{{ $variable }}              {{-- Escaped output --}}
{!! $htmlVariable !!}        {{-- Unescaped output --}}
{{ $var ?? 'default' }}      {{-- With default value --}}

{{-- Forms --}}
@csrf                        {{-- CSRF token --}}
@method('PUT')              {{-- Method spoofing --}}

{{-- Stacks (for scripts/styles) --}}
@push('scripts')
    <script src="..."></script>
@endpush
@stack('scripts')

{{-- Comments --}}
{{-- This is a Blade comment --}}
```

---

## 📚 Additional Resources

### File Naming Conventions:
- **Blade templates:** `filename.blade.php`
- **Partials:** `_partial.blade.php` (underscore prefix)
- **Layouts:** `layout-name.blade.php`
- **Components:** `component-name.blade.php`

### Folder Organization:
- Keep related views together in resource folders
- Separate admin views from public views when needed
- Use components for truly reusable UI elements
- Use layouts for page structure
- Use partials for repeated form/content sections

---

## ✨ Summary

This view structure provides:
- ✅ **Consistency:** Same pattern for all resources
- ✅ **Maintainability:** DRY principle with shared forms and layouts
- ✅ **Scalability:** Easy to add new resources
- ✅ **Organization:** Logical folder grouping
- ✅ **Reusability:** Components and partials
- ✅ **Flexibility:** Easy to customize per resource

Use this guide as a blueprint for creating consistent, maintainable view structures in your Laravel applications!
