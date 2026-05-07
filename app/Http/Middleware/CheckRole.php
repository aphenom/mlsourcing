<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, int $role): Response
    {
        $user = Auth::user();
        
        if ($user && $user->role === $role) {
            if ($role === 3) {
                if ($user->status === 'pending') {
                    return redirect()->route('seller.pending');
                }
                if ($user->status === 'blocked') {
                    auth()->logout();
                    return redirect()->route('login')->withErrors(['email' => __('pages.account_blocked')]);
                }
            }
            return $next($request);
        }

        return response()->view('errors.403', [], 403);
    }
}
