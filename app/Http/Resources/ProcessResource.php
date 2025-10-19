<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'msi_id' => $this->msi_id,
            'status' => (bool) $this->status,
            'machines_count' => $this->when(isset($this->machines_count), $this->machines_count),
            'orders_count' => $this->when(isset($this->orders_count), $this->orders_count),
            'forms_count' => $this->when(isset($this->forms_count), $this->forms_count),
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
}
