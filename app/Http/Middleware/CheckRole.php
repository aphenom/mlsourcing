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
            return $next($request);
        }

        // Redirect or return a 403 response if the user does not have the correct role
        return response()->view('errors.403', [], 403);
    }
}
