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

        $categories = DB::table('categories')->get();

        foreach ($categories as $category) {
            foreach ($subCategories as $sub) {
                DB::table('sub_categories')->insert([
                    'sub_category_name' => $sub['sub_category_name'],
                    'description' => $sub['description'],
                    'category_id' => $category->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
