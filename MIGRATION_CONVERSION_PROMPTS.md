# 🚀 MIGRATION & MODEL CONVERSION PROMPTS

## Complete Guide for Converting TypeORM Entities to Laravel

**Source Location**: `/Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/`  
**Target Location**: `/Users/thamjeedlal/Herd/pheonixFullstack/`  
**References**: 
- TypeORM Entities (original working code)
- DATABASE_SCHEMA.md (comprehensive documentation)

---

## 📋 CONVERSION ORDER

Follow this exact order to respect foreign key dependencies:

### PHASE 1: Base Tables (No Dependencies) - 10 Tables
1. user_permissions
2. machine_types
3. departments
4. shifts
5. processes
6. statuses
7. button_groups
8. qc_masters
9. sheets
10. tags

### PHASE 2: User & Dependencies - 6 Tables
11. users
12. sub_statuses (depends on statuses)
13. buttons (depends on button_groups)
14. machines (depends on machine_types, processes)
15. materials (depends on departments)
16. login_details (depends on users, machines, shifts)

### PHASE 3: Order Hierarchy - 3 Tables
17. orders (depends on materials, processes, users)
18. sections (depends on orders)
19. forms (depends on sections, materials, processes, statuses, etc.)

### PHASE 4: Pivot Tables - 3 Tables
20. form_machine
21. form_user
22. machine_user

### PHASE 5: Transaction Tables - 11 Tables
23. dmi_data
24. form_button_actions
25. line_clearances
26. manual_qc_verifications
27. sticky_notes
28. press_notes
29. documents
30. daily_tasks
31. daily_maintained_data
32. standard_productions
33. third_party_data

---

## 🔥 MASTER PROMPT - START HERE

```
I'm converting the Phoenix Manufacturing System from Node.js/TypeORM to Laravel.

PROJECT PATHS:
- Original Backend: /Users/thamjeedlal/Herd/phoneix-backend-main/
- Laravel Project: /Users/thamjeedlal/Herd/pheonixFullstack/
- Entity Files: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/
- Documentation: /Users/thamjeedlal/Herd/pheonixFullstack/DATABASE_SCHEMA.md

TASK: Convert TypeORM entities to Laravel migrations and models

Let's work through all 33 tables in dependency order, starting with PHASE 1.

For each table, I need:
1. Complete Laravel migration file (up & down methods)
2. Complete Laravel model with relationships
3. Validation rules (for FormRequest classes)

Let's start with TABLE 1: user_permissions

Please provide:
A) Complete migration code for: database/migrations/XXXX_create_user_permissions_table.php
B) Complete model code for: app/Models/UserPermission.php

Source files to reference:
- /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/User_permissions.ts
- /Users/thamjeedlal/Herd/pheonixFullstack/DATABASE_SCHEMA.md (user_permissions section)

Requirements:
- Match exact field names from TypeORM entity
- Include all 59 permission boolean fields
- Add proper indexes (role_name unique)
- Include timestamps
- Model should have $fillable, $casts, and relationships
- Add PHPDoc comments

Show complete code ready to paste.
```

---

## 📝 INDIVIDUAL TABLE PROMPTS

Use these prompts for each table in order:

---

### PHASE 1: BASE TABLES

#### 1️⃣ USER PERMISSIONS

```
Convert user_permissions table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/User_permissions.ts
Reference: DATABASE_SCHEMA.md - user_permissions section

Provide:
1. Migration: database/migrations/XXXX_create_user_permissions_table.php
   - 59 boolean permission fields
   - role_name (unique)
   - status (boolean, default true)
   - timestamps

2. Model: app/Models/UserPermission.php
   - $fillable array (all fields)
   - $casts array (boolean fields)
   - hasMany relationship to User
   - Helper method: hasPermission($permission)

Show complete code.
```

---

#### 2️⃣ MACHINE TYPES

```
Convert machine_types table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/machine_type.ts
Reference: DATABASE_SCHEMA.md - machine_types section

Provide:
1. Migration: database/migrations/XXXX_create_machine_types_table.php
   - name (unique)
   - description (nullable)
   - status (boolean)
   - timestamps

2. Model: app/Models/MachineType.php
   - $fillable array
   - $casts array
   - hasMany relationship to Machine

Show complete code.
```

---

#### 3️⃣ DEPARTMENTS

```
Convert departments table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/department.ts
Reference: DATABASE_SCHEMA.md - departments section

Provide:
1. Migration: database/migrations/XXXX_create_departments_table.php
   - name (unique)
   - description (nullable)
   - status (boolean)
   - created_by (foreign key to users, nullable)
   - timestamps

2. Model: app/Models/Department.php
   - $fillable array
   - hasMany relationship to Material
   - belongsTo relationship to User (as creator)

Show complete code.
```

---

#### 4️⃣ SHIFTS

```
Convert shifts table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/shift.ts
Reference: DATABASE_SCHEMA.md - shifts section

Provide:
1. Migration: database/migrations/XXXX_create_shifts_table.php
   - shift_id (unique)
   - shift_name
   - start_time (time)
   - end_time (time)
   - status (boolean)
   - created_by (foreign key)
   - timestamps

2. Model: app/Models/Shift.php
   - $fillable array
   - $casts array (time casts)
   - hasMany relationship to LoginDetail
   - belongsTo relationship to User (creator)

Show complete code.
```

---

#### 5️⃣ PROCESSES

```
Convert processes table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/process.ts
Reference: DATABASE_SCHEMA.md - processes section

Provide:
1. Migration: database/migrations/XXXX_create_processes_table.php
   - name (unique)
   - msi_id (unique)
   - status (boolean)
   - created_by (foreign key)
   - timestamps

2. Model: app/Models/Process.php
   - $fillable array
   - hasMany relationships to: Machine, Order, Form
   - belongsTo relationship to User (creator)

Show complete code.
```

---

#### 6️⃣ STATUSES

```
Convert statuses table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/Status.ts
Reference: DATABASE_SCHEMA.md - statuses section
Also check: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/enums.ts for status_type enum

Provide:
1. Migration: database/migrations/XXXX_create_statuses_table.php
   - status_code (unique)
   - status_name
   - status_type (enum: productive, unproductive, other)
   - remark (text, nullable)
   - img (string, nullable)
   - status (boolean)
   - created_by (foreign key)
   - timestamps

2. Model: app/Models/Status.php
   - $fillable array
   - $casts array (enum cast for status_type)
   - hasMany relationships to: SubStatus, Form, DmiData
   - belongsTo relationship to User (creator)

Show complete code.
```

---

#### 7️⃣ BUTTON GROUPS

```
Convert button_groups table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/button_group.ts
Reference: DATABASE_SCHEMA.md - button_groups section

Provide:
1. Migration: database/migrations/XXXX_create_button_groups_table.php
   - name (unique)
   - description (nullable)
   - status (boolean)
   - created_by (foreign key)
   - timestamps

2. Model: app/Models/ButtonGroup.php
   - $fillable array
   - hasMany relationships to: Button, Form
   - belongsTo relationship to User (creator)

Show complete code.
```

---

#### 8️⃣ QC MASTERS

```
Convert qc_masters table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/qc_mater.ts
Reference: DATABASE_SCHEMA.md - qc_masters section

Provide:
1. Migration: database/migrations/XXXX_create_qc_masters_table.php
   - qc_code (unique)
   - qc_name
   - description (nullable)
   - status (boolean)
   - created_by (foreign key)
   - timestamps

2. Model: app/Models/QcMaster.php
   - $fillable array
   - hasMany relationship to ManualQcVerification
   - belongsTo relationship to User (creator)

Show complete code.
```

---

#### 9️⃣ SHEETS

```
Convert sheets table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/sheet.ts
Reference: DATABASE_SCHEMA.md - sheets section

Provide:
1. Migration: database/migrations/XXXX_create_sheets_table.php
   - sheet_size (unique)
   - width (nullable)
   - height (nullable)
   - description (nullable)
   - status (boolean)
   - created_by (foreign key)
   - timestamps
   - soft deletes

2. Model: app/Models/Sheet.php
   - $fillable array
   - belongsTo relationship to User (creator)
   - SoftDeletes trait

Show complete code.
```

---

#### 🔟 TAGS

```
Convert tags table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/tag.ts
Reference: DATABASE_SCHEMA.md - tags section

Provide:
1. Migration: database/migrations/XXXX_create_tags_table.php
   - tag_name (unique)
   - description (nullable)
   - color (nullable)
   - status (boolean)
   - created_by (foreign key)
   - timestamps
   - soft deletes

2. Model: app/Models/Tag.php
   - $fillable array
   - belongsTo relationship to User (creator)
   - SoftDeletes trait

Show complete code.
```

---

### PHASE 2: USER & DEPENDENCIES

#### 1️⃣1️⃣ USERS

```
Convert users table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/User.ts
Reference: DATABASE_SCHEMA.md - users section
Also check: enums.ts for User_type enum

IMPORTANT: User model already exists, we need to MODIFY it.

Provide:
1. Migration: database/migrations/XXXX_modify_users_table.php
   - Add fields to existing users table:
   - user_name (unique)
   - phone_no (nullable)
   - is_super_user (boolean)
   - user_type (enum: operator, super_wiser, admin)
   - api_key (nullable) - for JWT token
   - last_login_time (timestamp, nullable)
   - permission_id (foreign key to user_permissions)
   - status (boolean)
   - Keep existing: name, email, password, timestamps

2. Model: app/Models/User.php (complete update)
   - Extend Authenticatable
   - HasApiTokens trait (Sanctum)
   - SoftDeletes trait
   - $fillable array
   - $hidden array (password, api_key)
   - $casts array (user_type enum, timestamps)
   - Relationships:
     * belongsTo: UserPermission
     * hasMany: LoginDetail
     * belongsToMany: Machine, Form
     * hasMany: created records (Order, Form, etc.)
   - Helper methods:
     * hasPermission($permission)
     * isSuperUser()
     * canAccessMachine($machineId)

Show complete code for both files.
```

---

#### 1️⃣2️⃣ SUB STATUSES

```
Convert sub_statuses table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/sub_status.ts
Reference: DATABASE_SCHEMA.md - sub_statuses section

Provide:
1. Migration: database/migrations/XXXX_create_sub_statuses_table.php
   - sub_status_code (unique)
   - sub_status_name
   - status_id (foreign key to statuses) - CASCADE on delete
   - remark (text, nullable)
   - status (boolean)
   - created_by (foreign key)
   - timestamps
   - soft deletes

2. Model: app/Models/SubStatus.php
   - $fillable array
   - belongsTo relationships: Status, User (creator)
   - hasMany relationships: Form, DmiData

Show complete code.
```

---

#### 1️⃣3️⃣ BUTTONS

```
Convert buttons table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/buttons.ts
Reference: DATABASE_SCHEMA.md - buttons section

Provide:
1. Migration: database/migrations/XXXX_create_buttons_table.php
   - button_name
   - button_group_id (foreign key to button_groups)
   - description (nullable)
   - status (boolean)
   - created_by (foreign key)
   - timestamps
   - soft deletes

2. Model: app/Models/Button.php
   - $fillable array
   - belongsTo relationships: ButtonGroup, User (creator)
   - hasMany relationship: FormButtonAction

Show complete code.
```

---

#### 1️⃣4️⃣ MACHINES (COMPLEX)

```
Convert machines table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/machine.ts
Reference: DATABASE_SCHEMA.md - machines section

Provide:
1. Migration: database/migrations/XXXX_create_machines_table.php
   - machine_id (unique)
   - machine_name
   - description (nullable)
   - Specifications (all nullable):
     * min_width, max_width, min_height, max_height
     * min_gsm, max_gsm
   - Capacity:
     * per_day_impression, per_hour_impression
     * make_ready_time, meter_per_impression
   - Costing:
     * minimum_cost, per_hour_cost
   - devise_url (nullable) - for DMI integration
   - remarks (text, nullable)
   - status (boolean)
   - Foreign keys:
     * machine_type_id → machine_types
     * process_id → processes
     * created_by → users
   - timestamps
   - soft deletes
   - Indexes on: machine_id, machine_name, machine_type_id

2. Model: app/Models/Machine.php
   - $fillable array
   - belongsTo: MachineType, Process, User (creator)
   - belongsToMany: User, Form
   - hasMany: DmiData, LoginDetail
   - Methods:
     * isAvailable()
     * getCurrentForm()
     * getUtilizationRate($dateFrom, $dateTo)

Show complete code.
```

---

#### 1️⃣5️⃣ MATERIALS

```
Convert materials table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/material.ts
Reference: DATABASE_SCHEMA.md - materials section

Provide:
1. Migration: database/migrations/XXXX_create_materials_table.php
   - material_id (unique)
   - name
   - gsm (nullable)
   - coating (boolean)
   - unit (nullable)
   - utilization (nullable)
   - description (nullable)
   - status (boolean)
   - department_id (foreign key to departments)
   - created_by (foreign key)
   - timestamps
   - soft deletes
   - Indexes on: material_id, name

2. Model: app/Models/Material.php
   - $fillable array
   - $casts array
   - belongsTo: Department, User (creator)
   - hasMany: Order, Form

Show complete code.
```

---

#### 1️⃣6️⃣ LOGIN DETAILS

```
Convert login_details table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/login_details.ts
Reference: DATABASE_SCHEMA.md - login_details section

Provide:
1. Migration: database/migrations/XXXX_create_login_details_table.php
   - user_id (foreign key to users) - CASCADE
   - machine_id (foreign key to machines, nullable)
   - shift_id (foreign key to shifts, nullable)
   - status (boolean) - true = active, false = logged out
   - log_out_time (timestamp, nullable)
   - timestamps (created_at = login time)
   - Indexes on: user_id, machine_id, shift_id, status, created_at

2. Model: app/Models/LoginDetail.php
   - $fillable array
   - $casts array (timestamps)
   - belongsTo: User, Machine, Shift
   - Methods:
     * logout() - sets status to false, log_out_time
     * getDuration() - calculates session duration
     * isActive()

Show complete code.
```

---

### PHASE 3: ORDER HIERARCHY

#### 1️⃣7️⃣ ORDERS

```
Convert orders table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/order.ts
Reference: DATABASE_SCHEMA.md - orders section

Provide:
1. Migration: database/migrations/XXXX_create_orders_table.php
   - job_card_no (unique)
   - client_name
   - pro_name (nullable)
   - ref_no (nullable)
   - prod_details (text)
   - job_details (text)
   - finish_size
   - finish_quantity
   - delivery_date (date)
   - production_start (date)
   - remark (text, nullable)
   - status (boolean)
   - closed (boolean) - order completion
   - Foreign keys:
     * material_id → materials
     * process_id → processes
     * created_by → users
   - timestamps
   - soft deletes
   - Indexes on: job_card_no, client_name, delivery_date, status, closed

2. Model: app/Models/Order.php
   - $fillable array
   - $casts array (dates)
   - belongsTo: Material, Process, User (creator)
   - hasMany: Section
   - Methods:
     * getProgressPercentage()
     * getTotalForms()
     * getCompletedForms()
     * canClose()

Show complete code.
```

---

#### 1️⃣8️⃣ SECTIONS

```
Convert sections table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/section.ts
Reference: DATABASE_SCHEMA.md - sections section

Provide:
1. Migration: database/migrations/XXXX_create_sections_table.php
   - section_id (unique)
   - order_id (foreign key to orders) - CASCADE
   - status (boolean)
   - created_by (foreign key)
   - timestamps
   - soft deletes
   - Indexes on: section_id, order_id

2. Model: app/Models/Section.php
   - $fillable array
   - belongsTo: Order, User (creator)
   - hasMany: Form
   - Methods:
     * getFormsCount()
     * getCompletedFormsCount()

Show complete code.
```

---

#### 1️⃣9️⃣ FORMS (MOST COMPLEX)

```
Convert forms table to Laravel - THIS IS THE MOST COMPLEX TABLE:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/form.ts
Reference: DATABASE_SCHEMA.md - forms section
Also check: enums.ts for form_status, job_process, form_side, Started_From enums

Provide:
1. Migration: database/migrations/XXXX_create_forms_table.php
   
   Fields to include:
   - msi_id (unique, nullable)
   - form_name (nullable)
   - section_id (foreign key to sections, nullable)
   
   Production Specifications:
   - sheet_quantity, sheet_size, glmp_speed, mr_time, mr_speed (all nullable)
   - no_of_labours (integer, nullable)
   - ups (integer, nullable)
   
   Quantity Tracking:
   - good_quantity, bad_quantity, total_quantity, wast_qty (all nullable)
   - excepted_qty, multiply_on_save (nullable)
   
   Details:
   - description (text, nullable)
   - coating (boolean)
   - is_manual (boolean)
   - schedule_date (date)
   
   Status & Verification:
   - status (boolean)
   - form_status (enum: job_pending, job_started, job_completed, other)
   - job_process (enum: make_ready, start, job_started, stop, close)
   - form_side (enum: front, back, other)
   - started_from (enum: msi, manual, other)
   - quality_verified (boolean)
   - verified (boolean)
   - line_cleared_success (boolean)
   
   Button Operations:
   - button_status (nullable)
   - ongoing_process_name (nullable)
   - button_history (json, nullable)
   - stop_reason (text, nullable)
   - pause_reason (text, nullable)
   
   Foreign Keys:
   - material_id → materials
   - process_id → processes
   - status_id → statuses
   - sub_status_id → sub_statuses
   - button_group_id → button_groups
   - worked_by_login_detail_id → login_details
   - created_by, updated_by, q_verified_by → users
   
   Timestamps:
   - l_v_time (last verification time)
   - created_at, updated_at
   - soft deletes
   
   Indexes on: msi_id, form_status, job_process, schedule_date, section_id, quality_verified

2. Model: app/Models/Form.php
   - $fillable array (all fields)
   - $casts array (enums, json, dates, booleans)
   - belongsTo relationships: Section, Material, Process, Status, SubStatus, ButtonGroup, LoginDetail (workedBy), User (creator, updater, qcVerifier)
   - belongsToMany: Machine, User (assigned operators)
   - hasMany: DmiData, FormButtonAction
   - hasOne: LineClearance
   - Methods:
     * startButton()
     * stopButton($reason)
     * pauseButton($reason)
     * resumeButton()
     * completeButton()
     * canStart(), canStop(), canPause(), canResume(), canComplete()
     * getEfficiency()
     * getQualityRate()
     * addToButtonHistory($action, $data)
   
Show complete code for both files.
```

---

### PHASE 4: PIVOT TABLES

#### 2️⃣0️⃣ FORM-MACHINE PIVOT

```
Create form_machine pivot table:

Reference: DATABASE_SCHEMA.md - pivot tables section

Provide:
Migration: database/migrations/XXXX_create_form_machine_table.php
   - id
   - form_id (foreign key to forms) - CASCADE
   - machine_id (foreign key to machines) - CASCADE
   - created_at
   - Unique constraint on (form_id, machine_id)
   - Indexes on both foreign keys

No model needed (Laravel handles automatically).
Show complete migration code.
```

---

#### 2️⃣1️⃣ FORM-USER PIVOT

```
Create form_user pivot table:

Reference: DATABASE_SCHEMA.md - pivot tables section

Provide:
Migration: database/migrations/XXXX_create_form_user_table.php
   - id
   - form_id (foreign key to forms) - CASCADE
   - user_id (foreign key to users) - CASCADE
   - created_at
   - Unique constraint on (form_id, user_id)
   - Indexes on both foreign keys

No model needed.
Show complete migration code.
```

---

#### 2️⃣2️⃣ MACHINE-USER PIVOT

```
Create machine_user pivot table:

Reference: DATABASE_SCHEMA.md - pivot tables section

Provide:
Migration: database/migrations/XXXX_create_machine_user_table.php
   - id
   - machine_id (foreign key to machines) - CASCADE
   - user_id (foreign key to users) - CASCADE
   - created_at
   - Unique constraint on (machine_id, user_id)
   - Indexes on both foreign keys

No model needed.
Show complete migration code.
```

---

### PHASE 5: TRANSACTION TABLES

#### 2️⃣3️⃣ DMI DATA

```
Convert dmi_data table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/dmi_data.ts
Reference: DATABASE_SCHEMA.md - dmi_data section
Check enums.ts for: Active_description, GeneralMaintance

Provide:
1. Migration: database/migrations/XXXX_create_dmi_data_table.php
   - form_id (foreign key to forms, nullable)
   - machine_id (foreign key to machines, nullable)
   - machine_type_id (foreign key to machine_types, nullable)
   - status_id (foreign key to statuses, nullable)
   - sub_status_id (foreign key to sub_statuses, nullable)
   
   Timing:
   - start_time (datetime, nullable)
   - end_time (datetime, nullable)
   - time (time, nullable) - duration
   
   Production Data:
   - speed, employee_count, good_lm, bad_lm, ups (all nullable)
   
   Status & Description:
   - active_description (enum: make_ready, running, pause, stop, other)
   - general_maintance (enum: yes, no, other)
   - ended_status (nullable)
   
   Flags:
   - status (boolean)
   - is_gen (boolean) - general maintenance flag
   - is_manual (boolean)
   
   Other:
   - remark (text, nullable)
   - order_id (string, nullable)
   - created_by (foreign key)
   - timestamps
   
   Indexes on: form_id, machine_id, start_time, end_time, active_description

2. Model: app/Models/DmiData.php
   - $fillable array
   - $casts array (enums, timestamps)
   - belongsTo: Form, Machine, MachineType, Status, SubStatus, User
   - Methods:
     * getDuration()
     * getEfficiency()

Show complete code.
```

---

#### 2️⃣4️⃣ FORM BUTTON ACTIONS

```
Convert form_button_actions table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/form_action_button.ts
Reference: DATABASE_SCHEMA.md - form_button_actions section

Provide:
1. Migration: database/migrations/XXXX_create_form_button_actions_table.php
   - form_id (foreign key to forms) - CASCADE
   - button_id (foreign key to buttons, nullable)
   - button_group_id (foreign key to button_groups, nullable)
   - action_type (string) - START, STOP, PAUSE, RESUME, COMPLETE
   - reason (text, nullable)
   - performed_by (foreign key to users, nullable)
   - login_detail_id (foreign key to login_details, nullable)
   - created_at (timestamp only)
   
   Indexes on: form_id, action_type, created_at

2. Model: app/Models/FormButtonAction.php
   - $fillable array
   - belongsTo: Form, Button, ButtonGroup, User (performer), LoginDetail

Show complete code.
```

---

#### 2️⃣5️⃣ LINE CLEARANCES

```
Convert line_clearances table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/line_clearance.ts
Reference: DATABASE_SCHEMA.md - line_clearances section

Provide:
1. Migration: database/migrations/XXXX_create_line_clearances_table.php
   - form_id (foreign key to forms, unique) - CASCADE
   - clearance_notes (text, nullable)
   - cleared_by (foreign key to users, nullable)
   - cleared_at (timestamp, nullable)
   - status (boolean) - clearance status
   - created_at, updated_at

2. Model: app/Models/LineClearance.php
   - $fillable array
   - $casts array
   - belongsTo: Form, User (clearer)

Show complete code.
```

---

#### 2️⃣6️⃣ MANUAL QC VERIFICATIONS

```
Convert manual_qc_verifications table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/manual_qc_verification.ts
Reference: DATABASE_SCHEMA.md - manual_qc_verifications section

Provide:
1. Migration: database/migrations/XXXX_create_manual_qc_verifications_table.php
   - form_id (foreign key to forms) - CASCADE
   - qc_master_id (foreign key to qc_masters, nullable)
   - verification_notes (text, nullable)
   - is_approved (boolean)
   - verified_by (foreign key to users, nullable)
   - verified_at (timestamp, nullable)
   - created_at, updated_at

2. Model: app/Models/ManualQcVerification.php
   - $fillable array
   - $casts array
   - belongsTo: Form, QcMaster, User (verifier)

Show complete code.
```

---

#### 2️⃣7️⃣ STICKY NOTES

```
Convert sticky_notes table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/sticky_note.ts
Reference: DATABASE_SCHEMA.md - sticky_notes section

Provide:
1. Migration: database/migrations/XXXX_create_sticky_notes_table.php
   - title (nullable)
   - content (text)
   - form_id (foreign key to forms, nullable)
   - machine_id (foreign key to machines, nullable)
   - priority (enum: low, medium, high)
   - status (boolean)
   - created_by (foreign key)
   - timestamps
   - soft deletes

2. Model: app/Models/StickyNote.php
   - $fillable array
   - $casts array (priority enum)
   - belongsTo: Form, Machine, User (creator)
   - SoftDeletes trait

Show complete code.
```

---

#### 2️⃣8️⃣ PRESS NOTES

```
Convert press_notes table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/press_notes.ts
Reference: DATABASE_SCHEMA.md - press_notes section

Provide:
1. Migration: database/migrations/XXXX_create_press_notes_table.php
   - form_id (foreign key to forms, nullable)
   - note_content (text)
   - created_by (foreign key)
   - created_at

2. Model: app/Models/PressNote.php
   - $fillable array
   - belongsTo: Form, User (creator)

Show complete code.
```

---

#### 2️⃣9️⃣ DOCUMENTS

```
Convert documents table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/Document.ts
Reference: DATABASE_SCHEMA.md - documents section

Provide:
1. Migration: database/migrations/XXXX_create_documents_table.php
   - file_name
   - file_path
   - file_type (nullable)
   - file_size (integer, nullable)
   - related_entity_type (nullable) - polymorphic type
   - related_entity_id (bigint, nullable) - polymorphic id
   - created_by (foreign key)
   - created_at

2. Model: app/Models/Document.php
   - $fillable array
   - belongsTo: User (creator)
   - morphTo: related (polymorphic)
   - Methods:
     * getUrl()
     * delete() - also delete physical file

Show complete code.
```

---

#### 3️⃣0️⃣ DAILY TASKS

```
Convert daily_tasks table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/daily_task.ts
Reference: DATABASE_SCHEMA.md - daily_tasks section

Provide:
1. Migration: database/migrations/XXXX_create_daily_tasks_table.php
   - task_name
   - task_date (date)
   - assigned_to (foreign key to users, nullable)
   - status (enum: pending, in_progress, completed)
   - created_at

2. Model: app/Models/DailyTask.php
   - $fillable array
   - $casts array (task_date, status enum)
   - belongsTo: User (assignee)

Show complete code.
```

---

#### 3️⃣1️⃣ DAILY MAINTAINED DATA

```
Convert daily_maintained_data table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/daily_maintained_data.ts
Reference: DATABASE_SCHEMA.md - daily_maintained_data section

Provide:
1. Migration: database/migrations/XXXX_create_daily_maintained_data_table.php
   - machine_id (foreign key to machines, nullable)
   - maintenance_date (date)
   - maintenance_notes (text, nullable)
   - performed_by (foreign key to users, nullable)
   - created_at

2. Model: app/Models/DailyMaintainedData.php
   - $fillable array
   - $casts array
   - belongsTo: Machine, User (performer)

Show complete code.
```

---

#### 3️⃣2️⃣ STANDARD PRODUCTIONS

```
Convert standard_productions table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/standerd_production.ts
Reference: DATABASE_SCHEMA.md - standard_productions section

Provide:
1. Migration: database/migrations/XXXX_create_standard_productions_table.php
   - machine_id (foreign key to machines, nullable)
   - standard_value (nullable)
   - description (nullable)
   - status (boolean)
   - created_at

2. Model: app/Models/StandardProduction.php
   - $fillable array
   - belongsTo: Machine

Show complete code.
```

---

#### 3️⃣3️⃣ THIRD PARTY DATA

```
Convert third_party_data table to Laravel:

Source: /Users/thamjeedlal/Herd/phoneix-backend-main/src/entity/third_party_data.ts
Reference: DATABASE_SCHEMA.md - third_party_data section

Provide:
1. Migration: database/migrations/XXXX_create_third_party_data_table.php
   - unq_id (unique, nullable)
   - job_bag_no (nullable)
   - machine_name (nullable)
   - raw_data (json, nullable) - store complete third-party data
   - processed (boolean) - processing status
   - created_at

2. Model: app/Models/ThirdPartyData.php
   - $fillable array
   - $casts array (json cast for raw_data)
   - Methods:
     * process() - convert to Order/Form

Show complete code.
```

---

## 🎯 AFTER ALL MIGRATIONS ARE COMPLETE

```
All 33 migrations and models are now complete. 

Next steps:
1. Run migrations: php artisan migrate
2. Create seeders for default data
3. Test relationships in tinker

Please provide:
1. UserPermissionSeeder - Create 5 default roles
2. MachineTypeSeeder - Create default machine types
3. ShiftSeeder - Create 3 default shifts
4. AdminUserSeeder - Create default admin user

Show complete seeder code for each.
```

---

## 📊 PROGRESS TRACKING

After completing each table, update PROGRESS_TRACKER.md:

```
Update my progress tracker:

Completed migrations:
- user_permissions ✅
- machine_types ✅
- [add completed tables]

Remaining: [number] tables

Update the PROGRESS_TRACKER.md file to reflect current status.
```

---

## ⚠️ IMPORTANT NOTES

1. **Always check enums.ts** for enum values when converting
2. **Preserve exact field names** from TypeORM entities
3. **Follow the dependency order** - don't skip ahead
4. **Test each migration** before moving to next
5. **Update PROGRESS_TRACKER.md** after each completion

---

*Use these prompts one by one in the exact order shown.*  
*Each prompt provides complete, copy-paste ready code.*  
*Total: 33 tables to convert.*
