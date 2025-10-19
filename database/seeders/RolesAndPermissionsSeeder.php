<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Dashboard
            'dashboard.view',
            
            // Login Menu
            'login-menu.view',
            'login-menu.create',
            'login-menu.update',
            'login-menu.delete',
            
            // Job Menu
            'job-menu.view',
            'job-menu.create',
            'job-menu.update',
            'job-menu.delete',
            
            // Manual Job Menu
            'manual-job-menu.view',
            'manual-job-menu.create',
            'manual-job-menu.update',
            'manual-job-menu.delete',
            
            // Quality Menu
            'quality-menu.view',
            'quality-menu.create',
            'quality-menu.update',
            'quality-menu.delete',
            
            // User Menu
            'user-menu.view',
            'user-menu.create',
            'user-menu.update',
            'user-menu.delete',
            
            // Role Menu
            'role-menu.view',
            'role-menu.create',
            'role-menu.update',
            'role-menu.delete',
            
            // Status Menu
            'status-menu.view',
            'status-menu.create',
            'status-menu.update',
            'status-menu.delete',
            
            // Sub Status Menu
            'sub-status-menu.view',
            'sub-status-menu.create',
            'sub-status-menu.update',
            'sub-status-menu.delete',
            
            // Sheet Size
            'sheet-size.view',
            'sheet-size.create',
            'sheet-size.update',
            'sheet-size.delete',
            
            // Material Master
            'material-master.view',
            'material-master.create',
            'material-master.update',
            'material-master.delete',
            
            // Machine Master
            'machine-master.view',
            'machine-master.create',
            'machine-master.update',
            'machine-master.delete',
            
            // Standard Production
            'standard-production.view',
            'standard-production.create',
            'standard-production.update',
            'standard-production.delete',
            
            // Alert
            'alert.view',
            'alert.create',
            'alert.update',
            'alert.delete',
            
            // Tag
            'tag.view',
            'tag.create',
            'tag.update',
            'tag.delete',
            
            // Job Creation
            'job-creation.view',
            'job-creation.create',
            'job-creation.update',
            'job-creation.delete',
            
            // Shift
            'shift.view',
            'shift.create',
            'shift.update',
            'shift.delete',
            
            // Machine Type
            'machine-type.view',
            'machine-type.create',
            'machine-type.update',
            'machine-type.delete',
            
            // Department
            'department.view',
            'department.create',
            'department.update',
            'department.delete',
            
            // Process
            'process.view',
            'process.create',
            'process.update',
            'process.delete',
            
            // QC Master
            'qc-master.view',
            'qc-master.create',
            'qc-master.update',
            'qc-master.delete',
            
            // Button Group
            'button-group.view',
            'button-group.create',
            'button-group.update',
            'button-group.delete',
            
            // Buttons
            'buttons.view',
            'buttons.create',
            'buttons.update',
            'buttons.delete',
            
            // DM Task
            'dm-task.view',
            'dm-task.create',
            'dm-task.update',
            'dm-task.delete',
            
            // Daily Task
            'daily-task.view',
            'daily-task.create',
            'daily-task.update',
            'daily-task.delete',
            
            // DMI
            'dmi.view',
            'dmi.create',
            'dmi.update',
            'dmi.delete',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->command->info('✓ Permissions created');

        // Create roles and assign permissions
        
        // 1. Super Admin Role - has all permissions
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());
        $this->command->info('✓ Super Admin role created with all permissions');

        // 2. Supervisor Role
        $supervisor = Role::create(['name' => 'Supervisor']);
        $supervisor->givePermissionTo([
            'dashboard.view',
            'login-menu.view',
            'job-menu.view',
            'job-menu.update',
            'job-menu.create',
            'manual-job-menu.view',
            'manual-job-menu.create',
            'quality-menu.view',
            'quality-menu.create',
            'user-menu.view',
            'status-menu.view',
            'sub-status-menu.view',
            'material-master.view',
            'machine-master.view',
            'shift.view',
            'process.view',
            'qc-master.view',
            'dmi.view',
            'dmi.create',
        ]);
        $this->command->info('✓ Supervisor role created');

        // 3. Operator Role
        $operator = Role::create(['name' => 'Operator']);
        $operator->givePermissionTo([
            'dashboard.view',
            'login-menu.view',
            'job-menu.view',
            'manual-job-menu.view',
            'quality-menu.view',
            'dmi.view',
            'dmi.create',
        ]);
        $this->command->info('✓ Operator role created');

        // 4. QC Inspector Role
        $qcInspector = Role::create(['name' => 'QC Inspector']);
        $qcInspector->givePermissionTo([
            'dashboard.view',
            'quality-menu.view',
            'quality-menu.create',
            'quality-menu.update',
            'qc-master.view',
            'job-menu.view',
        ]);
        $this->command->info('✓ QC Inspector role created');

        // 5. Viewer Role
        $viewer = Role::create(['name' => 'Viewer']);
        $viewer->givePermissionTo([
            'dashboard.view',
            'login-menu.view',
            'job-menu.view',
            'manual-job-menu.view',
            'quality-menu.view',
            'user-menu.view',
            'material-master.view',
            'machine-master.view',
        ]);
        $this->command->info('✓ Viewer role created');

        $this->command->info('✅ All roles and permissions seeded successfully!');
    }
}
