<?php

namespace Database\Seeders;

use App\Models\CheckSheet;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CheckSheetSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CheckSheet::factory(50)->create();
    }
}
