<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SameDayRateSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $addedBy = DB::table('users')->inRandomOrder()->value('id');
        $nairobiOfficeId = DB::table('offices')->where('name', 'Nairobi Office')->value('id');

        $bandedRates = [
            // Band 1: Within CBD
            ['destination' => 'CBD', 'bands' => 'WITHIN CBD', 'rate' => 300],

            // Band 2: Up to 5KM from CBD (Ksh 350)
            ['bands' => 'UP TO 5KM FROM CBD', 'rate' => 350, 'destinations' => [
                'Adams Arcade', 'Bahati', 'Chiromo', 'Eastlands', 'Eastleigh', 'Highridge', 'Industrial Area',
                'Kileleshwa', 'Kilimani', 'Lavington', 'Madaraka', 'Makongeni', 'Ngara', 'Muthaiga', 'Ngummo',
                'Nyayo Highrise', 'Pangani', 'Parklands', 'Yaya Center', 'Riverside Park', 'South B', 'South C',
                'Starehe', 'Upper Hill', 'Village Market', 'Westlands', 'Woodley'
            ]],

            // Band 3: 6 to 15KM (Ksh 400)
            ['bands' => '6 TO 15KM FROM CBD', 'rate' => 400, 'destinations' => [
                'Avenue Park', 'Baba Dogo', 'Banda', 'Bomas', 'BuruBuru', 'Continental', 'Donholm', 'Drive-in',
                'Evergreen', 'Fedha', 'Greenfield', 'Hill View', 'Lang\'ata', 'Jacaranda', 'Kasarani', 'Kangemi',
                'Karura', 'New Runda', 'Lenana', 'Loresho', 'Lower Kabete', 'Lumumba', 'Zimmerman', 'Makadara',
                'Mbagathi', 'Mountain View', 'Mimosa', 'Nyari', 'Racecourse', 'Riara', 'Ridgeways', 'Rosslyn',
                'Roysambu', 'Savannah'
            ]],

            // Band 4: 16 to 25KM (Ksh 500)
            ['bands' => '16 TO 25KM FROM CBD', 'rate' => 500, 'destinations' => [
                'Banana', 'Kahawa West', 'Karen', 'Kiambu', 'Kikuyu', 'Kimbo', 'Kiambaa', 'KU Referral Hospital',
                'KU University', 'Mlolongo', 'Ngong', 'Ongata Rongai', 'JKIA', 'Kahawa Sukari', 'Ruiru', 'Utawala',
                'Embakasi', 'Riruta', 'Ruaka', 'Imara Daima'
            ]],

            // Band 5: 25 to 50KM (Flat rate)
            ['destination' => '25 TO 50KM ZONE', 'bands' => '25 TO 50KM FROM CBD', 'rate' => 600],
        ];

        $rows = [];

        foreach ($bandedRates as $band) {
            if (isset($band['destinations'])) {
                foreach ($band['destinations'] as $destination) {
                    $rows[] = [
                        'office_id' => $nairobiOfficeId,
                        'destination' => $destination,
                        'bands' => $band['bands'],
                        'rate' => $band['rate'],
                        'additional_cost_per_kg' => 50,
                        'intercity_additional_cost_per_kg' => 100,
                        'applicableFrom' => $now,
                        'applicableTo' => null,
                        'status' => 'active',
                        'approvalStatus' => 'pending',
                        'dateApproved' => null,
                        'approvedBy' => null,
                        'added_by' => $addedBy,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            } else {
                $rows[] = [
                    'office_id' => $nairobiOfficeId,
                    'destination' => $band['destination'],
                    'bands' => $band['bands'],
                    'rate' => $band['rate'],
                    'additional_cost_per_kg' => 50,
                    'intercity_additional_cost_per_kg' => 100,
                    'applicableFrom' => $now,
                    'applicableTo' => null,
                    'status' => 'active',
                    'approvalStatus' => 'pending',
                    'dateApproved' => null,
                    'approvedBy' => null,
                    'added_by' => $addedBy,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Intercity Delivery (0-5KG)
        $intercityRates = [
            ['destination' => 'Mombasa', 'rate' => 3500],
            ['destination' => 'Kisumu', 'rate' => 3500],
            ['destination' => 'Eldoret', 'rate' => 2000],
            ['destination' => 'Nakuru', 'rate' => 1500],
        ];

        foreach ($intercityRates as $route) {
            $rows[] = [
                'office_id' => $nairobiOfficeId,
                'destination' => $route['destination'],
                'bands' => 'INTERCITY EXPRESS DELIVERY',
                'rate' => $route['rate'],
                'additional_cost_per_kg' => 50,
                'intercity_additional_cost_per_kg' => 100,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'status' => 'active',
                'approvalStatus' => 'pending',
                'dateApproved' => null,
                'approvedBy' => null,
                'added_by' => $addedBy,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('same_day_rates')->insert($rows);
    }
}
