<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * UserPermission Model
 * 
 * Represents role-based permission templates in the Phoenix Manufacturing System.
 * Each role defines granular permissions across all system modules.
 * 
 * @property int $id
 * @property string $role_name
 * @property bool $status
 * @property bool $dashboard_view
 * 
 * Login Menu Permissions
 * @property bool $login_menu_view
 * @property bool $login_menu_delete
 * @property bool $login_menu_update
 * @property bool $login_menu_create
 * 
 * Job Menu Permissions
 * @property bool $job_menu_view
 * @property bool $job_menu_delete
 * @property bool $job_menu_update
 * @property bool $job_menu_create
 * 
 * Manual Job Menu Permissions
 * @property bool $manual_job_menu_view
 * @property bool $manual_job_menu_delete
 * @property bool $manual_job_menu_update
 * @property bool $manual_job_menu_create
 * 
 * Quality Menu Permissions
 * @property bool $quality_menu_view
 * @property bool $quality_menu_delete
 * @property bool $quality_menu_update
 * @property bool $quality_menu_create
 * 
 * User Menu Permissions
 * @property bool $user_menu_view
 * @property bool $user_menu_delete
 * @property bool $user_menu_update
 * @property bool $user_menu_create
 * 
 * Role Menu Permissions
 * @property bool $role_menu_view
 * @property bool $role_menu_delete
 * @property bool $role_menu_update
 * @property bool $role_menu_create
 * 
 * Status Menu Permissions
 * @property bool $status_menu_view
 * @property bool $status_menu_delete
 * @property bool $status_menu_update
 * @property bool $status_menu_create
 * 
 * Sub Status Menu Permissions
 * @property bool $sub_status_menu_view
 * @property bool $sub_status_menu_delete
 * @property bool $sub_status_menu_update
 * @property bool $sub_status_menu_create
 * 
 * Sheet Size Permissions
 * @property bool $sheet_size_view
 * @property bool $sheet_size_delete
 * @property bool $sheet_size_update
 * @property bool $sheet_size_create
 * 
 * Material Master Permissions
 * @property bool $material_master_view
 * @property bool $material_master_delete
 * @property bool $material_master_update
 * @property bool $material_master_create
 * 
 * Machine Master Permissions
 * @property bool $machine_master_view
 * @property bool $machine_master_delete
 * @property bool $machine_master_update
 * @property bool $machine_master_create
 * 
 * Standard Production Permissions
 * @property bool $standerd_production_view
 * @property bool $standerd_production_delete
 * @property bool $standerd_production_update
 * @property bool $standerd_production_create
 * 
 * Alert Permissions
 * @property bool $alert_view
 * @property bool $alert_delete
 * @property bool $alert_update
 * @property bool $alert_create
 * 
 * Tag Permissions
 * @property bool $tag_view
 * @property bool $tag_delete
 * @property bool $tag_update
 * @property bool $tag_create
 * 
 * Job Creation Permissions
 * @property bool $job_creation_view
 * @property bool $job_creation_delete
 * @property bool $job_creation_update
 * @property bool $job_creation_create
 * 
 * Shift Permissions
 * @property bool $shift_view
 * @property bool $shift_delete
 * @property bool $shift_update
 * @property bool $shift_create
 * 
 * Machine Type Permissions
 * @property bool $machine_type_create
 * @property bool $machine_type_delete
 * @property bool $machine_type_update
 * @property bool $machine_type_view
 * 
 * Department Permissions
 * @property bool $department_view
 * @property bool $department_delete
 * @property bool $department_update
 * @property bool $department_create
 * 
 * Process Permissions
 * @property bool $process_view
 * @property bool $process_delete
 * @property bool $process_update
 * @property bool $process_create
 * 
 * QC Master Permissions
 * @property bool $QCMAster_view
 * @property bool $QCMAster_delete
 * @property bool $QCMAster_update
 * @property bool $QCMAster_create
 * 
 * Button Group Permissions
 * @property bool $Button_group_view
 * @property bool $Button_group_delete
 * @property bool $Button_group_update
 * @property bool $Button_group_create
 * 
 * Buttons Permissions
 * @property bool $buttons_view
 * @property bool $buttons_delete
 * @property bool $buttons_update
 * @property bool $buttons_create
 * 
 * DM Task Permissions
 * @property bool $DMTask_view
 * @property bool $DMTask_delete
 * @property bool $DMTask_update
 * @property bool $DMTask_create
 * 
 * Daily Task Permissions
 * @property bool $dailyTask_view
 * @property bool $dailyTask_delete
 * @property bool $dailyTask_update
 * @property bool $dailyTask_create
 * 
 * DMI Permissions
 * @property bool $DMI_view
 * @property bool $DMI_delete
 * @property bool $DMI_update
 * @property bool $DMI_create
 * 
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * Relationships
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */
class UserPermission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_name',
        'status',
        'dashboard_view',
        
        // Login Menu
        'login_menu_view',
        'login_menu_delete',
        'login_menu_update',
        'login_menu_create',
        
        // Job Menu
        'job_menu_view',
        'job_menu_delete',
        'job_menu_update',
        'job_menu_create',
        
        // Manual Job Menu
        'manual_job_menu_view',
        'manual_job_menu_delete',
        'manual_job_menu_update',
        'manual_job_menu_create',
        
        // Quality Menu
        'quality_menu_view',
        'quality_menu_delete',
        'quality_menu_update',
        'quality_menu_create',
        
        // User Menu
        'user_menu_view',
        'user_menu_delete',
        'user_menu_update',
        'user_menu_create',
        
        // Role Menu
        'role_menu_view',
        'role_menu_delete',
        'role_menu_update',
        'role_menu_create',
        
        // Status Menu
        'status_menu_view',
        'status_menu_delete',
        'status_menu_update',
        'status_menu_create',
        
        // Sub Status Menu
        'sub_status_menu_view',
        'sub_status_menu_delete',
        'sub_status_menu_update',
        'sub_status_menu_create',
        
        // Sheet Size
        'sheet_size_view',
        'sheet_size_delete',
        'sheet_size_update',
        'sheet_size_create',
        
        // Material Master
        'material_master_view',
        'material_master_delete',
        'material_master_update',
        'material_master_create',
        
        // Machine Master
        'machine_master_view',
        'machine_master_delete',
        'machine_master_update',
        'machine_master_create',
        
        // Standard Production
        'standerd_production_view',
        'standerd_production_delete',
        'standerd_production_update',
        'standerd_production_create',
        
        // Alert
        'alert_view',
        'alert_delete',
        'alert_update',
        'alert_create',
        
        // Tag
        'tag_view',
        'tag_delete',
        'tag_update',
        'tag_create',
        
        // Job Creation
        'job_creation_view',
        'job_creation_delete',
        'job_creation_update',
        'job_creation_create',
        
        // Shift
        'shift_view',
        'shift_delete',
        'shift_update',
        'shift_create',
        
        // Machine Type
        'machine_type_create',
        'machine_type_delete',
        'machine_type_update',
        'machine_type_view',
        
        // Department
        'department_view',
        'department_delete',
        'department_update',
        'department_create',
        
        // Process
        'process_view',
        'process_delete',
        'process_update',
        'process_create',
        
        // QC Master
        'QCMAster_view',
        'QCMAster_delete',
        'QCMAster_update',
        'QCMAster_create',
        
        // Button Group
        'Button_group_view',
        'Button_group_delete',
        'Button_group_update',
        'Button_group_create',
        
        // Buttons
        'buttons_view',
        'buttons_delete',
        'buttons_update',
        'buttons_create',
        
        // DM Task
        'DMTask_view',
        'DMTask_delete',
        'DMTask_update',
        'DMTask_create',
        
        // Daily Task
        'dailyTask_view',
        'dailyTask_delete',
        'dailyTask_update',
        'dailyTask_create',
        
        // DMI
        'DMI_view',
        'DMI_delete',
        'DMI_update',
        'DMI_create',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'dashboard_view' => 'boolean',
        
        // Login Menu
        'login_menu_view' => 'boolean',
        'login_menu_delete' => 'boolean',
        'login_menu_update' => 'boolean',
        'login_menu_create' => 'boolean',
        
        // Job Menu
        'job_menu_view' => 'boolean',
        'job_menu_delete' => 'boolean',
        'job_menu_update' => 'boolean',
        'job_menu_create' => 'boolean',
        
        // Manual Job Menu
        'manual_job_menu_view' => 'boolean',
        'manual_job_menu_delete' => 'boolean',
        'manual_job_menu_update' => 'boolean',
        'manual_job_menu_create' => 'boolean',
        
        // Quality Menu
        'quality_menu_view' => 'boolean',
        'quality_menu_delete' => 'boolean',
        'quality_menu_update' => 'boolean',
        'quality_menu_create' => 'boolean',
        
        // User Menu
        'user_menu_view' => 'boolean',
        'user_menu_delete' => 'boolean',
        'user_menu_update' => 'boolean',
        'user_menu_create' => 'boolean',
        
        // Role Menu
        'role_menu_view' => 'boolean',
        'role_menu_delete' => 'boolean',
        'role_menu_update' => 'boolean',
        'role_menu_create' => 'boolean',
        
        // Status Menu
        'status_menu_view' => 'boolean',
        'status_menu_delete' => 'boolean',
        'status_menu_update' => 'boolean',
        'status_menu_create' => 'boolean',
        
        // Sub Status Menu
        'sub_status_menu_view' => 'boolean',
        'sub_status_menu_delete' => 'boolean',
        'sub_status_menu_update' => 'boolean',
        'sub_status_menu_create' => 'boolean',
        
        // Sheet Size
        'sheet_size_view' => 'boolean',
        'sheet_size_delete' => 'boolean',
        'sheet_size_update' => 'boolean',
        'sheet_size_create' => 'boolean',
        
        // Material Master
        'material_master_view' => 'boolean',
        'material_master_delete' => 'boolean',
        'material_master_update' => 'boolean',
        'material_master_create' => 'boolean',
        
        // Machine Master
        'machine_master_view' => 'boolean',
        'machine_master_delete' => 'boolean',
        'machine_master_update' => 'boolean',
        'machine_master_create' => 'boolean',
        
        // Standard Production
        'standerd_production_view' => 'boolean',
        'standerd_production_delete' => 'boolean',
        'standerd_production_update' => 'boolean',
        'standerd_production_create' => 'boolean',
        
        // Alert
        'alert_view' => 'boolean',
        'alert_delete' => 'boolean',
        'alert_update' => 'boolean',
        'alert_create' => 'boolean',
        
        // Tag
        'tag_view' => 'boolean',
        'tag_delete' => 'boolean',
        'tag_update' => 'boolean',
        'tag_create' => 'boolean',
        
        // Job Creation
        'job_creation_view' => 'boolean',
        'job_creation_delete' => 'boolean',
        'job_creation_update' => 'boolean',
        'job_creation_create' => 'boolean',
        
        // Shift
        'shift_view' => 'boolean',
        'shift_delete' => 'boolean',
        'shift_update' => 'boolean',
        'shift_create' => 'boolean',
        
        // Machine Type
        'machine_type_create' => 'boolean',
        'machine_type_delete' => 'boolean',
        'machine_type_update' => 'boolean',
        'machine_type_view' => 'boolean',
        
        // Department
        'department_view' => 'boolean',
        'department_delete' => 'boolean',
        'department_update' => 'boolean',
        'department_create' => 'boolean',
        
        // Process
        'process_view' => 'boolean',
        'process_delete' => 'boolean',
        'process_update' => 'boolean',
        'process_create' => 'boolean',
        
        // QC Master
        'QCMAster_view' => 'boolean',
        'QCMAster_delete' => 'boolean',
        'QCMAster_update' => 'boolean',
        'QCMAster_create' => 'boolean',
        
        // Button Group
        'Button_group_view' => 'boolean',
        'Button_group_delete' => 'boolean',
        'Button_group_update' => 'boolean',
        'Button_group_create' => 'boolean',
        
        // Buttons
        'buttons_view' => 'boolean',
        'buttons_delete' => 'boolean',
        'buttons_update' => 'boolean',
        'buttons_create' => 'boolean',
        
        // DM Task
        'DMTask_view' => 'boolean',
        'DMTask_delete' => 'boolean',
        'DMTask_update' => 'boolean',
        'DMTask_create' => 'boolean',
        
        // Daily Task
        'dailyTask_view' => 'boolean',
        'dailyTask_delete' => 'boolean',
        'dailyTask_update' => 'boolean',
        'dailyTask_create' => 'boolean',
        
        // DMI
        'DMI_view' => 'boolean',
        'DMI_delete' => 'boolean',
        'DMI_update' => 'boolean',
        'DMI_create' => 'boolean',
        
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all users associated with this permission role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'permission_id');
    }

    /**
     * Scope to get only active permission roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Check if a specific permission is granted.
     *
     * @param string $permission Permission field name (e.g., 'user_menu_create')
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return $this->{$permission} ?? false;
    }

    /**
     * Get all permissions for a specific module.
     *
     * @param string $module Module name (e.g., 'user_menu', 'job_menu')
     * @return array
     */
    public function getModulePermissions(string $module): array
    {
        $permissions = [];
        $actions = ['view', 'create', 'update', 'delete'];
        
        foreach ($actions as $action) {
            $field = "{$module}_{$action}";
            if (isset($this->{$field})) {
                $permissions[$action] = $this->{$field};
            }
        }
        
        return $permissions;
    }

    /**
     * Get count of granted permissions for this role.
     *
     * @return int
     */
    public function getGrantedPermissionsCount(): int
    {
        $count = 0;
        $permissionFields = array_filter($this->fillable, function($field) {
            return $field !== 'role_name' && $field !== 'status';
        });
        
        foreach ($permissionFields as $field) {
            if ($this->{$field} === true) {
                $count++;
            }
        }
        
        return $count;
    }
}
