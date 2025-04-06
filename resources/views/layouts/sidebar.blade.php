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
            z-index: 1000; /* Ensure sidebar is above other content */
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #343a40; /* Dark background for sidebar */
            color: white;
            padding: 20px;
        }

        #sidebar.active {
            transform: translateX(0);
        }

        #sidebarToggle {
            z-index: 1001; /* Ensure button is above the sidebar */
            position: fixed;
            top: 10px;
            left: 10px;
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
            background-color: #343a40; /* Dark background for dropdown */
            border: none;
        }

        .dropdown-item {
            color: #fff; /* White text for dropdown items */
        }

        .dropdown-item:hover {
            background-color: #495057; /* Darker background on hover */
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
                    <a href="{{ route('home') }}" class="text-white text-decoration-none">
                        <i class="fas fa-home me-2"></i> Acceuil
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('equipments.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-network-wired me-2"></i> Equipements
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('assignments.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-users me-2"></i> Affectation
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('maintenances.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-wrench me-2"></i> Maintenances
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('stock.index')}}" class="text-white text-decoration-none">
                        <i class="fas fa-box-archive me-2"></i> Stock
                    </a>
                </li>

                <li class="mb-3">
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle w-100 text-start" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('settings') }}"><i class="fas fa-cog me-2"></i> Paramètres </a></li>
                            <!-- Logout Link -->
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnecter
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
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
