# Permission Mapping Reference

This document maps old permission field names to new Spatie permission names.

## Quick Find & Replace Guide

Use this for updating your codebase from old to new permission format.

### Format Pattern
```
OLD: {module}_{action}
NEW: {module}.{action}
```

## Complete Mapping Table

| Old Permission Field | New Spatie Permission | Category |
|---------------------|----------------------|----------|
| `dashboard_view` | `dashboard.view` | Dashboard |
| | | |
| `login_menu_view` | `login-menu.view` | Login |
| `login_menu_create` | `login-menu.create` | Login |
| `login_menu_update` | `login-menu.update` | Login |
| `login_menu_delete` | `login-menu.delete` | Login |
| | | |
| `job_menu_view` | `job-menu.view` | Job |
| `job_menu_create` | `job-menu.create` | Job |
| `job_menu_update` | `job-menu.update` | Job |
| `job_menu_delete` | `job-menu.delete` | Job |
| | | |
| `manual_job_menu_view` | `manual-job-menu.view` | Manual Job |
| `manual_job_menu_create` | `manual-job-menu.create` | Manual Job |
| `manual_job_menu_update` | `manual-job-menu.update` | Manual Job |
| `manual_job_menu_delete` | `manual-job-menu.delete` | Manual Job |
| | | |
| `quality_menu_view` | `quality-menu.view` | Quality |
| `quality_menu_create` | `quality-menu.create` | Quality |
| `quality_menu_update` | `quality-menu.update` | Quality |
| `quality_menu_delete` | `quality-menu.delete` | Quality |
| | | |
| `user_menu_view` | `user-menu.view` | User |
| `user_menu_create` | `user-menu.create` | User |
| `user_menu_update` | `user-menu.update` | User |
| `user_menu_delete` | `user-menu.delete` | User |
| | | |
| `role_menu_view` | `role-menu.view` | Role |
| `role_menu_create` | `role-menu.create` | Role |
| `role_menu_update` | `role-menu.update` | Role |
| `role_menu_delete` | `role-menu.delete` | Role |
| | | |
| `status_menu_view` | `status-menu.view` | Status |
| `status_menu_create` | `status-menu.create` | Status |
| `status_menu_update` | `status-menu.update` | Status |
| `status_menu_delete` | `status-menu.delete` | Status |
| | | |
| `sub_status_menu_view` | `sub-status-menu.view` | Sub Status |
| `sub_status_menu_create` | `sub-status-menu.create` | Sub Status |
| `sub_status_menu_update` | `sub-status-menu.update` | Sub Status |
| `sub_status_menu_delete` | `sub-status-menu.delete` | Sub Status |
| | | |
| `sheet_size_view` | `sheet-size.view` | Sheet Size |
| `sheet_size_create` | `sheet-size.create` | Sheet Size |
| `sheet_size_update` | `sheet-size.update` | Sheet Size |
| `sheet_size_delete` | `sheet-size.delete` | Sheet Size |
| | | |
| `material_master_view` | `material-master.view` | Material |
| `material_master_create` | `material-master.create` | Material |
| `material_master_update` | `material-master.update` | Material |
| `material_master_delete` | `material-master.delete` | Material |
| | | |
| `machine_master_view` | `machine-master.view` | Machine |
| `machine_master_create` | `machine-master.create` | Machine |
| `machine_master_update` | `machine-master.update` | Machine |
| `machine_master_delete` | `machine-master.delete` | Machine |
| | | |
| `standerd_production_view` | `standard-production.view` | Production |
| `standerd_production_create` | `standard-production.create` | Production |
| `standerd_production_update` | `standard-production.update` | Production |
| `standerd_production_delete` | `standard-production.delete` | Production |
| | | |
| `alert_view` | `alert.view` | Alert |
| `alert_create` | `alert.create` | Alert |
| `alert_update` | `alert.update` | Alert |
| `alert_delete` | `alert.delete` | Alert |
| | | |
| `tag_view` | `tag.view` | Tag |
| `tag_create` | `tag.create` | Tag |
| `tag_update` | `tag.update` | Tag |
| `tag_delete` | `tag.delete` | Tag |
| | | |
| `job_creation_view` | `job-creation.view` | Job Creation |
| `job_creation_create` | `job-creation.create` | Job Creation |
| `job_creation_update` | `job-creation.update` | Job Creation |
| `job_creation_delete` | `job-creation.delete` | Job Creation |
| | | |
| `shift_view` | `shift.view` | Shift |
| `shift_create` | `shift.create` | Shift |
| `shift_update` | `shift.update` | Shift |
| `shift_delete` | `shift.delete` | Shift |
| | | |
| `machine_type_view` | `machine-type.view` | Machine Type |
| `machine_type_create` | `machine-type.create` | Machine Type |
| `machine_type_update` | `machine-type.update` | Machine Type |
| `machine_type_delete` | `machine-type.delete` | Machine Type |
| | | |
| `department_view` | `department.view` | Department |
| `department_create` | `department.create` | Department |
| `department_update` | `department.update` | Department |
| `department_delete` | `department.delete` | Department |
| | | |
| `process_view` | `process.view` | Process |
| `process_create` | `process.create` | Process |
| `process_update` | `process.update` | Process |
| `process_delete` | `process.delete` | Process |
| | | |
| `QCMAster_view` | `qc-master.view` | QC Master |
| `QCMAster_create` | `qc-master.create` | QC Master |
| `QCMAster_update` | `qc-master.update` | QC Master |
| `QCMAster_delete` | `qc-master.delete` | QC Master |
| | | |
| `Button_group_view` | `button-group.view` | Button Group |
| `Button_group_create` | `button-group.create` | Button Group |
| `Button_group_update` | `button-group.update` | Button Group |
| `Button_group_delete` | `button-group.delete` | Button Group |
| | | |
| `buttons_view` | `buttons.view` | Buttons |
| `buttons_create` | `buttons.create` | Buttons |
| `buttons_update` | `buttons.update` | Buttons |
| `buttons_delete` | `buttons.delete` | Buttons |
| | | |
| `DMTask_view` | `dm-task.view` | DM Task |
| `DMTask_create` | `dm-task.create` | DM Task |
| `DMTask_update` | `dm-task.update` | DM Task |
| `DMTask_delete` | `dm-task.delete` | DM Task |
| | | |
| `dailyTask_view` | `daily-task.view` | Daily Task |
| `dailyTask_create` | `daily-task.create` | Daily Task |
| `dailyTask_update` | `daily-task.update` | Daily Task |
| `dailyTask_delete` | `daily-task.delete` | Daily Task |
| | | |
| `DMI_view` | `dmi.view` | DMI |
| `DMI_create` | `dmi.create` | DMI |
| `DMI_update` | `dmi.update` | DMI |
| `DMI_delete` | `dmi.delete` | DMI |

## Code Migration Examples

### Backend (PHP)

#### Old Way
```php
// Check permission
if ($user->permission->user_menu_view) {
    // ...
}

// Middleware
Route::middleware('check.permission:user_menu_view');

// Direct check
$userPermission->user_menu_create
```

#### New Way
```php
// Check permission
if ($user->hasPermissionTo('user-menu.view')) {
    // ...
}

// Middleware
Route::middleware('permission:user-menu.view');

// Or use helper trait
if ($error = $this->checkPermissionOrFail('user-menu.create')) {
    return $error;
}
```

### Frontend (JavaScript/Vue/React)

#### Old Way
```javascript
// Check permission
if (user.permission.user_menu_view) {
    // show element
}

// Permission list
const canView = user.permission.user_menu_view;
const canCreate = user.permission.user_menu_create;
```

#### New Way
```javascript
// Check permission
if (user.permissions.includes('user-menu.view')) {
    // show element
}

// Or if you receive permissions as array
const canView = user.permissions.includes('user-menu.view');
const canCreate = user.permissions.includes('user-menu.create');

// Check role
if (user.roles.some(role => role.name === 'Super Admin')) {
    // show admin content
}
```

## Search & Replace Patterns

Use these regex patterns in your IDE for bulk updates:

### Pattern 1: Permission Object Access
```regex
Find: \$user->permission->([a-z_]+)
Replace: $user->hasPermissionTo('$1')
```
Then manually convert underscores to hyphens and dots.

### Pattern 2: Middleware
```regex
Find: check\.permission:([a-z_]+)
Replace: permission:$1
```
Then manually convert underscores to hyphens and dots.

### Pattern 3: Direct Permission Checks
```regex
Find: ->([a-z_]+_(?:view|create|update|delete))
Replace: ->hasPermissionTo('$1')
```
Then manually convert underscores to hyphens and dots.

## Frontend API Response Structure

### Old Format
```json
{
  "user": {
    "id": 1,
    "name": "Admin",
    "permission": {
      "role_name": "Super Admin",
      "user_menu_view": true,
      "user_menu_create": true,
      "permissions": ["user_menu_view", "user_menu_create", ...]
    }
  }
}
```

### New Format (Spatie)
```json
{
  "user": {
    "id": 1,
    "name": "Admin",
    "roles": [
      {
        "id": 1,
        "name": "Super Admin"
      }
    ],
    "permissions": [
      "user-menu.view",
      "user-menu.create",
      "dashboard.view",
      ...
    ],
    "permission_list": [
      "user-menu.view",
      "user-menu.create",
      ...
    ]
  }
}
```

## Bulk Find & Replace Commands

### VS Code / PHPStorm
1. Open Find & Replace (Ctrl+Shift+H or Cmd+Shift+H)
2. Enable Regex mode
3. Use these patterns:

```
# Convert underscore to hyphen-dot format
Find: ([a-z]+)_([a-z]+)_(view|create|update|delete)
Replace: $1-$2.$3

# Convert permission checks
Find: ->permission->([a-z_]+)
Replace: ->hasPermissionTo('$1')

# Then manually fix the permission names
```

## Migration Checklist

- [ ] Update all route middleware
- [ ] Update all controller permission checks
- [ ] Update all Blade template permission checks
- [ ] Update frontend JavaScript permission checks
- [ ] Update API response handling in frontend
- [ ] Update permission seeder
- [ ] Update test files
- [ ] Update documentation
- [ ] Test all permission-protected routes
- [ ] Test all roles
- [ ] Clear caches

## Notes

1. **Spelling Correction**: `standerd_production` → `standard-production` (corrected typo)
2. **Case Changes**: `QCMAster` → `qc-master` (normalized casing)
3. **Consistency**: All hyphens instead of underscores for multi-word modules
4. **Dot Notation**: Action separated by dot (`.`) instead of underscore

## Testing Commands

```bash
# Test permission existence
php artisan tinker
>>> \Spatie\Permission\Models\Permission::pluck('name');

# Test user permissions
>>> $user = \App\Models\User::first();
>>> $user->getAllPermissions()->pluck('name');

# Test role permissions
>>> $role = \Spatie\Permission\Models\Role::findByName('Supervisor');
>>> $role->permissions->pluck('name');
```
