<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // CountrySeeder::class,
            // DelegateSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            CheckSheetSeeder::class,
            TaskListSeeder::class,
        ]);
    }
}
