<?php

namespace App\Http\Controllers;
use App\Models\User;
// use App\Traits\UserTrait;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
class FilterController extends Controller
{
    // use UserTrait;


    public function switch_currency(Request $request)
    {
//        dump(session()->all());
//        dump($request->all());

        $currency = $request->get('currency','XOF');

        // Vérifier si la langue est valide
        if (!in_array($currency, ['XOF', 'USD', 'RMB'])) {
            throw new \Exception('Monnaie non supportée.');
        }

        session()->put('currency', $currency);

        return redirect()->back();
    }


    /**
     * Show specified view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function switch_language(Request $request)
    {

        // Récupérer la langue choisie par l'utilisateur
        $locale = $request->get('locale','fr'); // 'fr' par défaut si non fourni

        // Vérifier si la langue est valide
        if (!in_array($locale, ['en', 'fr'])) {
            throw new \Exception('Langue non supportée.');
        }

        // Définir la langue et la sauvegarder dans la session
        App::setLocale($locale);
        session(['locale' => $locale]);

        return back();
    }

}
