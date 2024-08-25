<?php

namespace Database\Seeders;

use App\Models\SectionTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhyChooseUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SectionTitle::create(
            [
                'key' => 'why_choose_sub_title',
                'value' => 'Lorem ipsum dolor sit amet. In voluptate atque et dicta rerum rem enim sunt et laudantium molestias est soluta beatae',
            ]
        );
    }
}
