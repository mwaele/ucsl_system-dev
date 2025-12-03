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

        $types = ['on_account', 'walkin'];
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
            'Kilimani Pharma'
        ];

        /*
        |--------------------------------------------------------------------------
        | 1. CREATE 8 RANDOM CLIENTS
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 8; $i++) {
            $contactPerson = $faker->name;
            $accountNo = 'UCSL-' . mt_rand(10000, 99999);
            $password = 'password';
            $companyName = $faker->unique()->randomElement($kenyanCompanies);
            $email = strtolower(Str::slug($companyName)) . '@ucsl.co.ke';

            $clientId = DB::table('clients')->insertGetId([
                'accountNo' => $accountNo,
                'name' => $companyName,
                'email' => $email,
                'password' => Hash::make($password),
                'contact' => '0729395605',
                'address' => $faker->randomElement($streets) . ', ' . $faker->randomElement($cities),
                'city' => $faker->randomElement($cities),
                'building' => $faker->randomElement($buildings),
                'country' => 'Kenya',
                'category' => 'NULL',
                'contactPerson' => $contactPerson,
                'contactPersonPhone' => '0729395605',
                'contactPersonEmail' => strtolower(Str::slug($contactPerson)) . '@example.com',
                'contact_person_id_no' => mt_rand(10000000, 39999999),
                'type' => $faker->randomElement($types),
                'industry' => $faker->word,
                'kraPin' => 'A' . strtoupper(Str::random(8)),
                'postalCode' => mt_rand(10000, 99999),
                'status' => 'active',
                'special_rates_status' => null, // random clients = null
                'verificationCode' => strtoupper(Str::random(5)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign 1–3 random categories
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


        /*
        |--------------------------------------------------------------------------
        | 2. ADD TOWN TEAM (special_rates_status = on)
        |--------------------------------------------------------------------------
        */

        $townTeamId = DB::table('clients')->insertGetId([
            'accountNo' => 'UCSL-' . mt_rand(10000, 99999),
            'name' => 'Town Team',
            'email' => 'townteam@ucsl.co.ke',
            'password' => Hash::make('password'),
            'contact' => '0729395605',
            'address' => 'Nairobi',
            'city' => 'Nairobi',
            'building' => 'KICC',
            'country' => 'Kenya',
            'category' => 'NULL',
            'contactPerson' => 'Town Team Manager',
            'contactPersonPhone' => '0729395605',
            'contactPersonEmail' => 'townteam.manager@ucsl.co.ke',
            'contact_person_id_no' => mt_rand(10000000, 39999999),
            'type' => 'on_account',
            'industry' => 'Logistics',
            'kraPin' => 'A' . strtoupper(Str::random(8)),
            'postalCode' => 00100,
            'status' => 'active',
            'special_rates_status' => 'on',
            'verificationCode' => strtoupper(Str::random(5)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('client_categories')->insert([
            'client_id' => $townTeamId,
            'category_id' => $categoryIds[array_rand($categoryIds)],
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | 3. ADD JEFREY CODE (special_rates_status = on)
        |--------------------------------------------------------------------------
        */

        $jefreyCodeId = DB::table('clients')->insertGetId([
            'accountNo' => 'UCSL-' . mt_rand(10000, 99999),
            'name' => 'Jefrey Code',
            'email' => 'jefreycode@ucsl.co.ke',
            'password' => Hash::make('password'),
            'contact' => '0729395605',
            'address' => 'Nairobi',
            'city' => 'Nairobi',
            'building' => 'Kimathi House',
            'country' => 'Kenya',
            'category' => 'NULL',
            'contactPerson' => 'Jeffrey Manager',
            'contactPersonPhone' => '0729395605',
            'contactPersonEmail' => 'jefrey.manager@ucsl.co.ke',
            'contact_person_id_no' => mt_rand(10000000, 39999999),
            'type' => 'on_account',
            'industry' => 'Tech',
            'kraPin' => 'A' . strtoupper(Str::random(8)),
            'postalCode' => 00100,
            'status' => 'active',
            'special_rates_status' => 'on',
            'verificationCode' => strtoupper(Str::random(5)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('client_categories')->insert([
            'client_id' => $jefreyCodeId,
            'category_id' => $categoryIds[array_rand($categoryIds)],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info("Clients seeded successfully. Town Team & Jefrey Code are active special-rate clients.");
    }
}
