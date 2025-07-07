<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch first user ID for createdBy and approvedBy
        $userId = DB::table('users')->value('id') ?? 1;

        $offices = [
            [
                'createdBy' => $userId,
                'name' => 'Mombasa Office',
                'shortName' => 'MBS',
                'country' => 'Kenya',
                'city' => 'Mombasa',
                'longitude' => '39.6682',
                'latitude' => '-4.0435',
                'type' => 'staff',
                'mpesaTill' => 123456,
                'mpesaPaybill' => 654321,
                'mpesaTillStkCallBack' => 'https://example.com/mbs/till/stk',
                'mpesaTillC2bConfirmation' => 'https://example.com/mbs/till/confirm',
                'mpesaTillC2bValidation' => 'https://example.com/mbs/till/validate',
                'mpesaPaybillStkCallBack' => 'https://example.com/mbs/paybill/stk',
                'mpesaPaybillC2bConfirmation' => 'https://example.com/mbs/paybill/confirm',
                'mpesaPaybillC2bValidation' => 'https://example.com/mbs/paybill/validate',
                'approvedBy' => $userId,
                'status' => 'active',
            ],
            [
                'createdBy' => $userId,
                'name' => 'Nairobi Office',
                'shortName' => 'NRB',
                'country' => 'Kenya',
                'city' => 'Nairobi',
                'longitude' => '36.8219',
                'latitude' => '-1.2921',
                'type' => 'staff',
                'mpesaTill' => 234567,
                'mpesaPaybill' => 765432,
                'mpesaTillStkCallBack' => 'https://example.com/nrb/till/stk',
                'mpesaTillC2bConfirmation' => 'https://example.com/nrb/till/confirm',
                'mpesaTillC2bValidation' => 'https://example.com/nrb/till/validate',
                'mpesaPaybillStkCallBack' => 'https://example.com/nrb/paybill/stk',
                'mpesaPaybillC2bConfirmation' => 'https://example.com/nrb/paybill/confirm',
                'mpesaPaybillC2bValidation' => 'https://example.com/nrb/paybill/validate',
                'approvedBy' => $userId,
                'status' => 'active',
            ],
            [
                'createdBy' => $userId,
                'name' => 'Kisumu Office',
                'shortName' => 'KSM',
                'country' => 'Kenya',
                'city' => 'Kisumu',
                'longitude' => '34.7617',
                'latitude' => '-0.0917',
                'type' => 'agent',
                'mpesaTill' => 345678,
                'mpesaPaybill' => 876543,
                'mpesaTillStkCallBack' => 'https://example.com/ksm/till/stk',
                'mpesaTillC2bConfirmation' => 'https://example.com/ksm/till/confirm',
                'mpesaTillC2bValidation' => 'https://example.com/ksm/till/validate',
                'mpesaPaybillStkCallBack' => 'https://example.com/ksm/paybill/stk',
                'mpesaPaybillC2bConfirmation' => 'https://example.com/ksm/paybill/confirm',
                'mpesaPaybillC2bValidation' => 'https://example.com/ksm/paybill/validate',
                'approvedBy' => $userId,
                'status' => 'active',
            ],
            [
                'createdBy' => $userId,
                'name' => 'Nakuru Office',
                'shortName' => 'NKR',
                'country' => 'Kenya',
                'city' => 'Nakuru',
                'longitude' => '36.0800',
                'latitude' => '-0.3031',
                'type' => 'agent',
                'mpesaTill' => 456789,
                'mpesaPaybill' => 987654,
                'mpesaTillStkCallBack' => 'https://example.com/nkr/till/stk',
                'mpesaTillC2bConfirmation' => 'https://example.com/nkr/till/confirm',
                'mpesaTillC2bValidation' => 'https://example.com/nkr/till/validate',
                'mpesaPaybillStkCallBack' => 'https://example.com/nkr/paybill/stk',
                'mpesaPaybillC2bConfirmation' => 'https://example.com/nkr/paybill/confirm',
                'mpesaPaybillC2bValidation' => 'https://example.com/nkr/paybill/validate',
                'approvedBy' => $userId,
                'status' => 'pending',
            ],
            [
                'createdBy' => $userId,
                'name' => 'Malindi Office',
                'shortName' => 'MLD',
                'country' => 'Kenya',
                'city' => 'Malindi',
                'longitude' => '40.1169',
                'latitude' => '-3.2192',
                'type' => 'staff',
                'mpesaTill' => 567890,
                'mpesaPaybill' => 198765,
                'mpesaTillStkCallBack' => 'https://example.com/mld/till/stk',
                'mpesaTillC2bConfirmation' => 'https://example.com/mld/till/confirm',
                'mpesaTillC2bValidation' => 'https://example.com/mld/till/validate',
                'mpesaPaybillStkCallBack' => 'https://example.com/mld/paybill/stk',
                'mpesaPaybillC2bConfirmation' => 'https://example.com/mld/paybill/confirm',
                'mpesaPaybillC2bValidation' => 'https://example.com/mld/paybill/validate',
                'approvedBy' => $userId,
                'status' => 'active',
            ],
        ];

        DB::table('offices')->insert($offices);
    }
}

