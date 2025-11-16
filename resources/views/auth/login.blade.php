<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: white;
            padding: 20px 0;
            text-align: center;
        }

        .logo {
            max-width: 150px;
        }

        .main-section {
            height: 100vh; /* Ensure it takes full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('https://plus.unsplash.com/premium_photo-1661964050170-b9e54345217d?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8c2hpcHBpbmd8ZW58MHx8MHx8fDA%3D');
            background-size: cover;
            background-position: center;
            position: relative;
            margin: 0;  /* Remove any default margin */
            padding: 0; /* Remove any default padding */
        }

        .main-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #00A75236; /* Black color with 40% opacity */
            z-index: 1;
        }

        .login-form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 2;
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .btn-login {
            background-color: #00A752;
            color: white;
            width: 100%;
            border-radius: 5px;
        }

        .btn-login:hover {
            background-color:rgb(85, 255, 167);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
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
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <img src="{{ asset('adminTheme/assets/img/logo.png') }}" alt="Logo" class="logo">
    </header>

    <!-- Main Section -->
    <div class="container-fluid main-section">
        <div class="row w-100 m-0 justify-content-center align-items-center"> <!-- Added flex utilities -->
            <!-- Left Section (Login Form) -->
            <div class="col-md-6 p-0 d-flex justify-content-center align-items-center"> <!-- Added flex utilities -->
                <div class="login-form">
                    <h2 class="text-center">Login</h2>
                    <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login">Login</button>
                    
                    <!-- Links -->
                    <div class="links">
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                        <br>or<br>
                        <a href="{{ route('register') }}" class="create-account">Create Account</a>
                    </div>
                </form>

                </div>
            </div>

            <!-- Right Section (Image) -->
            <div class="col-md-6 p-0 d-flex justify-content-center align-items-center"> <!-- Added flex utilities -->
                <div class="image-container">
                    <img src="https://i.ibb.co/1ftfy9Rv/1.png" alt="Login Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>© 2025 ML Sourcing. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
