<?php

namespace Database\Seeders;

use App\Models\Button;
use Illuminate\Database\Seeder;

class ButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buttons = [
            [
                'name' => 'Start Job',
                'button_id' => 'BTN-001',
                'msi_id' => 'MSI-BTN-001',
                'is_stop' => false,
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Pause Job',
                'button_id' => 'BTN-002',
                'msi_id' => 'MSI-BTN-002',
                'is_stop' => true,
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Stop Job',
                'button_id' => 'BTN-003',
                'msi_id' => 'MSI-BTN-003',
                'is_stop' => true,
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Complete Job',
                'button_id' => 'BTN-004',
                'msi_id' => 'MSI-BTN-004',
                'is_stop' => false,
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Make Ready Start',
                'button_id' => 'BTN-005',
                'msi_id' => 'MSI-BTN-005',
                'is_stop' => false,
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Make Ready End',
                'button_id' => 'BTN-006',
                'msi_id' => 'MSI-BTN-006',
                'is_stop' => false,
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'name' => 'Breakdown',
                'button_id' => 'BTN-007',
                'msi_id' => 'MSI-BTN-007',
                'is_stop' => true,
                'status' => true,
                'created_by_id' => null,
            ],
        ];

        foreach ($buttons as $button) {
            Button::create($button);
        }

        $this->command->info('Buttons seeded successfully!');
    }
}
