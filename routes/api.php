<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
});

// Protected routes (authentication required)
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
        Route::get('/me', [AuthController::class, 'me'])->name('api.auth.me');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.auth.refresh');
        Route::post('/check-permission', [AuthController::class, 'checkPermission'])->name('api.auth.check-permission');
    });

    // Routes with permission checks will be added here
    // Example:
    // Route::middleware(['check.permission:user_menu_view'])->group(function () {
    //     Route::apiResource('users', UserController::class);
    // });
});
