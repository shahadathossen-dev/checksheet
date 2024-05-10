<?php

namespace App\Http\Controllers\Api;

use App\Enums\AdditionalTaskStatus;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Role;
use App\Facades\Helper;
use App\Models\TaskList;
use App\Models\CheckSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\TaskListStatus;
use App\Enums\CheckSheetType;
use App\Events\DueStatusEvent;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TaskListExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardItemRequest;
use App\Http\Requests\TaskListRequest;
use App\Models\AdditionalTask;
use App\Models\PurchaseRequest;
use App\Models\TaskItem;
use App\Services\StatusUpdateService;

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
        
        $pendingChecksheets = TaskList::where('user_id', $authUser->id)->pending()->select('id','type','due_date')
            ->with([
                // 'checksheet:id,title,created_at,updated_at',
                'items' => fn($q) => $q->pending()->with('checksheetItem:id,title,required')
            ])
            ->get()->groupBy('type');
        
        $pendingAdditionalTasks = AdditionalTask::where('user_id', $authUser->id)->pending()->get();
        $pendingPurchaseRequests = PurchaseRequest::where('user_id', $authUser->id)->pending()->get();

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

        $taskItem->update($request->only('note', 'done'));

        $taskItem->tasklist->updateStatus();
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
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

        $additionalTask->markAsDone();
        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
        
        // $additionalTask->update($request->only('note', 'status'));
    }

    /**
     * Update resource on db.
     * @param  \App\Models\PurchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePurchaseRequest(DashboardItemRequest $request)
    {
        if ($request->user()->cannot('update', PurchaseRequest::class)) {
            abort(403);
        }
        
        $purchaseRequest = PurchaseRequest::create(Helper::toSnakeCase($request->only('title', 'description', 'dueDate')));
        return response()->json(['status' => 'success', 'message' => 'Updated successfully', 'data' => $purchaseRequest]);
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

        $purchaseRequest->markAsDone();
        // $purchaseRequest->update($request->only('note', 'status'));
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
