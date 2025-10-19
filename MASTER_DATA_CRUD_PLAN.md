# Master Data CRUD Operations - Complete Implementation Plan

## Overview
Creating comprehensive CRUD operations for all master data modules following the established Machine Management pattern.

## Master Data Modules to Implement

### 1. **Material Management** ⭐ Priority 1
- **Model**: Material
- **Fields**: material_id, name, utilization, coating, description, gsm, unit, status, department_id
- **Relations**: department, createdBy, orders, forms
- **Complexity**: Medium (has dropdown for department)
- **Estimated Time**: 6 hours

### 2. **Machine Type Management** ⭐ Priority 2
- **Model**: MachineType
- **Fields**: type_id, name, remark, machine_type (enum), status
- **Relations**: machines, subStatuses
- **Complexity**: Low (simple master)
- **Estimated Time**: 3 hours

### 3. **Process Management** ⭐ Priority 2
- **Model**: Process
- **Fields**: name, msi_id, status, created_by_id
- **Relations**: createdBy, machines, orders, forms, qcMasters, buttonGroups
- **Complexity**: Low (simple master)
- **Estimated Time**: 3 hours

### 4. **Department Management** ⭐ Priority 2
- **Model**: Department
- **Fields**: department_code, name, remark, status
- **Relations**: materials
- **Complexity**: Low (simple master)
- **Estimated Time**: 3 hours

### 5. **Shift Management** ⭐ Priority 3
- **Model**: Shift
- **Fields**: shift_id, shift_name, start_time, end_time, status
- **Relations**: Users (via user_shifts), forms
- **Complexity**: Low (time fields)
- **Estimated Time**: 3 hours

### 6. **Section Management** ⭐ Priority 3
- **Model**: Section
- **Fields**: section_id, name, remark, status
- **Relations**: None (pure master)
- **Complexity**: Very Low
- **Estimated Time**: 2 hours

### 7. **Status Management** ⭐ Priority 3
- **Model**: Status
- **Fields**: status_id, name, remark, description, status
- **Relations**: subStatuses, forms
- **Complexity**: Low
- **Estimated Time**: 2 hours

## Standard Implementation Pattern

### Backend Structure (Per Module)
```
1. Controller (8 methods)
   - index() - List with filters, search, pagination
   - store() - Create new record
   - show() - View single record
   - update() - Update record
   - destroy() - Delete record
   - stats() - Get statistics
   - getDropdownData() - Helper for other modules (if needed)
   - bulkAction() - Optional bulk operations

2. Request Validation
   - FormRequest class with rules
   - Custom error messages
   - Update mode handling
   - Unique field validation

3. Resource Transformer
   - Transform model data
   - Include relationships
   - Format dates/times
   - Add computed fields

4. API Routes
   - RESTful endpoints
   - Permission middleware
   - Route grouping
```

### Frontend Structure (Per Module)
```
1. Index Page (List View)
   - Mobile: Floating filter bar + Card view + Infinite scroll
   - Desktop: Filter row + Table view + Pagination
   - Search with debounce (300ms)
   - Multi-filter support
   - Active filter badges
   - Permission-based actions

2. Form Modal Component
   - Create/Edit in single modal
   - Full-screen on mobile
   - Validation feedback
   - Loading states
   - Success/Error messages

3. Delete Confirmation
   - Modal with record name
   - Confirm/Cancel buttons
   - Loading state

4. Router & Sidebar
   - Add route to router.js
   - Add menu item to Sidebar.vue
   - Permission-based visibility
   - Active state highlighting
```

## Implementation Order & Timeline

### Week 1 (20 hours)
**Day 1-2: Material Management (6 hours)**
- MaterialController with CRUD
- MaterialRequest validation
- MaterialResource transformer
- Materials/Index.vue responsive
- MaterialFormModal.vue
- Department dropdown integration
- Test all features

**Day 3: Machine Type Management (3 hours)**
- MachineTypeController with CRUD
- MachineTypeRequest validation
- MachineTypeResource transformer
- MachineTypes/Index.vue responsive
- MachineTypeFormModal.vue
- Machine type enum dropdown
- Test all features

**Day 4: Process Management (3 hours)**
- ProcessController with CRUD
- ProcessRequest validation
- ProcessResource transformer
- Processes/Index.vue responsive
- ProcessFormModal.vue
- Test all features

**Day 5: Department Management (3 hours)**
- DepartmentController with CRUD
- DepartmentRequest validation
- DepartmentResource transformer
- Departments/Index.vue responsive
- DepartmentFormModal.vue
- Test all features

**Weekend Buffer**: 5 hours for bugs/testing

### Week 2 (10 hours)
**Day 1: Shift Management (3 hours)**
- ShiftController with CRUD
- ShiftRequest validation (time fields)
- ShiftResource transformer
- Shifts/Index.vue responsive
- ShiftFormModal.vue (time pickers)
- Test all features

**Day 2: Section Management (2 hours)**
- SectionController with CRUD
- SectionRequest validation
- SectionResource transformer
- Sections/Index.vue responsive
- SectionFormModal.vue
- Test all features

**Day 3: Status Management (2 hours)**
- StatusController with CRUD
- StatusRequest validation
- StatusResource transformer
- Statuses/Index.vue responsive
- StatusFormModal.vue
- Test all features

**Day 4-5: Integration Testing & Polish (3 hours)**
- Cross-module testing
- Permission testing
- Mobile responsive testing
- Bug fixes
- Documentation updates

## Standardized Code Templates

### 1. Controller Template
```php
class [Module]Controller extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        // List with filters, search, pagination
    }

    public function store([Module]Request $request): JsonResponse
    {
        // Create with transaction
    }

    public function show(int $id): JsonResponse
    {
        // Show single record
    }

    public function update([Module]Request $request, int $id): JsonResponse
    {
        // Update with transaction
    }

    public function destroy(int $id): JsonResponse
    {
        // Delete with relationship cleanup
    }

    public function stats(): JsonResponse
    {
        // Statistics
    }
}
```

### 2. Request Validation Template
```php
class [Module]Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            // Field validation rules
        ];

        // Handle update mode for unique fields
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            // Modify unique rules
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            // Custom error messages
        ];
    }
}
```

### 3. Resource Template
```php
class [Module]Resource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            // All model fields
            'created_by' => $this->whenLoaded('createdBy', function () {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                ];
            }),
            // Other relationships
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
```

### 4. Frontend Index Template
```vue
<template>
  <AdminLayout>
    <!-- Mobile: Floating Filter Bar -->
    <div class="md:hidden ...">
      <!-- Search + Filter Button -->
    </div>

    <!-- Desktop: Filter Row -->
    <div class="hidden md:flex ...">
      <!-- All filters inline -->
    </div>

    <!-- Loading State -->
    <div v-if="loading && !items.length" ...>

    <!-- Empty State -->
    <div v-else-if="!loading && !items.length" ...>

    <!-- Mobile: Card View -->
    <div class="md:hidden space-y-4">
      <div v-for="item in items" :key="item.id" class="card">
        <!-- Card content -->
      </div>
    </div>

    <!-- Desktop: Table View -->
    <div class="hidden md:block">
      <table>
        <!-- Table content -->
      </table>
      <!-- Pagination -->
    </div>
  </AdminLayout>
</template>

<script setup>
// Standard structure with filters, search, CRUD operations
</script>
```

### 5. Frontend Form Modal Template
```vue
<template>
  <Modal :show="show" @close="handleClose">
    <div class="p-6">
      <h2>{{ isEditMode ? 'Edit' : 'Create' }} [Module]</h2>
      
      <form @submit.prevent="handleSubmit">
        <!-- Form fields -->
        
        <div class="flex justify-end gap-3">
          <button type="button" @click="handleClose">Cancel</button>
          <button type="submit" :disabled="loading">
            {{ loading ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script setup>
// Standard form handling
</script>
```

## Key Features for All Modules

### ✅ Backend Features
- ✅ RESTful API endpoints
- ✅ Request validation with custom messages
- ✅ Database transactions for data integrity
- ✅ Relationship eager loading
- ✅ Search functionality
- ✅ Multi-filter support
- ✅ Pagination
- ✅ Statistics endpoints
- ✅ Error handling with ApiResponseTrait
- ✅ Permission middleware
- ✅ Soft deletes where applicable

### ✅ Frontend Features
- ✅ Responsive design (mobile-first)
- ✅ Floating filter bar (mobile)
- ✅ Filter row (desktop)
- ✅ Card view (mobile)
- ✅ Table view (desktop)
- ✅ Infinite scroll (mobile)
- ✅ Pagination (desktop)
- ✅ Debounced search (300ms)
- ✅ Active filter badges
- ✅ Create/Edit modal
- ✅ Delete confirmation
- ✅ Loading states
- ✅ Empty states
- ✅ Error handling
- ✅ Permission-based UI
- ✅ Touch-optimized (44px min)

## Testing Checklist (Per Module)

### Backend Testing
- [ ] Create record - success
- [ ] Create record - validation errors
- [ ] Create record - duplicate check
- [ ] List records - no filters
- [ ] List records - with search
- [ ] List records - with filters
- [ ] List records - pagination
- [ ] Show record - existing
- [ ] Show record - not found
- [ ] Update record - success
- [ ] Update record - validation errors
- [ ] Delete record - success
- [ ] Delete record - with relationships
- [ ] Statistics endpoint

### Frontend Testing
- [ ] Mobile filter bar opens/closes
- [ ] Search works with debounce
- [ ] Filters apply correctly
- [ ] Filter badges show count
- [ ] Reset filters works
- [ ] Card view displays correctly
- [ ] Infinite scroll loads more
- [ ] Create modal opens
- [ ] Create form submits
- [ ] Create form validates
- [ ] Edit modal pre-fills data
- [ ] Edit form submits
- [ ] Delete confirmation works
- [ ] Delete executes
- [ ] Permission checks work
- [ ] Loading states show
- [ ] Error messages display
- [ ] Success messages display
- [ ] Table view works (desktop)
- [ ] Pagination works (desktop)
- [ ] Responsive breakpoints work

## Success Criteria

### Per Module
- ✅ All CRUD operations working
- ✅ Validation preventing invalid data
- ✅ Search and filters functional
- ✅ Mobile responsive (< 768px)
- ✅ Desktop optimized (≥ 768px)
- ✅ No console errors
- ✅ No visual bugs
- ✅ Permission checks in place
- ✅ Loading states implemented
- ✅ Error handling working

### Overall Project
- ✅ 7 master modules complete
- ✅ Consistent UX across modules
- ✅ All tests passing
- ✅ Documentation updated
- ✅ Ready for next phase (Orders, Production)

## File Structure

```
Backend:
app/Http/Controllers/
  ├── MaterialController.php
  ├── MachineTypeController.php
  ├── ProcessController.php
  ├── DepartmentController.php
  ├── ShiftController.php
  ├── SectionController.php
  └── StatusController.php

app/Http/Requests/
  ├── MaterialRequest.php
  ├── MachineTypeRequest.php
  ├── ProcessRequest.php
  ├── DepartmentRequest.php
  ├── ShiftRequest.php
  ├── SectionRequest.php
  └── StatusRequest.php

app/Http/Resources/
  ├── MaterialResource.php
  ├── MachineTypeResource.php
  ├── ProcessResource.php
  ├── DepartmentResource.php
  ├── ShiftResource.php
  ├── SectionResource.php
  └── StatusResource.php

Frontend:
resources/js/Pages/
  ├── Materials/
  │   └── Index.vue
  ├── MachineTypes/
  │   └── Index.vue
  ├── Processes/
  │   └── Index.vue
  ├── Departments/
  │   └── Index.vue
  ├── Shifts/
  │   └── Index.vue
  ├── Sections/
  │   └── Index.vue
  └── Statuses/
      └── Index.vue

resources/js/Components/
  ├── MaterialFormModal.vue
  ├── MachineTypeFormModal.vue
  ├── ProcessFormModal.vue
  ├── DepartmentFormModal.vue
  ├── ShiftFormModal.vue
  ├── SectionFormModal.vue
  └── StatusFormModal.vue
```

## Next Steps

1. **Start with Material Management** (highest priority)
2. **Follow with simple masters** (MachineType, Process, Department)
3. **Complete complex masters** (Shift with time pickers)
4. **Finish with pure masters** (Section, Status)
5. **Integration testing**
6. **Move to Order Management**

## Notes

- All modules follow the same pattern established in Machine Management
- Responsive design is non-negotiable for all modules
- Permission checks must be implemented consistently
- Loading and error states are required
- Mobile UX should match desktop functionality
- Code should be DRY and reusable

---

**Total Estimated Time**: 30 hours  
**Target Completion**: 2 weeks  
**Priority**: High (blocking Order & Production modules)  
**Status**: Ready to Start with Material Management
