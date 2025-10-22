<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of sections.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Section::query();

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('section_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('remark', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status !== null && $status !== '') {
            $query->where('status', (bool) $status);
        }

        $sections = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->successResponse(
            SectionResource::collection($sections)->response()->getData(true),
            'Sections retrieved successfully'
        );
    }

    /**
     * Store a newly created section.
     */
    public function store(SectionRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $section = Section::create([
                'section_id' => $request->section_id,
                'name' => $request->name,
                'remark' => $request->remark,
                'status' => $request->status ?? true,
            ]);

            DB::commit();

            return $this->successResponse(
                new SectionResource($section),
                'Section created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create section: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified section.
     */
    public function show(int $id): JsonResponse
    {
        $section = Section::find($id);

        if (!$section) {
            return $this->errorResponse('Section not found', 404);
        }

        return $this->successResponse(
            new SectionResource($section),
            'Section retrieved successfully'
        );
    }

    /**
     * Update the specified section.
     */
    public function update(SectionRequest $request, int $id): JsonResponse
    {
        $section = Section::find($id);

        if (!$section) {
            return $this->errorResponse('Section not found', 404);
        }

        try {
            DB::beginTransaction();

            $section->update([
                'section_id' => $request->section_id,
                'name' => $request->name,
                'remark' => $request->remark,
                'status' => $request->status ?? $section->status,
            ]);

            DB::commit();

            return $this->successResponse(
                new SectionResource($section),
                'Section updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update section: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified section.
     */
    public function destroy(int $id): JsonResponse
    {
        $section = Section::find($id);

        if (!$section) {
            return $this->errorResponse('Section not found', 404);
        }

        try {
            DB::beginTransaction();
            $section->delete();
            DB::commit();

            return $this->successResponse(null, 'Section deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete section: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get section statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Section::count(),
            'active' => Section::where('status', true)->count(),
            'inactive' => Section::where('status', false)->count(),
        ];

        return $this->successResponse($stats, 'Section statistics retrieved successfully');
    }

    /**
     * Get sections for dropdown.
     */
    public function dropdown()
    {
        $sections = Section::where('status', true)
            ->with(['order:id,job_card_no,client_name'])
            ->select('id', 'section_id', 'order_id')
            ->orderBy('section_id')
            ->get();

        return $this->successResponse($sections, 'Sections for dropdown retrieved successfully');
    }
}
