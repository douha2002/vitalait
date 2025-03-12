@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg rounded-lg border-0 p-4 transition-transform hover:scale-105">
            <!-- Card Header -->
            <div class="card-header bg-white border-0 text-center py-4">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-user-circle me-2"></i>Connexion
                </h2>
                <p class="text-muted mb-0">Veuillez vous connecter pour accéder à votre compte.</p>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-4 form-floating">
                        <input id="email" type="email" name="email" required value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Adresse Email">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2 text-primary"></i>Adresse Email
                        </label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4 form-floating">
                        <input id="password" type="password" name="password" required
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Mot De Passe">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2 text-primary"></i>Mot De Passe
                        </label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted" for="remember">
                                <i class="me-2"></i>Se souvenir de moi
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                                <i class="fas fa-key me-2"></i>Mot De Passe Oublié?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-sign-in-alt me-2"></i>Se Connecter
                        </button>
                    </div>

                    <!-- Register Link -->
                    <p class="text-center text-muted mb-0">
                        Je n'ai pas de compte? 
                        <a href="{{ route('register') }}" class="text-primary text-decoration-none">
                            <i class="fas fa-user-plus me-2"></i>S'inscrire
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    // JavaScript for Floating Labels
    document.querySelectorAll('.form-floating input').forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', () => {
            if (!input.value) {
                input.parentElement.classList.remove('focused');
            }
        });
    });
</script>
@endsection