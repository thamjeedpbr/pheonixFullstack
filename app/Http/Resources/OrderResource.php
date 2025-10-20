<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_card_no' => $this->job_card_no,
            'client_name' => $this->client_name,
            'title' => $this->title,
            'description' => $this->description,
            'delivery_date' => $this->delivery_date,
            'priority' => $this->priority,
            'priority_label' => ucfirst($this->priority),
            'status' => $this->status,
            'status_label' => str_replace('_', ' ', ucwords($this->status, '_')),
            'sections' => $this->whenLoaded('sections', function() {
                return $this->sections->map(function($section) {
                    return [
                        'id' => $section->id,
                        'section_no' => $section->section_no,
                        'section_name' => $section->section_name,
                        'forms_count' => $section->forms->count() ?? 0,
                    ];
                });
            }),
            'sections_count' => $this->whenLoaded('sections', function() {
                return $this->sections->count();
            }),
            'created_by' => $this->whenLoaded('createdBy', function() {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                ];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'created_at_formatted' => $this->created_at?->format('M d, Y'),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'delivery_date_formatted' => $this->delivery_date ? date('M d, Y', strtotime($this->delivery_date)) : null,
            'days_until_delivery' => $this->delivery_date ? now()->diffInDays($this->delivery_date, false) : null,
        ];
    }
}
