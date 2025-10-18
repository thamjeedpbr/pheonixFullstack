<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            // Productive statuses
            [
                'Status_code' => 'STAT-001',
                'Status_name' => 'Running',
                'remark' => 'Machine is running production',
                'status' => true,
                'img' => null,
                'status_type' => 'general',
                'created_by_id' => null,
            ],
            [
                'Status_code' => 'STAT-002',
                'Status_name' => 'Make Ready',
                'remark' => 'Setting up for production',
                'status' => true,
                'img' => null,
                'status_type' => 'general',
                'created_by_id' => null,
            ],
            // Unproductive statuses
            [
                'Status_code' => 'STAT-003',
                'Status_name' => 'Breakdown',
                'remark' => 'Machine breakdown',
                'status' => true,
                'img' => null,
                'status_type' => 'stop',
                'created_by_id' => null,
            ],
            [
                'Status_code' => 'STAT-004',
                'Status_name' => 'Maintenance',
                'remark' => 'Scheduled maintenance',
                'status' => true,
                'img' => null,
                'status_type' => 'stop',
                'created_by_id' => null,
            ],
            [
                'Status_code' => 'STAT-005',
                'Status_name' => 'No Job',
                'remark' => 'Waiting for job assignment',
                'status' => true,
                'img' => null,
                'status_type' => 'stop',
                'created_by_id' => null,
            ],
            [
                'Status_code' => 'STAT-006',
                'Status_name' => 'Quality Check',
                'remark' => 'Quality inspection in progress',
                'status' => true,
                'img' => null,
                'status_type' => 'general',
                'created_by_id' => null,
            ],
            [
                'Status_code' => 'STAT-007',
                'Status_name' => 'Material Shortage',
                'remark' => 'Waiting for materials',
                'status' => true,
                'img' => null,
                'status_type' => 'stop',
                'created_by_id' => null,
            ],
            [
                'Status_code' => 'STAT-008',
                'Status_name' => 'Break',
                'remark' => 'Operator break time',
                'status' => true,
                'img' => null,
                'status_type' => 'stop',
                'created_by_id' => null,
            ],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }

        $this->command->info('Statuses seeded successfully!');
    }
}
