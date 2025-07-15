<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransporterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transporters')->insert([
            [
                'name' => 'John Logistics Ltd.',
                'phone_no' => '0712345678',
                'reg_details' => 'RC-0092345',
                'transporter_type' => 'Truck',
                'cbv_no' => 'CBV123456',
                'signature' => null,
                'email' => 'johnlogistics@example.com',
                'account_no' => 'ACC1001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FastMove Transporters',
                'phone_no' => '0798765432',
                'reg_details' => 'RC-0054321',
                'transporter_type' => 'Van',
                'cbv_no' => null,
                'signature' => null,
                'email' => 'fastmove@example.com',
                'account_no' => 'ACC1002',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
