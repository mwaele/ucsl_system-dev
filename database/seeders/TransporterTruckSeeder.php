<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransporterTruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transporter_trucks')->insert([
            [
                'transporter_id' => 1, // assumes transporter with ID 1 exists
                'reg_no' => 'KBS 123A',
                'driver_name' => 'Peter Mwangi',
                'driver_contact' => '0711222333',
                'driver_id_no' => '12345678',
                'truck_type' => '10-Ton Lorry',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transporter_id' => 2, // assumes transporter with ID 2 exists
                'reg_no' => 'KDA 456B',
                'driver_name' => 'Janet Wanjiku',
                'driver_contact' => '0799887766',
                'driver_id_no' => '87654321',
                'truck_type' => 'Mini Truck',
                'status' => 'in_transit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
