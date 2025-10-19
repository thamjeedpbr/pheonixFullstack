<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'department_code' => $this->department_code,
            'name' => $this->name,
            'remark' => $this->remark,
            'status' => (bool) $this->status,
            'materials_count' => $this->when(isset($this->materials_count), $this->materials_count),
            'materials' => $this->whenLoaded('materials', function () {
                return $this->materials->map(function ($material) {
                    return [
                        'id' => $material->id,
                        'material_id' => $material->material_id,
                        'name' => $material->name,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
