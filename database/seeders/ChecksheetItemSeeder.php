<?php

namespace Database\Seeders;

use App\Models\ChecksheetItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChecksheetItemSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChecksheetItem::factory(50)->create();
    }
}
