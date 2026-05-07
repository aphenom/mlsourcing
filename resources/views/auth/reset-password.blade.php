<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('global.reset_password_title') }} - ML Sourcing</title>
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
        .links { text-align: center; margin-top: 15px; }
        .links a { color: #00A752; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
        footer { background-color: #00A752; color: white; text-align: center; padding: 20px; }
        .password-toggle { position: relative; }
        .password-toggle .toggle-btn {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: #666; padding: 0;
        }
    </style>
</head>
<body>

<header>
    <img src="{{ asset('adminTheme/assets/img/logo.png') }}" alt="Logo" class="logo">
</header>

<div class="container-fluid main-section">
    <div class="d-flex justify-content-center align-items-center w-100 py-5">
        <div class="card-form">
            <h4 class="text-center mb-1 fw-bold">{{ __('global.reset_password_title') }}</h4>
            <p class="text-center text-muted mb-4" style="font-size:0.9rem;">
                {{ __('global.reset_password_desc') }}
            </p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- Token --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label fw-medium">{{ __('global.email_address') }}</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- New password --}}
                <div class="form-group">
                    <label for="password" class="form-label fw-medium">{{ __('global.new_password_label') }}</label>
                    <div class="password-toggle">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                            minlength="8">
                        <button type="button" class="toggle-btn" onclick="toggleVisibility('password', this)" tabindex="-1">
                            <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm password --}}
                <div class="form-group">
                    <label for="password_confirmation" class="form-label fw-medium">{{ __('global.confirm_password_label') }}</label>
                    <div class="password-toggle">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            required>
                        <button type="button" class="toggle-btn" onclick="toggleVisibility('password_confirmation', this)" tabindex="-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-green py-2">
                    {{ __('global.reset_password_btn') }}
                </button>

                <div class="links mt-3">
                    <a href="{{ route('login') }}">← {{ __('global.back_to_login') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>

<footer>
    <p class="mb-0">© {{ date('Y') }} ML Sourcing. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
function toggleVisibility(fieldId, btn) {
    var input = document.getElementById(fieldId);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.style.opacity = input.type === 'text' ? '0.5' : '1';
}
</script>
</body>
</html>
