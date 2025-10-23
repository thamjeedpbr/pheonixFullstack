<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    /**
     * Display sections for a specific order
     */
    public function index(Request $request)
    {
        $query = Section::with(['order', 'forms', 'createdBy']);

        // Filter by order
        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('section_id', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 15);
        $sections = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $sections->items(),
            'meta' => [
                'current_page' => $sections->currentPage(),
                'last_page' => $sections->lastPage(),
                'per_page' => $sections->perPage(),
                'total' => $sections->total(),
            ]
        ]);
    }

    /**
     * Store a newly created section (MUST belong to an order)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|string|max:255|unique:sections,section_id',
            'order_id' => 'required|exists:orders,id',
            'status' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // Verify order exists
            $order = Order::findOrFail($validated['order_id']);

            $section = Section::create([
                'section_id' => $validated['section_id'],
                'order_id' => $validated['order_id'],
                'status' => $validated['status'] ?? true,
                'created_by_id' => auth()->id(),
            ]);

            $section->load(['order', 'forms', 'createdBy']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Section created successfully',
                'data' => $section
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Section creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified section with all its forms
     */
    public function show($id)
    {
        try {
            $section = Section::with([
                'order',
                'forms.machines',
                'forms.users',
                'forms.material',
                'forms.section',
                'createdBy'
            ])->findOrFail($id);

            // Add forms count
            $section->forms_count = $section->forms->count();

            return response()->json([
                'success' => true,
                'data' => $section
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ], 404);
        }
    }

    /**
     * Update the specified section
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'section_id' => 'required|string|max:255|unique:sections,section_id,' . $id,
            'status' => 'boolean',
        ]);

        try {
            $section = Section::findOrFail($id);

            $section->update([
                'section_id' => $validated['section_id'],
                'status' => $validated['status'] ?? $section->status,
            ]);

            $section->load(['order', 'forms', 'createdBy']);

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
                'data' => $section
            ]);

        } catch (\Exception $e) {
            Log::error('Section update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified section (only if no forms exist)
     */
    public function destroy($id)
    {
        try {
            $section = Section::findOrFail($id);

            // Check if section has forms
            if ($section->forms()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete section with existing forms. Please delete all forms first.'
                ], 400);
            }

            $section->delete();

            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Section deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all sections for a specific order
     */
    public function getByOrder($orderId)
    {
        try {
            $sections = Section::where('order_id', $orderId)
                ->with(['forms'])
                ->withCount('forms')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $sections
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sections'
            ], 500);
        }
    }
}
