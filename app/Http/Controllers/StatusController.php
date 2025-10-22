<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusRequest;
use App\Http\Resources\StatusResource;
use App\Models\Status;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of statuses.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Status::query()->withCount(['subStatuses', 'forms']);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('remark', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status !== null && $status !== '') {
            $query->where('status', (bool) $status);
        }

        $statuses = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->successResponse(
            StatusResource::collection($statuses)->response()->getData(true),
            'Statuses retrieved successfully'
        );
    }

    /**
     * Store a newly created status.
     */
    public function store(StatusRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $status = Status::create([
                'status_id' => $request->status_id,
                'name' => $request->name,
                'remark' => $request->remark,
                'description' => $request->description,
                'status' => $request->status ?? true,
            ]);

            DB::commit();

            return $this->successResponse(
                new StatusResource($status),
                'Status created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified status.
     */
    public function show(int $id): JsonResponse
    {
        $status = Status::with(['subStatuses', 'forms'])
            ->withCount(['subStatuses', 'forms'])
            ->find($id);

        if (!$status) {
            return $this->errorResponse('Status not found', 404);
        }

        return $this->successResponse(
            new StatusResource($status),
            'Status retrieved successfully'
        );
    }

    /**
     * Update the specified status.
     */
    public function update(StatusRequest $request, int $id): JsonResponse
    {
        $status = Status::find($id);

        if (!$status) {
            return $this->errorResponse('Status not found', 404);
        }

        try {
            DB::beginTransaction();

            $status->update([
                'status_id' => $request->status_id,
                'name' => $request->name,
                'remark' => $request->remark,
                'description' => $request->description,
                'status' => $request->status ?? $status->status,
            ]);

            DB::commit();

            return $this->successResponse(
                new StatusResource($status),
                'Status updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified status.
     */
    public function destroy(int $id): JsonResponse
    {
        $status = Status::withCount(['subStatuses', 'forms'])->find($id);

        if (!$status) {
            return $this->errorResponse('Status not found', 404);
        }

        // Check if status has associated records
        $totalAssociations = $status->sub_statuses_count + $status->forms_count;
        if ($totalAssociations > 0) {
            return $this->errorResponse(
                'Cannot delete status. It has associated records (Sub Statuses: ' . $status->sub_statuses_count . 
                ', Forms: ' . $status->forms_count . ')',
                400
            );
        }

        try {
            DB::beginTransaction();
            $status->delete();
            DB::commit();

            return $this->successResponse(null, 'Status deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get status statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Status::count(),
            'active' => Status::where('status', true)->count(),
            'inactive' => Status::where('status', false)->count(),
            'with_sub_statuses' => Status::has('subStatuses')->count(),
            'with_forms' => Status::has('forms')->count(),
        ];

        return $this->successResponse($stats, 'Status statistics retrieved successfully');
    }

    /**
     * Get statuses for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        $statuses = Status::where('status', true)
            ->select('id', 'status_id', 'name', 'description')
            ->orderBy('name')
            ->get();

        return $this->successResponse($statuses, 'Statuses for dropdown retrieved successfully');
    }
}
