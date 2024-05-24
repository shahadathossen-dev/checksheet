<?php

namespace App\Http\Controllers\Api;

use App\Enums\AdditionalTaskStatus;
use App\Enums\PurchaseRequestStatus;
use App\Models\TaskList;
use App\Models\TaskItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\DueStatusEvent;
use App\Facades\Helper;
use App\Models\AdditionalTask;
use App\Models\PurchaseRequest;
use App\Http\Controllers\Controller;
use App\Services\StatusUpdateService;
use App\Http\Requests\DashboardItemRequest;

class DashboardControlller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $authUser = $request->user();

        if ($authUser->cannot('viewAny', TaskItem::class)) {
            abort(403);
        }
        
        // $pendingTaskItems = TaskItem::whereHas('tasklist', fn($q) => $q->where('user_id', $authUser->id)->pending())
        //     ->with('tasklist:id,type,due_date', 'checksheetItem:id,title')->pending()->get()->groupBy('tasklist.type');
        
        $pendingChecksheets = TaskList::where('user_id', $authUser->id)->whereDate('due_date', '>=', today()->subDay())
            ->select('id', 'type', 'due_date', 'status')
            ->with([
                // 'checksheet:id,title,created_at,updated_at',
                'items'
            ])
            ->get()->groupBy('type');
        
        $pendingAdditionalTasks = AdditionalTask::where('user_id', $authUser->id)->get();
        $pendingPurchaseRequests = PurchaseRequest::where('user_id', $authUser->id)->get();

        $data = [
            'checksheets' => $pendingChecksheets,
            'additionalTasksList' => $pendingAdditionalTasks,
            'purchaseRequestsList' => $pendingPurchaseRequests,
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Update resource on db.
     * @param  \App\Models\TaskItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTaskItem(DashboardItemRequest $request, TaskItem $taskItem)
    {
        if ($request->user()->cannot('update', $taskItem)) {
            abort(403);
        }

        // $taskItem->update($request->only('note', 'done'));
        $taskItem->update([
            'note' => $request->note,
            'done' => $request->done
        ]);

        $taskItem->tasklist->updateStatus();
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }

    /**
     * Update resource on db.
     * @param  \App\Models\AdditionalTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAdditionalTask(DashboardItemRequest $request)
    {
        if ($request->user()->cannot('create', AdditionalTask::class)) {
            abort(403);
        }
        
        $purchaseRequest = AdditionalTask::create(Helper::toSnakeCase(array_merge($request->only('title', 'description', 'dueDate'), ['userId' => auth()->id()])));
        return response()->json(['status' => 'success', 'message' => 'Crated successfully', 'data' => $purchaseRequest->fresh()]);
    }

    /**
     * Update resource on db.
     * @param  \App\Models\AdditionalTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdditionalTask(DashboardItemRequest $request, AdditionalTask $additionalTask)
    {
        if ($request->user()->cannot('update', $additionalTask)) {
            abort(403);
        }

        // $additionalTask->update($request->only('note', 'status'));
        $additionalTask->update([
            'status' => $request->status == AdditionalTaskStatus::DONE() ? AdditionalTaskStatus::DONE() : AdditionalTaskStatus::DUE()
        ]);

        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
        
    }

    /**
     * Update resource on db.
     * @param  \App\Models\PurchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePurchaseRequest(DashboardItemRequest $request)
    {
        if ($request->user()->cannot('create', PurchaseRequest::class)) {
            abort(403);
        }
        
        $purchaseRequest = PurchaseRequest::create(Helper::toSnakeCase($request->only('title', 'description')));
        return response()->json(['status' => 'success', 'message' => 'Created successfully', 'data' => $purchaseRequest->fresh()]);
    }

    /**
     * Update resource on db.
     * @param  \App\Models\PurchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePurchaseRequest(DashboardItemRequest $request, PurchaseRequest $purchaseRequest)
    {
        if ($request->user()->cannot('update', $purchaseRequest)) {
            abort(403);
        }

        $purchaseRequest->update($request->only('title', 'status'));

        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }

    /**
     * Delete resource on db.
     * @param  \App\Models\PurchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePurchaseRequest(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($request->user()->cannot('delete', $purchaseRequest)) {
            abort(403);
        }

        $purchaseRequest->delete();

        return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
    }

    public function testJob(Request $request, TaskList $tasklist)
    {
        // $tasklist = TaskList::pending()->first();
        $tasklist->items()->update(['done' => 1]);
        StatusUpdateService::update($tasklist);
        return 'success';
        DueStatusEvent::dispatch($tasklist);
        // StatusNotificationJob::dispatchAfterResponse($tasklist);
    }
}
