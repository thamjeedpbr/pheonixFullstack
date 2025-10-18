# SYSTEM PROMPT: Phoenix Manufacturing System - Module-by-Module Development

## Project Context

You are continuing development of the **Phoenix Manufacturing System**, a Laravel 11 + Vue.js 3 + Inertia.js manufacturing management application.

---

## Current Project Status (70% Complete)

### ✅ COMPLETED:
- **Database**: 36 tables migrated successfully
- **Models**: 30 Eloquent models with 162+ relationships
- **Seeders**: 55 records seeded (4 users, 4 machines, 7 materials, etc.)
- **Documentation**: Complete API specs, database schema, relationships

### 🔄 CURRENT PHASE:
**Phase 2: Module-by-Module Development (API + Frontend)**

Starting with: **Authentication & Login Module**

---

## Project Directories

```
PROJECT ROOT: /Users/thamjeedlal/Herd/pheonixFullstack/

Key Directories:
- app/Models/ - Eloquent models (30 models complete)
- app/Http/Controllers/ - API controllers (to be created)
- app/Http/Requests/ - Form validation (to be created)
- app/Http/Resources/ - API resources (to be created)
- app/Http/Middleware/ - Custom middleware (to be created)
- routes/api.php - API routes
- routes/web.php - Web routes
- database/migrations/ - 38 migrations (complete)
- database/seeders/ - 10 seeders (complete)
- resources/js/ - Vue.js frontend
- resources/js/Pages/ - Inertia pages
- resources/js/Components/ - Vue components
- resources/js/Layouts/ - Layout components
```

---

## Development Approach: MODULE-BY-MODULE

### For Each Module, Create:

**Backend (Laravel):**
1. Controller with CRUD operations
2. Form Request validation
3. API Resource transformer
4. API routes with middleware
5. Database transactions where needed
6. Proper error handling

**Frontend (Vue.js + Inertia):**
1. Page components
2. Reusable UI components
3. Form components with validation
4. Layout components (Navbar, Sidebar)
5. Responsive design (mobile-first)
6. Modern UI (Tailwind CSS)

---

## Current Module: AUTHENTICATION & LOGIN

### What Needs to Be Created:

#### Backend:
1. **app/Http/Controllers/AuthController.php**
   - login() - POST /api/login
   - logout() - POST /api/logout
   - me() - GET /api/user
   - refresh() - POST /api/refresh-token

2. **app/Http/Controllers/UserController.php**
   - index() - GET /api/users
   - store() - POST /api/users
   - show() - GET /api/users/{id}
   - update() - PUT /api/users/{id}
   - destroy() - DELETE /api/users/{id}

3. **app/Http/Requests/LoginRequest.php**
   - Validate username and password

4. **app/Http/Requests/UserRequest.php**
   - Validate user creation/update

5. **app/Http/Resources/UserResource.php**
   - Transform user data for API responses

6. **app/Http/Middleware/CheckPermission.php**
   - Check user permissions based on UserPermission model

#### Frontend:
1. **resources/js/Layouts/AuthenticatedLayout.vue**
   - Main admin layout with navbar and sidebar
   - Responsive design
   - User menu, notifications

2. **resources/js/Layouts/GuestLayout.vue**
   - Layout for login page

3. **resources/js/Components/Navbar.vue**
   - Top navigation bar
   - User profile dropdown
   - Notifications
   - Mobile hamburger menu

4. **resources/js/Components/Sidebar.vue**
   - Left sidebar navigation
   - Collapsible menu items
   - Active state indicators
   - Permission-based menu visibility

5. **resources/js/Pages/Auth/Login.vue**
   - Login form
   - Form validation
   - Error handling
   - Remember me checkbox
   - Responsive design

6. **resources/js/Pages/Dashboard.vue**
   - Main dashboard after login
   - Stats cards
   - Charts/graphs
   - Recent activity

7. **resources/js/Pages/Users/Index.vue**
   - User list with DataTable
   - Search and filters
   - Pagination
   - Action buttons (edit, delete)

8. **resources/js/Pages/Users/Create.vue**
   - User creation form
   - Role selection
   - Validation

9. **resources/js/Pages/Users/Edit.vue**
   - User edit form
   - Password change option

---

## Design Standards to Follow

### 1. Backend Standards:

#### Controller Structure:
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index()
    {
        // Use DB transactions for complex queries
        // Add pagination
        // Add filters and search
        // Return API Resource
    }
    
    public function store(UserRequest $request)
    {
        // Use DB transactions for data integrity
        DB::beginTransaction();
        try {
            // Create logic
            DB::commit();
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack();
            // Proper error handling
        }
    }
}
```

#### Always Use:
- ✅ Database transactions for create/update/delete
- ✅ Form Request validation
- ✅ API Resources for responses
- ✅ Proper HTTP status codes
- ✅ Try-catch blocks
- ✅ Eloquent relationships (eager loading)
- ✅ Query optimization (avoid N+1)
- ✅ Middleware for authentication and authorization

#### Error Handling:
```php
try {
    DB::beginTransaction();
    // Operations
    DB::commit();
    return response()->json(['message' => 'Success'], 200);
} catch (\Exception $e) {
    DB::rollBack();
    return response()->json([
        'message' => 'Error occurred',
        'error' => $e->getMessage()
    ], 500);
}
```

---

### 2. Frontend Standards:

#### Responsive Design:
- **Mobile First**: Design for mobile, then tablet, then desktop
- Use Tailwind breakpoints: `sm:`, `md:`, `lg:`, `xl:`, `2xl:`
- Sidebar should collapse on mobile
- Tables should be responsive/scrollable on mobile
- Forms should stack on mobile

#### Component Structure:
```vue
<template>
  <!-- Clean, semantic HTML -->
  <!-- Use Tailwind CSS classes -->
  <!-- Responsive utilities -->
</template>

<script setup>
// Composition API
// Imports
// Props
// State
// Computed
// Methods
// Lifecycle hooks
</script>

<style scoped>
/* Minimal custom CSS */
/* Prefer Tailwind utilities */
</style>
```

#### Color Scheme (Modern Dark/Light):
```javascript
// Primary: Blue
primary: '#3B82F6'
primary-dark: '#2563EB'

// Secondary: Slate
secondary: '#64748B'

// Success: Green
success: '#10B981'

// Warning: Amber
warning: '#F59E0B'

// Danger: Red
danger: '#EF4444'

// Background (Light mode)
bg-light: '#F8FAFC'
card-light: '#FFFFFF'

// Background (Dark mode)
bg-dark: '#0F172A'
card-dark: '#1E293B'
```

#### Layout Standards:

**Navbar Height:** 64px (h-16)
**Sidebar Width:** 256px (w-64) expanded, 64px (w-16) collapsed
**Content Padding:** p-6 on desktop, p-4 on mobile
**Card Border Radius:** rounded-lg
**Shadow:** shadow-md for cards

---

### 3. UI Component Standards:

#### Buttons:
```vue
<!-- Primary Button -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
  Primary Action
</button>

<!-- Secondary Button -->
<button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200">
  Secondary Action
</button>

<!-- Danger Button -->
<button class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
  Delete
</button>
```

#### Form Inputs:
```vue
<div class="mb-4">
  <label class="block text-sm font-medium text-gray-700 mb-2">
    Label
  </label>
  <input 
    type="text"
    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
    placeholder="Enter value"
  />
  <p v-if="error" class="text-red-600 text-sm mt-1">{{ error }}</p>
</div>
```

#### Cards:
```vue
<div class="bg-white rounded-lg shadow-md p-6">
  <!-- Card content -->
</div>
```

#### DataTables:
- Pagination
- Search
- Sorting
- Filters
- Actions column
- Responsive (scroll on mobile)

---

## Database Transaction Standards

### Always Use Transactions For:
1. Creating records with relationships
2. Updating multiple tables
3. Delete operations with cascades
4. Any operation that must be atomic

### Example:
```php
DB::beginTransaction();
try {
    // Create order
    $order = Order::create($orderData);
    
    // Create sections
    foreach ($sections as $sectionData) {
        $order->sections()->create($sectionData);
    }
    
    // Log activity
    ActivityLog::create([...]);
    
    DB::commit();
    return response()->json(['data' => $order], 201);
    
} catch (\Exception $e) {
    DB::rollBack();
    \Log::error('Order creation failed: ' . $e->getMessage());
    return response()->json([
        'message' => 'Failed to create order',
        'error' => $e->getMessage()
    ], 500);
}
```

---

## API Route Standards

### Route Structure:
```php
// routes/api.php

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    
    // Resource routes with permission middleware
    Route::middleware(['check.permission'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('machines', MachineController::class);
        Route::apiResource('materials', MaterialController::class);
        // ... more resources
    });
});
```

---

## Existing Database Models (Reference)

### Key Models Available:
```php
User - Users with permissions
UserPermission - Role-based permissions (59 fields)
Machine - Production machines
MachineType - Machine categories
Material - Materials inventory
Department - Material departments
Process - Manufacturing processes
Shift - Work shifts
Status - Production statuses
SubStatus - Detailed sub-statuses
Button - Action buttons
ButtonGroup - Button groupings
Order - Job cards
Section - Order sections
Form - Production forms (COMPLEX)
LoginDetail - Operator login tracking
DmiData - Production data
FormButtonAction - Action tracking
QcMaster - QC checklists
ManualQcVerification - QC verifications
LineClearance - Line clearances
StickyNote - Notes
PressNote - Press notes
Document - Document management
DailyTask - Daily tasks
DailyMaintainedData - Maintenance data
StandardProduction - Production standards
ThirdPartyData - External data
Sheet - Sheet sizes
Tag - Tags
```

### All Relationships Are Defined:
- Check model files for exact relationship methods
- Use eager loading: `User::with('permission', 'machines')->get()`

---

## Current Task: START WITH LOGIN MODULE

### Required Files to Create:

1. **Backend (Priority Order):**
   ```
   1. app/Http/Requests/LoginRequest.php
   2. app/Http/Controllers/AuthController.php
   3. app/Http/Resources/UserResource.php
   4. app/Http/Middleware/CheckPermission.php
   5. routes/api.php (update with auth routes)
   ```

2. **Frontend (Priority Order):**
   ```
   1. resources/js/Layouts/GuestLayout.vue
   2. resources/js/Pages/Auth/Login.vue
   3. resources/js/Layouts/AuthenticatedLayout.vue
   4. resources/js/Components/Navbar.vue
   5. resources/js/Components/Sidebar.vue
   6. resources/js/Pages/Dashboard.vue
   ```

---

## Testing Credentials

```
Username: admin
Password: password
Role: Super Admin (full permissions)

Username: supervisor1
Password: password
Role: Supervisor

Username: operator1
Password: password
Role: Operator
```

---

## Important Notes

### Security:
- ✅ Use Laravel Sanctum for API authentication
- ✅ Validate all inputs
- ✅ Hash passwords (bcrypt)
- ✅ CSRF protection on forms
- ✅ Permission checks on every route

### Performance:
- ✅ Use eager loading to avoid N+1 queries
- ✅ Cache frequently accessed data
- ✅ Paginate large datasets
- ✅ Use indexes (already in migrations)
- ✅ Optimize queries with `select()` to load only needed columns

### Code Quality:
- ✅ Follow PSR-12 coding standards
- ✅ Use meaningful variable names
- ✅ Add PHPDoc comments
- ✅ Keep methods small and focused
- ✅ DRY principle (Don't Repeat Yourself)

---

## Design Inspiration

**Admin Panel Style:** Modern, Clean, Professional
**Similar to:** 
- Tailwind UI
- Shadcn/ui
- Material Design (but lighter)

**Characteristics:**
- Spacious layouts
- Clean typography
- Subtle shadows
- Smooth transitions
- Intuitive navigation
- Mobile-responsive
- Fast loading
- Accessible (WCAG compliant)

---

## Next Steps (Prompt to Continue)

**To continue development, start with:**

```
"I need to create the Authentication & Login module for Phoenix Manufacturing System.

Please create:

1. Backend:
   - LoginRequest.php with validation
   - AuthController.php with login, logout, me methods
   - UserResource.php for API responses
   - Update routes/api.php with auth routes
   - Use Laravel Sanctum for token authentication
   - Include proper DB transactions and error handling

2. Frontend:
   - GuestLayout.vue for login page
   - Login.vue page with modern, responsive design
   - Use Tailwind CSS
   - Form validation
   - Error handling
   - Remember me option

Project location: /Users/thamjeedlal/Herd/pheonixFullstack/

Reference:
- User model: app/Models/User.php (already exists)
- UserPermission model: app/Models/UserPermission.php (already exists)
- Database has 4 test users (admin/password, supervisor1/password, operator1/password, operator2/password)

Follow all standards mentioned in the system prompt.
Start with backend files first, then frontend."
```

---

## Module Completion Checklist

After completing each module, verify:

**Backend:**
- [ ] Controller created with all CRUD methods
- [ ] Form Request validation working
- [ ] API Resource transformer working
- [ ] Routes defined with proper middleware
- [ ] Database transactions used correctly
- [ ] Error handling implemented
- [ ] Tested with Postman/Insomnia

**Frontend:**
- [ ] Pages created and working
- [ ] Components reusable
- [ ] Forms validate correctly
- [ ] Responsive on mobile, tablet, desktop
- [ ] API integration working
- [ ] Loading states implemented
- [ ] Error messages displayed
- [ ] Navigation working
- [ ] Permissions checked on UI

---

## Progress Tracking

Update `/Users/thamjeedlal/Herd/pheonixFullstack/PROGRESS_TRACKER.md` after each module completion.

---

**Current Status:** Ready to start Authentication & Login Module  
**Next Module After Login:** Master Data Management (Machines, Materials, etc.)  
**Overall Progress:** 70% Complete (Database done, API & Frontend pending)

---

*System Prompt Version: 1.0*  
*Last Updated: October 18, 2025*  
*Ready for Module-by-Module Development!* 🚀
