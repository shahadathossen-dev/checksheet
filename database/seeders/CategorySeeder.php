<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $fruit = Category::updateOrCreate(
                ['name' => 'Android'],
                [
                    'description' => 'Write your description here'
                ]
            );

            // $fruit->addMediaFromUrl(asset('img/categories/android.png'))->toMediaCollection('primary');
        });

        DB::transaction(function () {
            $vegetable = Category::updateOrCreate(
                ['name' => 'iPhone'],
                [
                    'description' => 'Write your description here'
                ]
            );

            // $vegetable->addMediaFromUrl(asset('img/categories/apple.png'))->toMediaCollection('primary');
        });

        DB::transaction(function () {
            $grocery = Category::updateOrCreate(
                ['name' => 'Desktop'],
                [
                    'description' => 'Write your description here'
                ]
            );

            // $grocery->addMediaFromUrl(asset('img/categories/desktop.png'))->toMediaCollection('primary');
        });

        DB::transaction(function () {
            $dairy = Category::updateOrCreate(
                ['name' => 'Combo'],
                [
                    'description' => 'Write your description here'
                ]
            );

            // $dairy->addMediaFromUrl(asset('img/categories/combo.png'))->toMediaCollection('primary');
        });
    }
}
