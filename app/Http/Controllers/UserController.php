<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Models\LoginDetail;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * User Management Controller
 * 
 * Handles CRUD operations for users with server-side pagination
 */
class UserController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of users with server-side pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 20);
            $search = $request->input('search');
            $userType = $request->input('user_type');
            $status = $request->input('status');

            $query = User::with(['permission', 'machines']);

            // Search
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('user_name', 'like', "%{$search}%")
                      ->orWhere('phone_no', 'like', "%{$search}%");
                });
            }

            // Filter by type
            if ($userType) {
                $query->where('user_type', $userType);
            }

            // Filter by status
            if ($status !== null && $status !== '') {
                $query->where('status', (bool) $status);
            }

            // Get paginated results
            $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

            // Return paginated response with meta data
            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => UserResource::collection($users->items()),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
                'prev_page_url' => $users->previousPageUrl(),
                'next_page_url' => $users->nextPageUrl(),
            ]);

        } catch (\Exception $e) {
            return $this->serverErrorResponse(
                'Failed to retrieve users',
                $e
            );
        }
    }

    /**
     * Store a newly created user
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'user_name' => $request->user_name,
                'name' => $request->name,
                'phone_no' => $request->phone_no,
                'user_type' => $request->user_type,
                'permission_id' => $request->permission_id,
                'status' => $request->status ?? true,
                'password' => Hash::make($request->password),
                'is_super_user' => $request->user_type === 'ADMIN',
            ]);

            // Attach machines
            if ($request->has('machine_ids') && is_array($request->machine_ids)) {
                $user->machines()->attach($request->machine_ids);
            }

            $user->load(['permission', 'machines']);

            DB::commit();

            return $this->createdResponse(
                new UserResource($user),
                'User created successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->serverErrorResponse(
                'Failed to create user',
                $e
            );
        }
    }

    /**
     * Display the specified user
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = User::with(['permission', 'machines'])->find($id);

            if (!$user) {
                return $this->notFoundResponse('User not found');
            }

            return $this->successResourceResponse(
                new UserResource($user),
                'User retrieved successfully'
            );

        } catch (\Exception $e) {
            return $this->serverErrorResponse(
                'Failed to retrieve user',
                $e
            );
        }
    }

    /**
     * Update the specified user
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->notFoundResponse('User not found');
            }

            $updateData = [
                'user_name' => $request->user_name,
                'name' => $request->name,
                'phone_no' => $request->phone_no,
                'user_type' => $request->user_type,
                'permission_id' => $request->permission_id,
                'is_super_user' => $request->user_type === 'ADMIN',
            ];

            if ($request->has('status')) {
                $updateData['status'] = $request->status;
            }

            // Update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Sync machines
            if ($request->has('machine_ids')) {
                if (is_array($request->machine_ids)) {
                    $user->machines()->sync($request->machine_ids);
                } else {
                    $user->machines()->detach();
                }
            }

            $user->load(['permission', 'machines']);

            DB::commit();

            return $this->successResourceResponse(
                new UserResource($user),
                'User updated successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->serverErrorResponse(
                'Failed to update user',
                $e
            );
        }
    }

    /**
     * Remove the specified user (soft delete)
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->notFoundResponse('User not found');
            }

            // Cannot delete own account
            $currentUser = $request->user();
            if ($currentUser && $currentUser->id === $user->id) {
                return $this->forbiddenResponse('You cannot delete your own account');
            }

            // Check if user has active sessions
            $hasActiveSessions = LoginDetail::where('user_id', $user->id)
                                           ->where('is_active', true)
                                           ->exists();

            if ($hasActiveSessions) {
                return $this->errorResponse(
                    'Cannot delete user with active sessions',
                    400
                );
            }

            // Soft delete by setting status to false
            $user->status = false;
            $user->save();

            $user->machines()->detach();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->serverErrorResponse(
                'Failed to delete user',
                $e
            );
        }
    }
}
