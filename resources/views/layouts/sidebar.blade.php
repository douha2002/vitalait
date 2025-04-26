@extends('layouts.app')

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Animate.css for Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Bootstrap and Custom CSS -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Custom Styles */
        #sidebar {
            width: 250px;
    transform: translateX(-100%);
    transition: transform 0.3s ease-in-out;
    z-index: 1000;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    background: linear-gradient(to bottom, #1e1e2f, #2a2a3f);
    color: white;
    padding: 20px;
    border-right: 1px solid #444;
    border-radius: 0 10px 10px 0;
        }

        #sidebar.active {
            transform: translateX(0);
        }

        #sidebarToggle {
            z-index: 1001;
    position: fixed;
    top: 10px;
    left: 10px;
    background-color: #fff;
    border: none;
    border-radius: 50%;
    padding: 8px 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .main-content {
            margin-left: 0;
    transition: margin-left 0.3s ease-in-out;
    padding: 20px;
        }

        .main-content.sidebar-active {
            margin-left: 250px;
        }

        .dropdown-menu {
    background-color: #2a2a3f;
    border: none;
    border-radius: 10px;
    padding: 10px;
}

        ul.list-unstyled li a,
.dropdown-item {
    transition: all 0.2s ease-in-out;
    border-radius: 8px;
    padding: 8px 10px;
}

ul.list-unstyled li a:hover,
.dropdown-item:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(5px);
}
.dropdown-toggle::after {
    margin-left: auto;
}
    </style>
</head>
<body>
    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <!-- Button to Toggle Sidebar -->
        <button id="sidebarToggle" class="btn btn-white">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        <div id="sidebar" class="bg-dark text-white">
            <h4 class="text-center mb-4">{{ __('') }}</h4>
            <ul class="list-unstyled">
                <li class="mb-3">
                    <a href="{{ route('home') }}" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-home me-2 text-info"></i> Accueil
                    </a>
                </li>
            
                <li class="mb-3">
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" type="button" id="equipementDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span><i class="fas fa-network-wired me-2 text-light"></i> Équipements</span>
                        </button>
                        <ul class="dropdown-menu shadow w-100 mt-2" aria-labelledby="equipementDropdown">
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('equipments.index') }}"><i class="fas fa-desktop me-2 text-primary"></i> Équipements</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('employes.index') }}"><i class="fas fa-users me-2 text-success"></i> Employés</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('fournisseurs.index') }}"><i class="fas fa-truck me-2 text-warning"></i> Fournisseurs</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('contrats.index') }}"><i class="fas fa-file-contract me-2 text-info"></i> Contrats</a></li>
                        </ul>
                    </div>
                </li>
            
                <li class="mb-3">
                    <a href="{{ route('assignments.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-random me-2 text-warning"></i> Affectation
                    </a>
                </li>
            
                <li class="mb-3">
                    <a href="{{ route('maintenances.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-tools me-2 text-danger"></i> Maintenances
                    </a>
                </li>
            
                <li class="mb-3">
                    <a href="{{ route('stock.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-boxes me-2 text-secondary"></i> Stock
                    </a>
                </li>
            
                <li class="mb-3">
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span><i class="fas fa-user-circle me-2 text-light"></i> {{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu shadow w-100 mt-2" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('settings') }}"><i class="fas fa-cog me-2 text-primary"></i> Paramètres</a></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnecter
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            
        </div>
    </div>

    <!-- Script to Handle Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');

            sidebarToggle.addEventListener('click', function(event) {
                // Prevent the click event from propagating to document
                event.stopPropagation();

                // Toggle sidebar visibility
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('sidebar-active');

            });

            // Close sidebar if clicked outside of it
            document.addEventListener('click', function(event) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                    mainContent.classList.remove('sidebar-active');
                    sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>'; // Reset to menu icon
                }
            });
        });
    </script>
</body>
</html>
