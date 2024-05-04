<?php

use App\Models\TaskList;
use App\Services\StatusUpdateService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test-command', function () {
    $tasklist = TaskList::pending()->first();
    $tasklist->items()->update(['done', 1]);
    StatusUpdateService::update($tasklist);

})->purpose('Test console commands');
