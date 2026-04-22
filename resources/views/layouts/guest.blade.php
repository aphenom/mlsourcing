<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO PRINCIPAL -->
        <title>ML SOURCING | Plateforme de sourcing & approvisionnement direct usine</title>

        <meta name="description" content="ML SOURCING est une plateforme de sourcing B2B spécialisée dans l’approvisionnement direct usine. Nous connectons les entreprises aux fabricants pour garantir qualité et prix d’usine.">

        <meta name="keywords" content="sourcing, plateforme de sourcing, approvisionnement industriel, sourcing B2B, prix usine, fournisseurs fabricants, sourcing international">

        <meta name="robots" content="index, follow">
        <meta name="author" content="ML SOURCING">

        <!-- RESPONSIVE -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- OPEN GRAPH (réseaux sociaux) -->
        <meta property="og:title" content="ML SOURCING | Sourcing direct usine au prix fabricant">
        <meta property="og:description" content="Plateforme de sourcing reliant directement les entreprises aux usines pour des produits de haute qualité aux prix d’usine.">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="fr_FR">

        <!-- TWITTER CARD -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="ML SOURCING | Plateforme de sourcing B2B">
        <meta name="twitter:description" content="Approvisionnement direct fabricant, sans intermédiaire, pour des produits qualitatifs aux prix d’usine.">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
