@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg rounded-lg border-0">
            <!-- Card Header -->
            <div class="card-header bg-primary text-white text-center py-4 rounded-top">
                <h3 class="fw-bold m-0">{{ __('Réinitialiser le mot de passe') }}</h3>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">
                            <i class="fas fa-envelope me-2 text-primary"></i>Adresse Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" 
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Entrez votre adresse email" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">
                            <i class="fas fa-lock me-2 text-primary"></i>Nouveau mot de passe
                        </label>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Entrez un nouveau mot de passe" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password-confirm" class="form-label fw-semibold">
                            <i class="fas fa-lock me-2 text-primary"></i>Confirmer le mot de passe
                        </label>
                        <input id="password-confirm" type="password" name="password_confirmation"
                            class="form-control" placeholder="Confirmez votre mot de passe" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-3">
                            <i class="fas fa-sync-alt me-2"></i>Réinitialiser le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
