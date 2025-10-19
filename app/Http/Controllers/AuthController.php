<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\LoginDetail;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Authentication Controller
 * 
 * Handles user authentication operations:
 * - Login
 * - Logout
 * - Get authenticated user
 * - Token refresh
 */
class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Login user and create access token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Find user by username
            $user = User::where('user_name', $request->user_name)
                        ->where('status', true)
                        ->with(['roles', 'permissions', 'machines'])
                        ->first();

            // Check if user exists
            if (!$user) {
                return $this->unauthorizedResponse('Invalid username or password');
            }

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return $this->unauthorizedResponse('Invalid username or password');
            }

            // Create Sanctum token
            $tokenName = 'auth_token_' . now()->timestamp;
            $token = $user->createToken($tokenName, ['*'])->plainTextToken;

            // Store token in user's api_key field
            $user->api_key = $token;
            $user->save();

            // Create login detail record
            $loginDetail = LoginDetail::create([
                'user_id' => $user->id,
                'login_time' => now(),
                'is_active' => true,
                'api_key' => $token,
            ]);

            DB::commit();

            // Return success response with token and user data
            return $this->successResponse([
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ], 'Login successful');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->serverErrorResponse(
                'Login failed. Please try again.',
                $e
            );
        }
    }

    /**
     * Logout user and revoke access token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            // Update login detail to inactive
            LoginDetail::where('user_id', $user->id)
                      ->where('is_active', true)
                      ->update([
                          'logout_time' => now(),
                          'is_active' => false,
                      ]);

            // Revoke all user's tokens
            $user->tokens()->delete();

            // Clear api_key
            $user->api_key = null;
            $user->save();

            DB::commit();

            return $this->successResponse(null, 'Logout successful');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->serverErrorResponse(
                'Logout failed. Please try again.',
                $e
            );
        }
    }

    /**
     * Get authenticated user information
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            // Load relationships
            $user->load(['roles', 'permissions', 'machines']);

            return $this->successResourceResponse(
                new UserResource($user),
                'User data retrieved successfully'
            );

        } catch (\Exception $e) {
            return $this->serverErrorResponse(
                'Failed to retrieve user data',
                $e
            );
        }
    }

    /**
     * Refresh user's access token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            // Revoke old tokens
            $user->tokens()->delete();

            // Create new token
            $tokenName = 'auth_token_' . now()->timestamp;
            $token = $user->createToken($tokenName, ['*'])->plainTextToken;

            // Update api_key
            $user->api_key = $token;
            $user->save();

            // Update login detail with new token
            LoginDetail::where('user_id', $user->id)
                      ->where('is_active', true)
                      ->update([
                          'api_key' => $token,
                      ]);

            DB::commit();

            return $this->successResponse([
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Token refreshed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->serverErrorResponse(
                'Token refresh failed',
                $e
            );
        }
    }

    /**
     * Check if user has specific permission
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPermission(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $permission = $request->input('permission');

            if (!$user || !$permission) {
                return $this->errorResponse('Invalid request', 400);
            }

            $hasPermission = $user->hasPermissionTo($permission);

            return $this->successResponse([
                'has_permission' => $hasPermission,
                'permission' => $permission,
            ], 'Permission check completed');

        } catch (\Exception $e) {
            return $this->serverErrorResponse(
                'Permission check failed',
                $e
            );
        }
    }

    /**
     * Check if user has specific role
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkRole(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $role = $request->input('role');

            if (!$user || !$role) {
                return $this->errorResponse('Invalid request', 400);
            }

            $hasRole = $user->hasRole($role);

            return $this->successResponse([
                'has_role' => $hasRole,
                'role' => $role,
            ], 'Role check completed');

        } catch (\Exception $e) {
            return $this->serverErrorResponse(
                'Role check failed',
                $e
            );
        }
    }
}
