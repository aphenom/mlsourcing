<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Currency;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share active currency info with all views
        View::composer('*', function ($view) {
            $code = session('currency', 'XOF');
            $view->with('_activeCurrency', $code);
            $view->with('_currencySymbol', currency_symbol($code));
            $view->with('_fxRate', fx_rate($code));
        });
    }
}
