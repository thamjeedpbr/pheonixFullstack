<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check Permission Middleware
 * 
 * Verifies that the authenticated user has specific permissions
 * Usage: Route::get('/users', [UserController::class, 'index'])->middleware('check.permission:user_menu_view');
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $permission
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $user = $request->user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login.',
            ], 401);
        }

        // Check if user is active
        if (!$user->status) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact administrator.',
            ], 403);
        }

        // If no specific permission is required, just check authentication
        if (!$permission) {
            return $next($request);
        }

        // Load user permission if not already loaded
        if (!$user->relationLoaded('permission')) {
            $user->load('permission');
        }

        $userPermission = $user->permission;

        // Check if user has permission record
        if (!$userPermission) {
            return response()->json([
                'success' => false,
                'message' => 'No permissions assigned to your account.',
            ], 403);
        }

        // Check if the specific permission exists and is true
        if (!isset($userPermission->{$permission}) || !$userPermission->{$permission}) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.',
                'required_permission' => $permission,
            ], 403);
        }

        // Permission granted, proceed to the route
        return $next($request);
    }
}
