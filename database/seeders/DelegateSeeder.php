<?php

namespace Database\Seeders;

use App\Models\Delegate;
use Illuminate\Database\Seeder;

class DelegateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Delegate::factory(50)->create();
    }
}
