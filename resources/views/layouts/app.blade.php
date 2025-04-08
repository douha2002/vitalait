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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   

<style>
    .search-container {
        width: 100%;
        padding: 10px 0;
        display: flex;
        justify-content: center;
        gap: 10px; /* Space between search and reset button */
    }

    .search-form {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 600px;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .search-form input {
        flex: 1;
        border: none;
        padding: 8px 12px;
        font-size: 1rem;
        border-radius: 4px;
        outline: none;
    }

    .search-form button {
        background: white;
        color: black;
        border: none;
        padding: 8px 15px;
        margin-left: 5px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .search-form button:hover {
        background: white;
    }

    .search-form button i {
        font-size: 1.2rem;
    }

    .reset-filter-btn {
        background: white;
        color: black;
        padding: 10px 15px;
        border-radius: 6px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .reset-filter-btn:hover {
        background: white;
    }

    .reset-filter-btn i {
        font-size: 1.2rem;
    }

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
.chart-container {
    position: relative;
    height: 60vh;
    min-height: 400px;
    width: 100%;
}

.chart-error {
    color: #f44336;
    padding: 20px;
    text-align: center;
}
    </style>
    
    
</head>
<body>

    <div class="content">
        @yield('content')
    </div>
    
   <!-- Your existing HTML content -->

  

</body>
</html>
