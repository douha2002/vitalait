<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'it_park') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
   


    <style>
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
    
        .form-label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            transition: all 0.3s ease-in-out;
            background-color: ;
            padding: 0 5px;
            color: #6c757d;
        }
    
        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: 0;
            transform: translateY(-50%) scale(0.9);
            color: #007bff;
            font-weight: bold;
        }
    
        .form-control {
            padding-top: 20px;
        }
        .right-button {
        display: flex;
        justify-content: flex-end;
        width: 100%;
    }
        .container {
    margin-top: 0 !important;
    padding-top: 0 !important;

}
    </style>
    
    
</head>
<body>

    <div class="content">
        @yield('content')
    </div>
    
    
</body>
</html>
