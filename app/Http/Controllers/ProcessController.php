<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessRequest;
use App\Http\Resources\ProcessResource;
use App\Models\Process;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of processes.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Process::query()->withCount(['machines', 'orders', 'forms']);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('msi_id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status !== null && $status !== '') {
            $query->where('status', (bool) $status);
        }

        $processes = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->successResponse(
            ProcessResource::collection($processes)->response()->getData(true),
            'Processes retrieved successfully'
        );
    }

    /**
     * Store a newly created process.
     */
    public function store(ProcessRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $process = Process::create([
                'name' => $request->name,
                'msi_id' => $request->msi_id,
                'status' => $request->status ?? true,
                'created_by_id' => auth()->id(),
            ]);

            DB::commit();

            return $this->successResponse(
                new ProcessResource($process),
                'Process created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create process: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified process.
     */
    public function show(int $id): JsonResponse
    {
        $process = Process::with(['machines', 'createdBy'])
            ->withCount(['machines', 'orders', 'forms'])
            ->find($id);

        if (!$process) {
            return $this->errorResponse('Process not found', 404);
        }

        return $this->successResponse(
            new ProcessResource($process),
            'Process retrieved successfully'
        );
    }

    /**
     * Update the specified process.
     */
    public function update(ProcessRequest $request, int $id): JsonResponse
    {
        $process = Process::find($id);

        if (!$process) {
            return $this->errorResponse('Process not found', 404);
        }

        try {
            DB::beginTransaction();

            $process->update([
                'name' => $request->name,
                'msi_id' => $request->msi_id,
                'status' => $request->status ?? $process->status,
            ]);

            DB::commit();

            return $this->successResponse(
                new ProcessResource($process),
                'Process updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update process: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified process.
     */
    public function destroy(int $id): JsonResponse
    {
        $process = Process::withCount(['machines', 'orders', 'forms'])->find($id);

        if (!$process) {
            return $this->errorResponse('Process not found', 404);
        }

        // Check if process has associated records
        $totalAssociations = $process->machines_count + $process->orders_count + $process->forms_count;
        if ($totalAssociations > 0) {
            return $this->errorResponse(
                'Cannot delete process. It has associated records (Machines: ' . $process->machines_count . 
                ', Orders: ' . $process->orders_count . 
                ', Forms: ' . $process->forms_count . ')',
                400
            );
        }

        try {
            DB::beginTransaction();
            $process->delete();
            DB::commit();

            return $this->successResponse(null, 'Process deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete process: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get process statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Process::count(),
            'active' => Process::where('status', true)->count(),
            'inactive' => Process::where('status', false)->count(),
            'with_machines' => Process::has('machines')->count(),
            'with_orders' => Process::has('orders')->count(),
            'with_forms' => Process::has('forms')->count(),
        ];

        return $this->successResponse($stats, 'Process statistics retrieved successfully');
    }

    /**
     * Get processes for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        $processes = Process::where('status', true)
            ->select('id', 'name', 'msi_id')
            ->orderBy('name')
            ->get();

        return $this->successResponse($processes, 'Processes for dropdown retrieved successfully');
    }
}
