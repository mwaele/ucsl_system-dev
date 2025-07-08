<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Kevin Otieno', 'email' => 'kotieno@example.com', 'role' => 'driver'],
            ['name' => 'Faith Wambui', 'email' => 'fwambui@example.com', 'role' => 'driver'],
            ['name' => 'James Mwangi', 'email' => 'jmwangi@example.com', 'role' => 'manager'],
            ['name' => 'Mercy Achieng', 'email' => 'machieng@example.com', 'role' => 'staff'],
            ['name' => 'John Kariuki', 'email' => 'jkariuki@example.com', 'role' => 'admin'],
            ['name' => 'Alice Njeri', 'email' => 'anjeri@example.com', 'role' => 'staff'],
            ['name' => 'Brian Kiprotich', 'email' => 'bkiprotich@example.com', 'role' => 'driver'],
            ['name' => 'Esther Naliaka', 'email' => 'enaliaka@example.com', 'role' => 'manager'],
            ['name' => 'Daniel Mutua', 'email' => 'dmutua@example.com', 'role' => 'superadmin'],
            ['name' => 'Grace Nduta', 'email' => 'gnduta@example.com', 'role' => 'admin'],
        ];

        foreach ($users as $index => $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'phone_number' => '07' . rand(10000000, 99999999),
                'empNo' => 'EMP' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'idNo' => rand(10000000, 39999999),
                'role' => $user['role'],
                'status' => 'active',
                'company' => 'Ufanisi Courier',
                'station' => ['Mombasa', 'Nairobi', 'Kisumu', 'Nakuru', 'Malindi'][rand(0, 4)],
                'password' => Hash::make('password'), // Default password
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
