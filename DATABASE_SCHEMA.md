# Database Schema Documentation - Phoenix Manufacturing System

## Database Design Principles

### Naming Conventions
- **Tables**: Singular form, snake_case (e.g., `user`, `order`, `form`)
- **Primary Keys**: `id` (BIGINT UNSIGNED AUTO_INCREMENT)
- **Foreign Keys**: `{table}_id` (e.g., `user_id`, `machine_id`)
- **Timestamps**: `created_at`, `updated_at` (Laravel standard)
- **Soft Deletes**: `deleted_at` (where applicable)
- **Boolean Fields**: Prefix with `is_` or descriptive name (e.g., `is_super_user`, `status`)

---

## Complete Entity List (30 Tables)

### Core Business Tables
1. users
2. user_permissions
3. login_details
4. orders
5. sections
6. forms
7. machines
8. materials
9. dmi_data

### Master Data Tables
10. statuses
11. sub_statuses
12. shifts
13. processes
14. machine_types
15. departments
16. button_groups
17. buttons
18. qc_masters

### Supporting Tables
19. form_button_actions
20. line_clearances
21. manual_qc_verifications
22. sheets
23. tags
24. sticky_notes
25. documents
26. daily_tasks
27. daily_maintained_data
28. press_notes
29. standard_productions
30. third_party_data

### Pivot Tables
31. form_machine (Many-to-Many: forms ↔ machines)
32. form_user (Many-to-Many: forms ↔ users)
33. machine_user (Many-to-Many: machines ↔ users)

---

## Entity Relationship Diagram

```
┌─────────────┐
│    users    │
└──────┬──────┘
       │
       ├──────────────────────┐
       │                      │
       ▼                      ▼
┌──────────────┐      ┌─────────────────┐
│user_permissions│    │ login_details   │
└──────────────┘      └────────┬────────┘
                               │
                 ┌─────────────┼─────────────┐
                 ▼             ▼             ▼
          ┌──────────┐  ┌──────────┐  ┌──────────┐
          │ machines │  │  shifts  │  │  forms   │
          └──────────┘  └──────────┘  └─────┬────┘
                                             │
                                    ┌────────┼────────┐
                                    ▼        ▼        ▼
                              ┌─────────┐ ┌────────┐ ┌─────────┐
                              │ orders  │ │sections│ │materials│
                              └─────────┘ └────────┘ └─────────┘
```

---

## Critical Relationships Summary

### User → Multiple Entities
- User → User_permissions (Many-to-One)
- User → Login_details (One-to-Many)
- User → Machine (Many-to-Many via machine_user)
- User creates: Orders, Forms, Machines, Materials, etc.

### Order → Section → Form Hierarchy
- Order (1) → Section (Many)
- Section (1) → Form (Many)
- Form → Machine (Many-to-Many)
- Form → Material (Many-to-One)

### Production Tracking Chain
- Form → Dmi_data (One-to-Many)
- Form → FormButtonActions (One-to-Many)
- Form → ManualQcVerification (One-to-Many)
- Form → LineClearance (One-to-One)

### Machine Relationships
- Machine → MachineType (Many-to-One)
- Machine → Process (Many-to-One)
- Machine → Dmi_data (One-to-Many)
- Machine → User (Many-to-Many via machine_user)

---

## Key Database Indexes Strategy

### High-Priority Indexes (Performance Critical)
```sql
-- Users table
CREATE INDEX idx_users_user_name ON users(user_name);
CREATE INDEX idx_users_user_type ON users(user_type);
CREATE INDEX idx_users_status ON users(status);

-- Forms table (Most queried)
CREATE INDEX idx_forms_msi_id ON forms(msi_id);
CREATE INDEX idx_forms_form_status ON forms(form_status);
CREATE INDEX idx_forms_schedule_date ON forms(schedule_date);
CREATE INDEX idx_forms_section_id ON forms(section_id);
CREATE INDEX idx_forms_created_at ON forms(created_at);
CREATE INDEX idx_forms_composite ON forms(form_status, schedule_date, status);

-- Login_details
CREATE INDEX idx_login_details_user_id ON login_details(user_id);
CREATE INDEX idx_login_details_created_at ON login_details(created_at);
CREATE INDEX idx_login_details_status ON login_details(status);

-- Dmi_data
CREATE INDEX idx_dmi_data_form_id ON dmi_data(form_id);
CREATE INDEX idx_dmi_data_machine_id ON dmi_data(machine_id);
CREATE INDEX idx_dmi_data_start_time ON dmi_data(start_time);

-- Orders
CREATE INDEX idx_orders_job_card_no ON orders(job_card_no);
CREATE INDEX idx_orders_delivery_date ON orders(delivery_date);
CREATE INDEX idx_orders_client_name ON orders(client_name);
```

---

## Data Integrity Constraints

### Cascade Delete Rules
- Order deleted → Sections deleted (CASCADE)
- Section deleted → Forms remain but section_id = NULL (SET NULL)
- User deleted → Created records keep reference = NULL (SET NULL)
- Form deleted → FormButtonActions deleted (CASCADE)
- Form deleted → Dmi_data keeps reference = NULL (SET NULL)

### Required Fields (NOT NULL)
- users: user_name, password, name
- orders: job_card_no, client_name, delivery_date
- machines: machine_id, machine_name
- materials: material_id, name
- forms: schedule_date

---

## JSON Fields Usage

### forms.button_history
```json
[
  {
    "action": "START",
    "timestamp": "2025-01-15T10:30:00Z",
    "user_id": 1,
    "login_detail_id": 5
  },
  {
    "action": "PAUSE",
    "timestamp": "2025-01-15T12:00:00Z",
    "user_id": 1,
    "reason": "Lunch break",
    "login_detail_id": 5
  }
]
```

### third_party_data.raw_data
```json
{
  "UnqId": "12345",
  "JobBagNo": "JB-001",
  "MachineName": "Press-01",
  "ClientName": "ABC Corp",
  "ProductName": "Brochure",
  // ... 40+ more fields
}
```

---

## Enum Values Reference

### user_type
- `operator` - Machine operators
- `super_wiser` - Supervisors
- `admin` - Administrators

### form_status
- `job_pending` - Not started
- `job_started` - In progress
- `job_completed` - Completed
- `other` - Other states

### job_process
- `make_ready` - Setup phase
- `start` - Initial start
- `job_started` - Production running
- `stop` - Stopped
- `close` - Closed/completed

### form_side
- `front` - Front side printing
- `back` - Back side printing
- `other` - Both or neither

### started_from
- `msi` - Started from MSI system
- `manual` - Manual start
- `other` - Other source

### status_type
- `productive` - Productive status
- `unproductive` - Non-productive status
- `other` - Other type

### active_description (dmi_data)
- `make_ready` - Setup
- `running` - Production running
- `pause` - Paused
- `stop` - Stopped
- `other` - Other state

### general_maintance (dmi_data)
- `yes` - Maintenance activity
- `no` - Regular operation
- `other` - Other

---

## Migration Order

Execute migrations in this specific order to respect foreign key dependencies:

1. **Base Tables (No dependencies)**
   - user_permissions
   - machine_types
   - departments
   - shifts
   - processes
   - statuses
   - button_groups
   - qc_masters
   - sheets
   - tags

2. **User Tables**
   - users (depends on: user_permissions)

3. **Sub-Master Tables**
   - sub_statuses (depends on: statuses)
   - buttons (depends on: button_groups)
   - machines (depends on: machine_types, processes, users)
   - materials (depends on: departments, users)

4. **Login & Session**
   - login_details (depends on: users, machines, shifts)

5. **Order Hierarchy**
   - orders (depends on: users, materials, processes)
   - sections (depends on: orders, users)

6. **Forms (Complex Dependencies)**
   - forms (depends on: sections, materials, processes, statuses, sub_statuses, button_groups, login_details, users)

7. **Pivot Tables**
   - form_machine (depends on: forms, machines)
   - form_user (depends on: forms, users)
   - machine_user (depends on: machines, users)

8. **Transaction Tables**
   - dmi_data (depends on: forms, machines, machine_types, statuses, sub_statuses, users)
   - form_button_actions (depends on: forms, buttons, button_groups, users, login_details)
   - line_clearances (depends on: forms, users)
   - manual_qc_verifications (depends on: forms, qc_masters, users)

9. **Supporting Tables**
   - sticky_notes (depends on: forms, machines, users)
   - press_notes (depends on: forms, users)
   - documents (depends on: users)
   - daily_tasks (depends on: users)
   - daily_maintained_data (depends on: machines, users)
   - standard_productions (depends on: machines)
   - third_party_data (no dependencies)

---

## Sample Data for Testing

### Default User Permission Roles
```sql
INSERT INTO user_permissions (role_name, status, dashboard_view) VALUES
('Super Admin', TRUE, TRUE),
('Supervisor', TRUE, TRUE),
('Operator', TRUE, TRUE),
('QC Inspector', TRUE, TRUE);

-- Super Admin gets all permissions set to TRUE
UPDATE user_permissions SET
    login_menu_view = TRUE, login_menu_create = TRUE, 
    job_menu_view = TRUE, job_menu_create = TRUE,
    -- ... all other permissions = TRUE
WHERE role_name = 'Super Admin';
```

### Default Machine Types
```sql
INSERT INTO machine_types (name, description, status) VALUES
('PRE_PRESS', 'Pre-press operations', TRUE),
('PRESS', 'Printing press machines', TRUE),
('POST_PRESS', 'Post-press finishing', TRUE),
('DIE_CUT', 'Die cutting machines', TRUE),
('OTHER', 'Other machine types', TRUE);
```

### Default Shifts
```sql
INSERT INTO shifts (shift_id, shift_name, start_time, end_time, status) VALUES
('SHIFT-A', 'Morning Shift', '06:00:00', '14:00:00', TRUE),
('SHIFT-B', 'Afternoon Shift', '14:00:00', '22:00:00', TRUE),
('SHIFT-C', 'Night Shift', '22:00:00', '06:00:00', TRUE);
```

### Default Admin User
```sql
-- Create Super Admin permission role first
INSERT INTO user_permissions (role_name, status, dashboard_view) VALUES
('Super Admin', TRUE, TRUE);

-- Create admin user
INSERT INTO users (
    user_name, 
    name, 
    password, 
    status, 
    is_super_user, 
    user_type, 
    permission_id
) VALUES (
    'admin',
    'System Administrator',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    TRUE,
    TRUE,
    'admin',
    (SELECT id FROM user_permissions WHERE role_name = 'Super Admin')
);
```

---

## Database Performance Optimization

### Query Optimization Guidelines

1. **Use Eager Loading** (Laravel)
```php
// Bad - N+1 queries
$forms = Form::all();
foreach($forms as $form) {
    echo $form->material->name; // Additional query for each form
}

// Good - Single query with joins
$forms = Form::with(['material', 'machines', 'section'])->get();
```

2. **Index Usage**
- Always filter by indexed columns
- Use composite indexes for multiple WHERE conditions
- Avoid SELECT * - specify only needed columns

3. **Pagination**
```php
// Always paginate large result sets
$forms = Form::where('status', true)
    ->orderBy('created_at', 'desc')
    ->paginate(20);
```

4. **Chunking for Large Datasets**
```php
// Process large datasets in chunks
Form::where('form_status', 'job_completed')
    ->chunk(100, function($forms) {
        foreach($forms as $form) {
            // Process form
        }
    });
```

---

## Database Backup Strategy

### Backup Schedule
- **Full Backup**: Daily at 2:00 AM
- **Incremental Backup**: Every 6 hours
- **Transaction Log Backup**: Every 15 minutes

### Critical Tables Priority
1. **High Priority** (Backup immediately on change)
   - forms
   - dmi_data
   - form_button_actions
   - orders

2. **Medium Priority** (Daily backup sufficient)
   - users
   - login_details
   - machines
   - materials

3. **Low Priority** (Weekly backup sufficient)
   - user_permissions
   - machine_types
   - departments
   - shifts

---

## Data Retention Policy

### Production Data
- **Forms**: Keep 2 years, then archive
- **Dmi_data**: Keep 1 year, then archive
- **Form_button_actions**: Keep 1 year, then archive
- **Orders**: Keep 3 years, then archive

### Session Data
- **Login_details**: Keep 6 months, then purge
- **Sticky_notes**: Keep 3 months, then purge

### Master Data
- Never delete, only soft delete with status flag
- Keep full audit trail

---

## Security Considerations

### Sensitive Data
- **users.password**: Bcrypt hashed (Laravel default)
- **users.api_key**: JWT token, expires after logout
- Never store plain text passwords

### Audit Trail
All tables include:
- `created_at` - Record creation timestamp
- `updated_at` - Last modification timestamp
- `created_by` - User who created record
- `deleted_at` - Soft delete timestamp (where applicable)

### SQL Injection Prevention
- Always use Laravel Query Builder or Eloquent ORM
- Never concatenate raw SQL with user input
- Use parameter binding for all dynamic queries

---

## Common Query Patterns

### 1. Get Active Forms for Machine
```sql
SELECT f.*, m.machine_name, mat.name as material_name
FROM forms f
INNER JOIN form_machine fm ON f.id = fm.form_id
INNER JOIN machines m ON fm.machine_id = m.id
LEFT JOIN materials mat ON f.material_id = mat.id
WHERE m.machine_id = ?
AND f.form_status = 'job_started'
AND f.status = TRUE;
```

### 2. Get User's Production History
```sql
SELECT f.*, COUNT(fba.id) as button_actions_count
FROM forms f
INNER JOIN form_user fu ON f.id = fu.form_id
LEFT JOIN form_button_actions fba ON f.id = fba.form_id
WHERE fu.user_id = ?
AND f.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY f.id
ORDER BY f.created_at DESC;
```

### 3. Machine Utilization Report
```sql
SELECT 
    m.machine_name,
    COUNT(DISTINCT f.id) as total_jobs,
    SUM(CASE WHEN f.form_status = 'job_completed' THEN 1 ELSE 0 END) as completed_jobs,
    SUM(CAST(f.good_quantity AS UNSIGNED)) as total_good_quantity,
    SUM(CAST(f.bad_quantity AS UNSIGNED)) as total_bad_quantity
FROM machines m
LEFT JOIN form_machine fm ON m.id = fm.machine_id
LEFT JOIN forms f ON fm.form_id = f.id
WHERE f.schedule_date BETWEEN ? AND ?
GROUP BY m.id
ORDER BY total_jobs DESC;
```

### 4. Daily Production Summary
```sql
SELECT 
    DATE(f.schedule_date) as production_date,
    COUNT(f.id) as total_forms,
    SUM(CASE WHEN f.form_status = 'job_completed' THEN 1 ELSE 0 END) as completed_forms,
    SUM(CAST(f.good_quantity AS UNSIGNED)) as total_good_quantity,
    AVG(CAST(f.glmp_speed AS UNSIGNED)) as avg_speed
FROM forms f
WHERE f.schedule_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DATE(f.schedule_date)
ORDER BY production_date DESC;
```

---

## Database Maintenance Tasks

### Daily Tasks
- Check for long-running queries
- Monitor table sizes and growth
- Verify backup completion
- Check replication status (if applicable)

### Weekly Tasks
- Optimize frequently queried tables
- Analyze slow query log
- Update table statistics
- Review and clean up old sessions

### Monthly Tasks
- Rebuild indexes
- Archive old data
- Review and optimize database schema
- Performance benchmarking

---

## Troubleshooting Common Issues

### Issue: Slow Form Listing
**Solution**: Ensure composite index exists
```sql
CREATE INDEX idx_forms_composite 
ON forms(form_status, schedule_date, status);
```

### Issue: Deadlocks on Form Updates
**Solution**: Update in consistent order, use row-level locking
```php
DB::transaction(function () {
    $form = Form::lockForUpdate()->find($id);
    $form->update($data);
});
```

### Issue: Large DMI Data Table
**Solution**: Implement partitioning by date
```sql
ALTER TABLE dmi_data
PARTITION BY RANGE (YEAR(created_at)) (
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION p_future VALUES LESS THAN MAXVALUE
);
```

---

*Document Version: 1.0*
*Last Updated: October 18, 2025*
*Total Tables: 33 (30 main + 3 pivot)*
