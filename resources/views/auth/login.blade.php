<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ML SOURCING | Plateforme de sourcing & approvisionnement direct usine</title>
    <meta name="description" content="ML SOURCING est une plateforme de sourcing B2B spécialisée dans l'approvisionnement direct usine. Nous connectons les entreprises aux fabricants pour garantir qualité et prix d'usine.">
    <meta name="keywords" content="sourcing, plateforme de sourcing, approvisionnement industriel, sourcing B2B, prix usine, fournisseurs fabricants, sourcing international">
    <meta name="robots" content="index, follow">
    <meta name="author" content="ML SOURCING">
    <meta property="og:title" content="ML SOURCING | Sourcing direct usine au prix fabricant">
    <meta property="og:description" content="Plateforme de sourcing reliant directement les entreprises aux usines pour des produits de haute qualité aux prix d'usine.">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="ML SOURCING | Plateforme de sourcing B2B">
    <meta name="twitter:description" content="Approvisionnement direct fabricant, sans intermédiaire, pour des produits qualitatifs aux prix d'usine.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: white;
            padding: 14px 0;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            flex-shrink: 0;
        }

        .logo { max-width: 120px; }

        .main-section {
            flex: 1;
            /* min-height lets the section grow when content is taller than the viewport */
            min-height: 0;
            display: flex;
            align-items: center;
            background-image: url('https://plus.unsplash.com/premium_photo-1661964050170-b9e54345217d?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8c2hpcHBpbmd8ZW58MHx8MHx8fDA%3D');
            background-size: cover;
            background-position: center;
            position: relative;
            padding: 2rem 1rem;
        }

        .main-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-color: #00A75236;
            z-index: 1;
        }

        .main-section > .row {
            position: relative;
            z-index: 2;
        }

        .login-form {
            background-color: white;
            padding: clamp(20px, 5vw, 40px);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .login-form h2 { font-size: clamp(1.25rem, 4vw, 1.6rem); }

        .btn-login {
            background-color: #00A752;
            color: white;
            width: 100%;
            border-radius: 6px;
            padding: 10px;
            font-size: 1rem;
            border: none;
            transition: background-color .2s;
        }
        .btn-login:hover, .btn-login:focus { background-color: #008f42; color: white; }

        .form-group { margin-bottom: 16px; }
        .form-group label { font-weight: 500; color: #555; font-size: .9rem; }

        .links { text-align: center; margin-top: 15px; }
        .links a { color: #00A752; text-decoration: none; font-size: .9rem; }
        .links a:hover { text-decoration: underline; }

        .image-container img { width: 100%; height: auto; border-radius: 10px; max-height: 70vh; object-fit: contain; }

        footer {
            background-color: #00A752;
            color: white;
            text-align: center;
            padding: 16px;
            flex-shrink: 0;
        }
        footer p { margin: 0; font-size: .85rem; }

        /* ── Mobile overrides ── */
        @media (max-width: 767.98px) {
            .main-section {
                align-items: flex-start;
                padding: 1.5rem 1rem;
            }
            .main-section > .row {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <header>
        <img src="{{ asset('adminTheme/assets/img/logo.png') }}" alt="Logo" class="logo">
    </header>

    <div class="container-fluid main-section">
        <div class="row w-100 m-0 justify-content-center align-items-center g-3">

            <!-- Form column: full width on mobile, half on md+ -->
            <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
                <div class="login-form">
                    <h2 class="text-center mb-4">{{ __('global.login_title') }}</h2>

                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">{{ session('status') }}</div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" autocomplete="email" required>
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('global.password') }}</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="current-password" required>
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('global.remember_me') }}</label>
                        </div>

                        <button type="submit" class="btn btn-login">{{ __('global.login_btn') }}</button>

                        <div class="links mt-3">
                            <a href="{{ route('password.request') }}">{{ __('global.forgot_password') }}?</a>
                            <br><span class="text-muted small">{{ __('global.or') }}</span><br>
                            <a href="{{ route('register') }}">{{ __('global.create_account') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Image column: hidden on mobile -->
            <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center">
                <div class="image-container">
                    <img src="https://i.ibb.co/1ftfy9Rv/1.png" alt="Login Image" loading="lazy">
                </div>
            </div>

        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ML Sourcing. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
