<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Slider;
use Database\Factories\WhyChooseUsFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WhyChooseUs;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // WhyChooseUs::factory(3)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(UserSeeder::class);
        Coupon::factory(3)->create();
        // $this->call(CategorySeeder::class);
    }
}
