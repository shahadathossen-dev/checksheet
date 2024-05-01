<?php

namespace App\Http\Controllers;

use App\Enums\CheckSheetStatus;
use App\Enums\CheckSheetType;
use App\Enums\TaskListStatus;
use App\Exports\CheckSheetExport;
use App\Models\CheckSheet;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckSheetRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

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
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
            'checksheetTypes' => CheckSheetType::toSelectOptions(),
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
            $checksheet = CheckSheet::create($request->only('title', 'description', 'due_by', 'user_id', 'type'));

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
            'checksheet' => $checksheet,
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
            'checksheet'  => $checksheet,
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
            $checksheet->update($request->only('title', 'description', 'due_by', 'user_id', 'type'));

            // Collect check sheet attributes from request
            $checksheetItems = collect($request->input('checksheetItems'))->values();

            // Clean removed check sheet items except new added items
            $checksheet->checksheetItems()->whereNotIn('id', $checksheetItems->pluck('id')->reject(fn ($id) => empty($id)))->delete();

            // Update or create check sheet items
            $checksheetItems->each(function ($attribute) use ($checksheet) {
                $checksheet->checksheetItems()->updateOrCreate(['title' => $attribute['title']], ['required' => $attribute['required']]);
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
     * Update the specified resource in storage.
     *
     * @param  \App\Models\CheckSheet  $checksheet
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(CheckSheet $checksheet)
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
                'models' => CheckSheet::filter($request->all())->orderBy('id', 'desc')->get()
            ])->download('checksheets.pdf');
    }

    public function getDetails(Request $request, $type)
    {
        $userId = request('user_id') ?? auth()->id();
        $checksheet = CheckSheet::where(['type' => $type, 'user_id' => $userId])->firstOrFail();
        return response()->json($checksheet, Response::HTTP_OK);
    }
}
