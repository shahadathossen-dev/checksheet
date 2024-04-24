<?php

namespace Database\Seeders;

use App\Enums\CheckSheetType;
use App\Models\CheckSheet;
use App\Models\ChecksheetItem;
use App\Models\User;
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
        $users = User::withoutSuperAdmin()->get();
        $checksheetTypes = collect(CheckSheetType::toArray())->values();

        $users->each(fn($user) => $checksheetTypes->each(fn($type) => CheckSheet::factory()
            ->has(ChecksheetItem::factory(5))
            ->create([
                    'type' => $type,
                    'user_id' => $user->id,
                    'due_by' => $type == CheckSheetType::MONTHLY() ? 30 : ($type == CheckSheetType::WEEKLY() ? 6 : 0)
                ])
            )
        );
        // $checksheets = CheckSheet::factory(50)->has(ChecksheetItem::factory(5))->create();
    }
}
