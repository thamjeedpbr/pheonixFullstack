<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            [
                'shift_id' => 'SHIFT-A',
                'shift_name' => 'Morning Shift',
                'start_time' => '06:00:00',
                'end_time' => '14:00:00',
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'shift_id' => 'SHIFT-B',
                'shift_name' => 'Afternoon Shift',
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'status' => true,
                'created_by_id' => null,
            ],
            [
                'shift_id' => 'SHIFT-C',
                'shift_name' => 'Night Shift',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'status' => true,
                'created_by_id' => null,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }

        $this->command->info('Shifts seeded successfully!');
    }
}
