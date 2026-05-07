<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = session('locale', config('app.locale'));

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
