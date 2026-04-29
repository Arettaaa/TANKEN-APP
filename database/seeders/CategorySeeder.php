<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Masukkan data statis agar ID-nya tetap 1 dan 2
        Category::create(['name' => 'Men', 'slug' => 'men']);
        Category::create(['name' => 'Women', 'slug' => 'women']);
    }
}