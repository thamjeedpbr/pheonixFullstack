# Update All Pages Permission Format

## Files that need updating:

1. `/resources/js/Pages/Machines/Index.vue`
2. `/resources/js/Pages/Materials/Index.vue`
3. `/resources/js/Pages/MachineTypes/Index.vue`
4. `/resources/js/Pages/Processes/Index.vue`
5. `/resources/js/Pages/Departments/Index.vue`
6. `/resources/js/Pages/Shifts/Index.vue`
7. `/resources/js/Pages/Sections/Index.vue`
8. `/resources/js/Pages/Statuses/Index.vue`

## Changes needed in each file:

### 1. Import the composable
```javascript
// ADD at the top of script
import { usePermissions } from '@/composables/usePermissions';

// THEN use it
const { hasPermission } = usePermissions();
```

### 2. Replace permission checks
```javascript
// REMOVE this entire function:
const hasPermission = (permission) => {
  const userPermission = authStore.user?.permission;
  if (!userPermission) return false;
  
  if (Array.isArray(userPermission.permissions)) {
    return userPermission.permissions.includes(permission);
  }
  
  return userPermission[permission] ?? false;
};
```

### 3. Update permission names

**Machines:**
```javascript
// OLD
'machine_master_create'
'machine_master_update'
'machine_master_delete'

// NEW
'machine-master.create'
'machine-master.update'
'machine-master.delete'
```

**Materials:**
```javascript
// OLD
'material_master_create'
'material_master_update'
'material_master_delete'

// NEW
'material-master.create'
'material-master.update'
'material-master.delete'
```

**Machine Types:**
```javascript
// OLD
'machine_type_create'
'machine_type_update'
'machine_type_delete'

// NEW
'machine-type.create'
'machine-type.update'
'machine-type.delete'
```

**Processes:**
```javascript
// OLD
'process_create'
'process_update'
'process_delete'

// NEW
'process.create'
'process.update'
'process.delete'
```

**Departments:**
```javascript
// OLD
'department_create'
'department_update'
'department_delete'

// NEW
'department.create'
'department.update'
'department.delete'
```

**Shifts:**
```javascript
// OLD
'shift_create'
'shift_update'
'shift_delete'

// NEW
'shift.create'
'shift.update'
'shift.delete'
```

**Sections:**
```javascript
// OLD
'section_create'
'section_update'
'section_delete'

// NEW
'section.create'
'section.update'
'section.delete'
```

**Statuses:**
```javascript
// OLD
'status_menu_create'
'status_menu_update'
'status_menu_delete'

// NEW
'status-menu.create'
'status-menu.update'
'status-menu.delete'
```

## Quick find/replace for each file:

1. Find: `const hasPermission = (permission) => {` ... entire function
   Replace with: `const { hasPermission } = usePermissions();`

2. Find old permission names, replace with new format

That's it! Each page will work with new Spatie permissions.
