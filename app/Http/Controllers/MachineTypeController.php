<?php

namespace App\Http\Controllers;

use App\Http\Requests\MachineTypeRequest;
use App\Http\Resources\MachineTypeResource;
use App\Models\MachineType;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachineTypeController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of machine types.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $machineType = $request->get('machine_type');
        $status = $request->get('status');

        $query = MachineType::with(['createdBy:id,name'])
            ->withCount('machines');

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('type_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('remark', 'like', "%{$search}%");
            });
        }

        // Filter by machine type enum
        if ($machineType) {
            $query->where('machine_type', $machineType);
        }

        // Filter by status
        if ($status !== null && $status !== '') {
            $query->where('status', (bool) $status);
        }

        $machineTypes = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->success(
            MachineTypeResource::collection($machineTypes)->response()->getData(true),
            'Machine types retrieved successfully'
        );
    }

    /**
     * Store a newly created machine type.
     */
    public function store(MachineTypeRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $machineType = MachineType::create([
                'type_id' => $request->type_id,
                'name' => $request->name,
                'remark' => $request->remark,
                'machine_type' => $request->machine_type,
                'status' => $request->status ?? true,
                'created_by_id' => auth()->id(),
            ]);

            DB::commit();

            return $this->success(
                new MachineTypeResource($machineType->load('createdBy')),
                'Machine type created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create machine type: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified machine type.
     */
    public function show(int $id): JsonResponse
    {
        $machineType = MachineType::with(['createdBy', 'machines'])
            ->withCount('machines')
            ->find($id);

        if (!$machineType) {
            return $this->error('Machine type not found', 404);
        }

        return $this->success(
            new MachineTypeResource($machineType),
            'Machine type retrieved successfully'
        );
    }

    /**
     * Update the specified machine type.
     */
    public function update(MachineTypeRequest $request, int $id): JsonResponse
    {
        $machineType = MachineType::find($id);

        if (!$machineType) {
            return $this->error('Machine type not found', 404);
        }

        try {
            DB::beginTransaction();

            $machineType->update([
                'type_id' => $request->type_id,
                'name' => $request->name,
                'remark' => $request->remark,
                'machine_type' => $request->machine_type,
                'status' => $request->status ?? $machineType->status,
            ]);

            DB::commit();

            return $this->success(
                new MachineTypeResource($machineType->load('createdBy')),
                'Machine type updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to update machine type: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified machine type.
     */
    public function destroy(int $id): JsonResponse
    {
        $machineType = MachineType::withCount('machines')->find($id);

        if (!$machineType) {
            return $this->error('Machine type not found', 404);
        }

        // Check if machine type has associated machines
        if ($machineType->machines_count > 0) {
            return $this->error(
                'Cannot delete machine type. It has ' . $machineType->machines_count . ' associated machines.',
                400
            );
        }

        try {
            DB::beginTransaction();
            $machineType->delete();
            DB::commit();

            return $this->success(null, 'Machine type deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to delete machine type: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get machine type statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => MachineType::count(),
            'active' => MachineType::where('status', true)->count(),
            'inactive' => MachineType::where('status', false)->count(),
            'by_type' => MachineType::select('machine_type', DB::raw('count(*) as count'))
                ->groupBy('machine_type')
                ->get()
                ->pluck('count', 'machine_type'),
        ];

        return $this->success($stats, 'Machine type statistics retrieved successfully');
    }

    /**
     * Get machine types for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        $machineTypes = MachineType::where('status', true)
            ->select('id', 'type_id', 'name', 'machine_type')
            ->orderBy('name')
            ->get();

        return $this->success($machineTypes, 'Machine types for dropdown retrieved successfully');
    }
}
