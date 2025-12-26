# AAB_EventPlanner - Complete Project Documentation

---

## 1. Project Overview

### Purpose
AAB_EventPlanner is a web-based event management system built with Laravel 11. It allows administrators to create, manage, and publish events while enabling users to browse, search, and register for events.

### Target Users
| Role | Description |
|------|-------------|
| **Admin** | Full system access: CRUD operations on events, categories, users, and registrations |
| **Manager** | Read-only access to admin panel: view events, categories, users, and registrations |
| **User** | Public access: browse events, register/unregister, manage profile and personal registrations |

### Core Features
- **Event Management**: Create, edit, archive events with images, pricing, capacity limits
- **Category System**: Organize events by categories
- **User Registration**: Users can register/unregister for events
- **Authentication**: Login, registration, session management
- **Profile Management**: Update profile info, password, avatar
- **Admin Panel**: Separate admin interface for managing all entities
- **Search & Filters**: Search events by title/description, filter by category and weekday
- **Role-Based Access Control**: Using Spatie Laravel Permission package
- **Image Uploads**: Event images and user avatars stored in public storage

---

## 2. Tech Stack

| Component | Technology |
|-----------|------------|
| **Framework** | Laravel 11 |
| **PHP Version** | ^8.2 |
| **Database** | MySQL (configured), SQLite (default fallback) |
| **Frontend** | Blade templates, vanilla CSS, vanilla JavaScript |
| **Build Tool** | Vite 5.x |
| **CSS Framework** | Custom CSS (no Bootstrap/Tailwind) |
| **Authentication** | Custom implementation (no Laravel Breeze/Jetstream) |
| **Authorization** | Spatie Laravel Permission ^6.24 |
| **Fonts** | Inter (via Bunny Fonts) |
| **File Storage** | Laravel Storage (public disk) |
| **HTTP Client** | Axios 1.6.4 (available, minimal usage) |

### Third-Party Packages
```json
{
    "require": {
        "laravel/framework": "^11.0",
        "laravel/tinker": "^2.9",
        "spatie/laravel-permission": "^6.24"
    }
}
```

---

## 3. Project Structure

### `/app` Directory

#### `/app/Models`
| Model | Table | Purpose |
|-------|-------|---------|
| `User` | `users` | User accounts with roles, avatar, phone |
| `AAB_Event` | `aab_events` | Event entities with all event details |
| `AAB_Category` | `aab_categories` | Event categories |
| `AAB_Registration` | `aab_registrations` | User-Event registration pivot |

**Model Relationships:**
- `User` hasMany `AAB_Event` (created_by)
- `User` hasMany `AAB_Registration`
- `User` belongsToMany `AAB_Event` (through registrations)
- `AAB_Event` belongsTo `AAB_Category`
- `AAB_Event` belongsTo `User` (creator)
- `AAB_Event` hasMany `AAB_Registration`
- `AAB_Category` hasMany `AAB_Event`
- `AAB_Registration` belongsTo `User`
- `AAB_Registration` belongsTo `AAB_Event`

#### `/app/Http/Controllers`
| Controller | Responsibility |
|------------|----------------|
| `AAB_AuthController` | Login, register, logout |
| `AAB_EventController` | Event CRUD, public listing, admin listing |
| `AAB_CategoryController` | Category CRUD |
| `AAB_UserController` | User CRUD (admin only) |
| `AAB_RegistrationController` | Event registration/unregistration |
| `AAB_ProfileController` | User profile management |

#### `/app/Http/Middleware`
| Middleware | Alias | Purpose |
|------------|-------|---------|
| `AAB_AdminMiddleware` | `admin` | Restricts routes to admin role only |

Registered in `bootstrap/app.php`:
```php
$middleware->alias([
    'admin' => \App\Http\Middleware\AAB_AdminMiddleware::class,
]);
```

#### `/app/Http/Requests`
| Request Class | Validates |
|---------------|-----------|
| `AAB_EventRequest` | Event creation/update: title, dates, place, price, category, capacity, image |
| `AAB_CategoryRequest` | Category name (unique) |
| `AAB_LoginRequest` | Email, password |
| `AAB_RegisterRequest` | Name, email (unique), password (confirmed), phone |

---

### `/routes/web.php` Logic

Routes are organized into logical groups:

```
PUBLIC ROUTES (no auth)
├── GET /              → redirect to /home
├── GET /home          → events.index (public event listing)
├── GET /events/{event} → events.show

AUTHENTICATION ROUTES
├── GET /login         → auth.login
├── POST /login        → toLogin
├── GET /register      → auth.register
├── POST /register     → toRegister
├── POST /logout       → logout

USER ROUTES (auth middleware)
├── GET /my-registrations → registrations.my
├── POST /events/{event}/register → registrations.store
├── DELETE /events/{event}/unregister → registrations.destroy
├── GET /profile       → profile.index
├── PUT /profile       → profile.update
├── PUT /profile/password → profile.password.update
├── PUT /profile/avatar → profile.avatar.update

ADMIN VIEW ROUTES (auth middleware - Admin & Manager)
├── GET /admin/events       → events.list
├── GET /admin/categories   → categories.index
├── GET /admin/users        → users.index
├── GET /admin/registrations → registrations.index

ADMIN CRUD ROUTES (auth + admin middleware)
├── Events: create, store, edit, update, destroy
├── Categories: store, update, destroy
├── Users: create, store, edit, update, destroy
```

---

### `/resources/views` Structure

```
views/
├── layouts/
│   ├── app.blade.php       # Main layout with header/footer
│   ├── header.blade.php    # Site header with logo, auth buttons
│   └── footer.blade.php    # Site footer
├── components/
│   ├── admin-nav.blade.php # Admin panel navigation tabs
│   └── user-dropdown.blade.php # User avatar dropdown menu
├── auth/
│   ├── login.blade.php     # Login form (split layout)
│   └── register.blade.php  # Registration form
├── events/
│   ├── index.blade.php     # Public event listing with search/filters
│   ├── show.blade.php      # Single event detail page
│   ├── list.blade.php      # Admin event table
│   ├── create.blade.php    # Event creation form
│   ├── edit.blade.php      # Event edit form
│   └── _form.blade.php     # Reusable event form partial
├── categories/
│   └── list.blade.php      # Admin categories table with modals
├── users/
│   ├── list.blade.php      # Admin users table
│   ├── create.blade.php    # User creation form
│   └── edit.blade.php      # User edit form
├── registrations/
│   ├── index.blade.php     # Admin all registrations view
│   └── my-registrations.blade.php # User's own registrations
├── profile/
│   └── index.blade.php     # User profile management
└── welcome.blade.php       # Default Laravel welcome (unused)
```

**Layout System:**
- `app.blade.php` is the master layout
- Uses `@yield('title')`, `@yield('content')`
- Conditionally includes header/footer (excluded on auth pages)
- Uses `@stack('styles')` and `@stack('scripts')` for page-specific assets

---

### `/public` Directory
```
public/
├── index.php       # Application entry point
├── storage/        # Symlink to storage/app/public (images)
└── build/          # Vite compiled assets (generated)
```

The `storage` symlink is created via `php artisan storage:link` and serves uploaded images.

---

### `/database` Directory

#### Migrations (execution order)
1. `0001_01_01_000000_create_users_table.php` - users, password_reset_tokens, sessions
2. `0001_01_01_000001_create_cache_table.php` - cache, cache_locks
3. `0001_01_01_000002_create_jobs_table.php` - jobs, job_batches, failed_jobs
4. `2025_12_15_185404_add_role_profile_image_phone_to_users_table.php` - adds role, profile_image, phone to users
5. `2025_12_15_185408_create_aab_categories_table.php` - aab_categories
6. `2025_12_15_185413_create_aab_events_table.php` - aab_events with foreign keys
7. `2025_12_15_185415_create_aab_registrations_table.php` - aab_registrations pivot
8. `2025_12_24_134837_create_permission_tables.php` - Spatie permission tables
9. `2025_12_24_152922_add_avatar_to_users_table.php` - adds avatar to users

#### Seeders
`DatabaseSeeder.php` creates:
- Permissions: view/edit/delete events, categories, users
- Roles: admin (all permissions), manager (view-only), user (no admin permissions)
- Default users:
  - `admin@eventplanner.com` / `admin123` (admin role)
  - `manager@eventplanner.com` / `manager123` (manager role)
  - `user@eventplanner.com` / `user123` (user role)
- Sample categories: Technology, Business, Arts & Culture, Sports, Education
- Sample events with various attributes

#### Factories
- `UserFactory.php` - generates fake users
- `AAB_CategoryFactory.php` - generates fake categories
- `AAB_EventFactory.php` - generates fake events

---

### `/config` Important Files
| File | Purpose |
|------|---------|
| `app.php` | Application name, timezone, locale |
| `auth.php` | Authentication guards and providers |
| `database.php` | Database connections (MySQL, SQLite) |
| `filesystems.php` | Disk configurations (local, public, s3) |
| `permission.php` | Spatie permission package configuration |
| `session.php` | Session driver configuration |

---

## 4. Database Design

### Tables Overview

#### `users`
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT |
| name | varchar(255) | NOT NULL |
| email | varchar(255) | UNIQUE, NOT NULL |
| email_verified_at | timestamp | NULLABLE |
| password | varchar(255) | NOT NULL |
| role | varchar(255) | DEFAULT 'user' |
| profile_image | varchar(255) | NULLABLE |
| avatar | varchar(255) | NULLABLE |
| phone | varchar(255) | NULLABLE |
| remember_token | varchar(100) | NULLABLE |
| timestamps | | created_at, updated_at |

#### `aab_categories`
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT |
| name | varchar(255) | NOT NULL |
| timestamps | | created_at, updated_at |

#### `aab_events`
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT |
| title | varchar(255) | NOT NULL |
| description | text | NOT NULL |
| start_date | datetime | NOT NULL |
| end_date | datetime | NOT NULL |
| place | varchar(255) | NOT NULL |
| price | decimal(10,2) | DEFAULT 0 |
| category_id | bigint | FK → aab_categories.id, CASCADE DELETE |
| capacity | int | NOT NULL |
| image | varchar(255) | NULLABLE |
| created_by | bigint | FK → users.id, CASCADE DELETE |
| is_free | boolean | DEFAULT false |
| status | enum('active','archived') | DEFAULT 'active' |
| timestamps | | created_at, updated_at |

#### `aab_registrations`
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT |
| user_id | bigint | FK → users.id, CASCADE DELETE |
| event_id | bigint | FK → aab_events.id, CASCADE DELETE |
| timestamps | | created_at, updated_at |
| | | UNIQUE(user_id, event_id) |

### Relationships Diagram
```
users (1) ─────────────────────── (N) aab_events
          created_by                  

users (N) ─────────────────────── (N) aab_events
          aab_registrations (pivot)

aab_categories (1) ────────────── (N) aab_events
                   category_id
```

### Foreign Key Logic
- Deleting a user cascades to delete their created events and registrations
- Deleting a category cascades to delete all events in that category
- Deleting an event cascades to delete all registrations for that event
- Unique constraint on (user_id, event_id) prevents duplicate registrations

---

## 5. Authentication & Authorization

### Authentication Flow

#### Login Flow
1. User visits `/login` → `AAB_AuthController@login` → `auth/login.blade.php`
2. Form submits to `/login` (POST) → `AAB_AuthController@toLogin`
3. `AAB_LoginRequest` validates email and password
4. `Auth::attempt($credentials)` checks credentials
5. On success: session regenerated, redirect based on role
   - Admin/Manager → `/admin/events`
   - User → `/home`
6. On failure: redirect back with error message

#### Registration Flow
1. User visits `/register` → `AAB_AuthController@register`
2. Form submits → `AAB_AuthController@toRegister`
3. `AAB_RegisterRequest` validates input
4. User created with hashed password, role='user'
5. Redirect to login with success message

#### Logout Flow
1. POST to `/logout` → `AAB_AuthController@logout`
2. `Auth::logout()`, session invalidated and regenerated
3. Redirect to login

### Role Handling (Spatie Laravel Permission)

**Roles:**
- `admin` - Full access to all features
- `manager` - View-only access to admin panel
- `user` - Public access only

**Permissions:**
```php
$permissions = [
    'view events', 'edit events', 'delete events', 'publish events',
    'view categories', 'edit categories', 'delete categories', 'create categories',
    'view users', 'create users', 'edit users', 'delete users',
];
```

**Permission Assignment:**
- Admin: All permissions
- Manager: view events, view categories, view users
- User: No admin permissions

### Middleware Usage

| Middleware | Applied To | Purpose |
|------------|------------|---------|
| `auth` | User routes, Admin routes | Require authentication |
| `admin` | Admin CRUD routes | Require admin role |

### Route Protection

```php
// Authenticated users only
Route::middleware('auth')->group(function () {
    // User routes
});

// Admin view routes (Admin & Manager can view)
Route::prefix('admin')->middleware('auth')->group(function () {
    // Lists/views only
});

// Admin CRUD routes (Admin only)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Create, update, delete operations
});
```

### Authorization in Controllers
Controllers use Laravel's authorization:
```php
$this->authorize('edit events');
$this->authorize('delete events');
$this->authorize('create categories');
```

Views use Blade directives:
```blade
@can('edit events')
    <a href="{{ route('events.create') }}">Create event</a>
@endcan
```

---

## 6. Event Management Logic

### Event Creation Flow
1. Admin accesses `/admin/events/create`
2. `AAB_EventController@create` checks `edit events` permission
3. Form displays with all categories loaded
4. Form submits to `/admin/events` (POST)
5. `AAB_EventRequest` validates:
   - title: required, max 255
   - description: required
   - start_date: required, after now
   - end_date: required, after start_date
   - place: required, max 255
   - price: nullable, numeric, min 0
   - category_id: required, exists in aab_categories
   - capacity: required, integer, min 1
   - image: nullable, image (jpeg,png,jpg,gif), max 2MB
   - is_free: nullable, boolean
   - status: nullable, in (active, archived)
6. `AAB_EventController@store`:
   - Handles image upload to `storage/app/public/events/`
   - Sets `created_by` to authenticated user
   - Auto-sets `is_free` based on price
   - Creates event record
7. Redirect to events listing

### Category Association
- Events belong to exactly one category
- Category dropdown populated from `AAB_Category::all()`
- Foreign key constraint ensures data integrity
- Cannot delete category if it has events (checked in controller)

### Image Upload Handling
```php
if ($request->hasFile('image')) {
    $imageName = time() . '.' . $request->image->extension();
    $request->image->storeAs('events', $imageName, 'public');
    $validated['image'] = 'events/' . $imageName;
}
```
- Images stored in `storage/app/public/events/`
- Accessible via `/storage/events/{filename}`
- Old images deleted on update
- Max size: 2MB
- Allowed types: jpeg, png, jpg, gif

### Validation Rules (AAB_EventRequest)
```php
return [
    'title' => ['required', 'string', 'max:255'],
    'description' => ['required', 'string'],
    'start_date' => ['required', 'date', 'after:now'],
    'end_date' => ['required', 'date', 'after:start_date'],
    'place' => ['required', 'string', 'max:255'],
    'price' => ['nullable', 'numeric', 'min:0'],
    'category_id' => ['required', 'exists:aab_categories,id'],
    'capacity' => ['required', 'integer', 'min:1'],
    'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    'is_free' => ['nullable', 'boolean'],
    'status' => ['nullable', 'in:active,archived'],
];
```

### Event Archival (Soft Delete)
Events are not deleted but archived:
```php
public function destroy(AAB_Event $event)
{
    $event->update(['status' => 'archived']);
    return redirect()->route('events.list')->with('success', 'Event archived.');
}
```

---

## 7. Admin Panel

### Admin-Only Routes
All routes under `/admin` prefix:
- `/admin/events` - Event management
- `/admin/categories` - Category management
- `/admin/users` - User management
- `/admin/registrations` - View all registrations

### CRUD Logic

#### Events
| Action | Route | Method | Permission |
|--------|-------|--------|------------|
| List | /admin/events | GET | auth |
| Create Form | /admin/events/create | GET | edit events |
| Store | /admin/events | POST | edit events |
| Edit Form | /admin/events/{event}/edit | GET | edit events |
| Update | /admin/events/{event} | PUT | edit events |
| Archive | /admin/events/{event} | DELETE | delete events |

#### Categories
| Action | Route | Method | Permission |
|--------|-------|--------|------------|
| List | /admin/categories | GET | auth |
| Store | /admin/categories | POST | create categories |
| Update | /admin/categories/{category} | PUT | edit categories |
| Delete | /admin/categories/{category} | DELETE | delete categories |

Categories use modal dialogs for create/edit (no separate pages).

#### Users
| Action | Route | Method | Permission |
|--------|-------|--------|------------|
| List | /admin/users | GET | auth |
| Create Form | /admin/users/create | GET | create users |
| Store | /admin/users | POST | create users |
| Edit Form | /admin/users/{user}/edit | GET | edit users |
| Update | /admin/users/{user} | PUT | edit users |
| Delete | /admin/users/{user} | DELETE | delete users |

### UI Separation
Admin views have distinct styling:
- Container: `.admin-container` with `.admin-content`
- Header: `.admin-header` with `.page-title`
- Navigation: `admin-nav.blade.php` component
- Tables: `.events-table-card` styling
- Action menus: Dropdown menus for edit/delete

### Admin Navigation Component
```blade
<nav class="admin-nav">
    <a href="{{ route('users.index') }}" class="admin-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Users</a>
    <a href="{{ route('registrations.index') }}" class="admin-nav-link">Registrations</a>
    <a href="{{ route('categories.index') }}" class="admin-nav-link">Categories</a>
    <a href="{{ route('events.list') }}" class="admin-nav-link">Events</a>
</nav>
```

---

## 8. Frontend Logic

### Layout Structure
Main layout (`layouts/app.blade.php`):
```blade
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'AAB Event Planner')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @if(!request()->routeIs('login') && !request()->routeIs('register'))
        @include('layouts.header')
    @endif

    <main>@yield('content')</main>

    @if(!request()->routeIs('login') && !request()->routeIs('register'))
        @include('layouts.footer')
    @endif

    @stack('scripts')
</body>
</html>
```

### Forms
- All forms use `@csrf` token
- Update forms use `@method('PUT')`
- Delete forms use `@method('DELETE')`
- File uploads use `enctype="multipart/form-data"`
- Validation errors displayed with `@error('field')...@enderror`
- Old values preserved with `old('field', $default)`

### Tables (Admin)
Structure:
```blade
<div class="events-table-card">
    <div class="table-header">
        <h2 class="table-title">Title</h2>
        <a href="..." class="btn-create-event">Create</a>
    </div>
    <div class="table-container">
        <table class="events-table">
            <thead>...</thead>
            <tbody>
                @forelse($items as $item)
                    <tr>...</tr>
                @empty
                    <tr><td colspan="N">No items found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
```

### Search & Filters
Public events page includes:
```blade
<form method="GET" action="{{ route('home') }}" class="filters">
    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}">
    <select name="weekday" onchange="this.form.submit()">
        <option value="">Weekdays</option>
        <option value="monday">Monday</option>
        <!-- ... -->
    </select>
    <select name="category_id" onchange="this.form.submit()">
        <option value="">Any category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</form>
```

Controller filtering:
```php
if ($request->filled('search')) {
    $query->where('title', 'like', '%' . $request->search . '%')
          ->orWhere('description', 'like', '%' . $request->search . '%');
}
if ($request->filled('category_id')) {
    $query->where('category_id', $request->category_id);
}
if ($request->filled('weekday')) {
    $query->whereRaw('DAYNAME(start_date) = ?', [ucfirst($request->weekday)]);
}
```

### Pagination
Using Laravel's built-in pagination:
```php
$events = $query->paginate(12); // Public: 12 per page
$events = $query->paginate(20); // Admin: 20 per page
```

```blade
@if($events->hasMorePages())
    <a href="{{ $events->nextPageUrl() }}">Load more...</a>
@endif

<!-- Or full pagination -->
{{ $events->links() }}
```

### Error Handling & Validation Messages
```blade
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
```

---

## 9. Build & Asset System

### Vite Configuration
`vite.config.js`:
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

### Package.json Scripts
```json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build"
    },
    "devDependencies": {
        "axios": "^1.6.4",
        "laravel-vite-plugin": "^1.0",
        "vite": "^5.0"
    }
}
```

### Development Workflow
```bash
# Install Node dependencies
npm install

# Development server (hot reload)
npm run dev

# Production build
npm run build
```

### Blade Integration
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

This directive:
- In development: includes Vite dev server scripts
- In production: includes compiled assets from `public/build/`

---

## 10. Common Issues & Fixes

### Vite Manifest Errors
**Error:** `Vite manifest not found`

**Cause:** Production assets not built or missing `public/build/manifest.json`

**Fix:**
```bash
npm run build
```

Or for development:
```bash
npm run dev
```

### Cache/Session Issues
**Symptoms:** Login not persisting, CSRF token mismatch

**Fix:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Or clear all:
```bash
php artisan optimize:clear
```

### Migration Problems
**Error:** Foreign key constraint fails

**Fix:** Run migrations in correct order or fresh:
```bash
php artisan migrate:fresh --seed
```

**Error:** Table already exists

**Fix:**
```bash
php artisan migrate:fresh  # WARNING: Drops all tables
```

### File Permission Issues
**Symptoms:** Cannot upload images, storage not accessible

**Fix:**
1. Create storage symlink:
```bash
php artisan storage:link
```

2. Ensure directories are writable:
```bash
# On Linux/Mac
chmod -R 775 storage bootstrap/cache
```

3. On Windows/XAMPP, ensure Apache has write access to `storage/` folder.

### Common MySQL Issues
**Error:** SQLSTATE[HY000] [2002] No connection

**Fix:** Check `.env` database configuration:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aab_eventplanner
DB_USERNAME=root
DB_PASSWORD=
```

### Spatie Permission Cache
After modifying roles/permissions:
```bash
php artisan permission:cache-reset
```

### Image Not Displaying
1. Ensure storage symlink exists: `php artisan storage:link`
2. Check image path in database matches actual file location
3. Verify file exists in `storage/app/public/events/` or `storage/app/public/avatars/`

---

## Prompt to Recreate This Project From Scratch

The following is a standalone prompt to recreate the AAB_EventPlanner project from zero.

---

### Prerequisites

1. PHP 8.2 or higher installed
2. Composer installed globally
3. Node.js 18+ and npm installed
4. MySQL database server (or use SQLite for simplicity)
5. A local development server (XAMPP, Laragon, or `php artisan serve`)

---

### Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel AAB_EventPlanner "11.*"
cd AAB_EventPlanner
```

---

### Step 2: Configure Environment

Edit `.env` file:
```env
APP_NAME="AAB Event Planner"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aab_eventplanner
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL:
```sql
CREATE DATABASE aab_eventplanner CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### Step 3: Install Spatie Permission Package

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

---

### Step 4: Create Migrations

Keep the default Laravel migrations, then create custom ones:

**Migration: Add role, profile_image, phone to users table**
```bash
php artisan make:migration add_role_profile_image_phone_to_users_table --table=users
```

Content:
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('user')->after('password');
        $table->string('profile_image')->nullable()->after('role');
        $table->string('phone')->nullable()->after('profile_image');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role', 'profile_image', 'phone']);
    });
}
```

**Migration: Add avatar to users table**
```bash
php artisan make:migration add_avatar_to_users_table --table=users
```

Content:
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('avatar')->nullable()->after('profile_image');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('avatar');
    });
}
```

**Migration: Create aab_categories table**
```bash
php artisan make:migration create_aab_categories_table
```

Content:
```php
public function up(): void
{
    Schema::create('aab_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('aab_categories');
}
```

**Migration: Create aab_events table**
```bash
php artisan make:migration create_aab_events_table
```

Content:
```php
public function up(): void
{
    Schema::create('aab_events', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->dateTime('start_date');
        $table->dateTime('end_date');
        $table->string('place');
        $table->decimal('price', 10, 2)->default(0);
        $table->foreignId('category_id')->constrained('aab_categories')->onDelete('cascade');
        $table->integer('capacity');
        $table->string('image')->nullable();
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->boolean('is_free')->default(false);
        $table->enum('status', ['active', 'archived'])->default('active');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('aab_events');
}
```

**Migration: Create aab_registrations table**
```bash
php artisan make:migration create_aab_registrations_table
```

Content:
```php
public function up(): void
{
    Schema::create('aab_registrations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('event_id')->constrained('aab_events')->onDelete('cascade');
        $table->timestamps();
        $table->unique(['user_id', 'event_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('aab_registrations');
}
```

---

### Step 5: Create Models

**app/Models/User.php** - Add traits and relationships:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'profile_image', 'avatar', 'phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events()
    {
        return $this->hasMany(AAB_Event::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(AAB_Registration::class, 'user_id');
    }

    public function registeredEvents()
    {
        return $this->belongsToMany(AAB_Event::class, 'aab_registrations', 'user_id', 'event_id')
                    ->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}
```

**app/Models/AAB_Category.php**:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AAB_Category extends Model
{
    use HasFactory;

    protected $table = 'aab_categories';
    protected $fillable = ['name'];

    public function events()
    {
        return $this->hasMany(AAB_Event::class, 'category_id');
    }
}
```

**app/Models/AAB_Event.php**:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AAB_Event extends Model
{
    use HasFactory;

    protected $table = 'aab_events';

    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'place',
        'price', 'category_id', 'capacity', 'image', 'created_by',
        'is_free', 'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(AAB_Category::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(AAB_Registration::class, 'event_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'aab_registrations', 'event_id', 'user_id')
                    ->withTimestamps();
    }

    public function getAvailablePlacesAttribute()
    {
        return $this->capacity - $this->registrations()->count();
    }

    public function isFull()
    {
        return $this->available_places <= 0;
    }
}
```

**app/Models/AAB_Registration.php**:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AAB_Registration extends Model
{
    protected $table = 'aab_registrations';
    protected $fillable = ['user_id', 'event_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(AAB_Event::class, 'event_id');
    }
}
```

---

### Step 6: Create Form Requests

```bash
php artisan make:request AAB_EventRequest
php artisan make:request AAB_CategoryRequest
php artisan make:request AAB_LoginRequest
php artisan make:request AAB_RegisterRequest
```

Implement validation rules for each (see Section 6 for rules).

---

### Step 7: Create Middleware

```bash
php artisan make:middleware AAB_AdminMiddleware
```

**app/Http/Middleware/AAB_AdminMiddleware.php**:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AAB_AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
```

**Register in bootstrap/app.php**:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AAB_AdminMiddleware::class,
    ]);
})
```

---

### Step 8: Create Controllers

```bash
php artisan make:controller AAB_AuthController
php artisan make:controller AAB_EventController
php artisan make:controller AAB_CategoryController
php artisan make:controller AAB_UserController
php artisan make:controller AAB_RegistrationController
php artisan make:controller AAB_ProfileController
```

Implement controller logic as shown in Section 6 and Section 7.

---

### Step 9: Create Routes

Edit **routes/web.php** with the route structure shown in Section 3.

---

### Step 10: Create Views

Create the directory structure:
```
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── header.blade.php
│   └── footer.blade.php
├── components/
│   ├── admin-nav.blade.php
│   └── user-dropdown.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── events/
│   ├── index.blade.php
│   ├── show.blade.php
│   ├── list.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── _form.blade.php
├── categories/
│   └── list.blade.php
├── users/
│   ├── list.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── registrations/
│   ├── index.blade.php
│   └── my-registrations.blade.php
└── profile/
    └── index.blade.php
```

Create each view with appropriate Blade content (see Section 8 for patterns).

---

### Step 11: Create CSS

Edit **resources/css/app.css** with custom styles for:
- Global resets and typography
- Header and footer
- User dropdown component
- Auth forms (split layout)
- Event cards grid
- Admin tables
- Form inputs
- Buttons
- Alerts
- Modals
- Pagination

---

### Step 12: Create Database Seeder

Edit **database/seeders/DatabaseSeeder.php**:
- Create permissions
- Create roles (admin, manager, user)
- Assign permissions to roles
- Create default users
- Create sample categories
- Create sample events

---

### Step 13: Run Migrations and Seed

```bash
php artisan migrate
php artisan db:seed
```

Or in one command:
```bash
php artisan migrate:fresh --seed
```

---

### Step 14: Create Storage Link

```bash
php artisan storage:link
```

---

### Step 15: Install Frontend Dependencies

```bash
npm install
```

---

### Step 16: Build Assets

Development:
```bash
npm run dev
```

Production:
```bash
npm run build
```

---

### Step 17: Start Development Server

```bash
php artisan serve
```

Access the application at `http://localhost:8000`

---

### Step 18: Test Login

- Admin: `admin@eventplanner.com` / `admin123`
- Manager: `manager@eventplanner.com` / `manager123`
- User: `user@eventplanner.com` / `user123`

---

### Summary of Commands

```bash
# Create project
composer create-project laravel/laravel AAB_EventPlanner "11.*"
cd AAB_EventPlanner

# Install Spatie Permission
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Create migrations (5 custom migrations)
php artisan make:migration add_role_profile_image_phone_to_users_table --table=users
php artisan make:migration add_avatar_to_users_table --table=users
php artisan make:migration create_aab_categories_table
php artisan make:migration create_aab_events_table
php artisan make:migration create_aab_registrations_table

# Create middleware
php artisan make:middleware AAB_AdminMiddleware

# Create form requests
php artisan make:request AAB_EventRequest
php artisan make:request AAB_CategoryRequest
php artisan make:request AAB_LoginRequest
php artisan make:request AAB_RegisterRequest

# Create controllers
php artisan make:controller AAB_AuthController
php artisan make:controller AAB_EventController
php artisan make:controller AAB_CategoryController
php artisan make:controller AAB_UserController
php artisan make:controller AAB_RegistrationController
php artisan make:controller AAB_ProfileController

# Run migrations and seed
php artisan migrate:fresh --seed

# Storage link
php artisan storage:link

# Install Node dependencies and build
npm install
npm run build

# Start server
php artisan serve
```

---

This completes the AAB_EventPlanner documentation. The project is a fully functional event management system with role-based access control, custom authentication, and a clean separation between public and admin interfaces.
