# 🚀 CONTINUATION PROMPTS - Phoenix Manufacturing System

## 📋 How to Use These Prompts

Copy and paste the prompt for the module you want to build next. Each prompt is self-contained and includes all necessary context.

---

## ⏭️ NEXT MODULE: Module 2 - User Management

### 📝 Prompt for Module 2:

```
Continue Phoenix Manufacturing System development - Module 2: User Management CRUD

PROJECT CONTEXT:
- Location: /Users/thamjeedlal/Herd/pheonixFullstack
- Stack: Laravel 11 + Vue.js 3 + Inertia.js + Tailwind CSS
- Authentication module is COMPLETE and working

COMPLETED MODULES:
✅ Module 1: Authentication & Login (100%)
- ApiResponseTrait (app/Traits/ApiResponseTrait.php)
- AuthController with login/logout/refresh
- Login page, Dashboard, Navbar, Sidebar
- All working and tested

WHAT TO BUILD NOW - Module 2: User Management

BACKEND (Create these files):
1. app/Http/Controllers/UserController.php
   - index() - List users with pagination, search, filters
   - store() - Create new user with validation
   - show() - Get single user with relationships
   - update() - Update user
   - destroy() - Delete user (soft delete)
   - Use ApiResponseTrait for all responses
   - Use DB transactions for create/update/delete
   - Eager load: permission, machines, loginDetails

2. app/Http/Requests/UserRequest.php
   - Validate: user_name (unique), name, phone_no, password, permission_id, user_type
   - Rules for create vs update (password required on create only)
   - Custom error messages

3. app/Http/Resources/UserCollection.php
   - Transform user list for index endpoint
   - Include pagination meta

4. routes/api.php (update)
   - Add: Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', 'check.permission:user_menu_view']);
   - Specific permissions: user_menu_create, user_menu_update, user_menu_delete

FRONTEND (Create these files):
1. resources/js/Pages/Users/Index.vue
   - User list with DataTable
   - Search by name/username
   - Filter by role (Super Admin, Supervisor, Operator)
   - Filter by status (Active/Inactive)
   - Pagination (20 per page)
   - Action buttons: Edit, Delete
   - "Create User" button (if has permission)
   - Mobile responsive table (scrollable)

2. resources/js/Pages/Users/Create.vue
   - Form fields: username, name, phone_no, password, confirm password
   - Role selection (dropdown from user_permissions)
   - User type selection (ADMIN, SUPER_WISER, OPERATOR)
   - Machine assignment (multi-select)
   - Form validation
   - Submit to POST /api/users
   - Success: redirect to users list
   - Error: show validation errors

3. resources/js/Pages/Users/Edit.vue
   - Pre-filled form (load user data)
   - Same fields as Create (password optional)
   - "Change Password" checkbox to show password fields
   - Update user via PUT /api/users/{id}
   - Success: redirect to users list

4. resources/js/Components/DataTable.vue (reusable)
   - Props: columns, data, loading
   - Sortable columns
   - Action column with buttons
   - Responsive design
   - Empty state message

5. resources/js/Components/Pagination.vue (reusable)
   - Props: currentPage, lastPage, perPage, total
   - First, Previous, Next, Last buttons
   - Page number display
   - Emit: page-changed event

DESIGN REQUIREMENTS:
- Use same design system as auth module (Tailwind CSS)
- Primary color: #3B82F6 (blue-600)
- Cards: bg-white rounded-lg shadow-md p-6
- Buttons: rounded-lg with transitions
- Mobile-first responsive
- Loading states for API calls
- Success/error notifications

USER MODEL SCHEMA (Reference):
- user_name (string, unique)
- name (string)
- phone_no (string)
- status (boolean) - Active/Inactive
- password (hashed)
- permission_id (FK to user_permissions)
- user_type (enum: admin/super_wiser/operator)
- is_super_user (boolean)

IMPORTANT NOTES:
- Use ApiResponseTrait in controller
- Use DB transactions for all write operations
- Check permissions with check.permission middleware
- Eager load relationships to avoid N+1 queries
- Use UserResource for API responses
- Test with user: admin/password
- Follow same patterns as AuthController

Start with backend files first, then frontend. Test each component before moving to next.
```

---

## 📝 Prompt for Module 3: Machine Management

```
Continue Phoenix Manufacturing System - Module 3: Machine Management

PROJECT CONTEXT:
- Location: /Users/thamjeedlal/Herd/pheonixFullstack
- Completed: Authentication (Module 1), User Management (Module 2)

BUILD: Machine Management CRUD System

BACKEND:
1. app/Http/Controllers/MachineController.php
   - CRUD operations with ApiResponseTrait
   - index() with filters: machine_type_id, process_id, status
   - Include relationships: machineType, process, users (assigned operators)

2. app/Http/Requests/MachineRequest.php
   - Validate: machine_name, machine_number, machine_type_id, process_id
   - unique validation for machine_number

3. app/Http/Resources/MachineResource.php
   - Transform machine data with relationships

4. routes/api.php
   - Route::apiResource('machines', MachineController::class)
   - Middleware: auth:sanctum, check.permission:machine_master_view

FRONTEND:
1. resources/js/Pages/Machines/Index.vue
   - List with filters (type, process, status)
   - Status indicator (active/inactive)
   - Assigned users count
   
2. resources/js/Pages/Machines/Create.vue
   - Machine information form
   - Machine type dropdown
   - Process dropdown
   - Status toggle

3. resources/js/Pages/Machines/Edit.vue
   - Pre-filled form
   - Assign/unassign operators

4. resources/js/Pages/Machines/Show.vue (optional)
   - Machine details
   - Assigned users list
   - Usage statistics

MACHINE MODEL SCHEMA:
- machine_name (string)
- machine_number (string, unique)
- machine_type_id (FK)
- process_id (FK)
- status (boolean)
- description (text)

Follow same patterns as User Management module.
```

---

## 📝 Prompt for Module 4: Material Management

```
Continue Phoenix Manufacturing System - Module 4: Material Management

PROJECT CONTEXT:
- Location: /Users/thamjeedlal/Herd/pheonixFullstack
- Completed: Auth, Users, Machines

BUILD: Material Management with Inventory Tracking

BACKEND:
1. app/Http/Controllers/MaterialController.php
   - CRUD with ApiResponseTrait
   - Filter by: department_id, material_type
   - Track: quantity_in_stock, reorder_level

2. app/Http/Requests/MaterialRequest.php
   - Validate: material_name (unique), material_code, department_id
   - Validate: quantity_in_stock (numeric, min:0)

3. app/Http/Resources/MaterialResource.php
   - Include: department relationship
   - Calculate: stock_status (low/normal/high)

4. routes/api.php
   - Route::apiResource('materials', MaterialController::class)
   - Middleware: check.permission:material_master_view

FRONTEND:
1. resources/js/Pages/Materials/Index.vue
   - List with low-stock highlighting
   - Filter by department
   - Stock level indicators

2. resources/js/Pages/Materials/Create.vue
   - Material info form
   - Department selection
   - Stock quantity

3. resources/js/Pages/Materials/Edit.vue
   - Update stock levels
   - Department change

MATERIAL MODEL SCHEMA:
- material_name (string, unique)
- material_code (string)
- department_id (FK)
- color (string)
- unit (string)
- quantity_in_stock (decimal)
- reorder_level (decimal)

Add "Low Stock" badge for materials below reorder level.
```

---

## 📝 Prompt for Module 5: Order Management

```
Continue Phoenix Manufacturing System - Module 5: Order Management (Job Cards)

PROJECT CONTEXT:
- Location: /Users/thamjeedlal/Herd/pheonixFullstack
- Completed: Auth, Users, Machines, Materials

BUILD: Order (Job Card) System with Sections

BACKEND:
1. app/Http/Controllers/OrderController.php
   - CRUD operations
   - index() with filters: status, date_range, customer
   - show() - Load with sections, forms (nested relationships)
   - Create order + sections in one transaction

2. app/Http/Controllers/SectionController.php
   - Nested under orders
   - CRUD operations
   - Load forms relationship

3. app/Http/Requests/OrderRequest.php
   - Validate order data + sections array
   - Nested validation for sections

4. routes/api.php
   - Route::apiResource('orders', OrderController::class)
   - Route::apiResource('orders.sections', SectionController::class)

FRONTEND:
1. resources/js/Pages/Orders/Index.vue
   - Order list with status badges
   - Filter: date range, status
   - Order timeline view

2. resources/js/Pages/Orders/Create.vue
   - Customer information
   - Add multiple sections dynamically
   - Section details: job_name, quantity, size

3. resources/js/Pages/Orders/Show.vue
   - Order details
   - Sections list with expand/collapse
   - Progress tracking

4. resources/js/Pages/Sections/Create.vue
   - Add section to existing order
   - Section form

ORDER MODEL SCHEMA:
- order_number (string, unique)
- customer_name (string)
- job_name (string)
- quantity (integer)
- order_date (date)
- delivery_date (date)
- status_id (FK)

SECTION MODEL SCHEMA:
- order_id (FK)
- section_number (integer)
- job_name (string)
- quantity (integer)
- size (string)

Complex: One order → Many sections → Many forms (production jobs)
```

---

## 📝 Prompt for Module 6: Production Forms (Most Complex)

```
Continue Phoenix Manufacturing System - Module 6: Production Forms & Operations

PROJECT CONTEXT:
- Location: /Users/thamjeedlal/Herd/pheonixFullstack
- Completed: Auth, Users, Machines, Materials, Orders

BUILD: Production Form System with Button Operations

BACKEND:
1. app/Http/Controllers/FormController.php (COMPLEX)
   - CRUD operations
   - startButton() - Start make-ready process
   - productionStart() - Begin production
   - pauseButton() - Pause with reason
   - resumeButton() - Resume production
   - stopButton() - Stop with reason
   - completeButton() - Complete form
   - All operations use DB transactions
   - Track button_history (JSON field)

2. app/Http/Controllers/FormButtonActionController.php
   - Track all button actions
   - Create record for each operation
   - Log: form_id, button_id, action_time, created_by

3. app/Http/Requests/FormRequest.php
   - Complex validation
   - Machine assignment required
   - Material selection required
   - User assignment required

4. routes/api.php
   - Route::apiResource('forms', FormController::class)
   - Route::post('forms/{id}/start', [FormController::class, 'startButton'])
   - Route::post('forms/{id}/pause', [FormController::class, 'pauseButton'])
   - Route::post('forms/{id}/resume', [FormController::class, 'resumeButton'])
   - Route::post('forms/{id}/stop', [FormController::class, 'stopButton'])
   - Route::post('forms/{id}/complete', [FormController::class, 'completeButton'])

FRONTEND:
1. resources/js/Pages/Forms/Index.vue
   - Form list grouped by status
   - Real-time status updates
   - Filter by machine, operator, status

2. resources/js/Pages/Forms/Create.vue
   - Select section (from orders)
   - Machine selection
   - Material selection (multiple)
   - Operator assignment (multiple)
   - Sheet size selection

3. resources/js/Pages/Forms/Show.vue (Complex)
   - Form details display
   - Button panel (START/PAUSE/RESUME/STOP/COMPLETE)
   - Button state management
   - Real-time status
   - Form timeline
   - Button history display

4. resources/js/Components/ButtonPanel.vue
   - Conditional button display based on form status
   - Reason input for pause/stop
   - Confirmation dialogs
   - Loading states during operations

5. resources/js/Components/FormTimeline.vue
   - Visual timeline of all operations
   - Color-coded status
   - Duration display

FORM MODEL SCHEMA:
- section_id (FK to sections)
- form_number (string, unique)
- machine_id (FK)
- material_id (FK)
- status_id (FK)
- sub_status_id (FK)
- started_from (enum: MSI/MANUAL)
- form_status (enum: PENDING/STARTED/COMPLETED)
- button_history (JSON)
- start_time, end_time (timestamps)

BUTTON WORKFLOW:
1. START → Make Ready process begins
2. PRODUCTION START → Actual production begins
3. PAUSE → Temporary stop (with reason)
4. RESUME → Continue from pause
5. STOP → End production (with reason)
6. COMPLETE → Finalize and close form

Each button action:
- Updates form status
- Creates FormButtonAction record
- Updates button_history JSON
- Logs timestamp and user

This is the most complex module - take time to plan the state machine.
```

---

## 📝 Prompt for Module 7: Quality Control

```
Continue Phoenix Manufacturing System - Module 7: Quality Control & Verification

PROJECT CONTEXT:
- Location: /Users/thamjeedlal/Herd/pheonixFullstack
- All previous modules complete

BUILD: QC System with Manual Verification & Line Clearance

BACKEND:
1. app/Http/Controllers/QcMasterController.php
   - CRUD for QC checklists
   - Assign to forms/machines

2. app/Http/Controllers/ManualQcVerificationController.php
   - Create verification records
   - Approve/reject forms
   - Quality metrics

3. app/Http/Controllers/LineClearanceController.php
   - Line clearance process
   - Approval workflow

FRONTEND:
1. resources/js/Pages/QC/Index.vue
   - Pending QC list
   - Approved/Rejected forms

2. resources/js/Pages/QC/Verification.vue
   - QC checklist form
   - Pass/fail options
   - Comments/notes
   - Image upload for defects

3. resources/js/Pages/QC/LineClearance.vue
   - Line clearance form
   - Supervisor approval

QC PROCESS:
- Form completed → QC required
- QC verification by operator
- Supervisor approval
- Line clearance
- Order completion

Add QC metrics to dashboard.
```

---

## 🎯 RECOMMENDED ORDER

Start with these prompts in order:

1. **Module 2: User Management** (Easiest, 6 hours)
2. **Module 3: Machine Management** (Simple, 4 hours)
3. **Module 4: Material Management** (Simple, 4 hours)
4. **Module 5: Order Management** (Medium, 8 hours)
5. **Module 6: Production Forms** (Complex, 12 hours)
6. **Module 7: Quality Control** (Medium, 6 hours)

---

## 📚 IMPORTANT REFERENCES

Before starting each module, review:
- **SYSTEM_PROMPT.md** - Development standards
- **MODULE_DEVELOPMENT_GUIDE.md** - Module checklist
- **DATABASE_SCHEMA.md** - Table structures
- **MODULE_STATUS_TRACKER.md** - Current progress

---

## ✅ CHECKLIST AFTER EACH MODULE

- [ ] Backend controller complete
- [ ] Form validation working
- [ ] API endpoints functional
- [ ] Frontend pages created
- [ ] CRUD operations work
- [ ] Permission checks implemented
- [ ] Mobile responsive
- [ ] Error handling complete
- [ ] Update MODULE_STATUS_TRACKER.md
- [ ] Update PROGRESS_TRACKER.md
- [ ] Test manually
- [ ] Create module completion doc

---

## 💡 PRO TIPS

1. **Copy entire prompt** - Each is self-contained
2. **Start with backend** - Test APIs with Postman
3. **Use ApiResponseTrait** - Consistent responses
4. **Follow auth module patterns** - Proven to work
5. **Test as you build** - Don't wait till end
6. **Update trackers** - Keep documentation current
7. **Commit often** - Small, focused commits

---

**Ready to continue? Copy the Module 2 prompt above and start building!** 🚀

*Last Updated: October 18, 2025*
