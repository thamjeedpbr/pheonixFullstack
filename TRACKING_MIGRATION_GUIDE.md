# 🚀 QUICK MIGRATION GUIDE

## Step-by-Step Instructions

### 1. Backup Database (if existing data)
```bash
# Export database
php artisan db:backup

# Or manually
mysqldump -u root -p phoenix_db > backup_$(date +%Y%m%d).sql
```

### 2. Run Migrations
```bash
# Fresh migration (WARNING: Deletes all data!)
php artisan migrate:fresh

# Or if you want to keep data, just run new migrations
php artisan migrate
```

### 3. Verify Tables
```bash
php artisan tinker

# Check form_machine table
DB::table('form_machine')->count();

# Check form_user table  
DB::table('form_user')->count();
```

### 4. Test the Models
```bash
php artisan tinker

# Create a test form
$form = Form::create([
    'form_name' => 'Test Form',
    'section_id' => 1,
    'schedule_date' => now(),
    'form_status' => 'job_pending',
    'status' => true,
    'created_by_id' => 1
]);

# Assign machines
$form->machines()->attach([1, 2]);

# Activate machine 1
App\Models\FormMachine::switchMachine($form->id, 1);

# Check active machine
$form->refresh();
$form->activeMachine(); // Should return machine 1

# Assign users
$form->users()->attach([1, 2]);

# Start user 1 working
App\Models\FormUser::switchUser($form->id, 1);

# Check working user
$form->refresh();
$form->workingUser(); // Should return user 1
```

### 5. Update Your Controller (if needed)
The FormController already works! But if you want to add machine/user switching:

```php
// In FormController.php, add these methods:

public function switchMachine(Request $request, $id)
{
    $request->validate([
        'machine_id' => 'required|exists:machines,id'
    ]);

    $assignment = FormMachine::switchMachine($id, $request->machine_id);

    return response()->json([
        'success' => true,
        'message' => 'Machine switched successfully',
        'data' => $assignment->load('machine')
    ]);
}

public function switchUser(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    $assignment = FormUser::switchUser($id, $request->user_id);

    return response()->json([
        'success' => true,
        'message' => 'User switched successfully',
        'data' => $assignment->load('user')
    ]);
}
```

### 6. Add Routes (optional)
```php
// In routes/api.php
Route::post('/forms/{id}/switch-machine', [FormController::class, 'switchMachine']);
Route::post('/forms/{id}/switch-user', [FormController::class, 'switchUser']);
Route::get('/forms/{id}/work-report', [FormController::class, 'getWorkReport']);
```

### 7. Done! ✅
Your pivot tables now track:
- Which machine is currently active
- When machines were selected/deselected
- Which user is currently working
- When users started/finished working
- Total duration for each
- Complete work history

---

## Quick Test Commands

```bash
# Check what's running right now
php artisan tinker
App\Models\FormMachine::where('is_active', true)->with('form', 'machine')->get();
App\Models\FormUser::where('is_working', true)->with('form', 'user')->get();

# Get a user's work history
App\Models\FormUser::getUserWorkHistory(userId: 1, limit: 10);

# Get today's work report for a user
App\Models\FormUser::getDailyUserReport(userId: 1);

# Get work report for a form
App\Models\FormUser::getFormWorkReport(formId: 1);
```

---

**That's it! Your tracking system is now ready!** 🎉
