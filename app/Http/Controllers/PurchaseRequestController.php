<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\PurchaseRequestStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CheckSheetExport;
use App\Facades\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequestRequest;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', PurchaseRequest::class)) {
            abort(403);
        }
        // Start from here ...
        return Inertia::render('PurchaseRequests/Index', [
            'purchaseRequests' => PurchaseRequest::filter($request->all())
                ->sorted()
                ->with('user')
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
            'statusOptions' => PurchaseRequestStatus::toSelectOptions(),
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
        if ($request->user()->cannot('create', PurchaseRequest::class)) {
            abort(403);
        }
        // Start from here ...
        return Inertia::render('PurchaseRequests/Create', [
            'statusOptions' => PurchaseRequestStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PurchaseRequestRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PurchaseRequestRequest $request)
    {
        if ($request->user()->cannot('create', PurchaseRequest::class)) {
            abort(403);
        }

        DB::transaction(function () use ($request) {
            $purchaseRequest = PurchaseRequest::create(Helper::toSnakeCase($request->only('title', 'description', 'dueDate')));
        });

        session()->flash('flash.banner', 'Created successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->saveAndContinue) {
            return back();
        }
        return redirect()->route('purchase-requests.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($request->user()->cannot('view', $purchaseRequest)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('PurchaseRequests/Show', [
            'purchaseRequest' => $purchaseRequest->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($request->user()->cannot('update', $purchaseRequest)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('PurchaseRequests/Edit', [
            'purchaseRequest'  => $purchaseRequest->load('user'),
            'statusOptions' => PurchaseRequestStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PurchaseRequestRequest  $request
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PurchaseRequestRequest $request, PurchaseRequest $purchaseRequest)
    {
        if ($request->user()->cannot('update', $purchaseRequest)) {
            abort(403);
        }

        // Start from here ...
        DB::transaction(function () use ($request, $purchaseRequest) {
            // Update check sheet
            $purchaseRequest->update(Helper::toSnakeCase($request->only('title', 'description', 'dueDate', 'status')));
        });

        session()->flash('flash.banner', 'Updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->updateAndContinue) {
            return back();
        }
        return redirect()->route('purchase-requests.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($request->user()->cannot('delete', $purchaseRequest)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $purchaseRequest) {
            $purchaseRequest->delete();
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(PurchaseRequest $purchaseRequest)
    {
        if (request()->user()->cannot('update', $purchaseRequest)) {
            abort(403);
        }

        $purchaseRequest->update(['status' => PurchaseRequestStatus::DONE()]);
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
        $resource = PurchaseRequest::filter($request->all())
            ->with('user')
            ->sorted()->get();
        return (new CheckSheetExport($resource))->download('purchase-requests.xlsx');
    }

    /**
     * Export sale invoices as pdf format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $resource = PurchaseRequest::filter($request->all())
            ->with('user')
            ->sorted()->get();
        return Pdf::loadView('exports.purchase-requests.pdf', ['models' => $resource])->stream('purchase-requests.pdf');
    }

    public function getDetails(Request $request, $userId)
    {
        $userId = request('userId') ?? auth()->id();
        $dueDate = request('dueDate') ?? today();
        $purchaseRequest = PurchaseRequest::where(['due_date' => $dueDate, 'user_id' => $userId])
        ->with('user')
        ->firstOrFail();
        return response()->json($purchaseRequest, Response::HTTP_OK);
    }
}
