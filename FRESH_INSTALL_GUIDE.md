# 🚀 FRESH INSTALLATION GUIDE

## Quick Start (One Command)

Run this single command to install everything:

```bash
composer require spatie/laravel-permission && \
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" && \
php artisan migrate:fresh --seed && \
php artisan permission:cache-reset
```

---

## Step-by-Step Installation

### Step 1: Install Spatie Permission
```bash
composer require spatie/laravel-permission
```

### Step 2: Publish Configuration
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### Step 3: Fresh Migration & Seed
This will **DELETE ALL DATA** and create everything fresh:
```bash
php artisan migrate:fresh --seed
```

This command will:
- Drop all tables and recreate them
- Run Spatie Permission migrations (creates roles, permissions tables)
- Run your custom migrations (creates users, login_details, etc.)
- Seed roles and permissions (156 permissions, 5 roles)
- Seed users with roles assigned

### Step 4: Clear Cache
```bash
php artisan permission:cache-reset
php artisan config:clear
php artisan cache:clear
```

---

## Verify Installation

### Check Database
```bash
php artisan tinker
```

```php
// Check roles (should return 5)
>>> \Spatie\Permission\Models\Role::count();

// Check permissions (should return 156)
>>> \Spatie\Permission\Models\Permission::count();

// Check users (should return 5)
>>> \App\Models\User::count();

// List all roles
>>> \Spatie\Permission\Models\Role::pluck('name');

// Check admin user
>>> $admin = \App\Models\User::where('user_name', 'admin')->first();
>>> $admin->roles->first()->name; // Should be "Super Admin"
>>> $admin->getAllPermissions()->count(); // Should be 156
```

---

## Default User Accounts

| Username | Password | Role | Permissions |
|----------|----------|------|-------------|
| admin | password | Super Admin | 156 (all) |
| supervisor1 | password | Supervisor | 18 |
| operator1 | password | Operator | 7 |
| operator2 | password | Operator | 7 |
| qc1 | password | QC Inspector | 6 |

---

## Test Login

### Using cURL
```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"user_name":"admin","password":"password"}'
```

### Expected Response
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "1|...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "user_name": "admin",
      "name": "System Administrator",
      "roles": [
        {"id": 1, "name": "Super Admin"}
      ],
      "permissions": [
        "dashboard.view",
        "user-menu.view",
        "user-menu.create",
        ...
      ]
    }
  }
}
```

---

## Test User Info

```bash
curl -X GET http://localhost/api/me \
  -H "Authorization: Bearer {your-token-here}"
```

---

## What Got Created

### Database Tables (Spatie)
- ✅ `roles` - 5 roles
- ✅ `permissions` - 156 permissions
- ✅ `model_has_roles` - User-role assignments
- ✅ `model_has_permissions` - Direct user permissions
- ✅ `role_has_permissions` - Role-permission assignments

### Your Existing Tables
- ✅ `users` - 5 test users
- ✅ `login_details` - Login tracking
- ✅ `machines` - Machine data
- ✅ All other existing tables

### Roles Created
1. **Super Admin** - 156 permissions (all)
2. **Supervisor** - 18 permissions
3. **Operator** - 7 permissions
4. **QC Inspector** - 6 permissions
5. **Viewer** - 7 permissions

---

## Quick Permission Check

```bash
php artisan tinker
```

```php
// Get a user
>>> $user = \App\Models\User::find(1);

// Check if user has permission
>>> $user->hasPermissionTo('user-menu.create');
// true or false

// Check if user has role
>>> $user->hasRole('Super Admin');
// true or false

// Get all user permissions
>>> $user->getAllPermissions()->pluck('name');
// Collection of permission names

// Get user roles
>>> $user->getRoleNames();
// Collection of role names
```

---

## Troubleshooting

### Problem: "Permission does not exist"
**Solution:**
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan permission:cache-reset
```

### Problem: "User has no roles"
**Solution:**
```bash
php artisan db:seed --class=UserSeeder
```

### Problem: Migration errors
**Solution:**
```bash
# Drop all tables and start fresh
php artisan migrate:fresh
# Then run seeders
php artisan db:seed
```

### Problem: Old permission_id errors
**Solution:** Make sure you're using the updated User model without permission_id relationship

---

## Next Steps

1. ✅ Installation complete
2. 🧪 Test login with each user
3. 📝 Update your routes to use new permission format
4. 🔧 Update controllers to use Spatie methods
5. 🎨 Update frontend permission checks
6. 📚 Read the full guides:
   - `SPATIE_PERMISSION_GUIDE.md` - Complete guide
   - `SPATIE_QUICK_REFERENCE.md` - Quick reference
   - `PERMISSION_MAPPING.md` - Old to new mapping

---

## Permission Format

**New Format:** `{module}.{action}`

Examples:
- `dashboard.view`
- `user-menu.create`
- `job-menu.update`
- `machine-master.delete`

---

## Usage Examples

### In Routes
```php
Route::middleware('permission:user-menu.view')
    ->get('/users', [UserController::class, 'index']);
```

### In Controllers
```php
if (!auth()->user()->hasPermissionTo('user-menu.create')) {
    abort(403);
}
```

### In Blade
```php
@can('user-menu.create')
    <button>Create User</button>
@endcan
```

---

## 🎉 You're All Set!

Your Spatie Permission system is now fully installed and configured.

**Need Help?**
- Full Guide: `SPATIE_PERMISSION_GUIDE.md`
- Quick Reference: `SPATIE_QUICK_REFERENCE.md`
- Official Docs: https://spatie.be/docs/laravel-permission

---

**Installation Date:** October 19, 2025  
**Status:** ✅ Ready to use
