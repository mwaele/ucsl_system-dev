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
        $addedBy = DB::table('users')->inRandomOrder()->value('id');
        $nairobiOfficeId = DB::table('offices')->where('name', 'Nairobi Office')->value('id');
        $mombasaOfficeId = DB::table('offices')->where('name', 'Mombasa Office')->value('id');
        $zones = DB::table('zones')->pluck('id', 'zone_name');
        $now = Carbon::now();

        // ------------------ Nairobi Routes (Normal) -------------------
        $nairobiOrigin = 'Nairobi';
        $zoneDestinationsFromNairobi = [
            'Zone 1A' => [
                'destinations' => ['Machakos', 'Emali', 'Makindu', 'Kibwezi', 'Mtito', 'Voi', 'Mariakani', 'Mazeras', 'Mombasa', 'Kitui'],
                'rate' => 500
            ],
            'Zone 2A' => [
                'destinations' => ['Naivasha', 'Gilgil', 'Nakuru', 'Kericho', 'Kisumu', 'Narok', 'Bomet', 'Eldoret', 'Kakamega', 'Kisii', 'Kitale', 'Litein'],
                'rate' => 500
            ],
            'Zone 3A' => [
                'destinations' => ['Thika', 'Sagana', 'Muranga', 'Kerugoya', 'Karatina', 'Nyeri', 'Othaya', 'Nanyuki', 'Embu', 'Matuu', 'Nkubu', 'Nyahururu', 'Chuka'],
                'rate' => 500
            ],
            'Zone 1B' => [
                'destinations' => ['Diani(1000)', 'Malindi(1000)', 'Watamu(1000)', 'Kilifi(900)', 'Mtwapa(800)', 'Kwale(800)', 'Lamu(2500)', 'Namanga(650)']
            ],
            'Zone 2B' => [
                'destinations' => ['Bungoma(600)', 'Kapsabet(600)', 'Migori(650)', 'Homabay(650)', 'Busia(650)', 'Siaya(650)', 'Awendo(650)', 'Muhoroni(650)', 'Bondo(650)']
            ],
            'Zone 3B' => [
                'destinations' => ['Maua(650)', 'Isiolo(650)', 'Meru(650)', 'Garissa(650)', 'Mwingi(650)']
            ],
        ];

        $this->insertRates($zoneDestinationsFromNairobi, $zones, $nairobiOrigin, $nairobiOfficeId, $addedBy, $now);

        // ------------------ Mombasa Routes (Normal) -------------------
        $mombasaOrigin = 'Mombasa';
        $zoneDestinationsFromMombasa = [
            'Zone 1A' => [
                'destinations' => ['Machakos', 'Emali', 'Makindu', 'Kibwezi', 'Mtito', 'Voi', 'Mariakani', 'Mazeras', 'Nairobi'],
                'rate' => 500
            ],
            'Zone 2A' => [
                'destinations' => ['Naivasha', 'Gilgil', 'Nakuru', 'Kericho', 'Kisumu', 'Narok', 'Bomet', 'Eldoret', 'Kakamega', 'Kisii', 'Kitale', 'Litein'],
                'rate' => 1000
            ],
            'Zone 3A' => [
                'destinations' => ['Thika', 'Sagana', 'Muranga', 'Kerugoya', 'Karatina', 'Nyeri', 'Othaya', 'Nanyuki', 'Embu', 'Matuu', 'Nkubu', 'Nyahururu', 'Chuka'],
                'rate' => 1000
            ],
            'Zone 1B' => [
                'destinations' => ['Diani(500)', 'Malindi(500)', 'Watamu(500)', 'Kilifi(400)', 'Mtwapa(300)', 'Kwale(500)', 'Lamu(2000)']
            ],
            'Zone 2B' => [
                'destinations' => ['Bungoma(1100)', 'Kapsabet(1100)', 'Migori(1100)', 'Homabay(1150)', 'Busia(1150)', 'Siaya(1150)', 'Muhoroni(1150)', 'Awendo(1150)']
            ],
            'Zone 3B' => [
                'destinations' => ['Maua(1150)', 'Isiolo(1150)', 'Meru(1150)', 'Garissa(1150)', 'Mwingi(1150)']
            ],
        ];

        $this->insertRates($zoneDestinationsFromMombasa, $zones, $mombasaOrigin, $mombasaOfficeId, $addedBy, $now);

        // ------------------ Nairobi Routes (Pharmaceutical) -------------------
        $pharmaRates = [
            'Zone 1A' => ['Machakos', 'Emali', 'Makindu', 'Kibwezi', 'Mtito', 'Voi', 'Mariakani', 'Mazeras', 'Mombasa', 'Kitui'],
            'Zone 2A' => ['Naivasha', 'Gilgil', 'Nakuru', 'Kericho', 'Kisumu', 'Narok', 'Bomet', 'Eldoret', 'Kakamega', 'Kisii', 'Kitale', 'Litein'],
            'Zone 3A' => ['Thika', 'Sagana', 'Muranga', 'Kerugoya', 'Karatina', 'Nyeri', 'Othaya', 'Nanyuki', 'Embu', 'Matuu', 'Nkubu', 'Nyahururu'],
            'Zone 1B' => ['Diani', 'Malindi', 'Watamu', 'Kilifi', 'Mtwapa', 'Kwale'],
            'Zone 2B' => ['Bungoma', 'Kapsabet', 'Migori', 'Homabay', 'Busia', 'Siaya', 'Awendo', 'Muhoroni', 'Bondo'],
            'Zone 3B' => ['Maua', 'Isiolo', 'Meru', 'Garissa', 'Mwingi', 'Kwale'],
        ];

        $pharmaRatesMap = [
            'Zone 1A' => 431.03,
            'Zone 2A' => 431.03,
            'Zone 3A' => 431.03,
            'Zone 1B' => 862.06,
            'Zone 2B' => 560.34,
            'Zone 3B' => 560.34,
        ];

        foreach ($pharmaRates as $zone => $destinations) {
            $zoneId = $zones[$zone] ?? null;
            if (!$zoneId) continue;

            foreach ($destinations as $destination) {
                DB::table('rates')->insert([
                    'approvedBy' => null,
                    'added_by' => $addedBy,
                    'office_id' => $nairobiOfficeId,
                    'zone_id' => $zoneId,
                    'routeFrom' => $nairobiOrigin . ' - ' . $destination,
                    'zone' => $zone,
                    'origin' => $nairobiOrigin,
                    'destination' => $destination,
                    'rate' => $pharmaRatesMap[$zone],
                    'type' => 'pharmaceutical',
                    'applicableFrom' => $now,
                    'applicableTo' => null,
                    'status' => 'active',
                    'approvalStatus' => 'approved',
                    'dateApproved' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ------------------ Same Day Rates (Type: same_day) ------------------ 
        $bandedRates = [
            ['destination' => 'CBD', 'bands' => 'WITHIN CBD', 'rate' => 300],
            ['bands' => 'UP TO 5KM FROM CBD', 'rate' => 350, 'destinations' => [
                'Adams Arcade', 'Bahati', 'Chiromo', 'Eastlands', 'Eastleigh', 'Highridge', 'Industrial Area',
                'Kileleshwa', 'Kilimani', 'Lavington', 'Madaraka', 'Makongeni', 'Ngara', 'Muthaiga', 'Ngummo',
                'Nyayo Highrise', 'Pangani', 'Parklands', 'Yaya Center', 'Riverside Park', 'South B', 'South C',
                'Starehe', 'Upper Hill', 'Village Market', 'Westlands', 'Woodley'
            ]],
            ['bands' => '6 TO 15KM FROM CBD', 'rate' => 400, 'destinations' => [
                'Avenue Park', 'Baba Dogo', 'Banda', 'Bomas', 'BuruBuru', 'Continental', 'Donholm', 'Drive-in',
                'Evergreen', 'Fedha', 'Greenfield', 'Hill View', 'Lang\'ata', 'Jacaranda', 'Kasarani', 'Kangemi',
                'Karura', 'New Runda', 'Lenana', 'Loresho', 'Lower Kabete', 'Lumumba', 'Zimmerman', 'Makadara',
                'Mbagathi', 'Mountain View', 'Mimosa', 'Nyari', 'Racecourse', 'Riara', 'Ridgeways', 'Rosslyn',
                'Roysambu', 'Savannah'
            ]],
            ['bands' => '16 TO 25KM FROM CBD', 'rate' => 500, 'destinations' => [
                'Banana', 'Kahawa West', 'Karen', 'Kiambu', 'Kikuyu', 'Kimbo', 'Kiambaa', 'KU Referral Hospital',
                'KU University', 'Mlolongo', 'Ngong', 'Ongata Rongai', 'JKIA', 'Kahawa Sukari', 'Ruiru', 'Utawala',
                'Embakasi', 'Riruta', 'Ruaka', 'Imara Daima'
            ]],
            ['destination' => '25 TO 50KM ZONE', 'bands' => '25 TO 50KM FROM CBD', 'rate' => 600],
        ];

        foreach ($bandedRates as $band) {
            if (isset($band['destinations'])) {
                foreach ($band['destinations'] as $destination) {
                    DB::table('rates')->insert([
                        'approvedBy' => null,
                        'added_by' => $addedBy,
                        'office_id' => $nairobiOfficeId,
                        'zone_id' => null,
                        'routeFrom' => 'Nairobi - ' . $destination,
                        'zone' => $band['bands'],
                        'origin' => 'Nairobi',
                        'destination' => $destination,
                        'rate' => $band['rate'],
                        'type' => 'Same Day',
                        'bands' => $band['bands'],
                        'additional_cost_per_kg' => 50,
                        'intercity_additional_cost_per_kg' => 100,
                        'applicableFrom' => $now,
                        'applicableTo' => null,
                        'status' => 'active',
                        'approvalStatus' => 'pending',
                        'dateApproved' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            } else {
                DB::table('rates')->insert([
                    'approvedBy' => null,
                    'added_by' => $addedBy,
                    'office_id' => $nairobiOfficeId,
                    'zone_id' => null,
                    'routeFrom' => 'Nairobi - ' . $band['destination'],
                    'zone' => $band['bands'],
                    'origin' => 'Nairobi',
                    'destination' => $band['destination'],
                    'rate' => $band['rate'],
                    'type' => 'same_day',
                    'bands' => $band['bands'],
                    'additional_cost_per_kg' => 50,
                    'intercity_additional_cost_per_kg' => 100,
                    'applicableFrom' => $now,
                    'applicableTo' => null,
                    'status' => 'active',
                    'approvalStatus' => 'pending',
                    'dateApproved' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ------------------ Intercity Same Day Rates ------------------
        $intercityRates = [
            ['destination' => 'Mombasa', 'rate' => 3500],
            ['destination' => 'Kisumu', 'rate' => 3500],
            ['destination' => 'Eldoret', 'rate' => 2000],
            ['destination' => 'Nakuru', 'rate' => 1500],
        ];

        foreach ($intercityRates as $route) {
            DB::table('rates')->insert([
                'approvedBy' => null,
                'added_by' => $addedBy,
                'office_id' => $nairobiOfficeId,
                'zone_id' => null,
                'routeFrom' => 'Nairobi - ' . $route['destination'],
                'zone' => 'INTERCITY EXPRESS DELIVERY',
                'origin' => 'Nairobi',
                'destination' => $route['destination'],
                'rate' => $route['rate'],
                'type' => 'same_day',
                'bands' => 'INTERCITY EXPRESS DELIVERY',
                'additional_cost_per_kg' => 50,
                'intercity_additional_cost_per_kg' => 100,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'status' => 'active',
                'approvalStatus' => 'pending',
                'dateApproved' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    protected function insertRates(array $zoneDestinations, $zones, string $origin, int $officeId, int $addedBy, Carbon $now): void
    {
        foreach ($zoneDestinations as $zoneName => $data) {
            $zoneId = $zones[$zoneName] ?? null;
            if (!$zoneId) continue;

            foreach ($data['destinations'] as $dest) {
                if (str_contains($dest, '(')) {
                    preg_match('/(.+)\((\d+)\)/', $dest, $matches);
                    $destination = trim($matches[1]);
                    $rate = (int) $matches[2];
                } else {
                    $destination = $dest;
                    $rate = $data['rate'] ?? 0;
                }

                DB::table('rates')->insert([
                    'approvedBy' => null,
                    'added_by' => $addedBy,
                    'office_id' => $officeId,
                    'zone_id' => $zoneId,
                    'routeFrom' => $origin . ' - ' . $destination,
                    'zone' => $zoneName,
                    'origin' => $origin,
                    'destination' => $destination,
                    'rate' => $rate,
                    'type' => 'normal',
                    'applicableFrom' => $now,
                    'applicableTo' => null,
                    'status' => 'active',
                    'approvalStatus' => 'approved',
                    'dateApproved' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}