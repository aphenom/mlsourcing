<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('pages.confirm_password_title') }} - ML Sourcing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f9f9f9; margin: 0; padding: 0; }
        header { background-color: white; padding: 20px 0; text-align: center; }
        .logo { max-width: 150px; }
        .main-section {
            min-height: calc(100vh - 130px);
            display: flex; justify-content: center; align-items: center;
            background-image: url('https://plus.unsplash.com/premium_photo-1661964050170-b9e54345217d?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8c2hpcHBpbmd8ZW58MHx8MHx8fDA%3D');
            background-size: cover; background-position: center; position: relative;
        }
        .main-section::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #00A75236; z-index: 1;
        }
        .card-form {
            background-color: white; padding: 40px; border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); z-index: 2; position: relative;
            width: 100%; max-width: 420px;
        }
        .btn-primary-green { background-color: #00A752; color: white; width: 100%; border: none; border-radius: 5px; }
        .btn-primary-green:hover { background-color: #009146; color: white; }
        .form-group { margin-bottom: 18px; }
        footer { background-color: #00A752; color: white; text-align: center; padding: 20px; }
    </style>
</head>
<body>

<header>
    <img src="{{ asset('adminTheme/assets/img/logo.png') }}" alt="Logo" class="logo">
</header>

<div class="container-fluid main-section">
    <div class="d-flex justify-content-center align-items-center w-100 py-5">
        <div class="card-form">
            <h4 class="text-center mb-1 fw-bold">{{ __('pages.confirm_password_title') }}</h4>
            <p class="text-center text-muted mb-4" style="font-size:0.9rem;">
                {{ __('pages.confirm_password_desc') }}
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="form-group">
                    <label for="password" class="form-label fw-medium">{{ __('global.password') }}</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary-green py-2">
                    {{ __('pages.confirm_btn') }}
                </button>
            </form>
        </div>
    </div>
</div>

<footer>
    <p class="mb-0">© {{ date('Y') }} ML Sourcing. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
