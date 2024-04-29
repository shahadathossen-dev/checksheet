<?php

namespace App\Http\Controllers;

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
        return Inertia::render('CheckSheets/Index', [
            'checksheets' => TaskList::filter($request->all())
                ->sorted()
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
            'checksheetTypes' => TaskListStatus::toSelectOptions(),
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
        return Inertia::render('Users/Create', [
            'checksheetTypes' => TaskListStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CheckSheetRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CheckSheetRequest $request)
    {
        if ($request->user()->cannot('create', TaskList::class)) {
            abort(403);
        }

        DB::transaction(function () use ($request) {
            $checksheet = TaskList::create($request->only('title', 'description', 'due_by', 'user_id', 'type'));
        });

        session()->flash('flash.banner', 'Created successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->saveAndContinue) {
            return back();
        }
        return redirect()->route('checkshets.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskList  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, TaskList $checksheet)
    {
        if ($request->user()->cannot('view', $checksheet)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('CheckSheets/Show', [
            'checksheet' => $checksheet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskList  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, TaskList $checksheet)
    {
        if ($request->user()->cannot('update', $checksheet)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('CheckSheets/Edit', [
            'checksheet'  => $checksheet,
            'checksheetTypes' => TaskListStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CheckSheetRequest  $request
     * @param  \App\Models\TaskList  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CheckSheetRequest $request, TaskList $checksheet)
    {
        if ($request->user()->cannot('update', $checksheet)) {
            abort(403);
        }

        // Start from here ...
        DB::transaction(function () use ($request, $checksheet) {
            $checksheet = $checksheet->update($request->only('title', 'description', 'due_by', 'user_id', 'type'));
        });

        session()->flash('flash.banner', 'Updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->updateAndContinue) {
            return back();
        }
        return redirect()->route('checkshets.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskList  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, TaskList $checksheet)
    {
        if ($request->user()->cannot('delete', $checksheet)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $checksheet) {
            $checksheet->delete();
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\TaskList  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(TaskList $checksheet)
    {
        if (request()->user()->cannot('update', $checksheet)) {
            abort(403);
        }

        $checksheet->update(['status' => TaskListStatus::DONE()]);
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
        return (new CheckSheetExport($request->all()))->download('checksheets.xlsx');
    }

    /**
     * Export sale invoices as pdf format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        return Pdf::loadView('exports.checksheets.pdf', [
                'models' => TaskList::filter($request->all())->orderBy('id', 'desc')->get()
            ])->download('checksheets.pdf');
    }
}
