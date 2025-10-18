<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Vue SPA - All routes handled by Vue Router
|--------------------------------------------------------------------------
*/

// Catch-all route - Serve Vue SPA for all routes
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
