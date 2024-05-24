<?php

use App\Http\Controllers\Api\DashboardControlller;
use App\Http\Controllers\Api\NotificationController;
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

    Route::get('/dashboard/details', [DashboardControlller::class, 'index'])->name('dashboard.details');
    Route::put('/task-items/{taskItem}', [DashboardControlller::class, 'updateTaskItem'])->name('api.task-items.update');
    Route::post('/additional-tasks', [DashboardControlller::class, 'storeAdditionalTask'])->name('api.additional-tasks.store');
    Route::put('/additional-tasks/{additionalTask}', [DashboardControlller::class, 'updateAdditionalTask'])->name('api.additional-tasks.update');
    Route::post('/purchase-requests', [DashboardControlller::class, 'storePurchaseRequest'])->name('api.purchase-requests.store');
    Route::put('/purchase-requests/{purchaseRequest}', [DashboardControlller::class, 'updatePurchaseRequest'])->name('api.purchase-requests.update');
    Route::delete('/purchase-requests/{purchaseRequest}', [DashboardControlller::class, 'deletePurchaseRequest'])->name('api.purchase-requests.delete');
    Route::get('/checksheets/details/{type}', [CheckSheetController::class, 'getDetails'])->name('checksheets.details');
    Route::get('/tasklists/details/{type}', [TaskListController::class, 'getDetails'])->name('tasklists.details');
    Route::get('/leaves/details/{type}', [LeaveController::class, 'getDetails'])->name('leaves.details');
    Route::post('/notifications', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::delete('/notifications', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');

    Route::get('/jobs/test/{tasklist}', [TaskListController::class, 'testJob'])->name('jobs.test.api');
});
