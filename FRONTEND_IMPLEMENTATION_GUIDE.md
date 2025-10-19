# Quick Frontend Implementation Guide

## 🚀 Step-by-Step: How to Create Each Frontend Module

Follow this exact pattern for each of the 6 remaining modules.

---

## Example: Machine Types Frontend

### Step 1: Create Index.vue Page

**File**: `resources/js/Pages/MachineTypes/Index.vue`

**Copy from**: `resources/js/Pages/Machines/Index.vue`

**Find and Replace**:
- `Machine` → `MachineType`
- `machine` → `machineType`
- `machines` → `machineTypes`
- `/api/machines` → `/api/machine-types`
- `machine_master_view` → `machine_type_view`
- `machine_master_create` → `machine_type_create`
- `machine_master_update` → `machine_type_update`
- `machine_master_delete` → `machine_type_delete`

**Update Table Columns**:
```vue
<thead>
  <tr>
    <th>Type ID</th>
    <th>Name</th>
    <th>Machine Type</th> <!-- ENUM -->
    <th>Status</th>
    <th>Actions</th>
  </tr>
</thead>
```

**Update Card Details**:
```vue
<div class="space-y-2">
  <div class="flex items-center justify-between">
    <span class="text-xs text-gray-500">Type ID</span>
    <span class="text-xs font-medium">{{ machineType.type_id }}</span>
  </div>
  <div class="flex items-center justify-between">
    <span class="text-xs text-gray-500">Machine Type</span>
    <span class="text-xs font-medium">{{ machineType.machine_type_label }}</span>
  </div>
  <div class="flex items-center justify-between">
    <span class="text-xs text-gray-500">Status</span>
    <span :class="statusBadgeClass(machineType.status)">
      {{ machineType.status ? 'Active' : 'Inactive' }}
    </span>
  </div>
</div>
```

**Update Filters**:
```vue
<!-- Mobile & Desktop -->
<select v-model="filters.machine_type">
  <option value="">All Types</option>
  <option value="PRE_PRESS">Pre-Press</option>
  <option value="PRESS">Press</option>
  <option value="POST_PRESS">Post-Press</option>
  <option value="DIE_CUT">Die Cut</option>
  <option value="OTHER">Other</option>
</select>
```

---

### Step 2: Create Form Modal

**File**: `resources/js/Components/MachineTypeFormModal.vue`

**Template**:
```vue
<template>
  <Modal :show="show" @close="handleClose">
    <div class="p-6">
      <h2 class="text-xl font-bold mb-4">
        {{ isEditMode ? 'Edit' : 'Create' }} Machine Type
      </h2>
      
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <!-- Type ID -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Type ID <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.type_id"
            type="text"
            class="w-full rounded-lg border border-gray-300 px-3 py-2"
            required
          />
          <p v-if="errors.type_id" class="mt-1 text-sm text-red-600">
            {{ errors.type_id[0] }}
          </p>
        </div>

        <!-- Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Name <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.name"
            type="text"
            class="w-full rounded-lg border border-gray-300 px-3 py-2"
            required
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">
            {{ errors.name[0] }}
          </p>
        </div>

        <!-- Machine Type ENUM -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Machine Type <span class="text-red-500">*</span>
          </label>
          <select
            v-model="form.machine_type"
            class="w-full rounded-lg border border-gray-300 px-3 py-2"
            required
          >
            <option value="">Select Type</option>
            <option value="PRE_PRESS">Pre-Press</option>
            <option value="PRESS">Press</option>
            <option value="POST_PRESS">Post-Press</option>
            <option value="DIE_CUT">Die Cut</option>
            <option value="OTHER">Other</option>
          </select>
          <p v-if="errors.machine_type" class="mt-1 text-sm text-red-600">
            {{ errors.machine_type[0] }}
          </p>
        </div>

        <!-- Remark -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Remark
          </label>
          <textarea
            v-model="form.remark"
            rows="3"
            class="w-full rounded-lg border border-gray-300 px-3 py-2"
          ></textarea>
        </div>

        <!-- Status -->
        <div>
          <label class="flex items-center">
            <input
              v-model="form.status"
              type="checkbox"
              class="rounded border-gray-300 text-blue-600"
            />
            <span class="ml-2 text-sm text-gray-700">Active</span>
          </label>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3 mt-6">
          <button
            type="button"
            @click="handleClose"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50"
          >
            {{ loading ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { useAuthStore } from '@/stores/auth';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
  show: Boolean,
  machineType: Object,
});

const emit = defineEmits(['close', 'saved']);
const authStore = useAuthStore();

const loading = ref(false);
const errors = ref({});

const form = reactive({
  type_id: '',
  name: '',
  machine_type: '',
  remark: '',
  status: true,
});

const isEditMode = computed(() => !!props.machineType);

watch(() => props.machineType, (newVal) => {
  if (newVal) {
    form.type_id = newVal.type_id || '';
    form.name = newVal.name || '';
    form.machine_type = newVal.machine_type || '';
    form.remark = newVal.remark || '';
    form.status = newVal.status ?? true;
  }
}, { immediate: true });

const handleSubmit = async () => {
  loading.value = true;
  errors.value = {};

  try {
    const url = isEditMode.value
      ? `/api/machine-types/${props.machineType.id}`
      : '/api/machine-types';

    const method = isEditMode.value ? 'PUT' : 'POST';

    const response = await fetch(url, {
      method,
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(form),
    });

    const result = await response.json();

    if (response.ok) {
      emit('saved');
      resetForm();
    } else {
      errors.value = result.errors || {};
      if (result.message) {
        alert(result.message);
      }
    }
  } catch (error) {
    console.error('Submit failed:', error);
    alert('An error occurred. Please try again.');
  } finally {
    loading.value = false;
  }
};

const handleClose = () => {
  resetForm();
  emit('close');
};

const resetForm = () => {
  form.type_id = '';
  form.name = '';
  form.machine_type = '';
  form.remark = '';
  form.status = true;
  errors.value = {};
};
</script>
```

---

### Step 3: Update Router

**File**: `resources/js/router.js`

**Add**:
```javascript
{
  path: '/machine-types',
  name: 'machine-types.index',
  component: () => import('./Pages/MachineTypes/Index.vue'),
  meta: { requiresAuth: true }
}
```

---

### Step 4: Update Sidebar

**File**: `resources/js/Components/Sidebar.vue`

**Add** (in Administration section):
```vue
<SidebarItem
  v-if="hasPermission('machine_type_view')"
  icon="server"
  label="Machine Types"
  to="/machine-types"
/>
```

---

## 🎯 Module-Specific Customizations

### 1. Machine Types ✅ (Example above)
**Special**: Enum dropdown for machine_type

### 2. Processes
**Fields**:
- `name` (required, unique)
- `msi_id` (optional)
- `status` (checkbox)

**Simple form, no special inputs**

### 3. Departments
**Fields**:
- `department_code` (required, unique)
- `name` (required, unique)
- `remark` (optional textarea)
- `status` (checkbox)

**Simple form, no special inputs**

### 4. Shifts ⚠️ Special
**Fields**:
- `shift_id` (required, unique)
- `shift_name` (required, unique)
- `start_time` (required, time picker - H:i:s format)
- `end_time` (required, time picker - H:i:s format)
- `status` (checkbox)

**Special Inputs Needed**:
```vue
<!-- Start Time -->
<div>
  <label>Start Time <span class="text-red-500">*</span></label>
  <input
    v-model="form.start_time"
    type="time"
    step="1"
    class="w-full rounded-lg border border-gray-300 px-3 py-2"
    required
  />
</div>

<!-- End Time -->
<div>
  <label>End Time <span class="text-red-500">*</span></label>
  <input
    v-model="form.end_time"
    type="time"
    step="1"
    class="w-full rounded-lg border border-gray-300 px-3 py-2"
    required
  />
</div>
```

**Convert to H:i:s format**:
```javascript
// On submit, ensure format is H:i:s
const formatTime = (time) => {
  if (!time) return '';
  const [hours, minutes] = time.split(':');
  return `${hours}:${minutes}:00`;
};

// Before sending
form.start_time = formatTime(form.start_time);
form.end_time = formatTime(form.end_time);
```

**Display Duration in Card**:
```vue
<div class="flex items-center justify-between">
  <span class="text-xs text-gray-500">Duration</span>
  <span class="text-xs font-medium">{{ shift.duration }}</span>
</div>
```

### 5. Sections
**Fields**:
- `section_id` (required, unique)
- `name` (required, unique)
- `remark` (optional textarea)
- `status` (checkbox)

**Simplest form, no special inputs**

### 6. Statuses
**Fields**:
- `status_id` (required, unique)
- `name` (required, unique)
- `description` (optional textarea)
- `remark` (optional textarea)
- `status` (checkbox)

**Simple form, two textarea fields**

---

## 📋 Checklist Template (Copy for each module)

### Machine Types
- [ ] Create `/Pages/MachineTypes/Index.vue`
- [ ] Create `/Components/MachineTypeFormModal.vue`
- [ ] Add route to router.js
- [ ] Add menu to Sidebar.vue
- [ ] Test Create
- [ ] Test Edit
- [ ] Test Delete
- [ ] Test Search
- [ ] Test Filters
- [ ] Test Mobile View
- [ ] Test Desktop View
- [ ] Check Permissions

### Processes
- [ ] Create `/Pages/Processes/Index.vue`
- [ ] Create `/Components/ProcessFormModal.vue`
- [ ] Add route to router.js
- [ ] Add menu to Sidebar.vue
- [ ] Test all features

### Departments
- [ ] Create `/Pages/Departments/Index.vue`
- [ ] Create `/Components/DepartmentFormModal.vue`
- [ ] Add route to router.js
- [ ] Add menu to Sidebar.vue
- [ ] Test all features

### Shifts
- [ ] Create `/Pages/Shifts/Index.vue`
- [ ] Create `/Components/ShiftFormModal.vue` (with time pickers)
- [ ] Add route to router.js
- [ ] Add menu to Sidebar.vue
- [ ] Test time validation
- [ ] Test all features

### Sections
- [ ] Create `/Pages/Sections/Index.vue`
- [ ] Create `/Components/SectionFormModal.vue`
- [ ] Add route to router.js
- [ ] Add menu to Sidebar.vue
- [ ] Test all features

### Statuses
- [ ] Create `/Pages/Statuses/Index.vue`
- [ ] Create `/Components/StatusFormModal.vue`
- [ ] Add route to router.js
- [ ] Add menu to Sidebar.vue
- [ ] Test all features

---

## 🎨 Icons for Sidebar

Use Heroicons or similar:
- **Machine Types**: `server` or `cpu-chip`
- **Processes**: `cog` or `arrow-path`
- **Departments**: `building-office` or `squares-2x2`
- **Shifts**: `clock` or `calendar-days`
- **Sections**: `rectangle-group` or `squares-plus`
- **Statuses**: `signal` or `chart-bar-square`

---

## ⚡ Quick Tips

### 1. Start Simple
- Copy Machines/Index.vue exactly
- Do find-replace for naming
- Test basic list first
- Then add filters
- Finally add create/edit

### 2. One Module at a Time
- Complete one module 100%
- Test thoroughly
- Then move to next
- Don't start all at once

### 3. Use Browser DevTools
- Check Network tab for API calls
- Check Console for errors
- Use Vue DevTools to inspect state
- Test responsive design toggle

### 4. Common Issues
- **404 on API**: Check route name matches
- **Validation errors**: Check field names match backend
- **Permission denied**: Check permission strings match
- **Mobile broken**: Check Tailwind breakpoints (md:)

---

## 🚀 Estimated Time per Module

- **Machine Types**: 3 hours (enum dropdown)
- **Processes**: 2 hours (simple)
- **Departments**: 2 hours (simple)
- **Shifts**: 3 hours (time pickers)
- **Sections**: 2 hours (simplest)
- **Statuses**: 2 hours (simple)

**Total**: 14 hours (< 2 days)

---

## ✅ Testing Checklist (Per Module)

### Functionality
- [ ] List loads correctly
- [ ] Search works
- [ ] Filters apply
- [ ] Create works
- [ ] Edit pre-fills data
- [ ] Update saves changes
- [ ] Delete confirms and removes
- [ ] Pagination works (desktop)
- [ ] Infinite scroll works (mobile)

### UI/UX
- [ ] Mobile view (< 768px) looks good
- [ ] Desktop view (>= 768px) looks good
- [ ] Floating filters work on mobile
- [ ] Cards display properly
- [ ] Table displays properly
- [ ] Buttons are touch-friendly
- [ ] Loading states show
- [ ] Empty states show
- [ ] Error messages display

### Permissions
- [ ] Create button shows with permission
- [ ] Edit button shows with permission
- [ ] Delete button shows with permission
- [ ] Actions hidden without permission

---

**Status**: Ready to implement all 6 frontends!  
**Pattern**: Established and proven with Machines  
**Confidence**: High - Follow this guide exactly  
**Support**: All backend APIs ready and tested  

🚀 **START WITH MACHINE TYPES - EASIEST TO HARDEST!** 🚀
