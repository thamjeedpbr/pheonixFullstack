<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormMachine;
use App\Models\FormUser;
use App\Http\Requests\ProductionFormRequest;
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
                'machines',
                'users',
                'material'
            ]);

            // Search by form_name
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where('form_name', 'like', "%{$search}%");
            }

            // Filter by section
            if ($request->has('section_id') && $request->section_id != '') {
                $query->where('section_id', $request->section_id);
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('form_status', $request->status);
            }

            // Filter by operator - check form_user pivot table
            if ($request->has('operator_id') && $request->operator_id != '') {
                $query->whereHas('users', function($q) use ($request) {
                    $q->where('users.id', $request->operator_id);
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
    public function store(ProductionFormRequest $request)
    {
        DB::beginTransaction();
        try {
            // Create form record
            $form = Form::create([
                'form_name' => $request->form_name,
                'section_id' => $request->section_id,
                'schedule_date' => $request->schedule_date,
                'excepted_qty' => $request->excepted_qty,
                'description' => $request->description,
                'material_id' => $request->material_id, // Single material reference
                'form_status' => 'job_pending',
                'status' => true,
                'created_by_id' => auth()->id(),
            ]);

            // Assign machines if provided (now multiple)
            if ($request->has('machine_ids') && is_array($request->machine_ids) && count($request->machine_ids) > 0) {
                $form->machines()->attach($request->machine_ids);
            }

            // Assign operators if provided
            if ($request->has('operator_ids') && is_array($request->operator_ids) && count($request->operator_ids) > 0) {
                $form->users()->attach($request->operator_ids);
            }

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machines',
                    'users',
                    'material'
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
                'machines',
                'users',
                'material',
                'formButtonActions',
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
    public function update(ProductionFormRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);

            // Update form details
            $form->update([
                'form_name' => $request->form_name,
                'schedule_date' => $request->schedule_date,
                'excepted_qty' => $request->excepted_qty,
                'description' => $request->description,
                'material_id' => $request->material_id, // Single material reference
            ]);

            // Update machine assignments (now multiple)
            if ($request->has('machine_ids')) {
                $form->machines()->sync($request->machine_ids ?? []);
            }

            // Update operators if provided
            if ($request->has('operator_ids')) {
                $form->users()->sync($request->operator_ids ?? []);
            }

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machines',
                    'users',
                    'material'
                ])),
                'Form updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update form: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified form from storage
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);

            // Only allow deletion if form is pending
            if ($form->form_status !== 'job_pending') {
                return $this->errorResponse(
                    'Cannot delete form that has been started. Only pending forms can be deleted.',
                    400
                );
            }

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
                'pending_forms' => Form::where('form_status', 'job_pending')->count(),
                'make_ready_forms' => Form::where('form_status', 'make_ready')->count(),
                'started_forms' => Form::where('form_status', 'job_started')->count(),
                'paused_forms' => Form::where('form_status', 'paused')->count(),
                'completed_forms' => Form::where('form_status', 'job_completed')->count(),
                'verified_forms' => Form::where('form_status', 'quality_verified')->count(),
                'cleared_forms' => Form::where('form_status', 'line_cleared')->count(),
                'scheduled_today' => Form::whereDate('schedule_date', today())->count(),
                'overdue_forms' => Form::where('schedule_date', '<', today())
                    ->whereNotIn('form_status', ['job_completed', 'quality_verified', 'line_cleared'])
                    ->count(),
            ];

            return $this->successResponse($stats, 'Form statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve statistics', 500);
        }
    }

    /**
     * Change form status with state machine validation
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:job_pending,make_ready,job_started,paused,stopped,job_completed,quality_verified,line_cleared'
        ]);

        DB::beginTransaction();
        try {
            $form = Form::findOrFail($id);
            $currentStatus = $form->form_status;
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
            $form->update(['form_status' => $newStatus]);

            DB::commit();

            return $this->successResponse(
                new FormResource($form->load([
                    'section.order',
                    'machines',
                    'users',
                    'material'
                ])),
                'Form status updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update status: ' . $e->getMessage(), 500);
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
