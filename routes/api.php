<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MachineTypeController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\OrderController;
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
        Route::post('/check-role', [AuthController::class, 'checkRole'])->name('api.auth.check-role');
    });

    // User Management Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.users.index');
        Route::post('/', [UserController::class, 'store'])->name('api.users.store');
        Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show');
        Route::put('/{id}', [UserController::class, 'update'])->name('api.users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.users.destroy');
    });

    // Roles endpoint
    Route::get('/roles', [UserController::class, 'getRoles'])->name('api.roles');

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

    // Machine Type Management Routes
    Route::prefix('machine-types')->group(function () {
        Route::get('/', [MachineTypeController::class, 'index'])->name('api.machine-types.index');
        Route::post('/', [MachineTypeController::class, 'store'])->name('api.machine-types.store');
        Route::get('/stats', [MachineTypeController::class, 'stats'])->name('api.machine-types.stats');
        Route::get('/dropdown', [MachineTypeController::class, 'dropdown'])->name('api.machine-types.dropdown');
        Route::get('/{id}', [MachineTypeController::class, 'show'])->name('api.machine-types.show');
        Route::put('/{id}', [MachineTypeController::class, 'update'])->name('api.machine-types.update');
        Route::delete('/{id}', [MachineTypeController::class, 'destroy'])->name('api.machine-types.destroy');
    });

    // Process Management Routes
    Route::prefix('processes')->group(function () {
        Route::get('/', [ProcessController::class, 'index'])->name('api.processes.index');
        Route::post('/', [ProcessController::class, 'store'])->name('api.processes.store');
        Route::get('/stats', [ProcessController::class, 'stats'])->name('api.processes.stats');
        Route::get('/dropdown', [ProcessController::class, 'dropdown'])->name('api.processes.dropdown');
        Route::get('/{id}', [ProcessController::class, 'show'])->name('api.processes.show');
        Route::put('/{id}', [ProcessController::class, 'update'])->name('api.processes.update');
        Route::delete('/{id}', [ProcessController::class, 'destroy'])->name('api.processes.destroy');
    });

    // Department Management Routes
    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('api.departments.index');
        Route::post('/', [DepartmentController::class, 'store'])->name('api.departments.store');
        Route::get('/stats', [DepartmentController::class, 'stats'])->name('api.departments.stats');
        Route::get('/dropdown', [DepartmentController::class, 'dropdown'])->name('api.departments.dropdown');
        Route::get('/{id}', [DepartmentController::class, 'show'])->name('api.departments.show');
        Route::put('/{id}', [DepartmentController::class, 'update'])->name('api.departments.update');
        Route::delete('/{id}', [DepartmentController::class, 'destroy'])->name('api.departments.destroy');
    });

    // Shift Management Routes
    Route::prefix('shifts')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('api.shifts.index');
        Route::post('/', [ShiftController::class, 'store'])->name('api.shifts.store');
        Route::get('/stats', [ShiftController::class, 'stats'])->name('api.shifts.stats');
        Route::get('/dropdown', [ShiftController::class, 'dropdown'])->name('api.shifts.dropdown');
        Route::get('/{id}', [ShiftController::class, 'show'])->name('api.shifts.show');
        Route::put('/{id}', [ShiftController::class, 'update'])->name('api.shifts.update');
        Route::delete('/{id}', [ShiftController::class, 'destroy'])->name('api.shifts.destroy');
    });

    // Section Management Routes
    Route::prefix('sections')->group(function () {
        Route::get('/', [SectionController::class, 'index'])->name('api.sections.index');
        Route::post('/', [SectionController::class, 'store'])->name('api.sections.store');
        Route::get('/stats', [SectionController::class, 'stats'])->name('api.sections.stats');
        Route::get('/dropdown', [SectionController::class, 'dropdown'])->name('api.sections.dropdown');
        Route::get('/{id}', [SectionController::class, 'show'])->name('api.sections.show');
        Route::put('/{id}', [SectionController::class, 'update'])->name('api.sections.update');
        Route::delete('/{id}', [SectionController::class, 'destroy'])->name('api.sections.destroy');
    });

    // Status Management Routes
    Route::prefix('statuses')->group(function () {
        Route::get('/', [StatusController::class, 'index'])->name('api.statuses.index');
        Route::post('/', [StatusController::class, 'store'])->name('api.statuses.store');
        Route::get('/stats', [StatusController::class, 'stats'])->name('api.statuses.stats');
        Route::get('/dropdown', [StatusController::class, 'dropdown'])->name('api.statuses.dropdown');
        Route::get('/{id}', [StatusController::class, 'show'])->name('api.statuses.show');
        Route::put('/{id}', [StatusController::class, 'update'])->name('api.statuses.update');
        Route::delete('/{id}', [StatusController::class, 'destroy'])->name('api.statuses.destroy');
    });

    // Order Management Routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('api.orders.index');
        Route::post('/', [OrderController::class, 'store'])->name('api.orders.store');
        Route::get('/stats', [OrderController::class, 'stats'])->name('api.orders.stats');
        Route::get('/status/{status}', [OrderController::class, 'byStatus'])->name('api.orders.byStatus');
        Route::get('/{id}', [OrderController::class, 'show'])->name('api.orders.show');
        Route::put('/{id}', [OrderController::class, 'update'])->name('api.orders.update');
        Route::patch('/{id}/status', [OrderController::class, 'changeStatus'])->name('api.orders.changeStatus');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('api.orders.destroy');
    });
});
