<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\CheckSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\CheckSheetType;
use App\Enums\TaskListStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CheckSheetExport;
use App\Facades\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckSheetRequest;

class CheckSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', CheckSheet::class)) {
            abort(403);
        }
        // Start from here ...
        return Inertia::render('CheckSheets/Index', [
            'checksheets' => CheckSheet::filter($request->all())
                ->sorted()
                ->with('assignee', 'author')
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
            'checksheetTypes' => CheckSheetType::toSelectOptions(),
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
        if ($request->user()->cannot('create', CheckSheet::class)) {
            abort(403);
        }
        // Start from here ...
        return Inertia::render('CheckSheets/Create', [
            'checksheetTypes' => CheckSheetType::toSelectOptions(),
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
        if ($request->user()->cannot('create', CheckSheet::class)) {
            abort(403);
        }

        DB::transaction(function () use ($request) {
            $checksheet = CheckSheet::create(Helper::toSnakeCase($request->only('title', 'description', 'dueBy', 'userId', 'type')));

            // Collect Check Sheet items from request and sync
            $checksheetItems = collect($request->input('checksheetItems'))->values();
            $checksheet->checksheetItems()->createMany($checksheetItems->toArray());
        });

        session()->flash('flash.banner', 'Created successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->saveAndContinue) {
            return back();
        }
        return redirect()->route('checksheets.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CheckSheet  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, CheckSheet $checksheet)
    {
        if ($request->user()->cannot('view', $checksheet)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('CheckSheets/Show', [
            'checksheet' => $checksheet->load('checksheetItems', 'assignee', 'author'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CheckSheet  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, CheckSheet $checksheet)
    {
        if ($request->user()->cannot('update', $checksheet)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('CheckSheets/Edit', [
            'checksheet'  => $checksheet->load('checksheetItems', 'assignee', 'author'),
            'checksheetTypes' => CheckSheetType::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CheckSheetRequest  $request
     * @param  \App\Models\CheckSheet  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CheckSheetRequest $request, CheckSheet $checksheet)
    {
        if ($request->user()->cannot('update', $checksheet)) {
            abort(403);
        }

        // Start from here ...
        DB::transaction(function () use ($request, $checksheet) {
            // Update check sheet
            $checksheet->update(Helper::toSnakeCase($request->only('title', 'description', 'dueBy')));

            // Collect check sheet attributes from request
            $checksheetItems = collect($request->input('checksheetItems'))->values();

            // Clean removed check sheet items except new added items
            $checksheet->checksheetItems()->whereNotIn('id', $checksheetItems->pluck('id')->reject(fn ($id) => empty($id)))->delete();

            // Update or create check sheet items
            $checksheetItems->each(function ($attribute) use ($checksheet) {
                $checksheet->checksheetItems()->updateOrCreate(
                    ['id' => $attribute['id'] ?? null],
                    [
                        'title' => $attribute['title'],
                        'required' => $attribute['required']
                    ]);
            });

        });

        session()->flash('flash.banner', 'Updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->updateAndContinue) {
            return back();
        }
        return redirect()->route('checksheets.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CheckSheet  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, CheckSheet $checksheet)
    {
        if ($request->user()->cannot('delete', $checksheet)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $checksheet) {
            $checksheet->delete();
        });
    }

    /**
     * Export sale invoices as excel format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        $resource = CheckSheet::filter($request->all())
            ->with('assignee', 'author')
            ->sorted()->get();
        return (new CheckSheetExport($resource))->download('checksheets.xlsx');
    }

    /**
     * Export sale invoices as pdf format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $resource = CheckSheet::filter($request->all())
            ->with('assignee', 'author')
            ->sorted()->get();
        return Pdf::loadView('exports.checksheets.pdf', ['models' => $resource])->stream('checksheets.pdf');
    }

    public function getDetails(Request $request, $type)
    {
        $userId = request('userId') ?? auth()->id();
        $checksheet = CheckSheet::where(['type' => $type, 'user_id' => $userId])
        ->with('assignee', 'checksheetItems')
        ->firstOrFail();
        return response()->json($checksheet, Response::HTTP_OK);
    }
}
