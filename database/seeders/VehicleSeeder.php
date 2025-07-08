<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $driverIds = DB::table('users')
            ->where('role', 'driver')
            ->pluck('id')
            ->shuffle()
            ->values()
            ->toArray();

        $companyIds = DB::table('company_infos')->pluck('id')->toArray();

        if (empty($driverIds)) {
            $this->command->warn('No drivers found. Please seed users with role = driver before seeding vehicles.');
            return;
        }

        if (empty($companyIds)) {
            $this->command->warn('No company_infos found. Please seed company_infos before seeding vehicles.');
            return;
        }

        $vehicles = [
            ['regNo' => 'KDG 123A', 'type' => 'Truck', 'color' => 'White', 'tonnage' => '5T'],
            ['regNo' => 'KCM 456B', 'type' => 'Van', 'color' => 'Blue', 'tonnage' => '2T'],
            ['regNo' => 'KBX 789C', 'type' => 'Pickup', 'color' => 'Red', 'tonnage' => '3T'],
            ['regNo' => 'KDA 321D', 'type' => 'Lorry', 'color' => 'Yellow', 'tonnage' => '7T'],
            ['regNo' => 'KBL 654E', 'type' => 'Truck', 'color' => 'Green', 'tonnage' => '10T'],
            ['regNo' => 'KCR 987F', 'type' => 'Van', 'color' => 'Silver', 'tonnage' => '1.5T'],
            ['regNo' => 'KCE 741G', 'type' => 'Trailer', 'color' => 'Black', 'tonnage' => '20T'],
        ];

        // Ensure we don’t assign more vehicles than there are drivers
        $vehicleCount = min(count($vehicles), count($driverIds));

        for ($i = 0; $i < $vehicleCount; $i++) {
            DB::table('vehicles')->insert([
                'regNo' => $vehicles[$i]['regNo'],
                'type' => $vehicles[$i]['type'],
                'color' => $vehicles[$i]['color'],
                'tonnage' => $vehicles[$i]['tonnage'],
                'status' => 'available',
                'description' => 'Assigned exclusively to one driver.',
                'user_id' => $driverIds[$i], // unique driver per vehicle
                'ownedBy' => $companyIds[array_rand($companyIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
