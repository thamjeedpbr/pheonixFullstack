<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of shifts.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Shift::query()->withCount('users');

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('shift_id', 'like', "%{$search}%")
                    ->orWhere('shift_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status !== null && $status !== '') {
            $query->where('status', (bool) $status);
        }

        $shifts = $query->orderBy('start_time')
            ->paginate($perPage);

        return $this->successResponse(
            ShiftResource::collection($shifts)->response()->getData(true),
            'Shifts retrieved successfully'
        );
    }

    /**
     * Store a newly created shift.
     */
    public function store(ShiftRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $shift = Shift::create([
                'shift_id' => $request->shift_id,
                'shift_name' => $request->shift_name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $request->status ?? true,
            ]);

            DB::commit();

            return $this->successResponse(
                new ShiftResource($shift),
                'Shift created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create shift: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified shift.
     */
    public function show(int $id): JsonResponse
    {
        $shift = Shift::with('users')
            ->withCount('users')
            ->find($id);

        if (!$shift) {
            return $this->errorResponse('Shift not found', 404);
        }

        return $this->successResponse(
            new ShiftResource($shift),
            'Shift retrieved successfully'
        );
    }

    /**
     * Update the specified shift.
     */
    public function update(ShiftRequest $request, int $id): JsonResponse
    {
        $shift = Shift::find($id);

        if (!$shift) {
            return $this->errorResponse('Shift not found', 404);
        }

        try {
            DB::beginTransaction();

            $shift->update([
                'shift_id' => $request->shift_id,
                'shift_name' => $request->shift_name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $request->status ?? $shift->status,
            ]);

            DB::commit();

            return $this->successResponse(
                new ShiftResource($shift),
                'Shift updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update shift: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified shift.
     */
    public function destroy(int $id): JsonResponse
    {
        $shift = Shift::withCount('users')->find($id);

        if (!$shift) {
            return $this->errorResponse('Shift not found', 404);
        }

        // Check if shift has associated users
        if ($shift->users_count > 0) {
            return $this->errorResponse(
                'Cannot delete shift. It has ' . $shift->users_count . ' associated users.',
                400
            );
        }

        try {
            DB::beginTransaction();
            $shift->delete();
            DB::commit();

            return $this->successResponse(null, 'Shift deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete shift: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get shift statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Shift::count(),
            'active' => Shift::where('status', true)->count(),
            'inactive' => Shift::where('status', false)->count(),
            'with_users' => Shift::has('users')->count(),
        ];

        return $this->successResponse($stats, 'Shift statistics retrieved successfully');
    }

    /**
     * Get shifts for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        $shifts = Shift::where('status', true)
            ->select('id', 'shift_id', 'shift_name', 'start_time', 'end_time')
            ->orderBy('start_time')
            ->get();

        return $this->successResponse($shifts, 'Shifts for dropdown retrieved successfully');
    }
}
