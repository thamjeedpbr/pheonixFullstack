<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($user) {
                return [
                    'id' => $user->id,
                    'user_name' => $user->user_name,
                    'name' => $user->name,
                    'phone_no' => $user->phone_no,
                    'user_type' => $user->user_type,
                    'status' => $user->status,
                    'is_super_user' => $user->is_super_user,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    'permission' => $user->permission ? [
                        'id' => $user->permission->id,
                        'role_name' => $user->permission->role_name,
                        'permissions' => $user->permission->permissions ?? [],
                    ] : null,
                    'machines' => $user->machines ? $user->machines->map(function($machine) {
                        return [
                            'id' => $machine->id,
                            'machine_name' => $machine->machine_name,
                        ];
                    }) : [],
                ];
            }),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
            ],
        ];
    }
}
