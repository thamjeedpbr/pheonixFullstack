# 🚀 PRODUCTION CORE MODULES - Complete Development Guide

**Phase 3: Production Core**  
**Start Date**: October 19, 2025  
**Estimated Duration**: 47 hours (~6 working days)  
**Priority**: CRITICAL  

---

## 📊 Overview

All master data modules (10/10) are complete! Now we enter the most critical phase - the production core that handles the actual manufacturing operations.

**What We've Built So Far** ✅:
- Authentication & User Management
- Machine & Machine Type Management
- Material Management
- Department, Process, Shift, Status Management
- Section Management

**What We're Building Now** ⏭️:
- Order Management (Job Cards)
- Form/Job Management (Production Jobs)
- Production Operations (Button State Machine)
- Quality Control
- Supporting Features

---

## 🎯 Module 11: Order Management (CRITICAL - START HERE)

### Overview
Orders (Job Cards) are the foundation of production tracking. Each order contains multiple sections, and each section can have multiple forms (production jobs).

**Hierarchy**: `Order → Sections → Forms`

### Time Estimate: 10 hours

---

### Backend Development (4 hours)

#### 1. Create OrderController
**Location**: `app/Http/Controllers/OrderController.php`

**Methods** (8 total):
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Section;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of orders with filters and pagination
     */
    public function index(Request $request)
    {
        try {
            $query = Order::with(['sections', 'createdBy']);

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('job_card_no', 'like', "%{$search}%")
                      ->orWhere('client_name', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Sort by latest
            $query->orderBy('created_at', 'desc');

            $perPage = $request->input('per_page', 20);
            $orders = $query->paginate($perPage);

            return $this->successResponse(
                OrderResource::collection($orders),
                'Orders retrieved successfully',
                200,
                [
                    'meta' => [
                        'current_page' => $orders->currentPage(),
                        'per_page' => $orders->perPage(),
                        'total' => $orders->total(),
                        'last_page' => $orders->lastPage()
                    ]
                ]
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve orders: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created order
     */
    public function store(OrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'job_card_no' => $request->job_card_no,
                'client_name' => $request->client_name,
                'title' => $request->title,
                'description' => $request->description,
                'delivery_date' => $request->delivery_date,
                'priority' => $request->priority ?? 'normal',
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return $this->successResponse(
                new OrderResource($order->load('sections', 'createdBy')),
                'Order created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create order: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        try {
            $order = Order::with(['sections.forms', 'createdBy'])->findOrFail($id);
            return $this->successResponse(
                new OrderResource($order),
                'Order retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Order not found', 404);
        }
    }

    /**
     * Update the specified order
     */
    public function update(OrderRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            
            $order->update([
                'job_card_no' => $request->job_card_no,
                'client_name' => $request->client_name,
                'title' => $request->title,
                'description' => $request->description,
                'delivery_date' => $request->delivery_date,
                'priority' => $request->priority ?? $order->priority,
                'status' => $request->status ?? $order->status,
            ]);

            DB::commit();

            return $this->successResponse(
                new OrderResource($order->load('sections', 'createdBy')),
                'Order updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update order: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified order
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            
            // Check if order has forms
            $formCount = $order->sections()->withCount('forms')->get()->sum('forms_count');
            if ($formCount > 0) {
                return $this->errorResponse('Cannot delete order with associated forms', 400);
            }

            $order->delete();
            DB::commit();

            return $this->successResponse(null, 'Order deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete order: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get order statistics
     */
    public function stats()
    {
        try {
            $stats = [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'in_progress_orders' => Order::where('status', 'in_progress')->count(),
                'completed_orders' => Order::where('status', 'completed')->count(),
                'urgent_orders' => Order::where('priority', 'urgent')->count(),
                'this_month_orders' => Order::whereMonth('created_at', now()->month)->count(),
            ];

            return $this->successResponse($stats, 'Order statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve statistics', 500);
        }
    }

    /**
     * Change order status
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            $order->update(['status' => $request->status]);
            DB::commit();

            return $this->successResponse(
                new OrderResource($order),
                'Order status updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get orders by status
     */
    public function byStatus($status)
    {
        try {
            $orders = Order::with(['sections', 'createdBy'])
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return $this->successResponse(
                OrderResource::collection($orders),
                'Orders retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve orders', 500);
        }
    }
}
```

---

#### 2. Create OrderRequest
**Location**: `app/Http/Requests/OrderRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $orderId = $this->route('id');

        return [
            'job_card_no' => 'required|string|max:50|unique:orders,job_card_no,' . $orderId,
            'client_name' => 'required|string|max:255',
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'delivery_date' => 'required|date|after_or_equal:today',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'job_card_no.required' => 'Job card number is required',
            'job_card_no.unique' => 'This job card number already exists',
            'client_name.required' => 'Client name is required',
            'title.required' => 'Order title is required',
            'delivery_date.required' => 'Delivery date is required',
            'delivery_date.after_or_equal' => 'Delivery date cannot be in the past',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
```

---

#### 3. Create OrderResource
**Location**: `app/Http/Resources/OrderResource.php`

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_card_no' => $this->job_card_no,
            'client_name' => $this->client_name,
            'title' => $this->title,
            'description' => $this->description,
            'delivery_date' => $this->delivery_date,
            'priority' => $this->priority,
            'status' => $this->status,
            'sections' => $this->whenLoaded('sections', function() {
                return $this->sections->map(function($section) {
                    return [
                        'id' => $section->id,
                        'section_no' => $section->section_no,
                        'section_name' => $section->section_name,
                        'forms_count' => $section->forms->count() ?? 0,
                    ];
                });
            }),
            'created_by' => $this->whenLoaded('createdBy', function() {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                ];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
```

---

#### 4. Add Routes
**Location**: `routes/api.php`

```php
// Order Management Routes
Route::middleware(['auth:sanctum', 'check.permission:order_view'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/stats', [OrderController::class, 'stats']);
    Route::get('/orders/status/{status}', [OrderController::class, 'byStatus']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'check.permission:order_create'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'check.permission:order_update'])->group(function () {
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'changeStatus']);
});

Route::middleware(['auth:sanctum', 'check.permission:order_delete'])->group(function () {
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
});
```

---

### Frontend Development (6 hours)

#### 1. Create Orders/Index.vue
**Location**: `resources/js/Pages/Orders/Index.vue`

**Features**:
- Responsive design (mobile card view, desktop table view)
- Floating filter bar (mobile)
- Search by job card number, client name
- Filter by status, priority, date range
- Infinite scroll (mobile) / Pagination (desktop)
- Status badges with colors
- Quick actions (view, edit, delete)
- Create new order button

**Key sections**:
```vue
<template>
  <AuthenticatedLayout>
    <!-- Mobile: Floating Filter Bar -->
    <div v-if="isMobile" class="mobile-filter-bar">
      <!-- Search input -->
      <!-- Advanced filters dropdown -->
    </div>

    <!-- Desktop: Filter Row -->
    <div v-else class="desktop-filter-row">
      <!-- Search, Status, Priority, Date filters -->
    </div>

    <!-- Mobile: Card View -->
    <div v-if="isMobile" class="order-cards">
      <div v-for="order in orders" :key="order.id" class="order-card">
        <!-- Order details in card format -->
      </div>
    </div>

    <!-- Desktop: Table View -->
    <div v-else class="order-table">
      <table>
        <!-- Job Card No, Client, Title, Sections, Status, Priority, Actions -->
      </table>
    </div>

    <!-- Pagination / Load More -->
  </AuthenticatedLayout>
</template>
```

---

#### 2. Create Orders/Create.vue
**Location**: `resources/js/Pages/Orders/Create.vue`

**Features**:
- Job card number input (auto-generate option)
- Client name input
- Order title
- Description textarea
- Delivery date picker
- Priority selection (low/normal/high/urgent)
- Form validation
- Loading state
- Error handling
- Success redirect

---

#### 3. Create Orders/Edit.vue
**Location**: `resources/js/Pages/Orders/Edit.vue`

**Features**:
- Pre-filled form from order data
- Same fields as create
- Update button
- Cancel button
- Validation
- Error handling

---

#### 4. Create Orders/Show.vue
**Location**: `resources/js/Pages/Orders/Show.vue`

**Features**:
- Order information display
- Client details
- Delivery date countdown
- Status timeline
- Sections list with forms count
- Add section button
- View/Edit/Delete section actions
- Back button
- Edit order button

---

#### 5. Update Sidebar
Add Orders menu item:
```vue
<!-- In Sidebar.vue -->
<router-link 
  v-if="hasPermission('order_view')"
  to="/orders"
  class="sidebar-item"
>
  <ClipboardList class="icon" />
  <span>Orders</span>
</router-link>
```

---

#### 6. Update Router
**Location**: `resources/js/router.js`

```javascript
import OrdersIndex from './Pages/Orders/Index.vue';
import OrdersCreate from './Pages/Orders/Create.vue';
import OrdersEdit from './Pages/Orders/Edit.vue';
import OrdersShow from './Pages/Orders/Show.vue';

{
  path: '/orders',
  name: 'orders.index',
  component: OrdersIndex,
  meta: { requiresAuth: true }
},
{
  path: '/orders/create',
  name: 'orders.create',
  component: OrdersCreate,
  meta: { requiresAuth: true }
},
{
  path: '/orders/:id',
  name: 'orders.show',
  component: OrdersShow,
  meta: { requiresAuth: true }
},
{
  path: '/orders/:id/edit',
  name: 'orders.edit',
  component: OrdersEdit,
  meta: { requiresAuth: true }
},
```

---

### Testing Checklist

Backend:
- [ ] Can list orders with pagination
- [ ] Can search orders
- [ ] Can filter by status/priority
- [ ] Can create new order
- [ ] Can view order details
- [ ] Can update order
- [ ] Can delete order (without forms)
- [ ] Cannot delete order with forms
- [ ] Can get order statistics
- [ ] Validation works properly

Frontend:
- [ ] Orders page loads without errors
- [ ] Can view orders in table (desktop)
- [ ] Can view orders in cards (mobile)
- [ ] Floating filters work (mobile)
- [ ] Search works with debounce
- [ ] Filters work correctly
- [ ] Can navigate to create order
- [ ] Create form works with validation
- [ ] Can edit order
- [ ] Can view order details
- [ ] Can delete order
- [ ] Status badges show correct colors
- [ ] Responsive on all screen sizes

---

## 🎯 Module 12: Form/Job Management (MOST CRITICAL)

### Overview
Forms (Production Jobs) are the heart of the system. Each form represents a specific production task assigned to a machine with operators and materials.

**Complexity**: HIGH - This is the most complex module with multiple relationships and state management.

### Time Estimate: 15 hours

---

### Backend Development (7 hours)

#### FormController Features Needed:
- Full CRUD operations
- Machine assignment
- Operator assignment (multiple)
- Material assignment (multiple)
- Form status management (8 states)
- Relationship with sections/orders
- Filtering and search
- Statistics

**Form Statuses**:
1. `job_pending` - Created, not started
2. `make_ready` - Preparation phase
3. `job_started` - Production running
4. `paused` - Temporarily stopped
5. `stopped` - Halted with reason
6. `job_completed` - Production finished
7. `quality_verified` - QC approved
8. `line_cleared` - Ready for next job

---

### Frontend Development (8 hours)

#### Forms/Index.vue Features:
- List all forms with advanced filters
- Filter by machine, operator, status, date
- Quick status change
- Real-time status updates
- Machine assignment indicator
- Operator avatars
- Material list
- Progress indicators

#### Forms/Create.vue Features:
- Select section/order
- Form name and details
- Schedule date/time
- Machine selection
- Operator selection (multi-select)
- Material selection (multi-select with quantities)
- Validation
- Step-by-step wizard (optional)

#### Forms/Show.vue Features (MOST IMPORTANT):
This is the production operations screen!

**Operation Panel** (Main feature):
- Make Ready button → Start make ready phase
- Start Production button → Begin production
- Pause button → Pause with reason modal
- Resume button → Resume from pause
- Stop button → Stop with reason modal
- Complete button → Mark as completed

**Button State Machine**:
```
pending → [Make Ready] → make_ready
make_ready → [Start Production] → job_started
job_started → [Pause] → paused
paused → [Resume] → job_started
job_started → [Stop] → stopped
stopped → Cannot resume (new form needed)
job_started → [Complete] → job_completed
```

**Other sections**:
- Form details display
- Machine information
- Operator list with shift info
- Material consumption tracking
- Button action history (timeline)
- DMI data display
- Quality check section
- Line clearance section
- Notes section

---

## 🎯 Module 13: Production Operations

### Overview
Handles all real-time production operations, button actions, DMI data entry, and operator logins.

### Time Estimate: 12 hours

### Backend Components:

#### 1. FormButtonActionController
- Record all button clicks
- Track timestamps
- Store reasons (for pause/stop)
- Calculate durations
- Maintain state machine

#### 2. DmiDataController
- Record production data (speed, good/bad quantity)
- Link to forms and machines
- Calculate efficiency
- Generate reports

#### 3. LoginDetailController
- Operator machine login/logout
- Track shift assignments
- Multiple operators per machine
- Active session management

### Frontend Components:

#### OperationPanel.vue (Core component)
- Dynamic button states
- Confirmation dialogs
- Reason selection modals
- Real-time updates
- Visual feedback
- Loading states

#### ButtonHistory.vue
- Chronological action list
- Duration calculations
- User information
- Reason display

#### DmiDataEntry.vue
- Real-time data input
- Counter displays
- Validation
- Auto-save option

---

## 🎯 Module 14: Quality Control

### Time Estimate: 8 hours

### Features:
- QC checklist management
- Manual verification forms
- Approve/Reject workflow
- Line clearance process
- QC history and reports

---

## 🎯 Module 15: Supporting Features

### Time Estimate: 6 hours

### Features:
- Sticky notes for forms/machines
- Press notes
- Daily tasks management
- Standard production values
- Document uploads

---

## 📋 Development Order

### Week 4 (Oct 21-25):
**Days 1-2**: Order Management (10 hours)
- Backend: Controller, Request, Resource, Routes (4 hours)
- Frontend: All pages (6 hours)
- Testing (included)

**Days 3-4**: Form Management - Part 1 (8 hours)
- Backend: FormController with CRUD (4 hours)
- Frontend: Index and Create pages (4 hours)

**Day 5**: Form Management - Part 2 (7 hours)
- Frontend: Show page with basic layout (3 hours)
- Frontend: Edit page (2 hours)
- Testing forms CRUD (2 hours)

### Week 5 (Oct 28-Nov 1):
**Days 1-2**: Production Operations (12 hours)
- Backend: ButtonAction, DMI, LoginDetail controllers (6 hours)
- Frontend: OperationPanel component (4 hours)
- Frontend: ButtonHistory, DMI entry (2 hours)

**Day 3**: Quality Control (8 hours)
- Backend: QC controllers (3 hours)
- Frontend: QC pages (4 hours)
- Testing (1 hour)

**Days 4-5**: Supporting Features (6 hours)
- Backend: Notes, Tasks controllers (2 hours)
- Frontend: Notes and Tasks pages (3 hours)
- Final testing and bug fixes (1 hour)

---

## 🧪 Testing Strategy

### For Each Module:

#### Backend Testing:
1. Test with Postman/Insomnia
2. Verify all CRUD operations
3. Test validation errors
4. Test permission checks
5. Test relationships loading
6. Test edge cases

#### Frontend Testing:
1. Desktop view (Chrome, Firefox, Safari)
2. Mobile view (responsive mode)
3. Form submissions
4. Validation messages
5. Error handling
6. Loading states
7. Navigation
8. Permission-based UI

---

## 💡 Development Tips

### Order Management:
- Keep it simple initially
- Focus on core CRUD first
- Add advanced features later
- Test relationship with sections

### Form Management:
- This is complex - take your time
- Build CRUD first, operations later
- Test state machine thoroughly
- Use clear naming for statuses
- Comment complex logic

### Production Operations:
- Button state machine is critical
- Test all state transitions
- Validate before state changes
- Log all actions
- Use transactions
- Real-time updates are key

### Testing:
- Test one feature at a time
- Use sample data
- Test edge cases
- Mobile testing is critical
- Performance matters

---

## 🎨 UI/UX Guidelines

### Colors for Statuses:
```css
pending: gray-400
make_ready: yellow-500
job_started: green-500
paused: orange-500
stopped: red-500
job_completed: blue-500
quality_verified: purple-500
line_cleared: green-600
```

### Button States:
- Disabled: gray with cursor-not-allowed
- Enabled: Primary color with hover effect
- Loading: Show spinner, disable button
- Success: Brief green flash

### Operation Panel Layout:
```
┌─────────────────────────────────┐
│  Form Status Badge              │
├─────────────────────────────────┤
│  ┌────┐ ┌────┐ ┌────┐          │
│  │ MR │ │ SP │ │Pause│  Buttons│
│  └────┘ └────┘ └────┘          │
├─────────────────────────────────┤
│  Current Status: Running        │
│  Duration: 2h 35m              │
├─────────────────────────────────┤
│  Action History (Timeline)      │
│  ●────────●────────●            │
└─────────────────────────────────┘
```

---

## 🚀 Success Criteria

### Order Management Complete When:
- [ ] Can create, view, edit, delete orders
- [ ] Can search and filter orders
- [ ] Sections can be linked to orders
- [ ] Status badges work correctly
- [ ] Mobile responsive
- [ ] All tests passing

### Form Management Complete When:
- [ ] Can create, view, edit, delete forms
- [ ] Can assign machines, operators, materials
- [ ] Form status updates correctly
- [ ] Relationships work properly
- [ ] Advanced filters functional
- [ ] Mobile responsive
- [ ] All tests passing

### Production Operations Complete When:
- [ ] Button state machine works correctly
- [ ] All state transitions validated
- [ ] Reasons required for pause/stop
- [ ] Action history logs all events
- [ ] DMI data records properly
- [ ] Operator logins tracked
- [ ] Real-time updates work
- [ ] All tests passing

---

## 📞 Quick Commands

```bash
# Start development
php artisan serve
npm run dev

# Test API endpoint
curl -X GET http://localhost:8000/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN"

# Clear cache
php artisan cache:clear
php artisan config:clear

# Check routes
php artisan route:list | grep orders

# Database refresh (if needed)
php artisan migrate:fresh --seed
```

---

## 🎯 Your Starting Point

**Right Now**: Start with Order Management Module

**Steps**:
1. Create OrderController with all methods
2. Create OrderRequest for validation
3. Create OrderResource for API responses
4. Add routes to api.php
5. Test backend with Postman
6. Create Orders/Index.vue (responsive)
7. Create Orders/Create.vue
8. Create Orders/Edit.vue
9. Create Orders/Show.vue
10. Update Sidebar and Router
11. Test frontend thoroughly
12. Move to Form Management

---

## 🏆 Motivation

You've built 10 master modules successfully! You have:
- ✅ Established patterns
- ✅ Responsive designs working
- ✅ API structure solid
- ✅ Component library growing

Now it's time to build the core that makes it all work together!

**You've got this!** 🚀

---

*Production Core Guide v1.0*  
*Created: October 19, 2025*  
*Ready to build the production heart of Phoenix!*
