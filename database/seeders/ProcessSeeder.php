<?php

namespace Database\Seeders;

use App\Models\Process;
use Illuminate\Database\Seeder;

class ProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $processes = [
            [
                'name' => 'Plate Making',
                'msi_id' => 'MSI-PROC-001',
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Offset Printing',
                'msi_id' => 'MSI-PROC-002',
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Die Cutting',
                'msi_id' => 'MSI-PROC-003',
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Lamination',
                'msi_id' => 'MSI-PROC-004',
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Binding',
                'msi_id' => 'MSI-PROC-005',
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'UV Coating',
                'msi_id' => 'MSI-PROC-006',
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Folding',
                'msi_id' => 'MSI-PROC-007',
                'status' => true,
                'created_by_id' => null,
            ],
        ];

        foreach ($processes as $process) {
            Process::create($process);
        }

        $this->command->info('Processes seeded successfully!');
    }
}
