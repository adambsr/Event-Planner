<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AAB_EventController;
use App\Http\Controllers\AAB_CategoryController;
use App\Http\Controllers\AAB_UserController;
use App\Http\Controllers\AAB_RegistrationController;
use App\Http\Controllers\AAB_AuthController;
use App\Http\Controllers\AAB_ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', function () {
    return redirect()->route('home');
});

// Public events listing
// Route::get('/events', [AAB_EventController::class, 'index'])->name('events.index');
Route::get('/home', [AAB_EventController::class, 'index'])->name('home'); // Alias for backward compatibility
Route::get('/events/{event}', [AAB_EventController::class, 'show'])->name('events.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AAB_AuthController::class, 'login'])->name('login');
Route::post('/login', [AAB_AuthController::class, 'toLogin'])->name('toLogin');
Route::get('/register', [AAB_AuthController::class, 'register'])->name('register');
Route::post('/register', [AAB_AuthController::class, 'toRegister'])->name('toRegister');
Route::post('/logout', [AAB_AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // User registrations
    Route::get('/my-registrations', [AAB_RegistrationController::class, 'myRegistrations'])->name('registrations.my');
    
    // Register/Unregister to events
    Route::post('/events/{event}/register', [AAB_RegistrationController::class, 'store'])->name('registrations.store');
    Route::delete('/events/{event}/unregister', [AAB_RegistrationController::class, 'destroy'])->name('registrations.destroy');
    
    // Profile routes
    Route::get('/profile', [AAB_ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [AAB_ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AAB_ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/avatar', [AAB_ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - View Only (Admin & Manager)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->group(function () {
    // Events - View list (Admin & Manager)
    Route::get('/events', [AAB_EventController::class, 'adminList'])->name('events.list');
    
    // Categories - View list (Admin & Manager)
    Route::get('/categories', [AAB_CategoryController::class, 'index'])->name('categories.index');
    
    // Users - View list (Admin & Manager)
    Route::get('/users', [AAB_UserController::class, 'index'])->name('users.index');
    
    // All registrations - View (Admin & Manager)
    Route::get('/registrations', [AAB_RegistrationController::class, 'index'])->name('registrations.index');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - Full CRUD (Admin Only)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Events CRUD
    Route::get('/events/create', [AAB_EventController::class, 'create'])->name('events.create');
    Route::post('/events', [AAB_EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [AAB_EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [AAB_EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [AAB_EventController::class, 'destroy'])->name('events.destroy');
    
    // Categories CRUD (modals handle create/edit forms)
    Route::post('/categories', [AAB_CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [AAB_CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AAB_CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Users CRUD
    Route::get('/users/create', [App\Http\Controllers\AAB_UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [App\Http\Controllers\AAB_UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\AAB_UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\AAB_UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\AAB_UserController::class, 'destroy'])->name('admin.users.destroy');
});
