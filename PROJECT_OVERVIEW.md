# Phoenix Manufacturing Management System - Laravel + Vue.js Implementation

## Project Overview

This document outlines the complete implementation plan for migrating the Phoenix Backend Manufacturing Management System from Node.js/TypeScript/TypeORM to Laravel 11 with Vue.js 3.

### Original System Technology Stack
- **Backend**: Node.js + Express.js + TypeScript
- **ORM**: TypeORM
- **Database**: MySQL
- **Authentication**: JWT + bcrypt

### Target System Technology Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue.js 3 + Composition API
- **UI Framework**: Tailwind CSS + Inertia.js
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **State Management**: Pinia

---

## System Purpose

Phoenix is a comprehensive **Manufacturing Management System** designed for printing and manufacturing facilities. The system manages:

1. **Production Planning & Scheduling**
   - Order management with job cards
   - Section-based production breakdown
   - Form (job) creation and assignment

2. **Real-time Production Monitoring**
   - Machine integration (DMI - Device Machine Integration)
   - Live production tracking
   - Quality metrics monitoring

3. **User & Access Management**
   - Role-based access control (59 granular permissions)
   - Three user types: Operator, Supervisor, Admin
   - Machine and shift-based user assignments

4. **Quality Control**
   - Multi-stage verification workflows
   - Manual QC verification
   - Quality standards management

5. **Master Data Management**
   - Machine master
   - Material master
   - Process management
   - Status/Sub-status hierarchies

---

## Key Business Workflows

### 1. Order to Production Flow
```
Customer Order → Sections → Forms (Jobs) → Machine Assignment → 
Material Specification → Scheduling → Production Execution → 
Quality Verification → Line Clearance → Delivery
```

### 2. Production Execution Flow
```
Login (User + Machine + Shift) → Select Form → Start Button → 
Make Ready → Production Start → Real-time Data Collection → 
Stop/Pause/Resume → Complete → QC Verification → Form Closure
```

### 3. User Session Management
```
User Login → Select Machine & Shift → Create Login Session → 
Generate JWT Token → Track Activities → Logout → Close Session
```

---

## Core Entities (30 Total)

### Primary Business Entities
1. **User** - System users with roles and permissions
2. **User_permissions** - Role-based permission matrix (59 permissions)
3. **Login_details** - User session tracking
4. **Order** - Customer orders with job cards
5. **Section** - Order subdivisions
6. **Form** - Production jobs/tasks
7. **Machine** - Production equipment
8. **Material** - Raw materials with specifications
9. **Dmi_data** - Device-Machine Integration data
10. **Status** - Production statuses
11. **SubStatus** - Detailed status breakdown

### Master Data Entities
12. **Shift** - Work shifts
13. **Process** - Production processes
14. **MachineType** - Machine categories
15. **Department** - Organizational departments
16. **ButtonsGroup** - Button operation groups
17. **Buttons** - Operation buttons
18. **QC_master** - Quality control standards

### Supporting Entities
19. **FormButtonActions** - Production operation history
20. **LineClearance** - Line clearance records
21. **ManualQcVerification** - Manual QC records
22. **Sheet** - Sheet size specifications
23. **Tag** - Tagging system
24. **StickyNote** - Production notes
25. **Document** - Document management
26. **DailyTask** - Daily task tracking
27. **DailyMaintainedData** - Maintenance records
28. **PressNotes** - Press operation notes
29. **StandardProduction** - Standard production values
30. **ThirdPartyData** - External system integration

---

## Database Relationships Overview

### User Management
- User → User_permissions (Many-to-One)
- User → Login_details (One-to-Many)
- User → Machine (Many-to-Many)

### Production Management
- Order → Section (One-to-Many)
- Section → Form (One-to-Many)
- Form → Machine (Many-to-Many)
- Form → Material (Many-to-One)
- Form → Dmi_data (One-to-Many)
- Form → User (Many-to-Many - assigned operators)
- Form → Status, SubStatus (Many-to-One)

### Machine & Operations
- Machine → MachineType (Many-to-One)
- Machine → Process (Many-to-One)
- Machine → Dmi_data (One-to-Many)

### Workflow Tracking
- Form → FormButtonActions (One-to-Many)
- Form → LineClearance (One-to-One)
- Form → ManualQcVerification (One-to-Many)

---

## User Types & Permission System

### User Types (Enum)
1. **OPERATOR**
   - Machine operators
   - Limited to assigned machines
   - Basic production operations

2. **SUPER_WISER** (Supervisor)
   - Department-level oversight
   - Production monitoring
   - Quality approval

3. **ADMIN**
   - Full system access
   - User management
   - Master data management

### Permission Categories (59 Total)

#### Dashboard
- dashboard_view

#### Login Management
- login_menu_view, create, update, delete

#### Job Management (4 categories)
- job_menu_* (regular jobs)
- manual_job_menu_* (manual jobs)
- job_creation_* (job creation specific)

#### Quality Control
- quality_menu_view, create, update, delete
- QCMAster_view, create, update, delete

#### User & Role Management
- user_menu_view, create, update, delete
- role_menu_view, create, update, delete

#### Master Data Management
- status_menu_* (Status)
- sub_status_menu_* (Sub-status)
- sheet_size_* (Sheet sizes)
- material_master_* (Materials)
- machine_master_* (Machines)
- machine_type_* (Machine types)
- department_* (Departments)
- process_* (Processes)
- shift_* (Shifts)

#### Operations & Tracking
- standerd_production_* (Standard values)
- alert_* (Alerts)
- tag_* (Tags)
- Button_group_* (Button groups)
- buttons_* (Buttons)
- DMTask_* (Daily maintenance tasks)
- dailyTask_* (Daily tasks)
- DMI_* (Device-Machine Integration)

---

## Enums & Constants

### User_type
- OPERATOR
- SUPER_WISER
- ADMIN

### form_status
- JOB_PENDING
- JOB_STARTED
- JOB_COMPLETED
- OTHER

### job_process
- MAKE_READY
- START
- JOB_STARTED
- STOP
- CLOSE

### Machine_type
- PRE_PRESS
- PRESS
- POST_PRESS
- DIE_CUT
- OTHER

### form_side
- FRONT
- BACK
- OTHER

### Started_From
- MSI
- MANUAL
- OTHER

### status_type
- PRODUCTIVE
- UNPRODUCTIVE
- OTHER

### Active_description
- MAKE_READY
- RUNNING
- PAUSE
- STOP
- OTHER

### GeneralMaintance
- YES
- NO
- OTHER

---

## Critical Features & Business Logic

### 1. Authentication Flow
- Username + Password + Machine + Shift selection
- Generate JWT token (stored in api_key field)
- Create login_details record
- Track session duration
- Automatic logout functionality

### 2. Form Button Operations
**Button States**: START, STOP, PAUSE, RESUME, COMPLETE

**Workflow**:
1. START → Initiates make-ready process
2. MAKE_READY → Setup phase before production
3. START (again) → Begin actual production
4. PAUSE → Temporary halt (with reason)
5. RESUME → Continue from pause
6. STOP → End production (with reason)
7. COMPLETE → Finalize job

**Tracking**:
- Each button action stored in FormButtonActions
- button_history JSON field in Form
- Timestamps for all operations
- Reason tracking for stops/pauses

### 3. DMI Integration (Device-Machine Integration)
**Purpose**: Real-time data collection from machines

**Data Collected**:
- Start/end times
- Production speed
- Good/bad linear meters (lm)
- Employee count (UPS)
- Status changes

**Storage**: Dmi_data table linked to Form and Machine

### 4. Quality Control Workflow
1. Job completion triggers QC requirement
2. Manual verification by authorized user
3. quality_verified flag updated
4. Final verification by supervisor
5. Line clearance process
6. Order closure when all forms complete

### 5. Third-party Integration
**Endpoint**: `/third_party/data`

**Purpose**: Import production planning data from external systems

**Data Fields**: 40+ fields including:
- Job details
- Machine assignments
- Material specifications
- Scheduling information

---

## API Structure Overview

### Authentication Routes
- POST `/user/login` - User authentication
- POST `/user/logout` - User logout
- GET `/user/profile` - Get user profile

### User Management Routes
- POST `/user/create` - Create user
- GET `/user/list/:page` - List users (paginated)
- GET `/user/view/:id` - View user details
- POST `/user/update` - Update user
- POST `/user/delete/:id` - Delete user

### Role Management Routes
- POST `/user/create-role` - Create role
- GET `/user/role-list/:page` - List roles
- GET `/user/role-view/:id` - View role details
- POST `/user/update-role` - Update role
- POST `/user/delete-role/:id` - Delete role

### Order Management Routes
- POST `/orders/create` - Create order
- GET `/orders/list/:page` - List orders
- GET `/orders/view/:id` - View order details
- POST `/orders/update` - Update order

### Form (Job) Management Routes
- POST `/form/create` - Create form
- GET `/form/list/:page` - List forms
- GET `/form/view/:id` - View form details
- POST `/form/update` - Update form
- POST `/form/start_button` - Start operation
- POST `/form/stop_button` - Stop operation
- POST `/form/pause_button` - Pause operation
- POST `/form/resume_button` - Resume operation
- POST `/form/create_button` - Complete operation

### Machine Routes
- POST `/machine/create` - Create machine
- GET `/machine/list/:page` - List machines
- GET `/machine/view/:id` - View machine details
- POST `/machine/update` - Update machine
- GET `/machine/stats` - Machine statistics

### Master Data Routes
- Material: `/material/*`
- Status: `/status/*`
- SubStatus: `/sub_status/*`
- Department: `/department/*`
- Process: `/process/*`
- Shift: `/shift/*`
- MachineType: `/machine_type/*`

### Reports & Analytics
- GET `/dashboard/stats` - Dashboard statistics
- GET `/reports/production/:date` - Production reports
- GET `/reports/machine_utilization` - Machine reports
- GET `/form/get_data_power_bi_ik` - Power BI integration

---

## Security Considerations

### Current Issues in Original System
1. ❌ Hardcoded API key for third-party integration
2. ❌ Weak default admin credentials
3. ❌ Many permission checks commented out
4. ❌ DB auto-synchronization enabled

### Required Security Implementations
1. ✅ Environment-based configuration
2. ✅ Strong password policies
3. ✅ Comprehensive permission checks
4. ✅ Input validation & sanitization
5. ✅ Rate limiting
6. ✅ HTTPS enforcement
7. ✅ Audit logging
8. ✅ CSRF protection (Laravel built-in)

---

## Integration Points

### 1. Third-party Planning System
- Import production planning data
- 40+ field mapping
- Batch processing capability

### 2. Machine Devices (DMI)
- Real-time data collection
- Production metrics
- Status updates

### 3. Power BI / Analytics
- Export production data
- Machine utilization reports
- Quality metrics

---

## Development Approach

### Phase 1: Foundation (Week 1-2)
- Database migration setup
- Models & relationships
- Authentication system
- Basic CRUD operations

### Phase 2: Core Features (Week 3-4)
- Order management
- Form/Job management
- Machine operations
- User management

### Phase 3: Advanced Features (Week 5-6)
- Button operations workflow
- DMI integration
- Quality control
- Reports & analytics

### Phase 4: UI/UX (Week 7-8)
- Vue.js components
- Dashboard
- Forms & validation
- Real-time updates

### Phase 5: Testing & Deployment (Week 9-10)
- Unit testing
- Integration testing
- Performance optimization
- Documentation
- Deployment

---

## Success Metrics

### Performance
- API response time < 200ms
- Database query optimization
- Real-time updates < 1s latency

### Quality
- Test coverage > 80%
- Zero critical security vulnerabilities
- Comprehensive documentation

### User Experience
- Intuitive UI/UX
- Mobile responsive
- Minimal training required

---

## Next Steps

1. **Review this documentation** - Ensure all stakeholders understand the scope
2. **Database schema design** - Finalize Laravel migrations
3. **API specification** - Document all endpoints
4. **UI/UX mockups** - Design key screens
5. **Development environment setup** - Configure Laravel + Vue.js
6. **Begin Phase 1 development** - Foundation work

---

*Document Version: 1.0*
*Created: October 18, 2025*
*Status: Initial Planning Phase*
