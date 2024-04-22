<?php

namespace App\Http\Controllers;

use App\Enums\ActiveStatus;
use App\Exports\DelegatesExport;
use App\Models\Delegate;
use Inertia\Inertia;
use Maatwebsite\Excel\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class DelegateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Delegate::class)) {
            abort(403);
        }
        // Start from here ...
        return Inertia::render('Delegates/Index', [
            'delegates' => Delegate::filter($request->all())
                ->sorted()
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
            'statusOptions' => ActiveStatus::toSelectOptions(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Delegate $delegate)
    {
        if ($request->user()->cannot('view', $delegate)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Delegates/Show', [
            'delegate' => $delegate,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Delegate $delegate)
    {
        if ($request->user()->cannot('update', $delegate)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Delegates/Edit', [
            'delegate'  => $delegate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Delegate  $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Delegate $delegate)
    {
        if ($request->user()->cannot('update', $delegate)) {
            abort(403);
        }

        // Start from here ...
        DB::transaction(function () use ($request, $delegate) {
            $delegate->update(array_merge(
                $request->only('name', 'email'),
                ['password' => !empty($request->password) ? bcrypt($request->password) : $delegate->password]
            ));

            if ($request->has('role')) {
                $delegate->syncRoles([$request->role]);
            }
        });

        session()->flash('flash.banner', 'Updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->updateAndContinue) {
            return back();
        }
        return redirect()->route('delegates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Delegate $delegate)
    {
        if ($request->user()->cannot('delete', $delegate)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $delegate) {
            $delegate->delete();
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Delegate $delegate)
    {
        if (request()->user()->cannot('update', $delegate)) {
            abort(403);
        }

        if ($delegate->status == ActiveStatus::ACTIVE()) {
            $delegate->update(['status' => ActiveStatus::INACTIVE()]);
            session()->flash('flash.banner', 'Delegate inactive successful.');
            session()->flash('flash.bannerStyle', 'success');
        } else {
            session()->flash('flash.banner', 'Delegate active successful.');
            session()->flash('flash.bannerStyle', 'success');
            $delegate->update(['status' => ActiveStatus::ACTIVE()]);
        }

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
        return (new DelegatesExport($request->all()))->download('delegates.xlsx');
    }

    /**
     * Export sale invoices as pdf format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        return Pdf::loadView('exports.delegates-pdf', [
                'models' => Delegate::filter($request->all())->orderBy('id', 'desc')->get()
            ])->download('delegates.pdf');
    }
}
