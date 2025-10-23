# 🎯 FORM MACHINE & USER TRACKING - COMPLETE!

**Date**: October 23, 2025  
**Feature**: Multi-assignment with active tracking and reporting  
**Status**: ✅ COMPLETE - READY FOR MIGRATION  

---

## 🎉 WHAT WE BUILT

### Pivot Models with Advanced Tracking:
1. **FormMachine** - Track which machines are assigned and which one is currently active
2. **FormUser** - Track which users are assigned and which one is currently working

### Key Features:
- ✅ **Multiple Assignments**: Assign multiple machines and users to one form
- ✅ **Active Tracking**: Only ONE machine active and ONE user working at a time
- ✅ **Timestamps**: Track when machines/users start and stop
- ✅ **Duration Tracking**: Calculate total time spent
- ✅ **Work History**: Complete audit trail
- ✅ **Reporting**: Built-in report methods

---

## 📊 DATABASE STRUCTURE

### form_machine Table:
```sql
id                  BIGINT PRIMARY KEY
form_id             BIGINT (FK to forms)
machine_id          BIGINT (FK to machines)
is_active           BOOLEAN DEFAULT false    -- Currently selected?
selected_at         TIMESTAMP NULL           -- When selected
deselected_at       TIMESTAMP NULL           -- When deselected
duration_minutes    INTEGER NULL             -- Total time used
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE (form_id, machine_id)
INDEX (form_id, is_active)
```

### form_user Table:
```sql
id                  BIGINT PRIMARY KEY
form_id             BIGINT (FK to forms)
user_id             BIGINT (FK to users)
is_working          BOOLEAN DEFAULT false    -- Currently working?
worked_at           TIMESTAMP NULL           -- When started working
finished_at         TIMESTAMP NULL           -- When finished
duration_minutes    INTEGER NULL             -- Total time worked
notes               TEXT NULL                -- User notes
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE (form_id, user_id)
INDEX (form_id, is_working)
INDEX (user_id, worked_at)
```

---

## 🔧 MODEL FILES CREATED

### 1. FormMachine.php ✅
**Location**: `/app/Models/FormMachine.php`

**Key Methods**:
- `activate()` - Set this machine as active (deactivates others)
- `deactivate()` - Mark machine as inactive
- `isActive()` - Check if currently active
- `getTotalDuration()` - Get total usage time
- `getFormattedDuration()` - Get time as "2h 30m"
- `getActiveMachine($formId)` - Get currently active machine for form
- `switchMachine($formId, $machineId)` - Switch to different machine

**Relationships**:
- `form()` - BelongsTo Form
- `machine()` - BelongsTo Machine

**Scopes**:
- `active()` - Get only active assignments
- `forForm($formId)` - Get assignments for specific form
- `forMachine($machineId)` - Get assignments for specific machine

### 2. FormUser.php ✅
**Location**: `/app/Models/FormUser.php`

**Key Methods**:
- `startWorking()` - Mark user as working (stops others)
- `stopWorking($notes)` - Mark user as finished
- `isWorking()` - Check if currently working
- `getTotalDuration()` - Get total work time
- `getFormattedDuration()` - Get time as "2h 30m"
- `getWorkingUser($formId)` - Get currently working user for form
- `switchUser($formId, $userId)` - Switch to different user
- `getUserWorkHistory($userId)` - Get work history for user
- `getFormWorkReport($formId)` - Get all workers for form
- `getDailyUserReport($userId, $date)` - Get daily work report
- `getUserTotalTime($userId, $start, $end)` - Get total time in range

**Relationships**:
- `form()` - BelongsTo Form
- `user()` - BelongsTo User

**Scopes**:
- `working()` - Get only working assignments
- `forForm($formId)` - Get assignments for specific form
- `forUser($userId)` - Get assignments for specific user
- `withHistory()` - Get assignments with work history

### 3. Form.php ✅ (Updated)
**Location**: `/app/Models/Form.php`

**Updated Relationships**:
```php
// Now uses pivot models with tracking fields
public function machines(): BelongsToMany
{
    return $this->belongsToMany(Machine::class, 'form_machine')
        ->using(FormMachine::class)
        ->withPivot(['is_active', 'selected_at', 'deselected_at', 'duration_minutes'])
        ->withTimestamps();
}

public function users(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'form_user')
        ->using(FormUser::class)
        ->withPivot(['is_working', 'worked_at', 'finished_at', 'duration_minutes', 'notes'])
        ->withTimestamps();
}
```

**New Helper Methods**:
```php
// Get currently active machine
$form->activeMachine();

// Get currently working user
$form->workingUser();
```

---

## 💻 USAGE EXAMPLES

### Assign Multiple Machines to Form:
```php
$form = Form::find(1);

// Assign 3 machines
$form->machines()->attach([1, 2, 3]);

// Now form has 3 machines assigned, none active yet
```

### Activate a Machine:
```php
use App\Models\FormMachine;

// Method 1: Using pivot model directly
$assignment = FormMachine::where('form_id', 1)
    ->where('machine_id', 2)
    ->first();
$assignment->activate();

// Method 2: Using helper method
FormMachine::switchMachine(formId: 1, newMachineId: 2);

// Result: Machine 2 is now active, others deactivated
// selected_at timestamp is recorded
```

### Deactivate a Machine:
```php
$assignment = FormMachine::where('form_id', 1)
    ->where('machine_id', 2)
    ->first();
$assignment->deactivate();

// Result:
// - is_active = false
// - deselected_at = now()
// - duration_minutes updated with time used
```

### Assign Multiple Users to Form:
```php
$form = Form::find(1);

// Assign 3 operators
$form->users()->attach([10, 11, 12]);

// Now form has 3 users assigned, none working yet
```

### Start User Working:
```php
use App\Models\FormUser;

// Method 1: Using pivot model
$assignment = FormUser::where('form_id', 1)
    ->where('user_id', 10)
    ->first();
$assignment->startWorking();

// Method 2: Using helper method
FormUser::switchUser(formId: 1, newUserId: 10);

// Result: User 10 is now working, others stopped
// worked_at timestamp is recorded
```

### Stop User Working:
```php
$assignment = FormUser::where('form_id', 1)
    ->where('user_id', 10)
    ->first();
$assignment->stopWorking('Completed front side printing');

// Result:
// - is_working = false
// - finished_at = now()
// - duration_minutes updated
// - notes saved
```

### Get Currently Active Machine:
```php
// Method 1: Using Form model
$form = Form::find(1);
$activeMachine = $form->activeMachine();

// Method 2: Using FormMachine
$activeMachine = FormMachine::getActiveMachine(formId: 1);

if ($activeMachine) {
    echo "Active: " . $activeMachine->machine_no;
    echo "Since: " . $activeMachine->pivot->selected_at;
}
```

### Get Currently Working User:
```php
// Method 1: Using Form model
$form = Form::find(1);
$workingUser = $form->workingUser();

// Method 2: Using FormUser
$workingUser = FormUser::getWorkingUser(formId: 1);

if ($workingUser) {
    echo "Working: " . $workingUser->name;
    echo "Since: " . $workingUser->pivot->worked_at;
}
```

### Get All Machines for Form (with status):
```php
$form = Form::with('machines')->find(1);

foreach ($form->machines as $machine) {
    echo $machine->machine_no;
    echo " - Active: " . ($machine->pivot->is_active ? 'Yes' : 'No');
    echo " - Duration: " . $machine->pivot->duration_minutes . "min";
}
```

### Get All Users for Form (with status):
```php
$form = Form::with('users')->find(1);

foreach ($form->users as $user) {
    echo $user->name;
    echo " - Working: " . ($user->pivot->is_working ? 'Yes' : 'No');
    echo " - Time: " . $user->pivot->duration_minutes . "min";
}
```

### Get Form Work Report:
```php
$report = FormUser::getFormWorkReport(formId: 1);

foreach ($report as $entry) {
    echo $entry['user_name'];
    echo " worked from " . $entry['worked_at'];
    echo " to " . $entry['finished_at'];
    echo " (" . $entry['duration_formatted'] . ")";
    echo " Notes: " . $entry['notes'];
}
```

### Get User Work History:
```php
$history = FormUser::getUserWorkHistory(userId: 10, limit: 20);

foreach ($history as $assignment) {
    echo "Form: " . $assignment->form->form_name;
    echo "Order: " . $assignment->form->section->order->job_card_no;
    echo "Time: " . $assignment->getFormattedDuration();
}
```

### Get Daily User Report:
```php
$report = FormUser::getDailyUserReport(
    userId: 10, 
    date: '2025-10-23'
);

foreach ($report as $entry) {
    echo "Order: " . $entry['order'];
    echo "Section: " . $entry['section'];
    echo "Form: " . $entry['form_name'];
    echo "Duration: " . $entry['duration_formatted'];
}
```

### Get User Total Time (Date Range):
```php
$totals = FormUser::getUserTotalTime(
    userId: 10,
    startDate: '2025-10-01',
    endDate: '2025-10-31'
);

echo "Total time worked in October: " . $totals['formatted'];
echo "That's " . $totals['hours'] . " hours and " . $totals['minutes'] . " minutes";
```

---

## 🎯 BUSINESS USE CASES

### Use Case 1: Machine Switch During Production
**Scenario**: Machine 1 breaks down, need to switch to Machine 2

```php
// Current: Machine 1 is active
$form = Form::find(1);
echo $form->activeMachine()->machine_no; // "M-001"

// Switch to Machine 2
FormMachine::switchMachine(1, 2);

// New: Machine 2 is active
$form->refresh();
echo $form->activeMachine()->machine_no; // "M-002"

// Machine 1 deactivated with timestamp and duration recorded
```

### Use Case 2: Operator Shift Change
**Scenario**: John finishes shift, Jane takes over

```php
// Current: John is working
$form = Form::find(1);
$currentWorker = $form->workingUser();
echo $currentWorker->name; // "John Doe"

// Stop John
$assignment = FormUser::where('form_id', 1)
    ->where('user_id', $currentWorker->id)
    ->first();
$assignment->stopWorking('End of shift');

// Start Jane
FormUser::switchUser(1, 12); // Jane's user_id

// New: Jane is working
$form->refresh();
echo $form->workingUser()->name; // "Jane Smith"
```

### Use Case 3: Daily Productivity Report
**Scenario**: Manager wants to see what each operator did today

```php
$users = User::where('user_type', 'operator')->get();

foreach ($users as $user) {
    echo "\n{$user->name}'s Work Today:\n";
    
    $report = FormUser::getDailyUserReport($user->id);
    
    foreach ($report as $entry) {
        echo "- {$entry['order']} / {$entry['form_name']}";
        echo " ({$entry['duration_formatted']})\n";
    }
    
    $totals = FormUser::getUserTotalTime(
        $user->id,
        now()->startOfDay(),
        now()->endOfDay()
    );
    
    echo "Total: {$totals['formatted']}\n";
}
```

### Use Case 4: Form Completion Report
**Scenario**: Quality team wants to know who worked on this form

```php
$form = Form::find(1);
$report = FormUser::getFormWorkReport($form->id);

echo "Workers on {$form->form_name}:\n";

foreach ($report as $entry) {
    echo "\n{$entry['user_name']}:";
    echo "\n  Started: {$entry['worked_at']}";
    echo "\n  Finished: " . ($entry['finished_at'] ?? 'Still working');
    echo "\n  Duration: {$entry['duration_formatted']}";
    echo "\n  Notes: {$entry['notes']}\n";
}
```

---

## 🔄 MIGRATION STEPS

### Step 1: Run Migrations
```bash
# Make sure you have fresh database or rollback
php artisan migrate:fresh

# Or if adding to existing
php artisan migrate
```

### Step 2: Update Existing Code
The Form model is already updated. Just use the new methods!

### Step 3: Test Relationships
```bash
php artisan tinker

# Test machine assignment
$form = Form::find(1);
$form->machines()->attach(1);
$form->machines()->attach(2);

# Test activation
FormMachine::switchMachine(1, 1);
$form->refresh();
$form->activeMachine(); // Should return machine 1

# Test user assignment
$form->users()->attach([10, 11]);

# Test working
FormUser::switchUser(1, 10);
$form->refresh();
$form->workingUser(); // Should return user 10
```

---

## 📊 REPORTING QUERIES

### Most Used Machines (by time):
```php
$topMachines = FormMachine::select('machine_id')
    ->selectRaw('SUM(duration_minutes) as total_time')
    ->groupBy('machine_id')
    ->orderBy('total_time', 'desc')
    ->with('machine')
    ->limit(10)
    ->get();
```

### Most Productive Operators (by time):
```php
$topOperators = FormUser::select('user_id')
    ->selectRaw('SUM(duration_minutes) as total_time')
    ->groupBy('user_id')
    ->orderBy('total_time', 'desc')
    ->with('user')
    ->limit(10)
    ->get();
```

### Currently Active Forms:
```php
$activeForms = Form::whereHas('machines', function($q) {
    $q->wherePivot('is_active', true);
})->with(['activeMachine', 'workingUser'])->get();
```

### Forms Waiting for Operator:
```php
$waiting = Form::whereDoesntHave('users', function($q) {
    $q->wherePivot('is_working', true);
})->where('form_status', 'job_started')->get();
```

---

## 🎨 FRONTEND INTEGRATION IDEAS

### Display Active Machine:
```vue
<div v-if="form.activeMachine" class="active-machine">
  <div class="badge badge-success">Active</div>
  <span>{{ form.activeMachine.machine_no }}</span>
  <span class="time">Since: {{ form.activeMachine.pivot.selected_at }}</span>
</div>
```

### Display Working Operator:
```vue
<div v-if="form.workingUser" class="working-user">
  <div class="badge badge-primary">Working</div>
  <span>{{ form.workingUser.name }}</span>
  <span class="time">Since: {{ form.workingUser.pivot.worked_at }}</span>
</div>
```

### Switch Machine Button:
```vue
<button @click="switchMachine(newMachineId)">
  Switch to {{ machine.machine_no }}
</button>

<script>
const switchMachine = async (machineId) => {
  await axios.post(`/api/forms/${formId}/switch-machine`, {
    machine_id: machineId
  });
  // Refresh form data
};
</script>
```

---

## ✅ SUMMARY

### What We Built:
1. ✅ **FormMachine Model** - Track machine assignments and active status
2. ✅ **FormUser Model** - Track user assignments and working status
3. ✅ **Updated Form Model** - Use new pivot models
4. ✅ **Updated Migrations** - Add tracking fields
5. ✅ **Helper Methods** - Easy activation/deactivation
6. ✅ **Reporting Methods** - Built-in reports
7. ✅ **Duration Tracking** - Calculate time automatically

### Key Features:
- Multiple machines/users per form
- Only one active/working at a time
- Timestamps for start/stop
- Duration calculation
- Work history
- Comprehensive reporting
- Easy to use API

### Ready For:
- ✅ Migration (run php artisan migrate)
- ✅ Production use
- ✅ Frontend integration
- ✅ Reporting dashboards

---

*Form Machine & User Tracking - Complete!*  
*Created: October 23, 2025*  
*Phoenix Manufacturing System*  
**Multi-Assignment with Tracking Ready!** 🎯✅
