<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of departments.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Department::query()->withCount('materials');

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('department_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('remark', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status !== null && $status !== '') {
            $query->where('status', (bool) $status);
        }

        $departments = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->success(
            DepartmentResource::collection($departments)->response()->getData(true),
            'Departments retrieved successfully'
        );
    }

    /**
     * Store a newly created department.
     */
    public function store(DepartmentRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $department = Department::create([
                'department_code' => $request->department_code,
                'name' => $request->name,
                'remark' => $request->remark,
                'status' => $request->status ?? true,
            ]);

            DB::commit();

            return $this->success(
                new DepartmentResource($department),
                'Department created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create department: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified department.
     */
    public function show(int $id): JsonResponse
    {
        $department = Department::with('materials')
            ->withCount('materials')
            ->find($id);

        if (!$department) {
            return $this->error('Department not found', 404);
        }

        return $this->success(
            new DepartmentResource($department),
            'Department retrieved successfully'
        );
    }

    /**
     * Update the specified department.
     */
    public function update(DepartmentRequest $request, int $id): JsonResponse
    {
        $department = Department::find($id);

        if (!$department) {
            return $this->error('Department not found', 404);
        }

        try {
            DB::beginTransaction();

            $department->update([
                'department_code' => $request->department_code,
                'name' => $request->name,
                'remark' => $request->remark,
                'status' => $request->status ?? $department->status,
            ]);

            DB::commit();

            return $this->success(
                new DepartmentResource($department),
                'Department updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to update department: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified department.
     */
    public function destroy(int $id): JsonResponse
    {
        $department = Department::withCount('materials')->find($id);

        if (!$department) {
            return $this->error('Department not found', 404);
        }

        // Check if department has associated materials
        if ($department->materials_count > 0) {
            return $this->error(
                'Cannot delete department. It has ' . $department->materials_count . ' associated materials.',
                400
            );
        }

        try {
            DB::beginTransaction();
            $department->delete();
            DB::commit();

            return $this->success(null, 'Department deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to delete department: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get department statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Department::count(),
            'active' => Department::where('status', true)->count(),
            'inactive' => Department::where('status', false)->count(),
            'with_materials' => Department::has('materials')->count(),
        ];

        return $this->success($stats, 'Department statistics retrieved successfully');
    }

    /**
     * Get departments for dropdown.
     */
    public function dropdown(): JsonResponse
    {
        $departments = Department::where('status', true)
            ->select('id', 'department_code', 'name')
            ->orderBy('name')
            ->get();

        return $this->success($departments, 'Departments for dropdown retrieved successfully');
    }
}
