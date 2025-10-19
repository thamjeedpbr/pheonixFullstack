<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type_id' => $this->type_id,
            'name' => $this->name,
            'remark' => $this->remark,
            'machine_type' => $this->machine_type,
            'machine_type_label' => $this->getMachineTypeLabel(),
            'status' => (bool) $this->status,
            'machines_count' => $this->when(isset($this->machines_count), $this->machines_count),
            'created_by' => $this->whenLoaded('createdBy', function () {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                ];
            }),
            'machines' => $this->whenLoaded('machines', function () {
                return $this->machines->map(function ($machine) {
                    return [
                        'id' => $machine->id,
                        'machine_id' => $machine->machine_id,
                        'machine_name' => $machine->machine_name,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Get human-readable label for machine type.
     */
    private function getMachineTypeLabel(): string
    {
        return match ($this->machine_type) {
            'PRE_PRESS' => 'Pre-Press',
            'PRESS' => 'Press',
            'POST_PRESS' => 'Post-Press',
            'DIE_CUT' => 'Die Cut',
            'OTHER' => 'Other',
            default => $this->machine_type,
        };
    }
}
