<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\CheckSheetController;
use App\Http\Controllers\DelegateController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\TaskListController;
use App\Models\CheckSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::get('delegates/excel', [DelegateController::class, 'excel'])->name('delegates.excel');
    // Route::get('delegates/pdf', [DelegateController::class, 'pdf'])->name('delegates.pdf');

    // Route::apiResource('products', ProductController::class);
    // Route::put('/products/{id}/restore', [ProductController::class, 'restore'])->name('rest.products.restore');
    // Route::get('/products/{id}/deleted', [ProductController::class, 'forceDelete'])->name('rest.products.force-delete');

    Route::get('/checksheets/details/{type}', [CheckSheetController::class, 'getDetails'])->name('checksheets.details');
    Route::get('/tasklists/details/{type}', [TaskListController::class, 'getDetails'])->name('tasklists.details');
    Route::get('/leaves/details/{type}', [LeaveController::class, 'getDetails'])->name('leaves.details');

    Route::get('/jobs/test/{tasklist}', [TaskListController::class, 'testJob'])->name('jobs.test.api');
});
