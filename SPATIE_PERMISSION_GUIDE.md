# Spatie Permission Implementation Guide

## Overview
This guide walks you through migrating from custom permission handling to Spatie Permission package.

## Installation Steps

### 1. Install Spatie Permission Package
```bash
composer require spatie/laravel-permission
```

### 2. Publish Configuration
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 3. Run Migrations
```bash
# Run Spatie migrations
php artisan migrate

# Run our custom migration to remove old permission_id
php artisan migrate
```

### 4. Seed Roles and Permissions
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=UserSeeder
```

Or seed everything at once:
```bash
php artisan migrate:fresh --seed
```

## What Changed

### Models
- **User Model**: Added `HasRoles` trait, removed `permission()` relationship
- **UserPermission Model**: Can be deleted (kept for reference if needed)

### Database
- **New Tables** (created by Spatie):
  - `roles` - Stores role definitions
  - `permissions` - Stores permission definitions
  - `model_has_permissions` - User direct permissions
  - `model_has_roles` - User role assignments
  - `role_has_permissions` - Role permissions mapping

- **Removed**:
  - `permission_id` foreign key from `users` table
  - Can optionally drop `user_permissions` table after migration

### Permissions Format
Old format: `user_menu_view`, `user_menu_create`
New format: `user-menu.view`, `user-menu.create`

## Roles & Permissions Structure

### Available Roles
1. **Super Admin** - All permissions
2. **Supervisor** - Management and monitoring permissions
3. **Operator** - Basic operation permissions
4. **QC Inspector** - Quality control permissions
5. **Viewer** - Read-only permissions

### Permission Naming Convention
Format: `{module}.{action}`

Examples:
- `dashboard.view`
- `user-menu.create`
- `job-menu.update`
- `machine-master.delete`

### All Permissions (156 total)
- Dashboard: `dashboard.view`
- Login Menu: `login-menu.view|create|update|delete`
- Job Menu: `job-menu.view|create|update|delete`
- Manual Job Menu: `manual-job-menu.view|create|update|delete`
- Quality Menu: `quality-menu.view|create|update|delete`
- User Menu: `user-menu.view|create|update|delete`
- Role Menu: `role-menu.view|create|update|delete`
- Status Menu: `status-menu.view|create|update|delete`
- Sub Status Menu: `sub-status-menu.view|create|update|delete`
- Sheet Size: `sheet-size.view|create|update|delete`
- Material Master: `material-master.view|create|update|delete`
- Machine Master: `machine-master.view|create|update|delete`
- Standard Production: `standard-production.view|create|update|delete`
- Alert: `alert.view|create|update|delete`
- Tag: `tag.view|create|update|delete`
- Job Creation: `job-creation.view|create|update|delete`
- Shift: `shift.view|create|update|delete`
- Machine Type: `machine-type.view|create|update|delete`
- Department: `department.view|create|update|delete`
- Process: `process.view|create|update|delete`
- QC Master: `qc-master.view|create|update|delete`
- Button Group: `button-group.view|create|update|delete`
- Buttons: `buttons.view|create|update|delete`
- DM Task: `dm-task.view|create|update|delete`
- Daily Task: `daily-task.view|create|update|delete`
- DMI: `dmi.view|create|update|delete`

## Usage Examples

### In Routes
```php
// Using built-in Spatie middleware
Route::middleware(['auth:sanctum', 'permission:user-menu.view'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});

// Using role middleware
Route::middleware(['auth:sanctum', 'role:Super Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Using custom middleware (still works)
Route::middleware(['auth:sanctum', 'check.permission:user-menu.create'])->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});

// Multiple permissions (user must have all)
Route::middleware(['auth:sanctum', 'permission:user-menu.view,user-menu.update'])->group(function () {
    Route::put('/users/{id}', [UserController::class, 'update']);
});

// Multiple roles (user can have any)
Route::middleware(['auth:sanctum', 'role:Super Admin|Supervisor'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index']);
});
```

### In Controllers

```php
use App\Traits\HasPermissionHelpers;

class UserController extends Controller
{
    use HasPermissionHelpers;

    public function index(Request $request)
    {
        // Method 1: Using helper trait
        if ($error = $this->checkPermissionOrFail('user-menu.view')) {
            return $error;
        }

        // Method 2: Direct check on user
        if (!auth()->user()->hasPermissionTo('user-menu.view')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Method 3: Check role
        if (!auth()->user()->hasRole('Super Admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Method 4: Check any permission
        if (!auth()->user()->hasAnyPermission(['user-menu.view', 'user-menu.create'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return User::all();
    }
}
```

### In Blade Views
```php
@role('Super Admin')
    <!-- Content for Super Admin -->
@endrole

@hasrole('Super Admin|Supervisor')
    <!-- Content for Super Admin or Supervisor -->
@endhasrole

@can('user-menu.create')
    <button>Create User</button>
@endcan

@canany(['user-menu.create', 'user-menu.update'])
    <button>Manage Users</button>
@endcanany
```

### User Model Methods

```php
// Check permission
$user->hasPermissionTo('user-menu.create');

// Check role
$user->hasRole('Super Admin');

// Check any role
$user->hasAnyRole(['Super Admin', 'Supervisor']);

// Check all roles
$user->hasAllRoles(['Super Admin', 'Supervisor']);

// Assign role
$user->assignRole('Supervisor');

// Remove role
$user->removeRole('Operator');

// Sync roles (removes all others)
$user->syncRoles(['Supervisor']);

// Give direct permission
$user->givePermissionTo('user-menu.create');

// Revoke permission
$user->revokePermissionTo('user-menu.create');

// Get all permissions (including from roles)
$user->getAllPermissions();

// Get direct permissions only
$user->getDirectPermissions();

// Get permissions via roles
$user->getPermissionsViaRoles();
```

### Role & Permission Management

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create new role
$role = Role::create(['name' => 'Custom Role']);

// Create new permission
$permission = Permission::create(['name' => 'custom.permission']);

// Assign permission to role
$role->givePermissionTo('user-menu.view');
$role->givePermissionTo(['user-menu.view', 'user-menu.create']);

// Revoke permission from role
$role->revokePermissionTo('user-menu.view');

// Sync permissions (removes all others)
$role->syncPermissions(['user-menu.view', 'user-menu.create']);

// Check if role has permission
$role->hasPermissionTo('user-menu.view');
```

## Migration Path from Old System

### Permission Mapping
```php
// Old System → New System
'user_menu_view' → 'user-menu.view'
'user_menu_create' → 'user-menu.create'
'user_menu_update' → 'user-menu.update'
'user_menu_delete' → 'user-menu.delete'
'QCMAster_view' → 'qc-master.view'
'standerd_production_view' → 'standard-production.view'
```

### Update Existing Code
1. Replace `$user->permission->user_menu_view` with `$user->hasPermissionTo('user-menu.view')`
2. Replace `check.permission:user_menu_view` with `permission:user-menu.view`
3. Update frontend permission checks to use new format

## Testing

### Test User Login
```bash
# Admin user
curl -X POST http://your-app.test/api/login \
  -H "Content-Type: application/json" \
  -d '{"user_name":"admin","password":"password"}'

# Check user permissions
curl -X GET http://your-app.test/api/me \
  -H "Authorization: Bearer {token}"
```

### Test Permission Checks
```bash
# Should work (admin has permission)
curl -X GET http://your-app.test/api/users \
  -H "Authorization: Bearer {admin-token}"

# Should fail (operator doesn't have permission)
curl -X POST http://your-app.test/api/users \
  -H "Authorization: Bearer {operator-token}"
```

## Cache Management

Spatie Permission caches permissions. Clear cache when making changes:

```bash
# Clear permission cache
php artisan permission:cache-reset

# Or use in code
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
```

## Troubleshooting

### Issue: "Permission does not exist"
**Solution**: Make sure permissions are seeded and cache is cleared
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan permission:cache-reset
```

### Issue: "User has no permission"
**Solution**: Check if role has the permission
```php
$role = Role::findByName('Supervisor');
dd($role->permissions->pluck('name'));
```

### Issue: Old permission fields still in database
**Solution**: Run the cleanup migration
```bash
php artisan migrate
```

## Cleanup (Optional)

After confirming everything works:

1. Drop old permission table:
```bash
php artisan make:migration drop_user_permissions_table
```

```php
Schema::dropIfExists('user_permissions');
```

2. Delete old files:
- `app/Models/UserPermission.php`
- `database/seeders/UserPermissionSeeder.php`
- `database/migrations/2025_10_18_074109_create_user_permissions_table.php`

## Benefits of Spatie Permission

1. **Industry Standard**: Widely used and well-maintained
2. **Flexible**: Support for direct permissions and role-based permissions
3. **Cached**: Built-in caching for performance
4. **Feature Rich**: Guards, wildcard permissions, teams support
5. **Well Documented**: Extensive documentation and community support
6. **Blade Directives**: Easy to use in views
7. **Database Driven**: Easily manage permissions through UI
8. **Middleware**: Built-in middleware for routes

## Additional Resources

- Official Docs: https://spatie.be/docs/laravel-permission
- GitHub: https://github.com/spatie/laravel-permission
- Video Tutorial: Search "Spatie Permission Laravel" on YouTube
