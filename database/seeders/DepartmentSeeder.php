<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'department_code' => 'DEPT-001',
                'name' => 'Paper Stock',
                'remark' => 'Various paper types and weights',
                'status' => true,
            ],
            [
                'department_code' => 'DEPT-002',
                'name' => 'Ink & Chemicals',
                'remark' => 'Printing inks and chemical supplies',
                'status' => true,
            ],
            [
                'department_code' => 'DEPT-003',
                'name' => 'Plates & Films',
                'remark' => 'Printing plates and film materials',
                'status' => true,
            ],
            [
                'department_code' => 'DEPT-004',
                'name' => 'Binding Materials',
                'remark' => 'Adhesives, wires, and binding supplies',
                'status' => true,
            ],
            [
                'department_code' => 'DEPT-005',
                'name' => 'Finishing Materials',
                'remark' => 'Lamination, coating, and finishing materials',
                'status' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        $this->command->info('Departments seeded successfully!');
    }
}
