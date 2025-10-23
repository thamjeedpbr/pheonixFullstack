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
            'form_name' => $this->form_name,
            'schedule_date' => $this->schedule_date,
            'schedule_date_formatted' => $this->schedule_date ? Carbon::parse($this->schedule_date)->format('M d, Y') : null,
            'excepted_qty' => $this->excepted_qty,
            'description' => $this->description,
            'form_status' => $this->form_status,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            
            // Section with order
            'section' => $this->whenLoaded('section', function() {
                return [
                    'id' => $this->section->id,
                    'section_id' => $this->section->section_id,
                    'section_no' => $this->section->section_no ?? null,
                    'order' => $this->section->order ? [
                        'id' => $this->section->order->id,
                        'job_card_no' => $this->section->order->job_card_no,
                        'client_name' => $this->section->order->client_name ?? null,
                        'title' => $this->section->order->title ?? null,
                    ] : null,
                ];
            }),

            // Multiple machines (many-to-many)
            'machines' => $this->whenLoaded('machines', function() {
                return $this->machines->map(function($machine) {
                    return [
                        'id' => $machine->id,
                        'machine_no' => $machine->machine_no,
                        'machine_name' => $machine->machine_name,
                        'is_active' => $machine->pivot->is_active ?? false,
                        'selected_at' => $machine->pivot->selected_at ?? null,
                    ];
                });
            }),

            // Single material (belongs-to)
            'material' => $this->whenLoaded('material', function() {
                return $this->material ? [
                    'id' => $this->material->id,
                    'material_id' => $this->material->material_id,
                    'name' => $this->material->name,
                    // Aliases for backward compatibility
                    'material_code' => $this->material->material_id,
                    'material_name' => $this->material->name,
                ] : null;
            }),

            // Material ID for form updates
            'material_id' => $this->material_id,

            // Operators/Users (many-to-many)
            'users' => $this->whenLoaded('users', function() {
                return $this->users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'user_name' => $user->user_name ?? $user->name,
                        'emp_code' => $user->emp_code ?? null,
                        'is_working' => $user->pivot->is_working ?? false,
                        'worked_at' => $user->pivot->worked_at ?? null,
                    ];
                });
            }),

            // Button actions count
            'button_actions_count' => $this->whenLoaded('formButtonActions', function() {
                return $this->formButtonActions->count();
            }),

            // DMI data count
            'dmi_data_count' => $this->whenLoaded('dmiData', function() {
                return $this->dmiData->count();
            }),
            
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

        return $labels[$this->form_status] ?? $this->form_status;
    }
}
