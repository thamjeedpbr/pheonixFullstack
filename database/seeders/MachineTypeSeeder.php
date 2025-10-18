<?php

namespace Database\Seeders;

use App\Models\MachineType;
use Illuminate\Database\Seeder;

class MachineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $machineTypes = [
            [
                'type_id' => 'MT-001',
                'name' => 'Pre-Press Machines',
                'remark' => 'Plate making and pre-press operations',
                'machine_type' => 'PRE_PRESS',
                'status' => true,
            ],
            [
                'type_id' => 'MT-002',
                'name' => 'Offset Printing Press',
                'remark' => 'Main printing press machines',
                'machine_type' => 'PRESS',
                'status' => true,
            ],
            [
                'type_id' => 'MT-003',
                'name' => 'Post-Press Equipment',
                'remark' => 'Finishing and binding operations',
                'machine_type' => 'POST_PRESS',
                'status' => true,
            ],
            [
                'type_id' => 'MT-004',
                'name' => 'Die Cutting Machines',
                'remark' => 'Cutting and shaping operations',
                'machine_type' => 'DIE_CUT',
                'status' => true,
            ],
            [
                'type_id' => 'MT-005',
                'name' => 'Other Equipment',
                'remark' => 'Miscellaneous manufacturing equipment',
                'machine_type' => 'OTHER',
                'status' => true,
            ],
        ];

        foreach ($machineTypes as $type) {
            MachineType::create($type);
        }

        $this->command->info('Machine types seeded successfully!');
    }
}
