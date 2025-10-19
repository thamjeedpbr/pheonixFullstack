<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Permission Helper Trait
 * 
 * Provides helper methods for permission checks in controllers
 */
trait HasPermissionHelpers
{
    /**
     * Check if authenticated user has permission
     *
     * @param string $permission
     * @return bool
     */
    protected function userHasPermission(string $permission): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        return $user->hasPermissionTo($permission);
    }

    /**
     * Check if authenticated user has role
     *
     * @param string|array $role
     * @return bool
     */
    protected function userHasRole(string|array $role): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        return $user->hasRole($role);
    }

    /**
     * Check if authenticated user has any of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    protected function userHasAnyPermission(array $permissions): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        return $user->hasAnyPermission($permissions);
    }

    /**
     * Check if authenticated user has all of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    protected function userHasAllPermissions(array $permissions): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        return $user->hasAllPermissions($permissions);
    }

    /**
     * Return forbidden response if user doesn't have permission
     *
     * @param string $permission
     * @param string $message
     * @return JsonResponse|null
     */
    protected function checkPermissionOrFail(string $permission, string $message = 'You do not have permission to perform this action.'): ?JsonResponse
    {
        if (!$this->userHasPermission($permission)) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'required_permission' => $permission,
            ], 403);
        }

        return null;
    }

    /**
     * Return forbidden response if user doesn't have role
     *
     * @param string|array $role
     * @param string $message
     * @return JsonResponse|null
     */
    protected function checkRoleOrFail(string|array $role, string $message = 'You do not have the required role to perform this action.'): ?JsonResponse
    {
        if (!$this->userHasRole($role)) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'required_role' => $role,
            ], 403);
        }

        return null;
    }
}
