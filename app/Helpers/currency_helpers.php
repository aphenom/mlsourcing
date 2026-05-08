<?php

use App\Models\Currency;

if (!function_exists('active_currency')) {
    function active_currency(): string
    {
        return session('currency', 'XOF');
    }
}

if (!function_exists('fx_rate')) {
    /**
     * Returns fcfa_per_unit for the given (or active) currency.
     * e.g. USD → 600, XOF → 1
     */
    function fx_rate(?string $code = null): float
    {
        return Currency::getRate($code ?? active_currency());
    }
}

if (!function_exists('to_fcfa')) {
    /**
     * Convert an amount expressed in the active (or given) currency to FCFA.
     */
    function to_fcfa(float|int $amount, ?string $code = null): float
    {
        return $amount * fx_rate($code ?? active_currency());
    }
}

if (!function_exists('from_fcfa')) {
    /**
     * Convert a FCFA amount to the active (or given) display currency.
     */
    function from_fcfa(float|int $fcfa, ?string $code = null): float
    {
        $rate = fx_rate($code ?? active_currency());
        return $rate > 0 ? $fcfa / $rate : $fcfa;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format a FCFA amount for display in the active (or given) currency.
     * Returns e.g. "100.00 $" / "60 000 FCFA" / "¥722.00"
     */
    function format_currency(float|int|null $fcfa, ?string $code = null): string
    {
        if ($fcfa === null || $fcfa === '') {
            return '—';
        }
        $code   = $code ?? active_currency();
        $amount = from_fcfa((float) $fcfa, $code);

        return match ($code) {
            'USD' => '$' . number_format($amount, 2),
            'RMB' => '¥' . number_format($amount, 2),
            default => number_format($amount, 0, ',', ' ') . ' FCFA',
        };
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol(?string $code = null): string
    {
        $code = $code ?? active_currency();
        return match ($code) {
            'USD' => '$',
            'RMB' => '¥',
            default => 'FCFA',
        };
    }
}
