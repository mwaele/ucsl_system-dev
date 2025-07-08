<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Medical', 'description' => 'Medical Products and Services'],
            ['category_name' => 'E-Commerce', 'description' => 'Online retail and logistics solutions'],
            ['category_name' => 'Manufacturing', 'description' => 'Goods production and supply chain'],
            ['category_name' => 'Construction', 'description' => 'Building materials and site logistics'],
        ];

        DB::table('categories')->insert($categories);
    }
}

