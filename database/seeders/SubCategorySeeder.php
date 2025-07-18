<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [
            ['sub_category_name' => 'Overnight', 'description' => 'Overnight Delivery'],
            ['sub_category_name' => 'Same Day', 'description' => 'Same Day Delivery'],
        ];

        foreach ($subCategories as $sub) {
            DB::table('sub_categories')->insert([
                'sub_category_name' => $sub['sub_category_name'],
                'description' => $sub['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
