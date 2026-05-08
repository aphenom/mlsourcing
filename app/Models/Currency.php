<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    protected $fillable = ['code', 'symbol', 'name', 'fcfa_per_unit', 'is_active', 'rate_updated_at'];

    protected $casts = ['fcfa_per_unit' => 'float', 'is_active' => 'boolean'];

    // Returns all active currencies, cached 10 min
    public static function allCached(): \Illuminate\Support\Collection
    {
        return Cache::remember('currencies', 600, fn() => self::where('is_active', true)->get());
    }

    // Returns fcfa_per_unit for a given code (e.g. 600 for USD)
    public static function getRate(string $code): float
    {
        return self::allCached()->firstWhere('code', $code)?->fcfa_per_unit ?? 1.0;
    }

    public static function getSymbol(string $code): string
    {
        return self::allCached()->firstWhere('code', $code)?->symbol ?? 'FCFA';
    }

    public static function forgetCache(): void
    {
        Cache::forget('currencies');
    }
}
