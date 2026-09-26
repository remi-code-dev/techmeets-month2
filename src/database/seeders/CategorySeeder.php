<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['お知らせ', '技術', '日記', 'レビュー'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
