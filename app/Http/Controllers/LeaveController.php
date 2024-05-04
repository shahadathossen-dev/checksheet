<?php

namespace App\Http\Controllers;

use App\Enums\LeaveType;
use App\Enums\LeaveStatus;
use App\Exports\LeaveExport;
use App\Facades\Helper;
use Inertia\Inertia;
use App\Models\Leave;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $authUser = $request->user();
        if ($authUser->cannot('viewAny', Leave::class)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Leaves/Index', [
            'leaves' => Leave::filter($request->all())
                ->when(
                    !$authUser->hasRole([Role::SUPER_ADMIN, Role::ADMIN]),
                    fn($query) => $query->where('user_id', $authUser->id)
                )
                ->sorted()
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
            'leaveTypes' => LeaveType::toSelectOptions(),
            'statusOptions' => LeaveStatus::toSelectOptions(),
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
        if ($request->user()->cannot('create', Leave::class)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Leaves/Create', [
            'leaveTypes' => LeaveType::toSelectOptions(),
            'statusOptions' => LeaveStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LeaveRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LeaveRequest $request)
    {
        if ($request->user()->cannot('create', Leave::class)) {
            abort(403);
        }

        // Start from here ...
        DB::transaction(function () use ($request) {
            $leave = Leave::create(Helper::toSnakeCase($request->only('userId', 'startDate', 'ednDate', 'type', 'title', 'description')));
            // Make leaves auto approved for now
            $leave->update(['status' => LeaveStatus::APPROVED()]);
        });


        session()->flash('flash.banner', 'Created successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->saveAndContinue) {
            return back();
        }
        return redirect()->route('leaves.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Leave  $leaf
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Leave $leaf)
    {
        if ($request->user()->cannot('view', $leaf)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Leaves/Show', [
            'leave' => $leaf,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Leave  $leaf
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Leave $leaf)
    {
        if ($request->user()->cannot('update', $leaf)) {
            abort(403);
        }

        // dd($leaf->id);
        // Start from here ...
        return Inertia::render('Leaves/Edit', [
            'leave' => $leaf,
            'leaveTypes' => LeaveType::toSelectOptions(),
            'statusOptions' => LeaveStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LeaveRequest  $request
     * @param  \App\Models\Leave  $leaf
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LeaveRequest $request, Leave $leaf)
    {
        if ($request->user()->cannot('update', $leaf)) {
            abort(403);
        }

        // Start from here ...
        $leaf->update(Helper::toSnakeCase($request->only('startDate', 'ednDate', 'title', 'description')));

        session()->flash('flash.banner', 'Updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->updateAndContinue) {
            return back();
        }
        return redirect()->route('leaves.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Leave  $leaf
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Leave $leaf)
    {
        if ($request->user()->cannot('delete', $leaf)) {
            abort(403);
        }

        // Start from here ...
        if ($leaf->delete()) {
            session()->flash('flash.banner', 'Deleted successfully.');
            session()->flash('flash.bannerStyle', 'success');
        }
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\leave  $leaf
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Leave $leaf)
    {
        if (request()->user()->cannot('update', $leaf)) {
            abort(403);
        }

        $leaf->update(['status' => $request->status]);
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
        $resource = Leave::filter($request->all())
            ->when(
                !$authUser->hasRole([Role::SUPER_ADMIN, Role::ADMIN]),
                fn($query) => $query->where('user_id', $authUser->id)
            )
            ->sorted()->get();
        return (new LeaveExport($resource))->download('leaves.xlsx');
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
        $resource = Leave::filter($request->all())
            ->when(
                !$authUser->hasRole([Role::SUPER_ADMIN, Role::ADMIN]),
                fn($query) => $query->where('user_id', $authUser->id)
            )
            ->sorted()->get();
        return Pdf::loadView('exports.leaves.pdf', ['models' => $resource])->stream('leaves.pdf');
    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(Request $request, $type)
    {
        $userId =  request()->input('userId') ?? auth()->id();

        $existingLeave = Leave::where(function($query) {
            $startDate = request()->input('startDate');
            $endDate =  request()->input('endDate');
            $query->whereBetween('start_date', [$startDate, $endDate]) // st < dst < et 
                ->orWhereBetween('end_date', [$startDate, $endDate]) // st < det < et
                ->orWhere([['start_date', '<=', $startDate], ['end_date', '>=', $startDate]]) // dst <= st <= det
                ->orWhere([['start_date', '<=', $endDate], ['end_date', '>=', $endDate]]); // dst <= et <= det
            })
            ->when(
                $type == LeaveType::INDIVIDUAL(), 
                fn($query) => $query->where('user_id', $userId),
                fn($query) => $query->where('type', LeaveType::GENERAL()),
            )
            ->first();

        return response()->json($existingLeave, $existingLeave ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);

    }
}
