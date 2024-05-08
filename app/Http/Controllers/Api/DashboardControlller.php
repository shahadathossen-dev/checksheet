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

        if ($authUser->cannot('viewAny', TaskList::class)) {
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
    public function updateTaskItem(Request $request, TaskItem $taskItem)
    {
        if ($request->user()->cannot('update', TaskList::class)) {
            abort(403);
        }
        $taskItem->update([
            'note' => $request->note,
            'done' => $request->done,
        ]);

        $taskItem->tasklist->updateStatus();
    }

    /**
     * Update resource on db.
     * @param  \App\Models\AdditionalTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdditionalTask(Request $request, AdditionalTask $additionalTask)
    {
        if ($request->user()->cannot('update', AdditionalTask::class)) {
            abort(403);
        }
        $additionalTask->markAsDone();
        
        // $additionalTask->update(Helper::toSnakeCase($request->only('note', 'status')));
    }

    /**
     * Update resource on db.
     * @param  \App\Models\PurchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePurchaseRequest(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($request->user()->cannot('update', PurchaseRequest::class)) {
            abort(403);
        }
        
        $purchaseRequest = PurchaseRequest::create(Helper::toSnakeCase($request->only('title', 'description', 'dueDate')));
    }

    /**
     * Update resource on db.
     * @param  \App\Models\PurchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePurchaseRequest(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($request->user()->cannot('update', PurchaseRequest::class)) {
            abort(403);
        }
        $purchaseRequest->markAsDone();
        // $purchaseRequest->update(Helper::toSnakeCase($request->only('note', 'status')));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, TaskList $tasklist)
    {
        if ($request->user()->cannot('view', $tasklist)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('TaskLists/Show', [
            'tasklist' => $tasklist->load('checksheet', 'items.checksheetItem', 'assignee', 'author'),
        ]);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, TaskList $tasklist)
    {
        if ($request->user()->cannot('update', $tasklist)) {
            abort(403);
        }

        // Start from here ...
        $tasklist->items->map(function($item) {
            $item->title = $item->checksheetItem->title;
            $item->required = $item->checksheetItem->required;
            return $item;
        });

        return Inertia::render('TaskLists/Edit', [
            'tasklist'  => $tasklist->load('checksheet', 'items.checksheetItem', 'assignee'),
            'checksheetTypes' => CheckSheetType::toSelectOptions(),
            'statusOptions' => TaskListStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TaskListRequest  $request
     * @param  \App\Models\TaskList  $tasklist
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TaskListRequest $request, TaskList $tasklist)
    {
        if ($request->user()->cannot('update', $tasklist)) {
            abort(403);
        }

        // Start from here ...
        DB::transaction(function () use ($request, $tasklist) {

            // Collect check sheet attributes from request
            $taskItems = collect($request->input('items'))->values();

            // Clean removed check sheet items except new added items
            $tasklist->items()->whereNotIn('id', $taskItems->pluck('id')->reject(fn ($id) => empty($id)))->delete();

            // Update or create check sheet items
            $taskItems->each(function ($attribute) use ($tasklist) {
                $tasklist->items()->updateOrCreate(
                    ['id' => $attribute['id'] ?? null],
                    [
                        'note' => $attribute['note'],
                        'done' => $attribute['done'],
                        'checksheet_item_id' => $attribute['checksheetItemId'],
                    ]
                );
            });

            $tasklist->updateStatus();
        });

        session()->flash('flash.banner', 'Updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->updateAndContinue) {
            return back();
        }
        return redirect()->route('tasklists.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, TaskList $tasklist)
    {
        if ($request->user()->cannot('delete', $tasklist)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $tasklist) {
            $tasklist->delete();
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(TaskList $tasklist)
    {
        if (request()->user()->cannot('update', $tasklist)) {
            abort(403);
        }

        $tasklist->update(['status' => TaskListStatus::DONE()]);
        session()->flash('flash.banner', 'Check Sheet udpated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        return back();
    }

    /**
     * Export sale invoices as excel format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        $authUser = $request->user();
        $resource = TaskList::filter($request->all())
            ->when(
                !$authUser->hasRole([Role::SUPER_ADMIN, Role::ADMIN]),
                fn($query) => $query->where('user_id', $authUser->id)
            )
            ->with('checksheet', 'items.checksheetItem', 'assignee', 'author')
            ->sorted()->get();
        return (new TaskListExport($resource))->download('tasklists.xlsx');
    }

    /**
     * Export sale invoices as pdf format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $authUser = $request->user();
        $resource = TaskList::filter($request->all())
            ->when(
                !$authUser->hasRole([Role::SUPER_ADMIN, Role::ADMIN]),
                fn($query) => $query->where('user_id', $authUser->id)
            )
            ->with('checksheet', 'items.checksheetItem', 'assignee', 'author')
            ->sorted()->get();
        return Pdf::loadView('exports.tasklists.pdf', ['models' => $resource])->download('tasklists.pdf');
    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(Request $request, $type)
    {
        $userId = request('userId') ?? auth()->id();
        $dueDate = request('dueDate') ?? today();

        $tasklist = TaskList::where(['type' => $type, 'user_id' => $userId])
            ->when($type == CheckSheetType::DAILY(),
                fn($q) => $q->whereDate('due_date', $dueDate),
                fn($q) => $q->whereDate('due_date', '>=', $dueDate),
            )
            ->with('items.checksheetItem')
            ->latest()->first();
            
        if($tasklist) {
            $tasklist->model = 'tasklist';
            $tasklist->checksheet_id = $tasklist->checksheet->id;
            $tasklist->title = $tasklist->checksheet->title;
            $tasklist->description = $tasklist->checksheet->description;
            $tasklist->items->map(function($item) {
                $item->title = $item->checksheetItem->title;
                $item->required = $item->checksheetItem->required;
                return $item;
            });
        } else {
            $tasklist = CheckSheet::where(['type' => $type, 'user_id' => $userId])
                ->with('checksheetItem')
                ->first();

            if(!$tasklist) return response()->json(['message' => 'Resource not found!'], Response::HTTP_NOT_FOUND);

            $today = today();
            if(!request('dueDate')) {
                if ($tasklist->due_by != null) {
                    $dueDate = $tasklist->type == CheckSheetType::MONTHLY() ?
                        $today->setDays($tasklist->due_by) :
                        ($tasklist->type == CheckSheetType::WEEKLY() ?
                        $today->startOfWeek()->addDays($tasklist->due_by) :
                        $today);
                } else {
                    $dueDate = $tasklist->type == CheckSheetType::MONTHLY() ? $today->endOfMonth() :
                        ($tasklist->type == CheckSheetType::WEEKLY() ? $today->endOfWeek() : $today);
                }
            }
            
            $tasklist->model = 'checksheet';
            $tasklist->checksheet_id = $tasklist->id;
            $tasklist->due_date = Carbon::parse($dueDate)->format('Y-m-d');
            $tasklist->dueDateFormatted = Carbon::parse($dueDate)->format('d, M Y');
            
            $tasklist->items = $tasklist->checksheetItems->map(function($item) {
                $item->done = 0;
                $item->note = null;
                $item->checksheet_item_id = $item->id;
                return $item;
            });
        }

        return response()->json($tasklist, Response::HTTP_OK);
    }
}
