<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Department;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paperDept = Department::where('department_code', 'DEPT-001')->first();
        $inkDept = Department::where('department_code', 'DEPT-002')->first();

        $materials = [
            [
                'material_id' => 'MAT-001',
                'name' => 'Art Paper 130 GSM',
                'utilization' => 'High quality printing',
                'coating' => true,
                'description' => 'Coated art paper for brochures and catalogs',
                'gsm' => '130',
                'unit' => 'Sheet',
                'status' => true,
                'department_id' => $paperDept->id,
                'created_by_id' => null,
            ],
            [
                'material_id' => 'MAT-002',
                'name' => 'Art Paper 170 GSM',
                'utilization' => 'Premium quality printing',
                'coating' => true,
                'description' => 'Heavy weight art paper for covers',
                'gsm' => '170',
                'unit' => 'Sheet',
                'status' => true,
                'department_id' => $paperDept->id,
                'created_by_id' => null,
            ],
            [
                'material_id' => 'MAT-003',
                'name' => 'Maplitho 80 GSM',
                'utilization' => 'Text pages',
                'coating' => false,
                'description' => 'Uncoated paper for book pages',
                'gsm' => '80',
                'unit' => 'Sheet',
                'status' => true,
                'department_id' => $paperDept->id,
                'created_by_id' => null,
            ],
            [
                'material_id' => 'MAT-004',
                'name' => 'Offset Ink - Cyan',
                'utilization' => 'CMYK printing',
                'coating' => false,
                'description' => 'Process cyan ink',
                'gsm' => null,
                'unit' => 'Kg',
                'status' => true,
                'department_id' => $inkDept->id,
                'created_by_id' => null,
            ],
            [
                'material_id' => 'MAT-005',
                'name' => 'Offset Ink - Magenta',
                'utilization' => 'CMYK printing',
                'coating' => false,
                'description' => 'Process magenta ink',
                'gsm' => null,
                'unit' => 'Kg',
                'status' => true,
                'department_id' => $inkDept->id,
                'created_by_id' => null,
            ],
            [
                'material_id' => 'MAT-006',
                'name' => 'Offset Ink - Yellow',
                'utilization' => 'CMYK printing',
                'coating' => false,
                'description' => 'Process yellow ink',
                'gsm' => null,
                'unit' => 'Kg',
                'status' => true,
                'department_id' => $inkDept->id,
                'created_by_id' => null,
            ],
            [
                'material_id' => 'MAT-007',
                'name' => 'Offset Ink - Black',
                'utilization' => 'CMYK printing',
                'coating' => false,
                'description' => 'Process black ink',
                'gsm' => null,
                'unit' => 'Kg',
                'status' => true,
                'department_id' => $inkDept->id,
                'created_by_id' => null,
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }

        $this->command->info('Materials seeded successfully!');
    }
}
