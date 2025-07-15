<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DispatcherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch existing office IDs
        $officeIds = DB::table('offices')->pluck('id');

        if ($officeIds->isEmpty()) {
            $this->command->warn('No offices found. Please seed the offices table first.');
            return;
        }

        DB::table('dispatchers')->insert([
            [
                'name' => 'Alice Mutua',
                'id_no' => '29561234',
                'phone_no' => '0721001001',
                'signature' => null,
                'office_id' => $officeIds[0],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Brian Otieno',
                'id_no' => '30889900',
                'phone_no' => '0744123123',
                'signature' => null,
                'office_id' => $officeIds[1] ?? $officeIds[0], // fallback if only 1 office
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
