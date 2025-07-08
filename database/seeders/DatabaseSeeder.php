<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanyInfoSeeder::class,
            UserSeeder::class,
            OfficeSeeder::class,
            VehicleSeeder::class,
            ZoneSeeder::class,
            RateSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
        ]);
    }
}
