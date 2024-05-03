<?php

namespace App\Http\Controllers;

use App\Enums\LeaveType;
use App\Enums\LeaveStatus;
use App\Facades\Helper;
use Inertia\Inertia;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
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
        if ($request->user()->cannot('viewAny', Leave::class)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Leaves/Index', [
            'leaves' => Leave::filter($request->all())
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
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Leave $leave)
    {
        if ($request->user()->cannot('view', $leave)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Leaves/Show', [
            'leave' => $leave,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Leave $leave)
    {
        if ($request->user()->cannot('update', $leave)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Leaves/Edit', [
            'leave' => $leave,
            'leaveTypes' => LeaveType::toSelectOptions(),
            'statusOptions' => LeaveStatus::toSelectOptions(),
            'users' => User::withoutSuperAdmin()->select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LeaveRequest  $request
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LeaveRequest $request, Leave $leave)
    {
        if ($request->user()->cannot('update', $leave)) {
            abort(403);
        }

        // Start from here ...
        $leave->update(Helper::toSnakeCase($request->only('startDate', 'ednDate', 'title', 'description')));

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
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Leave $leave)
    {
        if ($request->user()->cannot('delete', $leave)) {
            abort(403);
        }

        // Start from here ...
        if ($leave->delete()) {
            session()->flash('flash.banner', 'Deleted successfully.');
            session()->flash('flash.bannerStyle', 'success');
        }
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\leave  $leave
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, leave $leave)
    {
        if (request()->user()->cannot('update', $leave)) {
            abort(403);
        }

        $leave->update(['status' => $request->status]);
        session()->flash('flash.banner', 'Check Sheet udpated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        return back();
    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(Request $request, $type)
    {
        $startDate = request()->input('startDate');
        $endDate =  request()->input('endDate');
        $userId =  request()->input('userId') ?? auth()->id();
        $existingLeave = Leave::whereBetween('start_date', [$startDate, $endDate]) // st < dst < et 
            ->orWhereBetween('end_date', [$startDate, $endDate]) // st < det < et
            ->orWhere([['start_date', '<=', $startDate], ['end_date', '>=', $startDate]]) // dst <= st <= det
            ->orWhere([['start_date', '<=', $endDate], ['end_date', '>=', $endDate]]) // dst <= et <= det
            ->when(
                $type == LeaveType::INDIVIDUAL(), 
                fn($query) => $query->where('user_id', $userId),
                fn($query) => $query->where('type', LeaveType::GENERAL()),
            )
            ->first();

        return response()->json($existingLeave, $existingLeave ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);

    }
}
