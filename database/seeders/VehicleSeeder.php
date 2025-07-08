<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Only get users with role = driver
        $driverIds = DB::table('users')
            ->where('role', 'driver')
            ->pluck('id')
            ->toArray();

        $companyIds = DB::table('company_infos')->pluck('id')->toArray();

        // Check dependencies
        if (empty($driverIds)) {
            $this->command->warn('No drivers found. Please seed users with role = driver before seeding vehicles.');
            return;
        }

        if (empty($companyIds)) {
            $this->command->warn('No company_infos found. Please seed companies before seeding vehicles.');
            return;
        }

        $vehicles = [
            ['regNo' => 'KDG 123A', 'type' => 'Truck', 'color' => 'White', 'tonnage' => '5T'],
            ['regNo' => 'KCM 456B', 'type' => 'Van', 'color' => 'Blue', 'tonnage' => '2T'],
            ['regNo' => 'KBX 789C', 'type' => 'Pickup', 'color' => 'Red', 'tonnage' => '3T'],
        ];

        foreach ($vehicles as $vehicle) {
            DB::table('vehicles')->insert([
                'regNo' => $vehicle['regNo'],
                'type' => $vehicle['type'],
                'color' => $vehicle['color'],
                'tonnage' => $vehicle['tonnage'],
                'status' => 'available',
                'description' => 'Used for regional delivery operations.',
                'user_id' => $driverIds[array_rand($driverIds)], // only drivers
                'ownedBy' => $companyIds[array_rand($companyIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
