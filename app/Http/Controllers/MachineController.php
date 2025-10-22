<?php

namespace App\Http\Controllers;

use App\Http\Requests\MachineRequest;
use App\Http\Resources\MachineResource;
use App\Models\Machine;
use App\Models\MachineType;
use App\Models\Process;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class MachineController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of machines with pagination and filters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 20);
            $search = $request->input('search', '');
            $machineTypeId = $request->input('machine_type_id', '');
            $processId = $request->input('process_id', '');
            $status = $request->input('status', '');

            $query = Machine::with(['machineType', 'process', 'createdBy'])
                ->orderBy('created_at', 'desc');

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('machine_id', 'like', "%{$search}%")
                        ->orWhere('machine_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Machine type filter
            if ($machineTypeId !== '') {
                $query->where('machine_type_id', $machineTypeId);
            }

            // Process filter
            if ($processId !== '') {
                $query->where('process_id', $processId);
            }

            // Status filter
            if ($status !== '') {
                $query->where('status', $status === '1' || $status === 'true');
            }

            $machines = $query->paginate($perPage);

            return $this->successResponse(
                MachineResource::collection($machines)->response()->getData(true),
                'Machines retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve machines: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created machine in storage.
     *
     * @param MachineRequest $request
     * @return JsonResponse
     */
    public function store(MachineRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $machine = Machine::create([
                'machine_id' => $request->machine_id,
                'machine_name' => $request->machine_name,
                'description' => $request->description,
                'min_width' => $request->min_width,
                'min_height' => $request->min_height,
                'max_height' => $request->max_height,
                'max_width' => $request->max_width,
                'min_gsm' => $request->min_gsm,
                'max_gsm' => $request->max_gsm,
                'status' => $request->status ?? true,
                'per_day_impression' => $request->per_day_impression,
                'per_hour_impression' => $request->per_hour_impression,
                'remarks' => $request->remarks,
                'make_ready_time' => $request->make_ready_time,
                'minimum_cost' => $request->minimum_cost,
                'per_hour_cost' => $request->per_hour_cost,
                'meter_per_impression' => $request->meter_per_impression,
                'devise_url' => $request->devise_url,
                'machine_type_id' => $request->machine_type_id,
                'process_id' => $request->process_id,
                'created_by_id' => auth()->id(),
            ]);

            // Assign users if provided
            if ($request->has('user_ids') && is_array($request->user_ids)) {
                $machine->users()->sync($request->user_ids);
            }

            DB::commit();

            return $this->successResponse(
                new MachineResource($machine->load(['machineType', 'process', 'createdBy', 'users'])),
                'Machine created successfully',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                'Failed to create machine: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified machine.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $machine = Machine::with(['machineType', 'process', 'createdBy', 'users'])
                ->findOrFail($id);

            return $this->successResponse(
                new MachineResource($machine),
                'Machine retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Machine not found: ' . $e->getMessage(),
                404
            );
        }
    }

    /**
     * Update the specified machine in storage.
     *
     * @param MachineRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(MachineRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $machine = Machine::findOrFail($id);

            $machine->update([
                'machine_id' => $request->machine_id,
                'machine_name' => $request->machine_name,
                'description' => $request->description,
                'min_width' => $request->min_width,
                'min_height' => $request->min_height,
                'max_height' => $request->max_height,
                'max_width' => $request->max_width,
                'min_gsm' => $request->min_gsm,
                'max_gsm' => $request->max_gsm,
                'status' => $request->status ?? $machine->status,
                'per_day_impression' => $request->per_day_impression,
                'per_hour_impression' => $request->per_hour_impression,
                'remarks' => $request->remarks,
                'make_ready_time' => $request->make_ready_time,
                'minimum_cost' => $request->minimum_cost,
                'per_hour_cost' => $request->per_hour_cost,
                'meter_per_impression' => $request->meter_per_impression,
                'devise_url' => $request->devise_url,
                'machine_type_id' => $request->machine_type_id,
                'process_id' => $request->process_id,
            ]);

            // Update assigned users if provided
            if ($request->has('user_ids') && is_array($request->user_ids)) {
                $machine->users()->sync($request->user_ids);
            }

            DB::commit();

            return $this->successResponse(
                new MachineResource($machine->load(['machineType', 'process', 'createdBy', 'users'])),
                'Machine updated successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                'Failed to update machine: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified machine from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $machine = Machine::findOrFail($id);

            // Detach all relationships
            $machine->users()->detach();
            $machine->forms()->detach();

            $machine->delete();

            DB::commit();

            return $this->successResponse(
                null,
                'Machine deleted successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                'Failed to delete machine: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get all machine types for dropdown.
     *
     * @return JsonResponse
     */
    public function getMachineTypes(): JsonResponse
    {
        try {
            $machineTypes = MachineType::active()
                ->select('id', 'name', 'machine_type')
                ->orderBy('name')
                ->get();

            return $this->successResponse(
                $machineTypes,
                'Machine types retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve machine types: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get all processes for dropdown.
     *
     * @return JsonResponse
     */
    public function getProcesses(): JsonResponse
    {
        try {
            $processes = Process::active()
                ->select('id', 'name', 'msi_id')
                ->orderBy('name')
                ->get();

            return $this->successResponse(
                $processes,
                'Processes retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve processes: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get machine statistics.
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_machines' => Machine::count(),
                'active_machines' => Machine::where('status', true)->count(),
                'inactive_machines' => Machine::where('status', false)->count(),
                'by_type' => Machine::select('machine_type_id', DB::raw('count(*) as count'))
                    ->with('machineType:id,name')
                    ->groupBy('machine_type_id')
                    ->get(),
                'by_process' => Machine::select('process_id', DB::raw('count(*) as count'))
                    ->with('process:id,name')
                    ->groupBy('process_id')
                    ->get(),
            ];

            return $this->successResponse(
                $stats,
                'Machine statistics retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve statistics: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get machines for dropdown.
     *
     * @return JsonResponse
     */
    public function dropdown()
    {
        try {
            $machines = Machine::where('status', true)
                ->with(['machineType:id,name'])
                ->select('id', 'machine_id', 'machine_name', 'machine_type_id')
                ->orderBy('machine_name')
                ->get();

            return $this->successResponse(
                $machines,
                'Machines for dropdown retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve machines: ' . $e->getMessage(),
                500
            );
        }
    }
}
