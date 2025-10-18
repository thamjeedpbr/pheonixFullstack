# Phoenix Manufacturing System - Seeder Documentation

## 📋 Overview

Complete database seeders for the Phoenix Manufacturing System with realistic manufacturing data.

---

## 🌱 Seeders Created (10 Total)

### 1. **UserPermissionSeeder**
Creates 5 role-based permission templates:
- **Super Admin** - Full access to all features
- **Supervisor** - View and manage production
- **Operator** - Basic production operations
- **QC Inspector** - Quality control focus
- **Viewer** - Read-only access

**Records:** 5 roles with 59 permission fields each

---

### 2. **MachineTypeSeeder**
Creates machine type categories:
- PRE_PRESS - Plate making equipment
- PRESS - Main printing presses
- POST_PRESS - Finishing equipment
- DIE_CUT - Die cutting machines
- OTHER - Miscellaneous equipment

**Records:** 5 machine types

---

### 3. **DepartmentSeeder**
Creates material departments:
- Paper Stock
- Ink & Chemicals
- Plates & Films
- Binding Materials
- Finishing Materials

**Records:** 5 departments

---

### 4. **ShiftSeeder**
Creates work shifts:
- **SHIFT-A** - Morning (06:00 - 14:00)
- **SHIFT-B** - Afternoon (14:00 - 22:00)
- **SHIFT-C** - Night (22:00 - 06:00)

**Records:** 3 shifts

---

### 5. **ProcessSeeder**
Creates manufacturing processes:
- Plate Making
- Offset Printing
- Die Cutting
- Lamination
- Binding
- UV Coating
- Folding

**Records:** 7 processes

---

### 6. **StatusSeeder**
Creates production statuses:
- Running (productive)
- Make Ready (productive)
- Breakdown (stop)
- Maintenance (stop)
- No Job (stop)
- Quality Check (general)
- Material Shortage (stop)
- Break (stop)

**Records:** 8 statuses

---

### 7. **ButtonSeeder**
Creates action buttons:
- Start Job
- Pause Job (stop button)
- Stop Job (stop button)
- Complete Job
- Make Ready Start
- Make Ready End
- Breakdown (stop button)

**Records:** 7 buttons

---

### 8. **UserSeeder**
Creates default users:
- **admin** - Super Admin (admin)
- **supervisor1** - Supervisor (super_wiser)
- **operator1** - Operator (operator)
- **operator2** - Operator (operator)

**Records:** 4 users  
**Default Password:** password (for all users)

---

### 9. **MaterialSeeder**
Creates printing materials:
- Art Paper 130 GSM (coated)
- Art Paper 170 GSM (coated)
- Maplitho 80 GSM (uncoated)
- Offset Ink - Cyan
- Offset Ink - Magenta
- Offset Ink - Yellow
- Offset Ink - Black

**Records:** 7 materials

---

### 10. **MachineSeeder**
Creates production machines:
- **MACH-001** - Heidelberg Speedmaster 4 Color (Press)
- **MACH-002** - Komori Lithrone 5 Color (Press)
- **MACH-003** - Bobst Die Cutter SP102 (Die Cut)
- **MACH-004** - Lamination Machine LM-1100 (Post-Press)

**Records:** 4 machines with full specifications

---

## 🚀 How to Run Seeders

### Option 1: Run All Seeders
```bash
cd /Users/thamjeedlal/Herd/pheonixFullstack
php artisan db:seed
```

### Option 2: Fresh Migration + Seed
```bash
php artisan migrate:fresh --seed
```

### Option 3: Run Specific Seeder
```bash
php artisan db:seed --class=UserPermissionSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=MachineSeeder
```

---

## 📊 Seeding Order (Important!)

The seeders MUST run in this order due to foreign key constraints:

1. ✅ UserPermissionSeeder (no dependencies)
2. ✅ MachineTypeSeeder (no dependencies)
3. ✅ DepartmentSeeder (no dependencies)
4. ✅ ShiftSeeder (no dependencies)
5. ✅ ProcessSeeder (no dependencies)
6. ✅ StatusSeeder (no dependencies)
7. ✅ ButtonSeeder (no dependencies)
8. ✅ UserSeeder (requires: UserPermission)
9. ✅ MaterialSeeder (requires: Department)
10. ✅ MachineSeeder (requires: MachineType, Process)

**This order is already configured in `DatabaseSeeder.php`**

---

## 🔐 Default Credentials

| Username | Password | Role | Type |
|----------|----------|------|------|
| admin | password | Super Admin | admin |
| supervisor1 | password | Supervisor | super_wiser |
| operator1 | password | Operator | operator |
| operator2 | password | Operator | operator |

⚠️ **IMPORTANT:** Change these passwords in production!

---

## 📦 Total Records Seeded

| Table | Records |
|-------|---------|
| user_permissions | 5 |
| machine_types | 5 |
| departments | 5 |
| shifts | 3 |
| processes | 7 |
| statuses | 8 |
| buttons | 7 |
| users | 4 |
| materials | 7 |
| machines | 4 |
| **TOTAL** | **55 records** |

---

## ✅ Verification

After seeding, verify the data:

```bash
php artisan tinker
```

```php
// Check user permissions
>>> App\Models\UserPermission::count()
=> 5

// Check users
>>> App\Models\User::with('permission')->get()

// Check machines
>>> App\Models\Machine::with(['machineType', 'process'])->get()

// Test login
>>> $user = App\Models\User::where('user_name', 'admin')->first()
>>> $user->permission->role_name
=> "Super Admin"
```

---

## 🔧 Customization

### Add More Data

Edit the seeders to add more:
- Users for your team
- Machines in your facility
- Materials you use
- Custom processes
- More shifts or departments

### Change Default Password

In `UserSeeder.php`:
```php
'password' => Hash::make('your-secure-password'),
```

---

## 🎯 What's Included

### ✅ Realistic Manufacturing Data
- Real machine specifications (Heidelberg, Komori, Bobst)
- Standard printing processes
- Industry-standard paper types and GSM
- CMYK ink colors
- Typical shift timings
- Common production statuses

### ✅ Permission Matrix
- Granular 59-field permission system
- Role-based access control
- All CRUD operations covered
- Dashboard and menu permissions

### ✅ Production-Ready
- All foreign keys properly linked
- Realistic machine specifications
- Standard shift patterns
- Complete material catalog

---

## 🚨 Troubleshooting

### Error: Foreign key constraint fails
**Solution:** Seeders must run in order. Use `DatabaseSeeder.php` which has correct order.

### Error: Duplicate entry
**Solution:** Run `php artisan migrate:fresh --seed` to reset database.

### Error: Class not found
**Solution:** Run `composer dump-autoload` to refresh autoloader.

---

## 📝 Next Steps After Seeding

1. ✅ Verify all data is seeded correctly
2. ✅ Test login with admin credentials
3. ✅ Create sample orders (optional)
4. ✅ Create sample forms (optional)
5. ✅ Set up user-machine assignments
6. ✅ Configure production standards

---

## 🎉 Success!

Your Phoenix Manufacturing System database is now populated with:
- ✅ 5 User roles with complete permissions
- ✅ 4 Ready-to-use machines
- ✅ 7 Manufacturing processes
- ✅ 4 Users (including admin)
- ✅ 7 Materials for production
- ✅ 3 Shift patterns
- ✅ 8 Production statuses
- ✅ Complete action buttons

**You're ready to start production! 🚀**

---

*Created: October 18, 2025*  
*Total Seeders: 10*  
*Total Records: 55*  
*Status: Production Ready ✅*
