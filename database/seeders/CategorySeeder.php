<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Burger',
                'slug' => 'burger',
                'status' => 1,
                'show_at_home' => 1
            ],
            [
                'name' => 'Sandwich',
                'slug' => 'sandwich',
                'status' => 1,
                'show_at_home' => 1
            ],
            [
                'name' => 'Taco',
                'slug' => 'taco',
                'status' => 1,
                'show_at_home' => 1
            ]
        ]);
    }
}
