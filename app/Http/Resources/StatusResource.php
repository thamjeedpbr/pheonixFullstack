<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status_id' => $this->status_id,
            'name' => $this->name,
            'remark' => $this->remark,
            'description' => $this->description,
            'status' => (bool) $this->status,
            'sub_statuses_count' => $this->when(isset($this->sub_statuses_count), $this->sub_statuses_count),
            'forms_count' => $this->when(isset($this->forms_count), $this->forms_count),
            'sub_statuses' => $this->whenLoaded('subStatuses', function () {
                return $this->subStatuses->map(function ($subStatus) {
                    return [
                        'id' => $subStatus->id,
                        'sub_status_code' => $subStatus->sub_status_code,
                        'sub_status_name' => $subStatus->sub_status_name,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
