<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\App;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Récupérer la langue choisie par l'utilisateur
        $locale = $request->input('locale', 'fr'); // 'fr' par défaut si non fourni

        // Vérifier si la langue est valide
        if (!in_array($locale, ['en', 'fr'])) {
            throw new \Exception('Langue non supportée.');
        }

        // Définir la langue et la sauvegarder dans la session
        App::setLocale($locale);
        session(['locale' => $locale]);
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Récupérer la langue choisie par l'utilisateur
        $locale = $request->input('locale', 'fr'); // 'fr' par défaut si non fourni

        // Vérifier si la langue est valide
        if (!in_array($locale, ['en', 'fr'])) {
            throw new \Exception('Langue non supportée.');
        }

        // Définir la langue et la sauvegarder dans la session
        App::setLocale($locale);
        session(['locale' => $locale]);


        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
