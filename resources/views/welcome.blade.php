<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Welcome to IT Park</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .welcome-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .welcome-container h1 {
            font-size: 3.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .welcome-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .btn-custom {
            padding: 10px 30px;
            font-size: 1.1rem;
            border-radius: 25px;
            margin: 10px;
            transition: all 0.3s ease;
        }

        .btn-login {
            background: #fff;
            color: #6a11cb;
            border: 2px solid #fff;
        }

        .btn-login:hover {
            background: transparent;
            color: #fff;
        }

        .btn-signup {
            background: #6a11cb;
            color: #fff;
            border: 2px solid #6a11cb;
        }

        .btn-signup:hover {
            background: transparent;
            color: #6a11cb;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Bienvenue à IT Park</h1>
        <p>Rejoignez-nous et découvrez des fonctionnalités étonnantes conçues spécialement pour vous..</p>
        <div>
            <a href="{{ route('login') }}" class="btn btn-custom btn-login">Se Connecter</a>
            <a href="{{ route('register') }}" class="btn btn-custom btn-signup">S'inscrire</a>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>