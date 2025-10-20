<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Section;
use App\Models\Machine;
use App\Models\User;
use App\Models\Material;
use App\Models\FormUserAssignment;
use App\Models\FormMaterialAssignment;
use App\Http\Requests\FormRequest;
use App\Http\Resources\FormResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of forms with advanced filters and pagination
     */
    public function index(Request $request)
    {
        try {
            $query = Form::with([
                'section.order',
                'machine.machineType',
                'formUserAssignments.user',
                'formMaterialAssignments.material'
            ]);

            // Search by form_name or form_no
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('form_name', 'like', "%{$search}%")
                      ->orWhere('form_no', 'like', "%{$search}%");
                });
            }

            // Filter by section
            if ($request->has('section_id') && $request->section_id != '') {
                $query->where('section_id', $request->section_id);
            }

            // Filter by machine
            if ($request->has('machine_id') && $request->machine_id != '') {
                $query->where('machine_id', $request->machine_id);
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by operator - check form_user_assignments
            if ($request->has('operator_id') && $request->operator_id != '') {
                $query->whereHas('formUserAssignments', function($q) use ($request) {
                    $q->where('user_id', $request->operator_id);
                });
            }

            // Filter by schedule date range
            if ($request->has('date_from') && $request->date_from != '') {
                $query->whereDate('schedule_date', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to != '') {
                $query->whereDate('schedule_date', '<=', $request->date_to);
            }

            // Sort by latest schedule date
            $query->orderBy('schedule_date', 'desc');

            $perPage = $request->input('per_page', 20);
            $forms = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Forms retrieved successfully',
                'data' => FormResource::collection($forms->items()),
                'meta' => [
                    'current_page' => $forms->currentPage(),
                    'per_page' => $forms->perPage(),
                    'total' => $forms->total(),
                    'last_page' => $forms->lastPage(),
                    'from' => $forms->firstItem() ?? 0,
                    'to' => $forms->lastItem() ?? 0
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve forms: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created form
     */
    public function store(FormRequest $request)
    {
        DB::beginTransaction();
        try {
            // Create form record
            $form = Form::create([
                'form_no' => $request->form_no,
                'form_name' => $request->form_name,
                'section_id' => $request->section_id,
                'machine_id' => $request->machine_id,
                'schedule_date' => $request->schedule_date,
                'status' => 'job_pending',
            ]);

            // Assign operators if provided
            if ($request->has('operator_ids') && is_array($request->operator_ids)) {
                foreach ($request->operator_ids as $userId) {
                    FormUserAssignment::create([
                        'form_id' => $form->id,
                        'user_id' => $userId,
                    ]);
                }
            }

            // Assign materials with quantities if provided
            if ($request->has('material_ids') && is_array($request->material_ids)) {
                foreach ($request->material_ids as $index => $materialId) {
                    $quantity = $request->material_quantities[$index] ?? 0;
                    FormMaterialAssignment::create([
                        'form_id' => $form->id,
                        'material_id' => $materialId,
                        'quantity_assigned' => $quantity,
                    ]);
                }
            }

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machine.machineType',
                    'formUserAssignments.user',
                    'formMaterialAssignments.material'
                ])),
                'Form created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create form: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified form with all relationships
     */
    public function show($id)
    {
        try {
            $form = Form::with([
                'section.order',
                'machine.machineType',
                'formUserAssignments.user',
                'formMaterialAssignments.material',
                'formButtonActions.user',
                'dmiData'
            ])->findOrFail($id);

            return $this->successResponse(
                new FormResource($form),
                'Form retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Form not found', 404);
        }
    }

    /**
     * Update the specified form
     */
    public function update(FormRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);

            // Update form details
            $form->update([
                'form_no' => $request->form_no,
                'form_name' => $request->form_name,
                'section_id' => $request->section_id,
                'machine_id' => $request->machine_id,
                'schedule_date' => $request->schedule_date,
            ]);

            // Update operators if provided
            if ($request->has('operator_ids')) {
                // Delete existing assignments
                FormUserAssignment::where('form_id', $form->id)->delete();
                
                // Create new assignments
                if (is_array($request->operator_ids)) {
                    foreach ($request->operator_ids as $userId) {
                        FormUserAssignment::create([
                            'form_id' => $form->id,
                            'user_id' => $userId,
                        ]);
                    }
                }
            }

            // Update materials if provided
            if ($request->has('material_ids')) {
                // Delete existing assignments
                FormMaterialAssignment::where('form_id', $form->id)->delete();
                
                // Create new assignments
                if (is_array($request->material_ids)) {
                    foreach ($request->material_ids as $index => $materialId) {
                        $quantity = $request->material_quantities[$index] ?? 0;
                        FormMaterialAssignment::create([
                            'form_id' => $form->id,
                            'material_id' => $materialId,
                            'quantity_assigned' => $quantity,
                        ]);
                    }
                }
            }

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machine.machineType',
                    'formUserAssignments.user',
                    'formMaterialAssignments.material'
                ])),
                'Form updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update form: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified form - only if status is job_pending
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);

            // Can only delete forms with job_pending status
            if ($form->status !== 'job_pending') {
                return $this->errorResponse('Cannot delete form that has been started. Only pending forms can be deleted.', 400);
            }

            // Delete related assignments
            FormUserAssignment::where('form_id', $form->id)->delete();
            FormMaterialAssignment::where('form_id', $form->id)->delete();

            // Delete form
            $form->delete();

            DB::commit();

            return $this->successResponse(null, 'Form deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete form: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get form statistics
     */
    public function stats()
    {
        try {
            $stats = [
                'total_forms' => Form::count(),
                'job_pending' => Form::where('status', 'job_pending')->count(),
                'make_ready' => Form::where('status', 'make_ready')->count(),
                'job_started' => Form::where('status', 'job_started')->count(),
                'paused' => Form::where('status', 'paused')->count(),
                'stopped' => Form::where('status', 'stopped')->count(),
                'job_completed' => Form::where('status', 'job_completed')->count(),
                'quality_verified' => Form::where('status', 'quality_verified')->count(),
                'line_cleared' => Form::where('status', 'line_cleared')->count(),
                'forms_by_machine' => Form::select('machine_id', DB::raw('count(*) as total'))
                    ->with('machine:id,machine_id,machine_name')
                    ->groupBy('machine_id')
                    ->orderBy('total', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($item) {
                        return [
                            'machine_id' => $item->machine->machine_id ?? 'N/A',
                            'machine_name' => $item->machine->machine_name ?? 'N/A',
                            'total' => $item->total
                        ];
                    }),
                'scheduled_today' => Form::whereDate('schedule_date', today())->count(),
                'overdue_forms' => Form::whereDate('schedule_date', '<', today())
                    ->whereIn('status', ['job_pending', 'make_ready', 'job_started', 'paused'])
                    ->count(),
            ];

            return $this->successResponse($stats, 'Form statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve statistics', 500);
        }
    }

    /**
     * Get forms for a specific section
     */
    public function getFormsBySection($sectionId)
    {
        try {
            $forms = Form::with([
                'section.order',
                'machine.machineType',
                'formUserAssignments.user',
                'formMaterialAssignments.material'
            ])
            ->where('section_id', $sectionId)
            ->orderBy('schedule_date', 'desc')
            ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Forms retrieved successfully',
                'data' => FormResource::collection($forms->items()),
                'meta' => [
                    'current_page' => $forms->currentPage(),
                    'per_page' => $forms->perPage(),
                    'total' => $forms->total(),
                    'last_page' => $forms->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve forms', 500);
        }
    }

    /**
     * Get forms for a specific machine
     */
    public function getFormsByMachine($machineId)
    {
        try {
            $forms = Form::with([
                'section.order',
                'machine.machineType',
                'formUserAssignments.user',
                'formMaterialAssignments.material'
            ])
            ->where('machine_id', $machineId)
            ->orderBy('schedule_date', 'desc')
            ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Forms retrieved successfully',
                'data' => FormResource::collection($forms->items()),
                'meta' => [
                    'current_page' => $forms->currentPage(),
                    'per_page' => $forms->perPage(),
                    'total' => $forms->total(),
                    'last_page' => $forms->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve forms', 500);
        }
    }

    /**
     * Get forms for a specific operator
     */
    public function getFormsByOperator($userId)
    {
        try {
            $forms = Form::with([
                'section.order',
                'machine.machineType',
                'formUserAssignments.user',
                'formMaterialAssignments.material'
            ])
            ->whereHas('formUserAssignments', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('schedule_date', 'desc')
            ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Forms retrieved successfully',
                'data' => FormResource::collection($forms->items()),
                'meta' => [
                    'current_page' => $forms->currentPage(),
                    'per_page' => $forms->perPage(),
                    'total' => $forms->total(),
                    'last_page' => $forms->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve forms', 500);
        }
    }

    /**
     * Get available forms ready for production
     */
    public function getAvailableForms()
    {
        try {
            $forms = Form::with([
                'section.order',
                'machine.machineType',
                'formUserAssignments.user',
                'formMaterialAssignments.material'
            ])
            ->where('status', 'job_pending')
            ->whereDate('schedule_date', '<=', today())
            ->whereNotNull('machine_id')
            ->whereHas('formUserAssignments')
            ->whereHas('formMaterialAssignments')
            ->orderBy('schedule_date', 'asc')
            ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Available forms retrieved successfully',
                'data' => FormResource::collection($forms->items()),
                'meta' => [
                    'current_page' => $forms->currentPage(),
                    'per_page' => $forms->perPage(),
                    'total' => $forms->total(),
                    'last_page' => $forms->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve forms', 500);
        }
    }

    /**
     * Change form status - validates state machine transitions
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:job_pending,make_ready,job_started,paused,stopped,job_completed,quality_verified,line_cleared',
            'reason' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);
            $currentStatus = $form->status;
            $newStatus = $request->status;

            // Validate state machine transitions
            $validTransitions = [
                'job_pending' => ['make_ready'],
                'make_ready' => ['job_started'],
                'job_started' => ['paused', 'stopped', 'job_completed'],
                'paused' => ['job_started'], // Resume
                'stopped' => [], // Cannot transition from stopped
                'job_completed' => ['quality_verified'],
                'quality_verified' => ['line_cleared'],
                'line_cleared' => []
            ];

            if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
                return $this->errorResponse(
                    "Invalid status transition from {$currentStatus} to {$newStatus}",
                    400
                );
            }

            // Update status
            $form->update(['status' => $newStatus]);

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machine.machineType',
                    'formUserAssignments.user',
                    'formMaterialAssignments.material'
                ])),
                'Form status updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Assign or change machine for a form
     */
    public function assignMachine(Request $request, $id)
    {
        $request->validate([
            'machine_id' => 'required|exists:machines,id'
        ]);

        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);
            $form->update(['machine_id' => $request->machine_id]);

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machine.machineType',
                    'formUserAssignments.user',
                    'formMaterialAssignments.material'
                ])),
                'Machine assigned successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to assign machine: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Assign multiple operators to a form
     */
    public function assignOperators(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);

            // Delete existing assignments
            FormUserAssignment::where('form_id', $form->id)->delete();

            // Create new assignments
            foreach ($request->user_ids as $userId) {
                FormUserAssignment::create([
                    'form_id' => $form->id,
                    'user_id' => $userId,
                ]);
            }

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machine.machineType',
                    'formUserAssignments.user',
                    'formMaterialAssignments.material'
                ])),
                'Operators assigned successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to assign operators: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Assign materials with quantities to a form
     */
    public function assignMaterials(Request $request, $id)
    {
        $request->validate([
            'material_ids' => 'required|array',
            'material_ids.*' => 'exists:materials,id',
            'material_quantities' => 'required|array',
            'material_quantities.*' => 'numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);

            // Delete existing assignments
            FormMaterialAssignment::where('form_id', $form->id)->delete();

            // Create new assignments
            foreach ($request->material_ids as $index => $materialId) {
                $quantity = $request->material_quantities[$index] ?? 0;
                FormMaterialAssignment::create([
                    'form_id' => $form->id,
                    'material_id' => $materialId,
                    'quantity_assigned' => $quantity,
                ]);
            }

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machine.machineType',
                    'formUserAssignments.user',
                    'formMaterialAssignments.material'
                ])),
                'Materials assigned successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to assign materials: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get form history - all button actions for this form
     */
    public function getFormHistory($id)
    {
        try {
            $form = Form::findOrFail($id);
            
            $history = $form->formButtonActions()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($action) {
                    return [
                        'id' => $action->id,
                        'action_name' => $action->action_name,
                        'reason' => $action->reason,
                        'user' => [
                            'id' => $action->user->id ?? null,
                            'name' => $action->user->name ?? 'Unknown',
                            'emp_code' => $action->user->emp_code ?? 'N/A'
                        ],
                        'timestamp' => $action->created_at->toDateTimeString(),
                        'timestamp_formatted' => $action->created_at->format('M d, Y h:i A')
                    ];
                });

            return $this->successResponse($history, 'Form history retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve form history', 500);
        }
    }
}
