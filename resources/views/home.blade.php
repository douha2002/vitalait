@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
    <!-- Main Content -->
    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <h1 class="display-4">{{ __('Welcome to the Dashboard') }}</h1>
        <p class="lead">{{ __('You are logged in!') }}</p>

        <!-- Add more content here -->
    </div>
@endsection
    
