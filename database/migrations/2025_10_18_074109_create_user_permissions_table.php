<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for user_permissions table
 * 
 * This table stores role-based permission templates for the Phoenix Manufacturing System.
 * Each role (Super Admin, Supervisor, Operator, etc.) has specific permissions for different modules.
 * 
 * Total Permission Fields: 59 boolean fields covering all system modules
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            // Primary Key
            $table->id();
            
            // Role Information
            $table->string('role_name')->unique()->comment('Unique role identifier (e.g., Super Admin, Operator)');
            $table->boolean('status')->default(true)->comment('Role active status');
            
            // Dashboard Permissions
            $table->boolean('dashboard_view')->default(false)->comment('Can view dashboard');
            
            // Login Menu Permissions (4 fields)
            $table->boolean('login_menu_view')->default(false)->comment('Can view login menu');
            $table->boolean('login_menu_delete')->default(false)->comment('Can delete login records');
            $table->boolean('login_menu_update')->default(false)->comment('Can update login records');
            $table->boolean('login_menu_create')->default(false)->comment('Can create login records');
            
            // Job Menu Permissions (4 fields)
            $table->boolean('job_menu_view')->default(false)->comment('Can view jobs');
            $table->boolean('job_menu_delete')->default(false)->comment('Can delete jobs');
            $table->boolean('job_menu_update')->default(false)->comment('Can update jobs');
            $table->boolean('job_menu_create')->default(false)->comment('Can create jobs');
            
            // Manual Job Menu Permissions (4 fields)
            $table->boolean('manual_job_menu_view')->default(false)->comment('Can view manual jobs');
            $table->boolean('manual_job_menu_delete')->default(false)->comment('Can delete manual jobs');
            $table->boolean('manual_job_menu_update')->default(false)->comment('Can update manual jobs');
            $table->boolean('manual_job_menu_create')->default(false)->comment('Can create manual jobs');
            
            // Quality Menu Permissions (4 fields)
            $table->boolean('quality_menu_view')->default(false)->comment('Can view quality records');
            $table->boolean('quality_menu_delete')->default(false)->comment('Can delete quality records');
            $table->boolean('quality_menu_update')->default(false)->comment('Can update quality records');
            $table->boolean('quality_menu_create')->default(false)->comment('Can create quality records');
            
            // User Menu Permissions (4 fields)
            $table->boolean('user_menu_view')->default(false)->comment('Can view users');
            $table->boolean('user_menu_delete')->default(false)->comment('Can delete users');
            $table->boolean('user_menu_update')->default(false)->comment('Can update users');
            $table->boolean('user_menu_create')->default(false)->comment('Can create users');
            
            // Role Menu Permissions (4 fields)
            $table->boolean('role_menu_view')->default(false)->comment('Can view roles');
            $table->boolean('role_menu_delete')->default(false)->comment('Can delete roles');
            $table->boolean('role_menu_update')->default(false)->comment('Can update roles');
            $table->boolean('role_menu_create')->default(false)->comment('Can create roles');
            
            // Status Menu Permissions (4 fields)
            $table->boolean('status_menu_view')->default(false)->comment('Can view statuses');
            $table->boolean('status_menu_delete')->default(false)->comment('Can delete statuses');
            $table->boolean('status_menu_update')->default(false)->comment('Can update statuses');
            $table->boolean('status_menu_create')->default(false)->comment('Can create statuses');
            
            // Sub Status Menu Permissions (4 fields)
            $table->boolean('sub_status_menu_view')->default(false)->comment('Can view sub-statuses');
            $table->boolean('sub_status_menu_delete')->default(false)->comment('Can delete sub-statuses');
            $table->boolean('sub_status_menu_update')->default(false)->comment('Can update sub-statuses');
            $table->boolean('sub_status_menu_create')->default(false)->comment('Can create sub-statuses');
            
            // Sheet Size Permissions (4 fields)
            $table->boolean('sheet_size_view')->default(false)->comment('Can view sheet sizes');
            $table->boolean('sheet_size_delete')->default(false)->comment('Can delete sheet sizes');
            $table->boolean('sheet_size_update')->default(false)->comment('Can update sheet sizes');
            $table->boolean('sheet_size_create')->default(false)->comment('Can create sheet sizes');
            
            // Material Master Permissions (4 fields)
            $table->boolean('material_master_view')->default(false)->comment('Can view materials');
            $table->boolean('material_master_delete')->default(false)->comment('Can delete materials');
            $table->boolean('material_master_update')->default(false)->comment('Can update materials');
            $table->boolean('material_master_create')->default(false)->comment('Can create materials');
            
            // Machine Master Permissions (4 fields)
            $table->boolean('machine_master_view')->default(false)->comment('Can view machines');
            $table->boolean('machine_master_delete')->default(false)->comment('Can delete machines');
            $table->boolean('machine_master_update')->default(false)->comment('Can update machines');
            $table->boolean('machine_master_create')->default(false)->comment('Can create machines');
            
            // Standard Production Permissions (4 fields)
            $table->boolean('standerd_production_view')->default(false)->comment('Can view standard production');
            $table->boolean('standerd_production_delete')->default(false)->comment('Can delete standard production');
            $table->boolean('standerd_production_update')->default(false)->comment('Can update standard production');
            $table->boolean('standerd_production_create')->default(false)->comment('Can create standard production');
            
            // Alert Permissions (4 fields)
            $table->boolean('alert_view')->default(false)->comment('Can view alerts');
            $table->boolean('alert_delete')->default(false)->comment('Can delete alerts');
            $table->boolean('alert_update')->default(false)->comment('Can update alerts');
            $table->boolean('alert_create')->default(false)->comment('Can create alerts');
            
            // Tag Permissions (4 fields)
            $table->boolean('tag_view')->default(false)->comment('Can view tags');
            $table->boolean('tag_delete')->default(false)->comment('Can delete tags');
            $table->boolean('tag_update')->default(false)->comment('Can update tags');
            $table->boolean('tag_create')->default(false)->comment('Can create tags');
            
            // Job Creation Permissions (4 fields)
            $table->boolean('job_creation_view')->default(false)->comment('Can view job creation');
            $table->boolean('job_creation_delete')->default(false)->comment('Can delete job creation');
            $table->boolean('job_creation_update')->default(false)->comment('Can update job creation');
            $table->boolean('job_creation_create')->default(false)->comment('Can create job creation');
            
            // Shift Permissions (4 fields)
            $table->boolean('shift_view')->default(false)->comment('Can view shifts');
            $table->boolean('shift_delete')->default(false)->comment('Can delete shifts');
            $table->boolean('shift_update')->default(false)->comment('Can update shifts');
            $table->boolean('shift_create')->default(false)->comment('Can create shifts');
            
            // Machine Type Permissions (4 fields)
            $table->boolean('machine_type_create')->default(false)->comment('Can create machine types');
            $table->boolean('machine_type_delete')->default(false)->comment('Can delete machine types');
            $table->boolean('machine_type_update')->default(false)->comment('Can update machine types');
            $table->boolean('machine_type_view')->default(false)->comment('Can view machine types');
            
            // Department Permissions (4 fields)
            $table->boolean('department_view')->default(false)->comment('Can view departments');
            $table->boolean('department_delete')->default(false)->comment('Can delete departments');
            $table->boolean('department_update')->default(false)->comment('Can update departments');
            $table->boolean('department_create')->default(false)->comment('Can create departments');
            
            // Process Permissions (4 fields)
            $table->boolean('process_view')->default(false)->comment('Can view processes');
            $table->boolean('process_delete')->default(false)->comment('Can delete processes');
            $table->boolean('process_update')->default(false)->comment('Can update processes');
            $table->boolean('process_create')->default(false)->comment('Can create processes');
            
            // QC Master Permissions (4 fields)
            $table->boolean('QCMAster_view')->default(false)->comment('Can view QC masters');
            $table->boolean('QCMAster_delete')->default(false)->comment('Can delete QC masters');
            $table->boolean('QCMAster_update')->default(false)->comment('Can update QC masters');
            $table->boolean('QCMAster_create')->default(false)->comment('Can create QC masters');
            
            // Button Group Permissions (4 fields)
            $table->boolean('Button_group_view')->default(false)->comment('Can view button groups');
            $table->boolean('Button_group_delete')->default(false)->comment('Can delete button groups');
            $table->boolean('Button_group_update')->default(false)->comment('Can update button groups');
            $table->boolean('Button_group_create')->default(false)->comment('Can create button groups');
            
            // Buttons Permissions (4 fields)
            $table->boolean('buttons_view')->default(false)->comment('Can view buttons');
            $table->boolean('buttons_delete')->default(false)->comment('Can delete buttons');
            $table->boolean('buttons_update')->default(false)->comment('Can update buttons');
            $table->boolean('buttons_create')->default(false)->comment('Can create buttons');
            
            // DM Task Permissions (4 fields)
            $table->boolean('DMTask_view')->default(false)->comment('Can view DM tasks');
            $table->boolean('DMTask_delete')->default(false)->comment('Can delete DM tasks');
            $table->boolean('DMTask_update')->default(false)->comment('Can update DM tasks');
            $table->boolean('DMTask_create')->default(false)->comment('Can create DM tasks');
            
            // Daily Task Permissions (4 fields)
            $table->boolean('dailyTask_view')->default(false)->comment('Can view daily tasks');
            $table->boolean('dailyTask_delete')->default(false)->comment('Can delete daily tasks');
            $table->boolean('dailyTask_update')->default(false)->comment('Can update daily tasks');
            $table->boolean('dailyTask_create')->default(false)->comment('Can create daily tasks');
            
            // DMI Permissions (4 fields)
            $table->boolean('DMI_view')->default(false)->comment('Can view DMI data');
            $table->boolean('DMI_delete')->default(false)->comment('Can delete DMI data');
            $table->boolean('DMI_update')->default(false)->comment('Can update DMI data');
            $table->boolean('DMI_create')->default(false)->comment('Can create DMI data');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('status', 'idx_user_permissions_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
