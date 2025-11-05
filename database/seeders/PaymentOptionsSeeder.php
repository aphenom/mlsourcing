<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentOption;

class PaymentOptionsSeeder extends Seeder
{

    //

    public function run(): void
    {
        PaymentOption::create([
            'name' => 'Paypal',
            'image' => 'paypal.png',
            'details' => json_encode([
                'fields' => [
                    'email' => 'Paypal Email'
                ]
            ])
        ]);

        PaymentOption::create([
            'name' => 'CIH',
            'image' => 'cih.png',
            'details' => json_encode([
                'Titulaire' => 'MONSIEUR OUSSAMA SABRI',
                'RIB' => '230 780 3341037211010300 24',
                'IBAN' => 'MA64 2307 8033 4103 7211 0103 0024',
                'Code SWIFT' => 'CIHMMAMC',
                'N CIH' => '3341037211010300'
            ])
        ]);

        PaymentOption::create([
            'name' => 'Wise',
            'image' => 'wise.png',
            'details' => json_encode([
                'Account holder' => 'VIOMUR LTD',
                'ACH and Wire routing number' => '026073150',
                'Account number' => '8312291194',
                'Account type' => 'Checking',
                'Bank name and address' => 'Community Federal Savings Bank, 89-16 Jamaica Ave, Woodhaven NY 11421, United States'
            ])
        ]);
    }
}

