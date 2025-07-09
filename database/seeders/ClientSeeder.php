<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('en_KE');

        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        if (empty($categoryIds)) {
            $this->command->warn('No categories found. Please seed categories before clients.');
            return;
        }

        $types = ['cod', 'walkin'];
        $specialRates = ['on', null];
        $cities = ['Nairobi', 'Mombasa', 'Kisumu', 'Eldoret', 'Nakuru'];
        $buildings = ['Kenyatta Avenue', 'Moi Plaza', 'Sarit Centre', 'Westgate', 'Thika Road Mall'];
        $streets = ['Ngong Road', 'Kenyatta Avenue', 'Moi Avenue', 'Tom Mboya Street', 'Waiyaki Way'];
        $kenyanCompanies = [
            'Safari Tech Solutions',
            'Kenlog Logistics',
            'Jumuka Supplies',
            'Wakanda Distributors',
            'Maisha Medcare',
            'Tujenge Builders',
            'Nuru E-Commerce Ltd',
            'Kilimani Pharma',
            'Twende Ventures',
            'Msingi Manufacturing Co',
        ];

        for ($i = 0; $i < 10; $i++) {
            $contactPerson = $faker->name;
            $accountNo = 'UCSL-' . mt_rand(10000, 99999);
            $password = 'Client@123'; // default password
            $companyName = $faker->unique()->randomElement($kenyanCompanies);
            $email = strtolower(Str::slug($companyName)) . '@ucsl.co.ke'; // Local business-style email

            $clientId = DB::table('clients')->insertGetId([
                'accountNo' => $accountNo,
                'name' => $companyName,
                'email' => $email,
                'password' => Hash::make($password),
                'contact' => '07' . mt_rand(10000000, 99999999),
                'address' => $faker->randomElement($streets) . ', ' . $faker->randomElement($cities),
                'city' => $faker->randomElement($cities),
                'building' => $faker->randomElement($buildings),
                'country' => 'Kenya',
                'category' => 'NULL', // intentionally left null per your logic
                'contactPerson' => $contactPerson,
                'contactPersonPhone' => '07' . mt_rand(10000000, 99999999),
                'contactPersonEmail' => strtolower(Str::slug($contactPerson)) . '@example.com',
                'contact_person_id_no' => mt_rand(10000000, 39999999),
                'type' => $faker->randomElement($types),
                'industry' => $faker->word,
                'kraPin' => 'A' . strtoupper(Str::random(8)),
                'postalCode' => mt_rand(10000, 99999),
                'status' => 'active',
                'special_rates_status' => $faker->randomElement($specialRates),
                'verificationCode' => strtoupper(Str::random(5)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign 1–3 random categories to the pivot table
            $assignedCategories = $faker->randomElements($categoryIds, rand(1, 3));
            foreach ($assignedCategories as $catId) {
                DB::table('client_categories')->insert([
                    'client_id' => $clientId,
                    'category_id' => $catId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
