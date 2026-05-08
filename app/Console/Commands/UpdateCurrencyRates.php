<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateCurrencyRates extends Command
{
    protected $signature   = 'currency:update-rates {--force : Force update even if recently updated}';
    protected $description = 'Fetch latest USD and RMB exchange rates (FCFA base) from external API';

    // Free API — no key needed for XOF base
    private const API_URL = 'https://open.er-api.com/v6/latest/XOF';

    // Map our internal codes to ISO codes
    private const CODE_MAP = ['USD' => 'USD', 'RMB' => 'CNY'];

    public function handle(): int
    {
        try {
            $response = Http::timeout(10)->get(self::API_URL);

            if (!$response->successful()) {
                $this->warn('API returned ' . $response->status() . ' — keeping existing rates.');
                Log::warning('currency:update-rates API error', ['status' => $response->status()]);
                return self::FAILURE;
            }

            $rates = $response->json('rates', []);

            foreach (self::CODE_MAP as $internalCode => $isoCode) {
                if (!isset($rates[$isoCode])) {
                    continue;
                }

                // API gives: 1 XOF = X USD → invert to get FCFA per unit
                $apiRatePerXof  = (float) $rates[$isoCode];
                $fcfaPerUnit    = $apiRatePerXof > 0 ? round(1 / $apiRatePerXof, 6) : null;

                if (!$fcfaPerUnit) {
                    continue;
                }

                $currency = Currency::where('code', $internalCode)->first();
                if (!$currency) {
                    continue;
                }

                // Save history
                DB::table('currency_rate_history')->insert([
                    'code'          => $internalCode,
                    'fcfa_per_unit' => $currency->fcfa_per_unit,
                    'source'        => 'api',
                    'changed_at'    => now(),
                ]);

                $currency->update([
                    'fcfa_per_unit'    => $fcfaPerUnit,
                    'rate_updated_at'  => now(),
                ]);

                $this->info("Updated {$internalCode}: 1 {$internalCode} = {$fcfaPerUnit} FCFA");
            }

            Currency::forgetCache();
            $this->info('Currency rates updated successfully.');
            return self::SUCCESS;

        } catch (\Throwable $e) {
            Log::error('currency:update-rates failed', ['error' => $e->getMessage()]);
            $this->error('Failed to update rates: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
