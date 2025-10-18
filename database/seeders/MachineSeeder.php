<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\MachineType;
use App\Models\Process;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pressType = MachineType::where('machine_type', 'PRESS')->first();
        $postPressType = MachineType::where('machine_type', 'POST_PRESS')->first();
        $dieCutType = MachineType::where('machine_type', 'DIE_CUT')->first();
        
        $printingProcess = Process::where('name', 'Offset Printing')->first();
        $dieCuttingProcess = Process::where('name', 'Die Cutting')->first();
        $laminationProcess = Process::where('name', 'Lamination')->first();

        $machines = [
            [
                'machine_id' => 'MACH-001',
                'machine_name' => 'Heidelberg Speedmaster 4 Color',
                'description' => '4 color offset printing press',
                'min_width' => '300',
                'min_height' => '450',
                'max_height' => '700',
                'max_width' => '1020',
                'min_gsm' => '80',
                'max_gsm' => '350',
                'status' => true,
                'per_day_impression' => '15000',
                'per_hour_impression' => '10000',
                'remarks' => 'Main production machine',
                'make_ready_time' => '30',
                'minimum_cost' => '5000',
                'per_hour_cost' => '2000',
                'meter_per_impression' => '1',
                'devise_url' => null,
                'machine_type_id' => $pressType->id,
                'process_id' => $printingProcess->id,
                'created_by_id' => null,
            ],
            [
                'machine_id' => 'MACH-002',
                'machine_name' => 'Komori Lithrone 5 Color',
                'description' => '5 color offset printing press with coater',
                'min_width' => '350',
                'min_height' => '500',
                'max_height' => '740',
                'max_width' => '1050',
                'min_gsm' => '80',
                'max_gsm' => '400',
                'status' => true,
                'per_day_impression' => '18000',
                'per_hour_impression' => '12000',
                'remarks' => 'High-end machine with coating unit',
                'make_ready_time' => '45',
                'minimum_cost' => '6000',
                'per_hour_cost' => '2500',
                'meter_per_impression' => '1',
                'devise_url' => null,
                'machine_type_id' => $pressType->id,
                'process_id' => $printingProcess->id,
                'created_by_id' => null,
            ],
            [
                'machine_id' => 'MACH-003',
                'machine_name' => 'Bobst Die Cutter SP102',
                'description' => 'Automatic die cutting machine',
                'min_width' => '200',
                'min_height' => '300',
                'max_height' => '750',
                'max_width' => '1050',
                'min_gsm' => '150',
                'max_gsm' => '2000',
                'status' => true,
                'per_day_impression' => '8000',
                'per_hour_impression' => '5000',
                'remarks' => 'Precision die cutting',
                'make_ready_time' => '60',
                'minimum_cost' => '4000',
                'per_hour_cost' => '1500',
                'meter_per_impression' => null,
                'devise_url' => null,
                'machine_type_id' => $dieCutType->id,
                'process_id' => $dieCuttingProcess->id,
                'created_by_id' => null,
            ],
            [
                'machine_id' => 'MACH-004',
                'machine_name' => 'Lamination Machine LM-1100',
                'description' => 'Thermal lamination machine',
                'min_width' => '300',
                'min_height' => '300',
                'max_height' => '1100',
                'max_width' => '1100',
                'min_gsm' => '100',
                'max_gsm' => '500',
                'status' => true,
                'per_day_impression' => '10000',
                'per_hour_impression' => '6000',
                'remarks' => 'Matte and gloss lamination',
                'make_ready_time' => '20',
                'minimum_cost' => '2000',
                'per_hour_cost' => '800',
                'meter_per_impression' => null,
                'devise_url' => null,
                'machine_type_id' => $postPressType->id,
                'process_id' => $laminationProcess->id,
                'created_by_id' => null,
            ],
        ];

        foreach ($machines as $machine) {
            Machine::create($machine);
        }

        $this->command->info('Machines seeded successfully!');
    }
}
