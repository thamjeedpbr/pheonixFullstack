# Spatie Permission Quick Reference

## Installation
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## User Methods
```php
// Roles
$user->assignRole('Super Admin');
$user->removeRole('Operator');
$user->syncRoles(['Supervisor', 'QC Inspector']);
$user->hasRole('Super Admin');
$user->hasAnyRole(['Super Admin', 'Supervisor']);
$user->getRoleNames(); // Returns collection

// Permissions
$user->givePermissionTo('user-menu.create');
$user->revokePermissionTo('user-menu.create');
$user->hasPermissionTo('user-menu.view');
$user->hasAnyPermission(['user-menu.view', 'job-menu.view']);
$user->hasAllPermissions(['user-menu.view', 'user-menu.create']);
$user->getAllPermissions(); // Includes role permissions
$user->getDirectPermissions(); // Only direct permissions
```

## Route Protection
```php
// Single permission
Route::middleware('permission:user-menu.view')->get('/users', [UserController::class, 'index']);

// Multiple permissions (must have all)
Route::middleware('permission:user-menu.view,user-menu.create')->post('/users', [UserController::class, 'store']);

// Role
Route::middleware('role:Super Admin')->get('/admin', [AdminController::class, 'dashboard']);

// Multiple roles (can have any)
Route::middleware('role:Super Admin|Supervisor')->get('/reports', [ReportController::class, 'index']);

// Role OR Permission
Route::middleware('role_or_permission:Super Admin|user-menu.view')->get('/data', [DataController::class, 'index']);
```

## Controller Checks
```php
// Check in controller
if (!auth()->user()->hasPermissionTo('user-menu.create')) {
    abort(403);
}

// With helper trait
use App\Traits\HasPermissionHelpers;

if ($error = $this->checkPermissionOrFail('user-menu.create')) {
    return $error;
}
```

## Blade Directives
```php
@role('Super Admin')
    <!-- Admin only content -->
@endrole

@hasrole('Super Admin|Supervisor')
    <!-- Admin or Supervisor content -->
@endhasrole

@can('user-menu.create')
    <button>Create User</button>
@endcan

@canany(['user-menu.view', 'user-menu.update'])
    <!-- Has any of these permissions -->
@endcanany
```

## Permission Format
```
{module}.{action}

Examples:
- dashboard.view
- user-menu.create
- job-menu.update
- machine-master.delete
```

## Available Roles
- Super Admin (all permissions)
- Supervisor (management permissions)
- Operator (basic permissions)
- QC Inspector (quality permissions)
- Viewer (read-only)

## Cache Management
```bash
# Clear permission cache
php artisan permission:cache-reset

# In code
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
```

## Common Patterns

### Create User with Role
```php
$user = User::create([...]);
$user->assignRole('Operator');
```

### Check Multiple Permissions
```php
if ($user->hasAnyPermission(['user-menu.view', 'user-menu.create'])) {
    // User has at least one permission
}

if ($user->hasAllPermissions(['user-menu.view', 'user-menu.create'])) {
    // User has all permissions
}
```

### Get User Permissions for Frontend
```php
return [
    'roles' => $user->getRoleNames(),
    'permissions' => $user->getAllPermissions()->pluck('name'),
];
```

### Create New Permission & Assign
```php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$permission = Permission::create(['name' => 'custom.action']);
$role = Role::findByName('Supervisor');
$role->givePermissionTo($permission);
```

## Default Credentials
```
admin / password - Super Admin
supervisor1 / password - Supervisor
operator1 / password - Operator
operator2 / password - Operator
qc1 / password - QC Inspector
```
