<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpecialRatesSeeder extends Seeder
{
    public function run()
    {
        $townTeamId = DB::table('clients')->where('name', 'Town Team')->value('id');
        $jefreyCodeId = DB::table('clients')->where('name', 'Jefrey Code')->value('id');
        $officeId = DB::table('offices')->where('name', 'Nairobi Office')->value('id');

        if (!$townTeamId || !$jefreyCodeId || !$officeId) {
            dd("Client or Office not found. Please confirm names.");
        }

        $now = Carbon::now();

        // -----------------------------
        // TOWN TEAM DESTINATIONS
        // -----------------------------
        $townTeamSameDay = [
            'Adams Arcade', 'Bahati', 'Chiromo', 'Eastlands', 'Eastleigh', 'Highridge',
            'Industrial Area','Kileleshwa', 'Kilimani', 'Lavington', 'Madaraka', 'Makongeni',
            'Ngara', 'Muthaiga', 'Ngummo', 'Nyayo Highrise', 'Pangani', 'Parklands',
            'Yaya Center', 'Riverside Park', 'South B', 'South C', 'Starehe', 'Upper Hill',
            'Village Market', 'Westlands', 'Woodley', 'Avenue Park', 'Baba Dogo', 'Banda',
            'Bomas', 'BuruBuru', 'Continental', 'Donholm', 'Drive-in', 'Evergreen',
            'Fedha', 'Greenfield', 'Hill View', "Lang'ata", 'Jacaranda', 'Kasarani',
            'Kangemi', 'Karura', 'New Runda', 'Lenana', 'Loresho', 'Lower Kabete',
            'Lumumba', 'Zimmerman', 'Makadara', 'Mbagathi', 'Mountain View', 'Mimosa',
            'Nyari', 'Racecourse', 'Riara', 'Ridgeways', 'Rosslyn', 'Roysambu', 'Savannah',
            'Banana', 'Kahawa West', 'Karen', 'Kiambu', 'Kikuyu', 'Kimbo', 'Kiambaa',
            'KU Referral Hospital', 'KU University', 'Mlolongo', 'Ngong', 'Ongata Rongai',
            'JKIA', 'Kahawa Sukari', 'Ruiru', 'Utawala', 'Embakasi', 'Riruta', 'Ruaka',
            'Imara Daima','Within Nairobi'
        ];

        $townTeamOvernight = [
            'Machakos', 'Emali', 'Makindu', 'Kibwezi', 'Mtito', 'Voi', 'Mariakani', 'Mazeras',
            'Mombasa', 'Kitui','Naivasha', 'Gilgil', 'Nakuru', 'Kericho', 'Kisumu', 'Narok',
            'Bomet', 'Eldoret', 'Kakamega', 'Kisii', 'Kitale', 'Litein','Thika', 'Sagana',
            'Muranga', 'Kerugoya', 'Karatina', 'Nyeri', 'Othaya', 'Nanyuki', 'Embu', 'Matuu',
            'Nkubu', 'Nyahururu','Diani', 'Malindi', 'Watamu', 'Kilifi', 'Mtwapa', 'Kwale',
            'Bungoma', 'Kapsabet', 'Migori', 'Homabay', 'Busia', 'Siaya', 'Awendo', 'Muhoroni',
            'Bondo','Maua', 'Isiolo', 'Meru', 'Garissa', 'Mwingi', 'Kwale'
        ];


        // Insert Town Team Same Day
        foreach ($townTeamSameDay as $destination) {
            DB::table('special_rates')->insert([
                'added_by' => 1, // adjust if needed
                'routeFrom' => 'Nairobi',
                'destination' => $destination,
                'type' => 'Same Day',
                'rate' => 400,
                'additional_cost_per_kg' => 50,
                'status' => 'active',
                'approvalStatus' => 'approved',
                'client_id' => $townTeamId,
                'office_id' => $officeId,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // Insert Town Team Overnight
        foreach ($townTeamOvernight as $destination) {
            DB::table('special_rates')->insert([
                'added_by' => 1,
                'routeFrom' => 'Nairobi',
                'destination' => $destination,
                'type' => 'Overnight',
                'rate' => 1200,
                'additional_cost_per_kg' => 50,
                'status' => 'active',
                'approvalStatus' => 'approved',
                'client_id' => $townTeamId,
                'office_id' => $officeId,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }


        // -----------------------------
        // JEFREY CODE (Similar to Town Team)
        // -----------------------------

        // Add "Outside Nairobi" to same day list
        $jefreySameDay = $townTeamSameDay;
        $jefreySameDay[] = 'Outside Nairobi';

        foreach ($jefreySameDay as $destination) {
            DB::table('special_rates')->insert([
                'added_by' => 1,
                'routeFrom' => 'Nairobi',
                'destination' => $destination,
                'type' => 'Same Day',
                'rate' => ($destination === 'Outside Nairobi') ? 500 : 400,
                'additional_cost_per_kg' => 30,
                'status' => 'active',
                'approvalStatus' => 'approved',
                'client_id' => $jefreyCodeId,
                'office_id' => $officeId,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // Overnight (same list as Town Team)
        foreach ($townTeamOvernight as $destination) {
            DB::table('special_rates')->insert([
                'added_by' => 1,
                'routeFrom' => 'Nairobi',
                'destination' => $destination,
                'type' => 'Overnight',
                'rate' => 500,
                'additional_cost_per_kg' => 30,
                'status' => 'active',
                'approvalStatus' => 'approved',
                'client_id' => $jefreyCodeId,
                'office_id' => $officeId,
                'applicableFrom' => $now,
                'applicableTo' => null,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}

