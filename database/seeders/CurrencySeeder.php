<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'XOF', 'symbol' => 'FCFA', 'name' => 'Franc CFA (UEMOA)', 'fcfa_per_unit' => 1,   'is_active' => true],
            ['code' => 'USD', 'symbol' => '$',    'name' => 'Dollar américain',   'fcfa_per_unit' => 600, 'is_active' => true],
            ['code' => 'RMB', 'symbol' => '¥',    'name' => 'Yuan chinois',       'fcfa_per_unit' => 83,  'is_active' => true],
        ];

        foreach ($currencies as $c) {
            DB::table('currencies')->updateOrInsert(
                ['code' => $c['code']],
                array_merge($c, [
                    'rate_updated_at' => now(),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ])
            );
        }
    }
}
