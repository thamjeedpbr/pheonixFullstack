<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'user_name' => 'admin',
                'name' => 'System Administrator',
                'phone_no' => '+91 9876543210',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => true,
                'api_key' => null,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'admin',
                'role' => 'Super Admin',
            ],
            [
                'user_name' => 'supervisor1',
                'name' => 'John Supervisor',
                'phone_no' => '+91 9876543211',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => false,
                'api_key' => null,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'super_wiser',
                'role' => 'Supervisor',
            ],
            [
                'user_name' => 'operator1',
                'name' => 'Mike Operator',
                'phone_no' => '+91 9876543212',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => false,
                'api_key' => null,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'operator',
                'role' => 'Operator',
            ],
            [
                'user_name' => 'operator2',
                'name' => 'Sarah Operator',
                'phone_no' => '+91 9876543213',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => false,
                'api_key' => null,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'operator',
                'role' => 'Operator',
            ],
            [
                'user_name' => 'qc1',
                'name' => 'Quality Inspector',
                'phone_no' => '+91 9876543214',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => false,
                'api_key' => null,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'operator',
                'role' => 'QC Inspector',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::create($userData);
            $user->assignRole($role);
            
            $this->command->info("✓ Created user: {$user->user_name} with role: {$role}");
        }

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('');
        $this->command->info('Default credentials:');
        $this->command->info('Username: admin | Password: password | Role: Super Admin');
        $this->command->info('Username: supervisor1 | Password: password | Role: Supervisor');
        $this->command->info('Username: operator1 | Password: password | Role: Operator');
        $this->command->info('Username: operator2 | Password: password | Role: Operator');
        $this->command->info('Username: qc1 | Password: password | Role: QC Inspector');
    }
}
