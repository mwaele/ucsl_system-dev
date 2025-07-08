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
        $origin = 'Nairobi';
        $addedBy = DB::table('users')->inRandomOrder()->value('id');
        $nairobiOfficeId = DB::table('offices')->where('name', 'Nairobi Office')->value('id');

        $zones = DB::table('zones')->pluck('id', 'zone_name');

        $now = Carbon::now();

        $zoneDestinations = [
            'Zone 1A' => [
                'destinations' => ['Machakos', 'Emali', 'Makindu', 'Kibwezi', 'Mtito', 'Voi', 'Mariakani', 'Mazeras', 'Mombasa', 'Kitui'],
                'rate' => 500
            ],
            'Zone 2A' => [
                'destinations' => ['Naivasha', 'Gilgil', 'Nakuru', 'Kericho', 'Kisumu', 'Narok', 'Bomet', 'Eldoret', 'Kakamega', 'Kisii', 'Kitale'],
                'rate' => 500
            ],
            'Zone 3A' => [
                'destinations' => ['Thika', 'Sagana', 'Muranga', 'Kerugoya', 'Karatina', 'Nyeri', 'Othaya', 'Nanyuki', 'Embu', 'Matuu', 'Nkubu', 'Nyahururu'],
                'rate' => 500
            ],
            'Zone 1B' => [
                'destinations' => ['Diani(1000)', 'Malindi(1000)', 'Watamu(1000)', 'Kilifi(900)', 'Mtwapa(800)', 'Kwale(800)', 'Lamu(2500)', 'Namanga(650)']
            ],
            'Zone 2B' => [
                'destinations' => ['Bungoma(600)', 'Kapsabet(600)', 'Migori(650)', 'Homabay(650)', 'Busia(650)', 'Siaya(650)']
            ],
            'Zone 3B' => [
                'destinations' => ['Maua(650)', 'Isiolo(650)', 'Meru(650)', 'Garissa(650)', 'Mwingi(650)']
            ],
        ];

        foreach ($zoneDestinations as $zoneName => $data) {
            $zoneId = $zones[$zoneName] ?? null;
            if (!$zoneId) continue;

            foreach ($data['destinations'] as $dest) {
                // Handle rate format for B zones (e.g., "Diani(1000)")
                if (str_contains($dest, '(')) {
                    preg_match('/(.+)\((\d+)\)/', $dest, $matches);
                    $destination = trim($matches[1]);
                    $rate = (int) $matches[2];
                } else {
                    $destination = $dest;
                    $rate = $data['rate'];
                }

                DB::table('rates')->insert([
                    'approvedBy' => null,
                    'added_by' => $addedBy,
                    'office_id' => $nairobiOfficeId,
                    'zone_id' => $zoneId,
                    'routeFrom' => $origin . ' - ' . $destination,
                    'zone' => $zoneName,
                    'origin' => $origin,
                    'destination' => $destination,
                    'rate' => $rate,
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
    }
}

