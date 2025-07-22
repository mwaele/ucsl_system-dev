<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RateSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $addedBy = DB::table('users')->inRandomOrder()->value('id');
        $zones = DB::table('zones')->pluck('id', 'zone_name');

        $nairobiOfficeId = DB::table('offices')->where('name', 'Nairobi Office')->value('id');
        $mombasaOfficeId = DB::table('offices')->where('name', 'Mombasa Office')->value('id');

        // Seed normal rates
        $this->insertNormalRates($zones, $addedBy, $now, $nairobiOfficeId, $mombasaOfficeId);

        // Seed pharmaceutical rates
        $this->insertPharmaRates($zones, $addedBy, $now, $nairobiOfficeId);

        // Seed same-day rates
        $this->insertSameDayRates($addedBy, $nairobiOfficeId, $now);
    }

    protected function insertNormalRates($zones, $addedBy, $now, $nairobiOfficeId, $mombasaOfficeId)
    {
        $rows = [];

        foreach ($zones as $zoneName => $zoneId) {
            $rows[] = [
                'approvedBy' => null,
                'added_by' => $addedBy,
                'office_id' => $nairobiOfficeId,
                'zone_id' => $zoneId,
                'routeFrom' => 'Nairobi - ' . $zoneName,
                'zone' => $zoneName,
                'origin' => 'Nairobi',
                'destination' => $zoneName,
                'rate' => rand(500, 1500),
                'type' => 'normal',
                'bands' => null,
                'additional_cost_per_kg' => 50,
                'intercity_additional_cost_per_kg' => 100,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'status' => 'active',
                'approvalStatus' => 'pending',
                'dateApproved' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $rows[] = [
                'approvedBy' => null,
                'added_by' => $addedBy,
                'office_id' => $mombasaOfficeId,
                'zone_id' => $zoneId,
                'routeFrom' => 'Mombasa - ' . $zoneName,
                'zone' => $zoneName,
                'origin' => 'Mombasa',
                'destination' => $zoneName,
                'rate' => rand(800, 1800),
                'type' => 'normal',
                'bands' => null,
                'additional_cost_per_kg' => 50,
                'intercity_additional_cost_per_kg' => 100,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'status' => 'active',
                'approvalStatus' => 'pending',
                'dateApproved' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('rates')->insert($rows);
    }

    protected function insertPharmaRates($zones, $addedBy, $now, $nairobiOfficeId)
    {
        $rows = [];

        foreach ($zones as $zoneName => $zoneId) {
            $rows[] = [
                'approvedBy' => null,
                'added_by' => $addedBy,
                'office_id' => $nairobiOfficeId,
                'zone_id' => $zoneId,
                'routeFrom' => 'Nairobi - ' . $zoneName,
                'zone' => $zoneName,
                'origin' => 'Nairobi',
                'destination' => $zoneName,
                'rate' => rand(700, 1600),
                'type' => 'pharmaceutical',
                'bands' => null,
                'additional_cost_per_kg' => 75,
                'intercity_additional_cost_per_kg' => 125,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'status' => 'active',
                'approvalStatus' => 'pending',
                'dateApproved' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('rates')->insert($rows);
    }

    protected function insertSameDayRates($addedBy, $officeId, $now)
    {
        $rows = [];

        $bandedRates = [
            ['destination' => 'CBD', 'bands' => 'WITHIN CBD', 'rate' => 300],
            ['bands' => 'UP TO 5KM FROM CBD', 'rate' => 350, 'destinations' => [
                'Adams Arcade', 'Bahati', 'Chiromo', 'Eastlands', 'Eastleigh', 'Highridge', 'Industrial Area',
                'Kileleshwa', 'Kilimani', 'Lavington', 'Madaraka', 'Makongeni', 'Ngara', 'Muthaiga', 'Ngummo',
                'Nyayo Highrise', 'Pangani', 'Parklands', 'Yaya Center', 'Riverside Park', 'South B', 'South C',
                'Starehe', 'Upper Hill', 'Village Market', 'Westlands', 'Woodley']
            ],
            ['bands' => '6 TO 15KM FROM CBD', 'rate' => 400, 'destinations' => [
                'Avenue Park', 'Baba Dogo', 'Banda', 'Bomas', 'BuruBuru', 'Continental', 'Donholm', 'Drive-in',
                'Evergreen', 'Fedha', 'Greenfield', 'Hill View', 'Lang\'ata', 'Jacaranda', 'Kasarani', 'Kangemi',
                'Karura', 'New Runda', 'Lenana', 'Loresho', 'Lower Kabete', 'Lumumba', 'Zimmerman', 'Makadara',
                'Mbagathi', 'Mountain View', 'Mimosa', 'Nyari', 'Racecourse', 'Riara', 'Ridgeways', 'Rosslyn',
                'Roysambu', 'Savannah']
            ],
            ['bands' => '16 TO 25KM FROM CBD', 'rate' => 500, 'destinations' => [
                'Banana', 'Kahawa West', 'Karen', 'Kiambu', 'Kikuyu', 'Kimbo', 'Kiambaa', 'KU Referral Hospital',
                'KU University', 'Mlolongo', 'Ngong', 'Ongata Rongai', 'JKIA', 'Kahawa Sukari', 'Ruiru', 'Utawala',
                'Embakasi', 'Riruta', 'Ruaka', 'Imara Daima']
            ],
            ['destination' => '25 TO 50KM ZONE', 'bands' => '25 TO 50KM FROM CBD', 'rate' => 600],
        ];

        foreach ($bandedRates as $band) {
            if (isset($band['destinations'])) {
                foreach ($band['destinations'] as $destination) {
                    $rows[] = $this->formatSameDayRow($addedBy, $officeId, $now, $band['bands'], $destination, $band['rate']);
                }
            } else {
                $rows[] = $this->formatSameDayRow($addedBy, $officeId, $now, $band['bands'], $band['destination'], $band['rate']);
            }
        }

        $intercityRates = [
            ['destination' => 'Mombasa', 'rate' => 3500],
            ['destination' => 'Kisumu', 'rate' => 3500],
            ['destination' => 'Eldoret', 'rate' => 2000],
            ['destination' => 'Nakuru', 'rate' => 1500],
        ];

        foreach ($intercityRates as $route) {
            $rows[] = $this->formatSameDayRow($addedBy, $officeId, $now, 'INTERCITY EXPRESS DELIVERY', $route['destination'], $route['rate']);
        }

        DB::table('rates')->insert($rows);
    }

    protected function formatSameDayRow($addedBy, $officeId, $now, $band, $destination, $rate)
    {
        return [
            'approvedBy' => null,
            'added_by' => $addedBy,
            'office_id' => $officeId,
            'zone_id' => null,
            'routeFrom' => 'Nairobi - ' . $destination,
            'zone' => null,
            'origin' => 'Nairobi',
            'destination' => $destination,
            'rate' => $rate,
            'type' => 'same_day',
            'bands' => $band,
            'additional_cost_per_kg' => 50,
            'intercity_additional_cost_per_kg' => 100,
            'applicableFrom' => $now,
            'applicableTo' => null,
            'status' => 'active',
            'approvalStatus' => 'pending',
            'dateApproved' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
