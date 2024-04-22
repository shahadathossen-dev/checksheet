<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\DelegateController;
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

    // Route::apiResource('products', ProductController::class);
    // Route::put('/products/{id}/restore', [ProductController::class, 'restore'])->name('rest.products.restore');
    // Route::get('/products/{id}/deleted', [ProductController::class, 'forceDelete'])->name('rest.products.force-delete');


    // Route::get('delegates/excel', [DelegateController::class, 'excel'])->name('delegates.excel');
    // Route::get('delegates/pdf', [DelegateController::class, 'pdf'])->name('delegates.pdf');
});
