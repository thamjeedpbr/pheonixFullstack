<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminPermission = UserPermission::where('role_name', 'Super Admin')->first();
        $supervisorPermission = UserPermission::where('role_name', 'Supervisor')->first();
        $operatorPermission = UserPermission::where('role_name', 'Operator')->first();

        $users = [
            [
                'user_name' => 'admin',
                'name' => 'System Administrator',
                'phone_no' => '+91 9876543210',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => true,
                'api_key' => null,
                'permission_id' => $superAdminPermission->id,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'admin',
            ],
            [
                'user_name' => 'supervisor1',
                'name' => 'John Supervisor',
                'phone_no' => '+91 9876543211',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => false,
                'api_key' => null,
                'permission_id' => $supervisorPermission->id,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'super_wiser',
            ],
            [
                'user_name' => 'operator1',
                'name' => 'Mike Operator',
                'phone_no' => '+91 9876543212',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => false,
                'api_key' => null,
                'permission_id' => $operatorPermission->id,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'operator',
            ],
            [
                'user_name' => 'operator2',
                'name' => 'Sarah Operator',
                'phone_no' => '+91 9876543213',
                'status' => true,
                'password' => Hash::make('password'),
                'is_super_user' => false,
                'api_key' => null,
                'permission_id' => $operatorPermission->id,
                'login_detail_id' => null,
                'last_login_time' => null,
                'user_type' => 'operator',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Default credentials:');
        $this->command->info('Username: admin | Password: password');
        $this->command->info('Username: supervisor1 | Password: password');
        $this->command->info('Username: operator1 | Password: password');
    }
}
