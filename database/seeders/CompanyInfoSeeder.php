<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'company_name' => 'Ufanisi Courier Services Ltd',
                'website' => 'https://www.ufanisi-courier.co.ke',
                'location' => 'Nairobi, Kenya',
                'address' => 'Moi Avenue, Pioneer Building, Nairobi',
                'pin' => 'P012345678A',
                'logo' => 'logos/ufanisi.png',
                'slogan' => 'Delivering Efficiency Everywhere',
                'contact' => '+254712345678',
                'email' => 'info@ufanisi-courier.co.ke',
            ],
            [
                'company_name' => 'ExpressGo Logistics',
                'website' => 'https://www.expressgo.co.ke',
                'location' => 'Mombasa, Kenya',
                'address' => 'Digo Road, Mombasa',
                'pin' => 'P987654321B',
                'logo' => 'logos/expressgo.png',
                'slogan' => 'Speed Meets Reliability',
                'contact' => '+254798765432',
                'email' => 'support@expressgo.co.ke',
            ],
            [
                'company_name' => 'FastShip Africa',
                'website' => 'https://www.fastship.africa',
                'location' => 'Kisumu, Kenya',
                'address' => 'Jomo Kenyatta Hwy, Kisumu',
                'pin' => 'P112233445C',
                'logo' => 'logos/fastship.png',
                'slogan' => 'Your Partner in Rapid Delivery',
                'contact' => '+254700112233',
                'email' => 'contact@fastship.africa',
            ],
        ];

        DB::table('company_infos')->insert($companies);
    }
}
