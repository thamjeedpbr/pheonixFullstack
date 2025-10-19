# Materials Module - Critical Fixes Required

## Issues Found

### 1. Authentication Error (401)
**Error**: "Request failed with status code 401"  
**Cause**: Using axios without proper Bearer token authentication

**Solution**: Replace axios with fetch + Bearer token from authStore

### 2. Props Access Error  
**Error**: "Cannot read properties of undefined (reading 'props')"  
**Cause**: Accessing `$page.props.auth.permissions` before page loads

**Solution**: Use authStore.user.permissions or computed properties

### 3. Wrong Layout
**Issue**: Using `AdminLayout` instead of `AuthenticatedLayout`  
**Solution**: Import and use `AuthenticatedLayout`

---

## Quick Fix Steps

### Step 1: Update Materials/Index.vue - Replace axios with fetch

**Find this pattern:**
```javascript
const response = await axios.get('/api/materials', { params });
```

**Replace with:**
```javascript
const params = new URLSearchParams();
params.append('page', page);
params.append('per_page', perPage.value);
if (filters.value.search) params.append('search', filters.value.search);
if (filters.value.department_id) params.append('department_id', filters.value.department_id);
if (filters.value.coating !== '') params.append('coating', filters.value.coating);
if (filters.value.status !== '') params.append('status', filters.value.status);

const response = await fetch(`/api/materials?${params}`, {
  headers: {
    'Authorization': `Bearer ${authStore.token}`,
    'Accept': 'application/json',
  },
});

if (response.ok) {
  const result = await response.json();
  // Handle result.data
}
```

### Step 2: Update Layout Import

**Find:**
```javascript
import AdminLayout from '@/Layouts/AdminLayout.vue';
```

**Replace with:**
```javascript
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
```

### Step 3: Update Template Layout

**Find:**
```vue
<AdminLayout>
```

**Replace with:**
```vue
<AuthenticatedLayout>
```

### Step 4: Add authStore

**Add after imports:**
```javascript
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
```

### Step 5: Fix Permission Checks

**Find all instances of:**
```vue
v-if="$page.props.auth.permissions.material_master_create"
```

**Replace with computed properties:**
```javascript
const canCreate = computed(() => {
  return authStore.user?.permission?.permissions?.includes('material_master_create') || false;
});

const canUpdate = computed(() => {
  return authStore.user?.permission?.permissions?.includes('material_master_update') || false;
});

const canDelete = computed(() => {
  return authStore.user?.permission?.permissions?.includes('material_master_delete') || false;
});
```

**Then use in template:**
```vue
v-if="canCreate"
v-if="canUpdate"
v-if="canDelete"
```

---

## Complete Solution

The best approach is to **copy the entire Machines/Index.vue structure** and adapt it for Materials since they follow the same pattern.

### Files to Update:
1. `/resources/js/Pages/Materials/Index.vue` - Main fix
2. `/resources/js/Components/MaterialFormModal.vue` - Update to use fetch

### Key Pattern from Machines:
- Use `AuthenticatedLayout`
- Use `fetch` with Bearer token
- Use `authStore` for authentication
- Use computed properties for permissions
- Same responsive structure (mobile cards + desktop table)

---

## Testing After Fix

1. Clear browser cache
2. Reload page
3. Check console for errors
4. Test:
   - Page loads without errors
   - Can see materials list
   - Can create material
   - Can edit material
   - Can delete material
   - Filters work
   - Search works
   - Mobile responsive works

---

**Priority**: CRITICAL  
**Estimated Time**: 30 minutes  
**Next Step**: Replace Materials/Index.vue content with Machines pattern adapted for materials
