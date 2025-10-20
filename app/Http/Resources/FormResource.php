<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'form_no' => $this->form_no,
            'form_name' => $this->form_name,
            'schedule_date' => $this->schedule_date,
            'schedule_date_formatted' => $this->schedule_date ? Carbon::parse($this->schedule_date)->format('M d, Y') : null,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            
            // Section with order
            'section' => $this->whenLoaded('section', function() {
                return [
                    'id' => $this->section->id,
                    'section_no' => $this->section->section_no,
                    'section_name' => $this->section->section_name,
                    'order' => $this->section->order ? [
                        'id' => $this->section->order->id,
                        'job_card_no' => $this->section->order->job_card_no,
                        'client_name' => $this->section->order->client_name,
                        'title' => $this->section->order->title,
                    ] : null,
                ];
            }),

            // Machine with type
            'machine' => $this->whenLoaded('machine', function() {
                return $this->machine ? [
                    'id' => $this->machine->id,
                    'machine_id' => $this->machine->machine_id,
                    'machine_name' => $this->machine->machine_name,
                    'machine_type' => $this->machine->machineType ? [
                        'id' => $this->machine->machineType->id,
                        'name' => $this->machine->machineType->name,
                    ] : null,
                ] : null;
            }),

            // Operators
            'operators' => $this->whenLoaded('formUserAssignments', function() {
                return $this->formUserAssignments->map(function($assignment) {
                    return [
                        'id' => $assignment->user->id,
                        'name' => $assignment->user->name,
                        'emp_code' => $assignment->user->emp_code,
                        'department' => $assignment->user->department->name ?? 'N/A',
                    ];
                });
            }),

            // Materials with quantities
            'materials' => $this->whenLoaded('formMaterialAssignments', function() {
                return $this->formMaterialAssignments->map(function($assignment) {
                    return [
                        'id' => $assignment->material->id,
                        'material_name' => $assignment->material->material_name,
                        'material_code' => $assignment->material->material_code,
                        'quantity_assigned' => $assignment->quantity_assigned,
                        'quantity_consumed' => $assignment->quantity_consumed ?? 0, // For future DMI data
                    ];
                });
            }),

            // Button actions count
            'button_actions_count' => $this->formButtonActions ? $this->formButtonActions->count() : 0,
            
            // Timestamps
            'created_at' => $this->created_at?->toDateTimeString(),
            'created_at_formatted' => $this->created_at?->format('M d, Y h:i A'),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'updated_at_formatted' => $this->updated_at?->format('M d, Y h:i A'),
        ];
    }

    /**
     * Get human-readable status label
     */
    private function getStatusLabel(): string
    {
        $labels = [
            'job_pending' => 'Pending',
            'make_ready' => 'Make Ready',
            'job_started' => 'In Production',
            'paused' => 'Paused',
            'stopped' => 'Stopped',
            'job_completed' => 'Completed',
            'quality_verified' => 'QC Verified',
            'line_cleared' => 'Line Cleared',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
