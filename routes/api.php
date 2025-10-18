<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\UserPermission;
use App\Models\Machine;
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

    // Permissions endpoint
    Route::get('/permissions', function () {
        return response()->json([
            'success' => true,
            'data' => UserPermission::select('id', 'role_name')->get(),
        ]);
    })->name('api.permissions');

    // Machines endpoint
    Route::get('/machines', function (Request $request) {
        $query = Machine::select('id', 'machine_name', 'machine_type', 'status');
        
        if ($request->has('status')) {
            $query->where('status', (bool) $request->status);
        }
        
        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    })->name('api.machines');
});
