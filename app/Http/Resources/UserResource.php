<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User Resource
 * 
 * Transforms User model data for API responses
 * Excludes sensitive information like passwords and tokens
 */
class UserResource extends JsonResource
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
            'user_name' => $this->user_name,
            'name' => $this->name,
            'phone_no' => $this->phone_no,
            'user_type' => $this->user_type,
            'status' => (bool) $this->status,
            'is_super_user' => (bool) $this->is_super_user,
            
            // Roles and Permissions using Spatie
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                    ];
                });
            }),
            
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->getAllPermissions()->pluck('name');
            }),
            
            // Direct permissions list
            'permission_list' => $this->when(
                $this->relationLoaded('roles') || $this->relationLoaded('permissions'),
                function () {
                    return $this->getAllPermissions()->pluck('name');
                }
            ),
            
            'machines' => $this->whenLoaded('machines', function () {
                return $this->machines->map(function ($machine) {
                    return [
                        'id' => $machine->id,
                        'machine_name' => $machine->machine_name,
                        'machine_number' => $machine->machine_number,
                    ];
                });
            }),
            
            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'success' => true,
        ];
    }
}
