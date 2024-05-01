<?php

namespace App\Http\Controllers;

use App\Enums\CheckSheetType;
use App\Models\User;
use Inertia\Inertia;
use App\Models\TaskList;
use Illuminate\Http\Request;
use App\Enums\TaskListStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CheckSheetExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckSheetRequest;
use App\Http\Requests\TaskListRequest;
use App\Models\CheckSheet;
use Illuminate\Http\Response;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', TaskList::class)) {
            abort(403);
        }
        // Start from here ...
        return Inertia::render('TaskLists/Index', [
            'tasklists' => TaskList::filter($request->all())
                ->sorted()
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
            'statusOptions' => TaskListStatus::toSelectOptions(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', TaskList::class)) {
            abort(403);
        }
        // Start from here ...
        return Inertia::render('TaskLists/Create', [
            'checksheetTypes' => CheckSheetType::toSelectOptions(),
            'statusOptions' => TaskListStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CheckSheetRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaskListRequest $request)
    {
        if ($request->user()->cannot('create', TaskList::class)) {
            abort(403);
        }

        DB::transaction(function () use ($request) {
            $tasklist = TaskList::create($request->only('checksheet_id', 'due_date', 'user_id', 'type'));
            
            // Collect Check Sheet items from request and sync
            $taskItems = collect($request->input('items'))->values();
            $tasklist->items()->createMany($taskItems->toArray());
        });

        session()->flash('flash.banner', 'Created successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->saveAndContinue) {
            return back();
        }
        return redirect()->route('tasklists.index');
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
            'tasklist' => $tasklist,
        ]);
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
        return Inertia::render('TaskLists/Edit', [
            'tasklist'  => $tasklist,
            'checksheetTypes' => CheckSheetType::toSelectOptions(),
            'statusOptions' => TaskListStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CheckSheetRequest  $request
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
            $tasklist->update($request->only('checksheet_id', 'due_date', 'user_id', 'type'));
            // Collect check sheet attributes from request
            $taskItems = collect($request->input('items'))->values();

            // Clean removed check sheet items except new added items
            $tasklist->items()->whereNotIn('id', $taskItems->pluck('id')->reject(fn ($id) => empty($id)))->delete();

            // Update or create check sheet items
            $taskItems->each(function ($attribute) use ($tasklist) {
                $tasklist->items()->updateOrCreate(
                    ['checksheet_id' => $attribute['checksheet_id']],
                    [
                        'note' => $attribute['note'],
                        'done' => $attribute['done'],
                    ]
                );
            });
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
        return (new CheckSheetExport($request->all()))->download('tasklists.xlsx');
    }

    /**
     * Export sale invoices as pdf format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        return Pdf::loadView('exports.tasklists.pdf', [
                'models' => TaskList::filter($request->all())->orderBy('id', 'desc')->get()
            ])->download('tasklists.pdf');
    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(Request $request, $type)
    {
        $userId = request('user_id') ?? auth()->id();
        $dueDate = request('due_date') ?? today();

        $tasklist = TaskList::where(['type' => $type, 'user_id' => $userId])
            ->when($type == CheckSheetType::DAILY(),
                fn($q) => $q->whereDate('due_date', $dueDate),
                fn($q) => $q->whereDate('due_date', '>=', $dueDate),
            )
            ->latest()->first();
        if($tasklist) {
            $tasklist->model = 'tasklist';
            $tasklist->checksheet_id = $tasklist->checksheet->id;
            $tasklist->due_date = $tasklist->dueDate;
            $tasklist->title = $tasklist->checksheet->title;
            $tasklist->description = $tasklist->checksheet->description;
            $tasklist->items->map(fn($item) => $item->title = $item->checksheetItem->title);
        } else {
            $tasklist = CheckSheet::where(['type' => $type, 'user_id' => $userId])->first();

            if(!$tasklist) return response()->json(['message' => 'Resource not found!'], Response::HTTP_NOT_FOUND);

            $today = today();
            if(!request('due_date')) {
                if ($tasklist->due_by != null) {
                    $dueDate = $tasklist->type == CheckSheetType::MONTHLY() ?
                        $today->setDays($tasklist->due_by) :
                        ($tasklist->type == CheckSheetType::WEEKLY() ?
                        $today->startOfWeek()->addDays($tasklist->due_by) :
                        $today);
                } else {
                    $dueDate = $tasklist->type == CheckSheetType::MONTHLY() ? $today->endOfMonth() :
                        ($tasklist->type == CheckSheetType::WEEKLY() ? $today->endOfWeek() :
                        $today);
                }
            }
            
            $tasklist->model = 'checksheet';
            $tasklist->checksheet_id = $tasklist->id;
            $tasklist->due_date = $dueDate;
            $tasklist->dueDateFormatted = $dueDate->format('d, M Y');
            
            $tasklist->items = $tasklist->checksheetItems->map(function($item) {
                $item->done = null;
                $item->note = null;
                $item->checksheet_item_id = $item->id;
                return $item;
            });
        }

        return response()->json($tasklist, Response::HTTP_OK);
    }
}
