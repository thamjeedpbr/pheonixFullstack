# Module Development Guide - Phoenix Manufacturing System

## 📋 Complete Module Checklist

Use this as a reference for developing each module systematically.

---

## 🎯 Module Structure Template

### Each Module Should Have:

#### Backend (Laravel):
- [ ] **Controller** - Business logic
- [ ] **Form Request** - Validation rules
- [ ] **API Resource** - Response transformation
- [ ] **Routes** - API endpoints
- [ ] **Tests** - Unit & feature tests (optional)

#### Frontend (Vue.js):
- [ ] **Pages** - Main views
- [ ] **Components** - Reusable UI pieces
- [ ] **Composables** - Shared logic (optional)
- [ ] **Store** - State management (if needed)

---

## 📦 Module 1: Authentication & Login

### Backend Files:

#### 1. LoginRequest.php
```
Location: app/Http/Requests/LoginRequest.php
Purpose: Validate login credentials
Fields: user_name, password, remember
Rules: required, string, exists in users table
```

#### 2. AuthController.php
```
Location: app/Http/Controllers/AuthController.php
Methods:
  - login(LoginRequest $request): JsonResponse
  - logout(Request $request): JsonResponse
  - me(Request $request): JsonResponse
  - refresh(Request $request): JsonResponse
Uses: Laravel Sanctum, DB transactions, UserResource
```

#### 3. UserResource.php
```
Location: app/Http/Resources/UserResource.php
Purpose: Transform user data for API responses
Include: id, user_name, name, email, user_type, permission (relationship)
Exclude: password, api_key, remember_token
```

#### 4. CheckPermission.php
```
Location: app/Http/Middleware/CheckPermission.php
Purpose: Check user permissions before allowing access
Logic: Read permission from UserPermission model, check specific permissions
```

#### 5. Routes
```
Location: routes/api.php
Add:
  POST /api/login - public
  POST /api/logout - protected
  GET /api/user - protected
  POST /api/refresh - protected
```

### Frontend Files:

#### 1. GuestLayout.vue
```
Location: resources/js/Layouts/GuestLayout.vue
Purpose: Layout wrapper for login page
Features: Centered content, logo, footer
```

#### 2. Login.vue
```
Location: resources/js/Pages/Auth/Login.vue
Features:
  - Login form (username, password, remember me)
  - Validation
  - Error handling
  - Loading state
  - Redirect after login
  - Responsive design
```

#### 3. AuthenticatedLayout.vue
```
Location: resources/js/Layouts/AuthenticatedLayout.vue
Features:
  - Navbar component
  - Sidebar component
  - Main content area
  - Footer
  - Mobile responsive
  - Collapsible sidebar
```

#### 4. Navbar.vue
```
Location: resources/js/Components/Navbar.vue
Features:
  - Logo
  - Search bar
  - Notifications icon
  - User menu (profile, settings, logout)
  - Hamburger menu (mobile)
  - Height: 64px (h-16)
```

#### 5. Sidebar.vue
```
Location: resources/js/Components/Sidebar.vue
Features:
  - Navigation menu items
  - Icons (Lucide/Heroicons)
  - Active state highlighting
  - Collapsible on mobile
  - Permission-based menu visibility
  - Width: 256px expanded, 64px collapsed
```

#### 6. Dashboard.vue
```
Location: resources/js/Pages/Dashboard.vue
Features:
  - Welcome message
  - Stats cards (total orders, machines, users, etc.)
  - Recent activity
  - Charts/graphs
  - Quick actions
```

---

## 📦 Module 2: Master Data Management

### Backend Files:

#### Controllers to Create:
```
1. MachineController.php - Machine CRUD
2. MachineTypeController.php - Machine type CRUD
3. MaterialController.php - Material CRUD
4. DepartmentController.php - Department CRUD
5. ProcessController.php - Process CRUD
6. ShiftController.php - Shift CRUD
7. StatusController.php - Status CRUD
8. SubStatusController.php - Sub-status CRUD
```

#### Form Requests:
```
1. MachineRequest.php
2. MaterialRequest.php
3. ProcessRequest.php
etc. (one for each controller)
```

#### API Resources:
```
1. MachineResource.php
2. MaterialResource.php
3. ProcessResource.php
etc. (one for each controller)
```

### Frontend Files:

#### For Each Entity (e.g., Machines):
```
1. resources/js/Pages/Machines/Index.vue - List view
2. resources/js/Pages/Machines/Create.vue - Create form
3. resources/js/Pages/Machines/Edit.vue - Edit form
4. resources/js/Pages/Machines/Show.vue - Detail view (optional)
5. resources/js/Components/MachineCard.vue - Reusable card component
```

#### Shared Components:
```
1. DataTable.vue - Reusable table with pagination
2. SearchFilter.vue - Search and filter component
3. ConfirmDialog.vue - Confirmation modal
4. FormInput.vue - Styled input component
5. FormSelect.vue - Styled select component
```

---

## 📦 Module 3: Production Management

### Backend Files:

#### Controllers:
```
1. OrderController.php - Job card management
2. SectionController.php - Section CRUD
3. FormController.php - Production form (MOST COMPLEX)
4. LoginDetailController.php - Operator login to machines
5. ButtonController.php - Button CRUD
6. ButtonGroupController.php - Button group CRUD
```

### Frontend Files:

#### Pages:
```
1. Orders/Index.vue - Order list
2. Orders/Create.vue - Create job card
3. Orders/Edit.vue - Edit order
4. Forms/Index.vue - Production forms list
5. Forms/Create.vue - Create production form
6. Forms/Show.vue - View form details with actions
7. OperatorLogin.vue - Operator machine login screen
```

---

## 📦 Module 4: Production Tracking

### Backend Files:

#### Controllers:
```
1. DmiDataController.php - Production data recording
2. FormButtonActionController.php - Button action tracking
3. DashboardController.php - Dashboard analytics
4. ReportController.php - Reports generation
```

### Frontend Files:

#### Pages:
```
1. Dashboard.vue - Real-time production dashboard
2. ProductionTracking.vue - Live production status
3. MachineStatus.vue - Machine status display
4. Reports/Index.vue - Reports listing
5. Reports/ProductionReport.vue - Production report
```

#### Components:
```
1. StatusCard.vue - Machine status card
2. ProductionChart.vue - Production chart
3. LiveCounter.vue - Real-time counter
4. ActionButtons.vue - Start/Stop/Pause buttons
```

---

## 📦 Module 5: Quality Control

### Backend Files:

#### Controllers:
```
1. QcMasterController.php - QC checklist CRUD
2. ManualQcVerificationController.php - QC verification
3. LineClearanceController.php - Line clearance
```

### Frontend Files:

#### Pages:
```
1. QC/Index.vue - QC checklist management
2. QC/Verification.vue - QC verification form
3. QC/LineClearance.vue - Line clearance form
4. QC/History.vue - QC history
```

---

## 📦 Module 6: Supporting Features

### Backend Files:

#### Controllers:
```
1. StickyNoteController.php - Sticky notes
2. PressNoteController.php - Press notes
3. DocumentController.php - File uploads
4. DailyTaskController.php - Daily tasks
5. StandardProductionController.php - Production standards
```

### Frontend Files:

#### Pages:
```
1. Notes/Index.vue - All notes
2. Documents/Index.vue - Document library
3. Tasks/Index.vue - Daily tasks
4. Settings/Index.vue - System settings
```

---

## 🎨 Design System Reference

### Colors:
```css
/* Primary */
--primary-50: #eff6ff
--primary-500: #3b82f6
--primary-600: #2563eb
--primary-700: #1d4ed8

/* Secondary */
--gray-50: #f9fafb
--gray-100: #f3f4f6
--gray-500: #6b7280
--gray-900: #111827

/* Success */
--success: #10b981

/* Warning */
--warning: #f59e0b

/* Danger */
--danger: #ef4444
```

### Typography:
```css
/* Headings */
.text-h1: text-3xl font-bold
.text-h2: text-2xl font-bold
.text-h3: text-xl font-semibold
.text-h4: text-lg font-semibold

/* Body */
.text-body: text-base
.text-small: text-sm
.text-xs: text-xs
```

### Spacing:
```css
/* Container padding */
p-6 (24px) on desktop
p-4 (16px) on mobile

/* Card spacing */
p-6 for card content
gap-4 between elements

/* Form spacing */
mb-4 between form fields
gap-6 between sections
```

---

## ✅ Testing Checklist (Per Module)

### Backend Testing:
- [ ] Can create new record via API
- [ ] Can retrieve list of records with pagination
- [ ] Can get single record by ID
- [ ] Can update existing record
- [ ] Can delete record
- [ ] Validation working (try invalid data)
- [ ] Authentication required (try without token)
- [ ] Permissions checked (try with wrong role)
- [ ] Database transactions working (check rollback on error)
- [ ] Error responses are JSON with proper status codes

### Frontend Testing:
- [ ] Page loads without errors
- [ ] Form submission works
- [ ] Validation messages appear
- [ ] Loading states show during API calls
- [ ] Success messages appear
- [ ] Error messages appear and are helpful
- [ ] Table pagination works
- [ ] Search and filters work
- [ ] Edit and delete actions work
- [ ] Responsive on mobile (test with dev tools)
- [ ] Sidebar collapses on mobile
- [ ] Navigation works
- [ ] Logout works
- [ ] Permission-based UI hiding works

---

## 📊 Module Completion Report Template

```markdown
# Module: [Module Name]

## Completion Date: [Date]

### Backend (✅/❌):
- [✅] Controller created
- [✅] Form Request validation
- [✅] API Resource transformer
- [✅] Routes configured
- [✅] Tested with Postman

### Frontend (✅/❌):
- [✅] Pages created
- [✅] Components created
- [✅] Forms validated
- [✅] API integrated
- [✅] Responsive design
- [✅] Tested on mobile

### Features:
- List of features implemented
- Any notes or special considerations

### Known Issues:
- Any issues or future improvements

### Screenshots:
- [Attach screenshots if applicable]

### Next Module:
- [Name of next module to work on]
```

---

## 🔧 Useful Commands

### Laravel:
```bash
# Create controller
php artisan make:controller API/AuthController

# Create request
php artisan make:request LoginRequest

# Create resource
php artisan make:resource UserResource

# Create middleware
php artisan make:middleware CheckPermission

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Vue.js:
```bash
# Install dependencies
npm install

# Run dev server
npm run dev

# Build for production
npm run build

# Format code
npm run lint
```

---

## 📚 Reference Links

### Laravel:
- Controllers: https://laravel.com/docs/11.x/controllers
- Validation: https://laravel.com/docs/11.x/validation
- API Resources: https://laravel.com/docs/11.x/eloquent-resources
- Sanctum: https://laravel.com/docs/11.x/sanctum

### Vue.js:
- Composition API: https://vuejs.org/guide/introduction.html
- Inertia.js: https://inertiajs.com/
- Tailwind CSS: https://tailwindcss.com/docs

---

## 💡 Pro Tips

1. **Always start with Backend** - Test APIs before building UI
2. **Use API Resources** - Never return raw models in responses
3. **Validate everything** - Use Form Requests for validation
4. **Use DB transactions** - Ensure data integrity
5. **Keep controllers thin** - Move complex logic to services
6. **Reuse components** - Build a component library
7. **Mobile first** - Design for mobile, then scale up
8. **Test early** - Test as you build, not after
9. **Document as you go** - Update docs with each module
10. **Commit often** - Small, focused commits with clear messages

---

*Module Development Guide v1.0*  
*Last Updated: October 18, 2025*  
*For Phoenix Manufacturing System*
