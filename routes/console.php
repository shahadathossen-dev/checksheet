<?php

use App\Models\Leave;
use App\Enums\LeaveType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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

Artisan::command('perform:daily-check', function () {
    $this->call('update:tasklists');

    $today = today();
    $generalHoliday = Leave::where([['start_date', '<=', $today], ['end_date', '>=', $today]])->where('type', LeaveType::GENERAL())->exists();

    // Skip weekends and general holidays
    if(!in_array($today->dayOfWeek, [0, 6]) || !$generalHoliday)
    $this->call('generate:tasklists');
})->purpose('Update and generate tasklists on daily basis.');

Artisan::command('seed:super-admin', function () {
    // Create super admin
    DB::transaction(function () {
        $admin = User::create([
            'name'      => "Shahadat Hossen",
            'email'     => 'shobujlingdu@gmail.com',
            'password'  => bcrypt('password'),
        ]);

        $admin->markEmailAsVerified();
        $admin->assignRole(Role::SUPER_ADMIN);
    });

    $this->info('Super Admin created successfully.');
})->purpose('Seed Super Admin user in DB.');

Artisan::command('update:permissions', function () {
    User::superAdmin()->first()->givePermissionTo(Permission::where('guard_name', 'web')->get());
})->purpose('Give permissions to super admin user for new model.');

