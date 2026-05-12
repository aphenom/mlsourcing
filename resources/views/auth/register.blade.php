<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - ML SOURCING | Plateforme de sourcing & approvisionnement direct usine</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            flex-shrink: 0;
        }

        .logo { max-width: 120px; }

        .main-section {
            flex: 1;
            display: flex;
            align-items: stretch;
            background-image: url('https://plus.unsplash.com/premium_photo-1661964050170-b9e54345217d?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8c2hpcHBpbmd8ZW58MHx8MHx8fDA%3D');
            background-size: cover;
            background-position: center;
            position: relative;
            padding: 0;
        }

        .main-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-color: #00A75236;
            z-index: 1;
            pointer-events: none;
        }

        /* Row stretches to fill the full section height */
        .main-section > .row {
            position: relative;
            z-index: 2;
            min-height: 100%;
            flex: 1;
        }

        /* Form column: padded internally, scrollable if form overflows */
        .form-col {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem 1.5rem;
            overflow-y: auto;
        }

        /* Image column: zero padding, sticky so it stays in view while form scrolls */
        .image-col {
            padding: 0;
            overflow: hidden;
            position: sticky;
            top: 0;
            height: 100vh;
            align-self: flex-start;
        }

        .signup-form {
            background-color: white;
            padding: clamp(20px, 5vw, 40px);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
        }

        .signup-form h2 { font-size: clamp(1.25rem, 4vw, 1.6rem); }

        .btn-signup {
            background-color: #00A752;
            color: white;
            width: 100%;
            border-radius: 6px;
            padding: 10px;
            font-size: 1rem;
            border: none;
            transition: background-color .2s;
        }
        .btn-signup:hover, .btn-signup:focus { background-color: #008f42; color: white; }

        .form-group { margin-bottom: 14px; }
        .form-group label { font-weight: 500; color: #555; font-size: .875rem; }
        .form-group .form-control { font-size: .9rem; }

        .links { text-align: center; margin-top: 15px; }
        .links a { color: #00A752; text-decoration: none; font-size: .9rem; }
        .links a:hover { text-decoration: underline; }

        /* Image fills its column completely */
        .image-container {
            height: 100%;
            width: 100%;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

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
            .form-col { padding: 1.25rem 0.75rem; align-items: flex-start; }
        }
    </style>
</head>
<body>

    <header>
        <img src="{{ asset('adminTheme/assets/img/logo.png') }}" alt="Logo" class="logo">
    </header>

    <div class="container-fluid main-section">
        <div class="row w-100 m-0 align-items-stretch g-0">

            <!-- Form column: full width on mobile, half on md+ -->
            <div class="col-12 col-md-6 form-col">
                <div class="signup-form">
                    <h2 class="text-center mb-3">{{ __('global.sign_up_title') }}</h2>
                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">{{ __('global.nom') }}</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" autocomplete="name" required>
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_number">{{ __('global.contact') }} <span class="text-muted fw-normal">(ex : +21260000000)</span></label>
                            <input type="tel" id="phone_number" name="phone_number"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                value="{{ old('phone_number') }}" autocomplete="tel" required>
                            @error('phone_number')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

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
                                autocomplete="new-password" required>
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">{{ __('global.confirm_password') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" autocomplete="new-password" required>
                        </div>

                        <div class="form-group">
                            <label for="address">{{ __('global.address') }}</label>
                            <textarea id="address" name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                rows="2" required>{{ old('address') }}</textarea>
                            @error('address')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group">
                            <label for="user_type">{{ __('global.type_compte') }}</label>
                            <select id="user_type" name="user_type"
                                class="form-select @error('user_type') is-invalid @enderror" required>
                                <option value="particular" {{ old('user_type') == 'particular' ? 'selected' : '' }}>{{ __('global.particulier') }}</option>
                                <option value="company" {{ old('user_type') == 'company' ? 'selected' : '' }}>{{ __('global.compagnie') }}</option>
                            </select>
                            @error('user_type')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Particular Fields -->
                        <div id="particular-fields" class="conditional-fields" style="display:none;">
                            <div class="form-group">
                                <label for="identity_perso">{{ __('global.photo_identity') }}</label>
                                <input type="file" id="identity_perso" name="identity_perso"
                                    class="form-control @error('identity_perso') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.pdf">
                                @error('identity_perso')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <!-- Company Fields -->
                        <div id="company-fields" class="conditional-fields" style="display:none;">
                            <div class="form-group">
                                <label for="company_name">{{ __('global.company_name') }}</label>
                                <input type="text" id="company_name" name="company_name"
                                    class="form-control @error('company_name') is-invalid @enderror">
                                @error('company_name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group">
                                <label for="company_information">{{ __('global.company_information') }}</label>
                                <textarea id="company_information" name="company_information"
                                    class="form-control @error('company_information') is-invalid @enderror"
                                    rows="3"></textarea>
                                @error('company_information')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group">
                                <label for="company_document">{{ __('global.company_document') }}</label>
                                <input type="file" id="company_document" name="company_document"
                                    class="form-control @error('company_document') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.pdf">
                                @error('company_document')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-signup mt-2">{{ __('global.sign_up_btn') }}</button>

                        <div class="links">
                            <a href="{{ route('login') }}">{{ __('global.login') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Image column: hidden on mobile, full height on desktop -->
            <div class="col-md-6 d-none d-md-block image-col">
                <div class="image-container">
                    <img src="https://i.ibb.co/1ftfy9Rv/1.png" alt="Sign up Image" loading="lazy">
                </div>
            </div>

        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ML Sourcing. All Rights Reserved.</p>
    </footer>

    <script>
        const typeSelect = document.getElementById('user_type');
        const particularFields = document.getElementById('particular-fields');
        const companyFields = document.getElementById('company-fields');

        typeSelect.addEventListener('change', function () {
            const v = this.value;
            particularFields.style.display = v === 'particular' ? 'block' : 'none';
            companyFields.style.display    = v === 'company'    ? 'block' : 'none';
        });

        typeSelect.dispatchEvent(new Event('change'));
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
