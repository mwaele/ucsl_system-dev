<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            ['zone_name' => 'Zone 1A', 'description' => 'Zone 1A region'],
            ['zone_name' => 'Zone 2A', 'description' => 'Zone 2A region'],
            ['zone_name' => 'Zone 3A', 'description' => 'Zone 3A region'],
            ['zone_name' => 'Zone 1B', 'description' => 'Zone 1B region'],
            ['zone_name' => 'Zone 2B', 'description' => 'Zone 2B region'],
            ['zone_name' => 'Zone 3B', 'description' => 'Zone 3B region'],
        ];

        DB::table('zones')->insert($zones);
    }
}
