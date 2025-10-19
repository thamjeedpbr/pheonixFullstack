<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MaterialController;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
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

    // User Management Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.users.index');
        Route::post('/', [UserController::class, 'store'])->name('api.users.store');
        Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show');
        Route::put('/{id}', [UserController::class, 'update'])->name('api.users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.users.destroy');
    });

    // Machine Management Routes
    Route::prefix('machines')->group(function () {
        Route::get('/', [MachineController::class, 'index'])->name('api.machines.index');
        Route::post('/', [MachineController::class, 'store'])->name('api.machines.store');
        Route::get('/stats', [MachineController::class, 'stats'])->name('api.machines.stats');
        Route::get('/types', [MachineController::class, 'getMachineTypes'])->name('api.machines.types');
        Route::get('/processes', [MachineController::class, 'getProcesses'])->name('api.machines.processes');
        Route::get('/{id}', [MachineController::class, 'show'])->name('api.machines.show');
        Route::put('/{id}', [MachineController::class, 'update'])->name('api.machines.update');
        Route::delete('/{id}', [MachineController::class, 'destroy'])->name('api.machines.destroy');
    });

    // Material Management Routes
    Route::prefix('materials')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('api.materials.index');
        Route::post('/', [MaterialController::class, 'store'])->name('api.materials.store');
        Route::get('/stats', [MaterialController::class, 'stats'])->name('api.materials.stats');
        Route::get('/departments', [MaterialController::class, 'getDepartments'])->name('api.materials.departments');
        Route::get('/{id}', [MaterialController::class, 'show'])->name('api.materials.show');
        Route::put('/{id}', [MaterialController::class, 'update'])->name('api.materials.update');
        Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('api.materials.destroy');
    });

    // Permissions endpoint
    Route::get('/permissions', function () {
        return response()->json([
            'success' => true,
            'data' => UserPermission::select('id', 'role_name')->get(),
        ]);
    })->name('api.permissions');
});
