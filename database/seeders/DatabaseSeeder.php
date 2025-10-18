<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Phoenix Manufacturing System Database Seeding...');
        $this->command->newLine();

        // Order is important due to foreign key constraints
        $this->command->info('📋 Seeding base tables...');
        
        $this->call([
            // Priority 1: Base tables (no dependencies)
            UserPermissionSeeder::class,
            MachineTypeSeeder::class,
            DepartmentSeeder::class,
            ShiftSeeder::class,
            ProcessSeeder::class,
            StatusSeeder::class,
            ButtonSeeder::class,
            
            // Priority 2: Tables with dependencies
            UserSeeder::class,
            MaterialSeeder::class,
            MachineSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('   - User Permissions: 5 roles');
        $this->command->info('   - Machine Types: 5 types');
        $this->command->info('   - Departments: 5 departments');
        $this->command->info('   - Shifts: 3 shifts');
        $this->command->info('   - Processes: 7 processes');
        $this->command->info('   - Statuses: 8 statuses');
        $this->command->info('   - Buttons: 7 buttons');
        $this->command->info('   - Users: 4 users');
        $this->command->info('   - Materials: 7 materials');
        $this->command->info('   - Machines: 4 machines');
        $this->command->newLine();
        $this->command->info('🔐 Default Login Credentials:');
        $this->command->info('   Admin: admin / password');
        $this->command->info('   Supervisor: supervisor1 / password');
        $this->command->info('   Operator: operator1 / password');
        $this->command->newLine();
        $this->command->info('🚀 Your Phoenix Manufacturing System is ready to use!');
    }
}
