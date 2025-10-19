<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shift_id' => $this->shift_id,
            'shift_name' => $this->shift_name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'start_time_formatted' => $this->start_time ? \Carbon\Carbon::parse($this->start_time)->format('h:i A') : null,
            'end_time_formatted' => $this->end_time ? \Carbon\Carbon::parse($this->end_time)->format('h:i A') : null,
            'duration' => $this->getDuration(),
            'status' => (bool) $this->status,
            'users_count' => $this->when(isset($this->users_count), $this->users_count),
            'users' => $this->whenLoaded('users', function () {
                return $this->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'user_id' => $user->user_id,
                        'name' => $user->name,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Calculate shift duration in hours.
     */
    private function getDuration(): ?string
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        if ($end->lt($start)) {
            $end->addDay();
        }
        
        $hours = $start->diffInHours($end);
        $minutes = $start->copy()->addHours($hours)->diffInMinutes($end);
        
        return $hours . 'h ' . $minutes . 'm';
    }
}
