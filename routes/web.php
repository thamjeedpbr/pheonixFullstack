<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return Inertia::render('Auth/Login');
    })->name('login');
});

// Authenticated routes
Route::middleware(['web'])->group(function () {
    
    // Dashboard (check token via JavaScript, not server-side)
    Route::get('/dashboard', function () {
        // For Inertia, we'll pass null user and let frontend handle auth
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Profile routes (to be created later)
    Route::get('/profile', function () {
        return Inertia::render('Profile/Edit');
    })->name('profile.edit');

    // Other routes will be added as we develop more modules
    
});
