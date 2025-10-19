# Frontend Permission Updates - Summary

## What Changed

### 1. Permission Format Change

**OLD FORMAT:**
```javascript
'user_menu_view'
'machine_master_view'
'status_menu_view'
```

**NEW FORMAT (Spatie):**
```javascript
'user-menu.view'
'machine-master.view'
'status-menu.view'
```

---

## Files Updated

### ✅ `/resources/js/Components/Sidebar.vue`

**Changed:**
- `hasPermission('user_menu_view')` → `hasPermission('user-menu.view')`
- `hasPermission('machine_master_view')` → `hasPermission('machine-master.view')`
- `hasPermission('material_master_view')` → `hasPermission('material-master.view')`
- `hasPermission('machine_type_view')` → `hasPermission('machine-type.view')`
- `hasPermission('process_view')` → `hasPermission('process.view')`
- `hasPermission('department_view')` → `hasPermission('department.view')`
- `hasPermission('shift_view')` → `hasPermission('shift.view')`
- `hasPermission('section_view')` → `hasPermission('section.view')`
- `hasPermission('status_menu_view')` → `hasPermission('status-menu.view')`

**Permission check logic updated:**
```javascript
// OLD
const hasPermission = (permission) => {
  if (!props.user || !props.user.permission) return false;
  const permissions = props.user.permission.permissions || [];
  return permissions.includes(permission);
};

// NEW
const hasPermission = (permission) => {
  if (!props.user) return false;
  const permissions = props.user.permissions || props.user.permission_list || [];
  return permissions.includes(permission);
};
```

### ✅ NEW: `/resources/js/composables/usePermissions.js`

Created reusable permission helper with methods:
- `hasPermission(permission)` - Check single permission
- `hasAnyPermission([permissions])` - Check if has any
- `hasAllPermissions([permissions])` - Check if has all
- `hasRole(roleName)` - Check role
- `hasAnyRole([roleNames])` - Check if has any role
- `getAllPermissions()` - Get all permissions
- `getAllRoles()` - Get all roles
- `isSuperAdmin()` - Check if super admin

---

## API Response Structure Change

### OLD Response (Custom Permission System)
```json
{
  "user": {
    "id": 1,
    "name": "Admin",
    "permission": {
      "role_name": "Super Admin",
      "permissions": ["user_menu_view", "machine_master_view", ...]
    }
  }
}
```

### NEW Response (Spatie Permission)
```json
{
  "user": {
    "id": 1,
    "name": "Admin",
    "roles": [
      {"id": 1, "name": "Super Admin"}
    ],
    "permissions": [
      "user-menu.view",
      "machine-master.view",
      ...
    ],
    "permission_list": [...]
  }
}
```

---

## How to Use in Components

### Option 1: Use Composable (Recommended)
```vue
<script setup>
import { usePermissions } from '@/composables/usePermissions';

const { hasPermission, hasRole, isSuperAdmin } = usePermissions();
</script>

<template>
  <button v-if="hasPermission('user-menu.create')">
    Create User
  </button>
  
  <div v-if="hasRole('Super Admin')">
    Admin Only Content
  </div>
  
  <div v-if="isSuperAdmin()">
    Super Admin Content
  </div>
</template>
```

### Option 2: Direct Check (Current in Sidebar.vue)
```vue
<script setup>
const props = defineProps({
  user: Object
});

const hasPermission = (permission) => {
  if (!props.user) return false;
  const permissions = props.user.permissions || [];
  return permissions.includes(permission);
};
</script>

<template>
  <div v-if="hasPermission('user-menu.view')">
    <!-- Content -->
  </div>
</template>
```

---

## Complete Permission Mapping

### Users Module
- `user_menu_view` → `user-menu.view`
- `user_menu_create` → `user-menu.create`
- `user_menu_update` → `user-menu.update`
- `user_menu_delete` → `user-menu.delete`

### Machine Module
- `machine_master_view` → `machine-master.view`
- `machine_master_create` → `machine-master.create`
- `machine_master_update` → `machine-master.update`
- `machine_master_delete` → `machine-master.delete`

### Material Module
- `material_master_view` → `material-master.view`
- `material_master_create` → `material-master.create`
- `material_master_update` → `material-master.update`
- `material_master_delete` → `material-master.delete`

### Machine Type Module
- `machine_type_view` → `machine-type.view`
- `machine_type_create` → `machine-type.create`
- `machine_type_update` → `machine-type.update`
- `machine_type_delete` → `machine-type.delete`

### Process Module
- `process_view` → `process.view`
- `process_create` → `process.create`
- `process_update` → `process.update`
- `process_delete` → `process.delete`

### Department Module
- `department_view` → `department.view`
- `department_create` → `department.create`
- `department_update` → `department.update`
- `department_delete` → `department.delete`

### Shift Module
- `shift_view` → `shift.view`
- `shift_create` → `shift.create`
- `shift_update` → `shift.update`
- `shift_delete` → `shift.delete`

### Status Module
- `status_menu_view` → `status-menu.view`
- `status_menu_create` → `status-menu.create`
- `status_menu_update` → `status-menu.update`
- `status_menu_delete` → `status-menu.delete`

---

## Files That Need Manual Updates

Search for old permission format in these files and update:

```bash
# Search for old format
grep -r "user_menu_view\|machine_master_view\|permission\." resources/js/

# Files to check:
- resources/js/Pages/Users/Index.vue
- resources/js/Pages/Machines/Index.vue
- resources/js/Pages/Materials/Index.vue
- resources/js/Pages/MachineTypes/Index.vue
- resources/js/Pages/Processes/Index.vue
- resources/js/Pages/Departments/Index.vue
- resources/js/Pages/Shifts/Index.vue
- resources/js/Pages/Statuses/Index.vue
- resources/js/Pages/Sections/Index.vue
- Any other component using permissions
```

---

## Testing Checklist

After installation, test:

- [ ] Login with admin user
- [ ] Check sidebar shows all menu items
- [ ] Login with supervisor user
- [ ] Check sidebar shows limited menu items
- [ ] Login with operator user
- [ ] Check sidebar shows minimal menu items
- [ ] Check console for any permission errors
- [ ] Test navigation to each page
- [ ] Test create/edit/delete buttons visibility

---

## Next Steps

1. ✅ Run fresh migration: `php artisan migrate:fresh --seed`
2. ✅ Test login with different users
3. 🔍 Search and update remaining Vue components
4. 🔍 Update any page components checking permissions
5. 🧪 Test all permission-protected features

---

**Status:** Sidebar.vue updated ✅  
**Composable:** Created ✅  
**Remaining:** Update individual page components manually
