<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Section;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of orders with filters and pagination
     */
    public function index(Request $request)
    {
        try {
            $query = Order::with(['sections', 'createdBy']);

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('job_card_no', 'like', "%{$search}%")
                      ->orWhere('client_name', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by priority
            if ($request->has('priority') && $request->priority != '') {
                $query->where('priority', $request->priority);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from != '') {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to != '') {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Sort by latest
            $query->orderBy('created_at', 'desc');

            $perPage = $request->input('per_page', 20);
            $orders = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully',
                'data' => OrderResource::collection($orders->items()),
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'last_page' => $orders->lastPage(),
                    'from' => $orders->firstItem() ?? 0,
                    'to' => $orders->lastItem() ?? 0
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve orders: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created order
     */
    public function store(OrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'job_card_no' => $request->job_card_no,
                'client_name' => $request->client_name,
                'title' => $request->title,
                'description' => $request->description,
                'delivery_date' => $request->delivery_date,
                'priority' => $request->priority ?? 'normal',
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return $this->successResponse(
                new OrderResource($order->load('sections', 'createdBy')),
                'Order created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create order: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        try {
            $order = Order::with(['sections.forms', 'createdBy'])->findOrFail($id);
            return $this->successResponse(
                new OrderResource($order),
                'Order retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Order not found', 404);
        }
    }

    /**
     * Update the specified order
     */
    public function update(OrderRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            
            $order->update([
                'job_card_no' => $request->job_card_no,
                'client_name' => $request->client_name,
                'title' => $request->title,
                'description' => $request->description,
                'delivery_date' => $request->delivery_date,
                'priority' => $request->priority ?? $order->priority,
                'status' => $request->status ?? $order->status,
            ]);

            DB::commit();

            return $this->successResponse(
                new OrderResource($order->load('sections', 'createdBy')),
                'Order updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update order: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified order
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            
            // Check if order has forms
            $formCount = $order->sections()->withCount('forms')->get()->sum('forms_count');
            if ($formCount > 0) {
                return $this->errorResponse('Cannot delete order with associated forms', 400);
            }

            $order->delete();
            DB::commit();

            return $this->successResponse(null, 'Order deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete order: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get order statistics
     */
    public function stats()
    {
        try {
            $stats = [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'in_progress_orders' => Order::where('status', 'in_progress')->count(),
                'completed_orders' => Order::where('status', 'completed')->count(),
                'cancelled_orders' => Order::where('status', 'cancelled')->count(),
                'urgent_orders' => Order::where('priority', 'urgent')->count(),
                'this_month_orders' => Order::whereMonth('created_at', now()->month)->count(),
            ];

            return $this->successResponse($stats, 'Order statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve statistics', 500);
        }
    }

    /**
     * Change order status
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            $order->update(['status' => $request->status]);
            DB::commit();

            return $this->successResponse(
                new OrderResource($order->load('sections', 'createdBy')),
                'Order status updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get orders by status
     */
    public function byStatus($status)
    {
        try {
            $orders = Order::with(['sections', 'createdBy'])
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully',
                'data' => OrderResource::collection($orders->items()),
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'last_page' => $orders->lastPage(),
                    'from' => $orders->firstItem() ?? 0,
                    'to' => $orders->lastItem() ?? 0
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve orders', 500);
        }
    }
}
