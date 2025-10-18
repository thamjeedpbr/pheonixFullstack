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
            
            // Relationships
            'permission' => $this->whenLoaded('permission', function () {
                return [
                    'id' => $this->permission->id,
                    'role_name' => $this->permission->role_name,
                    'description' => $this->permission->description,
                    // Include specific permissions if needed
                    'permissions' => $this->getPermissionsList(),
                ];
            }),
            
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
     * Get list of permissions for the user
     *
     * @return array
     */
    protected function getPermissionsList(): array
    {
        if (!$this->permission) {
            return [];
        }

        $permissions = [];
        $permissionFields = [
            // Dashboard
            'dashboard_view',
            
            // Login Management
            'login_menu_view', 'login_menu_create', 'login_menu_update', 'login_menu_delete',
            
            // Job Management
            'job_menu_view', 'job_menu_create', 'job_menu_update', 'job_menu_delete',
            'manual_job_menu_view', 'manual_job_menu_create', 'manual_job_menu_update', 'manual_job_menu_delete',
            
            // Quality Management
            'quality_menu_view', 'quality_menu_create', 'quality_menu_update', 'quality_menu_delete',
            
            // User Management
            'user_menu_view', 'user_menu_create', 'user_menu_update', 'user_menu_delete',
            
            // Role Management
            'role_menu_view', 'role_menu_create', 'role_menu_update', 'role_menu_delete',
            
            // Machine Master
            'machine_master_view', 'machine_master_create', 'machine_master_update', 'machine_master_delete',
            
            // Material Master
            'material_master_view', 'material_master_create', 'material_master_update', 'material_master_delete',
        ];

        foreach ($permissionFields as $field) {
            if ($this->permission->{$field}) {
                $permissions[] = $field;
            }
        }

        return $permissions;
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
