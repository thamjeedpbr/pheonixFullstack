<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialRequest;
use App\Http\Resources\MaterialResource;
use App\Models\Material;
use App\Models\Department;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class MaterialController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of materials with pagination and filters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 20);
            $search = $request->input('search', '');
            $departmentId = $request->input('department_id', '');
            $status = $request->input('status', '');
            $coating = $request->input('coating', '');

            $query = Material::with(['department', 'createdBy'])
                ->orderBy('created_at', 'desc');

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('material_id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('utilization', 'like', "%{$search}%");
                });
            }

            // Department filter
            if ($departmentId !== '') {
                $query->where('department_id', $departmentId);
            }

            // Status filter
            if ($status !== '') {
                $query->where('status', $status === '1' || $status === 'true');
            }

            // Coating filter
            if ($coating !== '') {
                $query->where('coating', $coating === '1' || $coating === 'true');
            }

            $materials = $query->paginate($perPage);

            return $this->successResponse(
                MaterialResource::collection($materials)->response()->getData(true),
                'Materials retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve materials: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created material in storage.
     *
     * @param MaterialRequest $request
     * @return JsonResponse
     */
    public function store(MaterialRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $material = Material::create([
                'material_id' => $request->material_id,
                'name' => $request->name,
                'utilization' => $request->utilization,
                'coating' => $request->coating ?? false,
                'description' => $request->description,
                'gsm' => $request->gsm,
                'unit' => $request->unit,
                'status' => $request->status ?? true,
                'department_id' => $request->department_id,
                'created_by_id' => auth()->id(),
            ]);

            DB::commit();

            return $this->successResponse(
                new MaterialResource($material->load(['department', 'createdBy'])),
                'Material created successfully',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                'Failed to create material: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified material.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $material = Material::with(['department', 'createdBy'])
                ->findOrFail($id);

            return $this->successResponse(
                new MaterialResource($material),
                'Material retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Material not found: ' . $e->getMessage(),
                404
            );
        }
    }

    /**
     * Update the specified material in storage.
     *
     * @param MaterialRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(MaterialRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $material = Material::findOrFail($id);

            $material->update([
                'material_id' => $request->material_id,
                'name' => $request->name,
                'utilization' => $request->utilization,
                'coating' => $request->coating ?? $material->coating,
                'description' => $request->description,
                'gsm' => $request->gsm,
                'unit' => $request->unit,
                'status' => $request->status ?? $material->status,
                'department_id' => $request->department_id,
            ]);

            DB::commit();

            return $this->successResponse(
                new MaterialResource($material->load(['department', 'createdBy'])),
                'Material updated successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                'Failed to update material: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified material from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $material = Material::findOrFail($id);

            // Check if material is used in orders or forms
            if ($material->orders()->count() > 0 || $material->forms()->count() > 0) {
                return $this->errorResponse(
                    'Cannot delete material that is being used in orders or forms',
                    422
                );
            }

            $material->delete();

            DB::commit();

            return $this->successResponse(
                null,
                'Material deleted successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                'Failed to delete material: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get all departments for dropdown.
     *
     * @return JsonResponse
     */
    public function getDepartments(): JsonResponse
    {
        try {
            $departments = Department::active()
                ->select('id', 'name', 'department_code')
                ->orderBy('name')
                ->get();

            return $this->successResponse(
                $departments,
                'Departments retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve departments: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get material statistics.
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_materials' => Material::count(),
                'active_materials' => Material::where('status', true)->count(),
                'inactive_materials' => Material::where('status', false)->count(),
                'coated_materials' => Material::where('coating', true)->count(),
                'by_department' => Material::select('department_id', DB::raw('count(*) as count'))
                    ->with('department:id,name')
                    ->groupBy('department_id')
                    ->get(),
                'total_gsm_range' => [
                    'min' => Material::min('gsm'),
                    'max' => Material::max('gsm'),
                ],
            ];

            return $this->successResponse(
                $stats,
                'Material statistics retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve statistics: ' . $e->getMessage(),
                500
            );
        }
    }
       /**
     * Get materials for dropdown.
     *
     * @return JsonResponse
     */
    public function dropdown()
    {
        try {
            $materials = Material::where('status', true)
                ->with(['department:id,name'])
                ->select('id', 'name', 'department_id')
                ->orderBy('name')
                ->get();

            return $this->successResponse(
                $materials,
                'Materials for dropdown retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve materials: ' . $e->getMessage(),
                500
            );
        }
    }
}
