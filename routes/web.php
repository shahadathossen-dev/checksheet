<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckSheetController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DelegateController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\TaskListController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    // Resource Route
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::put('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/products/{id}/deleted', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    
    // Delegates
    Route::resource('delegates', DelegateController::class);
    Route::get('/delegates/export/excel', [DelegateController::class, 'exportExcel'])->name('delegates.excel');
    Route::get('/delegates/export/pdf', [DelegateController::class, 'exportPdf'])->name('delegates.pdf');
    Route::post('/delegates/{delegate}/update-status', [DelegateController::class, 'updateStatus'])->name('delegates.update-status');

    // CheckSheets
    Route::resource('checksheets', CheckSheetController::class);
    Route::get('/checksheets/export/excel', [CheckSheetController::class, 'exportExcel'])->name('checksheets.excel');
    Route::get('/checksheets/export/pdf', [CheckSheetController::class, 'exportPdf'])->name('checksheets.pdf');
    Route::post('/checksheets/{checksheet}/update-status', [CheckSheetController::class, 'updateStatus'])->name('checksheets.update-status');
    
    // TaksLists
    Route::resource('tasklists', TaskListController::class);
    Route::get('/tasklists/export/excel', [TaskListController::class, 'exportExcel'])->name('tasklists.excel');
    Route::get('/tasklists/export/pdf', [TaskListController::class, 'exportPdf'])->name('tasklists.pdf');
    Route::post('/tasklists/{tasklist}/update-status', [TaskListController::class, 'updateStatus'])->name('tasklists.update-status');
    
    // Leaves
    Route::resource('leaves', LeaveController::class);
    Route::get('/leaves/export/excel', [LeaveController::class, 'exportExcel'])->name('leaves.excel');
    Route::get('/leaves/export/pdf', [LeaveController::class, 'exportPdf'])->name('leaves.pdf');
    Route::post('/leaves/{leave}/update-status', [LeaveController::class, 'updateStatus'])->name('leaves.update-status');
    
    Route::get('/test', fn() => dd('test'))->name('route.test');
    Route::get('/jobs/test/{tasklist}', [TaskListController::class, 'testJob'])->name('jobs.test.web');
});

// Message Route
