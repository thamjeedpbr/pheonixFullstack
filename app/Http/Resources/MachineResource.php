<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
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
            'machine_id' => $this->machine_id,
            'machine_name' => $this->machine_name,
            'description' => $this->description,
            'min_width' => $this->min_width,
            'min_height' => $this->min_height,
            'max_height' => $this->max_height,
            'max_width' => $this->max_width,
            'min_gsm' => $this->min_gsm,
            'max_gsm' => $this->max_gsm,
            'status' => $this->status,
            'per_day_impression' => $this->per_day_impression,
            'per_hour_impression' => $this->per_hour_impression,
            'remarks' => $this->remarks,
            'make_ready_time' => $this->make_ready_time,
            'minimum_cost' => $this->minimum_cost,
            'per_hour_cost' => $this->per_hour_cost,
            'meter_per_impression' => $this->meter_per_impression,
            'devise_url' => $this->devise_url,
            
            // Relationships
            'machine_type' => $this->whenLoaded('machineType', function () {
                return [
                    'id' => $this->machineType->id,
                    'name' => $this->machineType->name,
                    'machine_type' => $this->machineType->machine_type,
                ];
            }),
            
            'process' => $this->whenLoaded('process', function () {
                return [
                    'id' => $this->process->id,
                    'name' => $this->process->name,
                    'msi_id' => $this->process->msi_id,
                ];
            }),
            
            'created_by' => $this->whenLoaded('createdBy', function () {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                    'user_name' => $this->createdBy->user_name,
                ];
            }),
            
            'users' => $this->whenLoaded('users', function () {
                return $this->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'user_name' => $user->user_name,
                        'user_type' => $user->user_type,
                    ];
                });
            }),
            
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
