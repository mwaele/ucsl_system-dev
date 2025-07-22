<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransporterTruckSeeder extends Seeder
{
    public function run(): void
    {
        $trucks = [
            [
                'transporter_id' => 1,
                'reg_no' => 'KBS 123A',
                'driver_name' => 'Peter Mwangi',
                'driver_contact' => '0711222333',
                'driver_id_no' => '12345678',
                'truck_type' => '10-Ton Lorry',
                'status' => 'available',
            ],
            [
                'transporter_id' => 2,
                'reg_no' => 'KDA 456B',
                'driver_name' => 'Janet Wanjiku',
                'driver_contact' => '0799887766',
                'driver_id_no' => '87654321',
                'truck_type' => 'Mini Truck',
                'status' => 'in_transit',
            ],
            [
                'transporter_id' => 1,
                'reg_no' => 'KCE 789C',
                'driver_name' => 'James Otieno',
                'driver_contact' => '0722333444',
                'driver_id_no' => '11223344',
                'truck_type' => 'Box Truck',
                'status' => 'maintenance',
            ],
            [
                'transporter_id' => 2,
                'reg_no' => 'KDF 321D',
                'driver_name' => 'Lucy Njeri',
                'driver_contact' => '0733111222',
                'driver_id_no' => '44332211',
                'truck_type' => 'Tipper Truck',
                'status' => 'available',
            ],
            [
                'transporter_id' => 1,
                'reg_no' => 'KDG 654E',
                'driver_name' => 'Brian Kiprotich',
                'driver_contact' => '0700111222',
                'driver_id_no' => '99887766',
                'truck_type' => 'Flatbed Truck',
                'status' => 'in_transit',
            ],
            [
                'transporter_id' => 2,
                'reg_no' => 'KDJ 987F',
                'driver_name' => 'Grace Akinyi',
                'driver_contact' => '0788333222',
                'driver_id_no' => '55667788',
                'truck_type' => 'Container Truck',
                'status' => 'available',
            ],
            [
                'transporter_id' => 1,
                'reg_no' => 'KDK 111G',
                'driver_name' => 'Samuel Maina',
                'driver_contact' => '0721222333',
                'driver_id_no' => '33445566',
                'truck_type' => 'Refrigerated Truck',
                'status' => 'in_transit',
            ],
            [
                'transporter_id' => 2,
                'reg_no' => 'KDL 222H',
                'driver_name' => 'Esther Mumo',
                'driver_contact' => '0744222333',
                'driver_id_no' => '66778899',
                'truck_type' => 'Pickup',
                'status' => 'available',
            ],
            [
                'transporter_id' => 1,
                'reg_no' => 'KDM 333J',
                'driver_name' => 'Anthony Kimani',
                'driver_contact' => '0711777888',
                'driver_id_no' => '22334455',
                'truck_type' => 'Canter',
                'status' => 'maintenance',
            ],
            [
                'transporter_id' => 2,
                'reg_no' => 'KDN 444K',
                'driver_name' => 'Caroline Chebet',
                'driver_contact' => '0766333444',
                'driver_id_no' => '77889900',
                'truck_type' => 'Fuel Tanker',
                'status' => 'available',
            ],
        ];

        foreach ($trucks as &$truck) {
            $truck['created_at'] = now();
            $truck['updated_at'] = now();
        }

        DB::table('transporter_trucks')->insert($trucks);
    }
}
