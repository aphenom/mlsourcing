<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SEO PRINCIPAL -->
    <title>Inscription - ML SOURCING | Plateforme de sourcing & approvisionnement direct usine</title>

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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
        }

        header {
            background-color: white;
            padding: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo {
            max-width: 150px;
        }

        .main-section {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('https://plus.unsplash.com/premium_photo-1661964050170-b9e54345217d?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8c2hpcHBpbmd8ZW58MHx8MHx8fDA%3D');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .main-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #00A75236;
            z-index: 1;
        }

        .signup-form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 2;
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .btn-signup {
            background-color: #00A752;
            color: white;
            width: 100%;
            border-radius: 5px;
        }

        .btn-signup:hover {
            background-color:rgb(85, 255, 167);
        }

        .form-group {
            margin-bottom: 20px;
        }

        footer {
            background-color: #00A752;
            color: white;
            text-align: center;
            padding: 20px;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .image-container img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .links {
            text-align: center;
            margin-top: 15px;
        }

        .links a {
            color: #00A752;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <img src="{{ asset('adminTheme/assets/img/logo.png') }}" alt="Logo" class="logo">
    </header>

    <!-- Main Section -->
    <div class="container-fluid main-section">
        <div class="row w-100 m-0 justify-content-center align-items-center">
            <!-- Left Section (Sign-Up Form) -->
            <div class="col-md-6 p-0 d-flex justify-content-center align-items-center">
                <div class="signup-form">
                    <h2 class="text-center">{{ __('global.sign_up_title') }}</h2>
                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">{{ __('global.nom') }}</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <label for="phone_number">{{ __('global.contact') }} : (ex : +21260000000)</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" required>
                            @error('phone_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">{{ __('global.password') }}</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation">{{ __('global.confirm_password') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">{{ __('global.address') }}</label>
                            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Account Type -->
                        <div class="form-group">
                            <label for="user_type">{{ __('global.type_compte') }}</label>
                            <select id="user_type" name="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                <option value="particular" {{ old('user_type') == 'particular' ? 'selected' : '' }}>{{ __('global.particulier') }}</option>
                                <option value="company" {{ old('user_type') == 'company' ? 'selected' : '' }}>{{ __('global.compagnie') }}</option>
                            </select>
                            @error('user_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Particular Fields -->
                        <div id="particular-fields" class="conditional-fields" style="display: none;">
                            <div class="form-group">
                                <label for="identity_perso">{{ __('global.photo_identity') }}</label>
                                <input type="file" id="identity_perso" name="identity_perso" class="form-control @error('identity_perso') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                                @error('identity_perso')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Company Fields -->
                        <div id="company-fields" class="conditional-fields" style="display: none;">
                            <!-- Company Name -->
                            <div class="form-group">
                                <label for="company_name">{{ __('global.company_name') }}</label>
                                <input type="text" id="company_name" name="company_name" class="form-control @error('company_name') is-invalid @enderror">
                                @error('company_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Company Information -->
                            <div class="form-group">
                                <label for="company_information">{{ __('global.company_information') }}</label>
                                <textarea id="company_information" name="company_information" class="form-control @error('company_information') is-invalid @enderror" rows="4"></textarea>
                                @error('company_information')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Company Document -->
                            <div class="form-group">
                                <label for="company_document">{{ __('global.company_document') }}</label>
                                <input type="file" id="company_document" name="company_document" class="form-control @error('company_document') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                                @error('company_document')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-signup">{{ __('global.sign_up_btn') }}</button>

                        <!-- Links -->
                        <div class="links">
                            <a href="{{ route('login') }}" class="create-account">{{ __('global.login') }}</a>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Right Section (Image) -->
            <div class="col-md-6 p-0 d-flex justify-content-center align-items-center">
                <div class="image-container">
                    <img src="https://i.ibb.co/1ftfy9Rv/1.png" alt="Sign up Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© <?php echo date('Y'); ?> ML Sourcing. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript to Toggle Fields -->
    <script>
        const typeSelect = document.getElementById('user_type');
        const particularFields = document.getElementById('particular-fields');
        const companyFields = document.getElementById('company-fields');

        typeSelect.addEventListener('change', function () {
            const selectedValue = this.value;

            if (selectedValue === 'particular') {
                particularFields.style.display = 'block';
                companyFields.style.display = 'none';
            } else if (selectedValue === 'company') {
                particularFields.style.display = 'none';
                companyFields.style.display = 'block';
            } else {
                particularFields.style.display = 'none';
                companyFields.style.display = 'none';
            }
        });

        // Trigger change on page load to set initial visibility
        typeSelect.dispatchEvent(new Event('change'));
    </script>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
