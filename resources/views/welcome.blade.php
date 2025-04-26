<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IT Park Accueil</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        .carousel-inner img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            filter: brightness(0.6);
        }

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 10;
        }

        .overlay-content h1 {
            font-size: 3.5rem;
            font-weight: 600;
        }

        .overlay-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .top-right-buttons {
            position: absolute;
            top: 20px;
            right: 30px;
            z-index: 20;
        }

        .btn-custom {
            padding: 8px 20px;
            font-size: 0.95rem;
            border-radius: 20px;
            margin-left: 10px;
            transition: all 0.3s ease;
        }

        .btn-login {
            background: #fff;
            color: #2575fc;
            border: 2px solid #fff;
        }

        .btn-login:hover {
            background: transparent;
            color: #fff;
        }

        .btn-signup {
            background: #2575fc;
            color: #fff;
            border: 2px solid #2575fc;
        }

        .btn-signup:hover {
            background: transparent;
            color: #2575fc;
        }
    </style>
</head>
<body>

    <div class="top-right-buttons">
        <a href="{{ route('login') }}" class="btn btn-custom btn-login">Se connecter</a>
        <a href="{{ route('register') }}" class="btn btn-custom btn-signup">S'inscrire</a>
    </div>

    <div id="vitalaitCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <div class="carousel-item">
                <img src="{{ asset('storage/154704179768_content.jpg') }}" class="d-block w-100" alt="Vitalait logo">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/ali-moez-klebi-vitalait-20-ans-768x431.jpg') }}" class="d-block w-100" alt="Vitalait anniversaire">
            </div>
            <div class="carousel-item active">
                <img src="{{ asset('storage/Gamme-1920x1080-pxl--4-.png') }}" class="d-block w-100" alt="Vitalait produits">
            </div>
            
            <div class="carousel-item">
                <img src="{{ asset('storage/csm_UHT_milk_70c4349c9b-1024x581.png') }}" class="d-block w-100" alt="Vitalait usine">
            </div>
            
        </div>

        <!-- Flèches de navigation -->
        <button class="carousel-control-prev" type="button" data-bs-target="#vitalaitCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#vitalaitCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
