<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
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
            margin: 0;
            padding: 0;
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

        .verification-form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 2;
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .btn-action {
            background-color: #00A752;
            color: white;
            width: 100%;
            border-radius: 5px;
        }

        .btn-action:hover {
            background-color: rgb(85, 255, 167);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
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
            <!-- Verification Form -->
            <div class="col-md-6 p-0 d-flex justify-content-center align-items-center">
                <div class="verification-form">
                    <h2 class="text-center">Email Verification</h2>

                    <div class="mb-4 text-center">
                        <p>Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success" role="alert">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                    @endif

                    <div class="mt-4 text-center">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div>
                                <button type="submit" class="btn btn-action">Resend Verification Email</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-link text-sm text-gray-600 hover:text-gray-900">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Section (Image) -->
            <div class="col-md-6 p-0 d-flex justify-content-center align-items-center">
                <div class="image-container">
                    <img src="https://i.ibb.co/1ftfy9Rv/1.png"alt="Verification Image" style="width: 100%; height: auto; border-radius: 10px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© <?php echo date('Y'); ?> ML Sourcing. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>