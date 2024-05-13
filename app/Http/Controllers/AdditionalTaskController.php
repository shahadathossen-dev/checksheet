<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\AdditionalTask;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\AdditionalTaskStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CheckSheetExport;
use App\Facades\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionalTaskRequest;
use App\Models\Role;

class AdditionalTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $authUser = $request->user();
        if ($request->user()->cannot('viewAny', AdditionalTask::class)) {
            abort(403);
        }
        
        // Start from here ...
        return Inertia::render('AdditionalTasks/Index', [
            'additionalTasks' => AdditionalTask::filter($request->all())
                ->when(
                    !$authUser->hasRole([Role::SUPER_ADMIN, Role::ADMIN]),
                    fn($query) => $query->where('user_id', $authUser->id)
                )
                ->sorted()
                ->with('assignee', 'author')
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
            'statusOptions' => AdditionalTaskStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', AdditionalTask::class)) {
            abort(403);
        }
        // Start from here ...
        return Inertia::render('AdditionalTasks/Create', [
            'statusOptions' => AdditionalTaskStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AdditionalTaskRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdditionalTaskRequest $request)
    {
        if ($request->user()->cannot('create', AdditionalTask::class)) {
            abort(403);
        }

        DB::transaction(function () use ($request) {
            $additionalTask = AdditionalTask::create(Helper::toSnakeCase($request->only('title', 'description', 'dueDate', 'userId')));
        });

        session()->flash('flash.banner', 'Created successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->saveAndContinue) {
            return back();
        }
        return redirect()->route('additional-tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdditionalTask  $additionalTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, AdditionalTask $additionalTask)
    {
        if ($request->user()->cannot('view', $additionalTask)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('AdditionalTasks/Show', [
            'additionalTask' => $additionalTask->load('assignee', 'author'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdditionalTask  $additionalTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, AdditionalTask $additionalTask)
    {
        if ($request->user()->cannot('update', $additionalTask)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('AdditionalTasks/Edit', [
            'additionalTask'  => $additionalTask->load('assignee', 'author'),
            'statusOptions' => AdditionalTaskStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdditionalTaskRequest  $request
     * @param  \App\Models\AdditionalTask  $additionalTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AdditionalTaskRequest $request, AdditionalTask $additionalTask)
    {
        if ($request->user()->cannot('update', $additionalTask)) {
            abort(403);
        }

        // Start from here ...
        DB::transaction(function () use ($request, $additionalTask) {
            // Update check sheet
            $additionalTask->update(Helper::toSnakeCase($request->only('title', 'description', 'dueDate', 'status')));
        });

        session()->flash('flash.banner', 'Updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->updateAndContinue) {
            return back();
        }
        return redirect()->route('additional-tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdditionalTask  $additionalTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, AdditionalTask $additionalTask)
    {
        if ($request->user()->cannot('delete', $additionalTask)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $additionalTask) {
            $additionalTask->delete();
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\AdditionalTask  $additionalTask
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(AdditionalTask $additionalTask)
    {
        if (request()->user()->cannot('update', $additionalTask)) {
            abort(403);
        }

        $additionalTask->update(['status' => AdditionalTaskStatus::DONE()]);
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
        $resource = AdditionalTask::filter($request->all())
            ->with('assignee', 'author')
            ->sorted()->get();
        return (new CheckSheetExport($resource))->download('additional-tasks.xlsx');
    }

    /**
     * Export sale invoices as pdf format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $resource = AdditionalTask::filter($request->all())
            ->with('assignee', 'author')
            ->sorted()->get();
        return Pdf::loadView('exports.additional-tasks.pdf', ['models' => $resource])->stream('additional-tasks.pdf');
    }

    public function getDetails(Request $request, $userId)
    {
        $userId = request('userId') ?? auth()->id();
        $dueDate = request('dueDate') ?? today();
        $additionalTask = AdditionalTask::where(['due_date' => $dueDate, 'user_id' => $userId])
        ->with('assignee', 'author')
        ->firstOrFail();
        return response()->json($additionalTask, Response::HTTP_OK);
    }
}
