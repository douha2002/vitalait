@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg rounded-lg border-0 p-4 transition-transform hover:scale-105">
            <!-- Card Header -->
            <div class="card-header bg-white border-0 text-center py-4">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-user-plus me-2"></i>Inscription
                </h2>
                <p class="text-muted mb-0">Veuillez vous inscrire pour créer un compte.</p>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-4 form-floating">
                        <input id="name" type="text" name="name" required value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nom">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2 text-primary"></i>Nom
                        </label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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

                    <!-- Confirm Password Field -->
                    <div class="mb-4 form-floating">
                        <input id="password-confirm" type="password" name="password_confirmation" required
                            class="form-control" placeholder="Confirmez le mot de passe">
                        <label for="password-confirm" class="form-label">
                            <i class="fas fa-lock me-2 text-primary"></i>Confirmez le mot de passe
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-user-check me-2"></i>S'inscrire
                        </button>
                    </div>

                    <!-- Login Link -->
                    <p class="text-center text-muted mb-0">
                        Vous avez déjà un compte ? 
                        <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                            <i class="fas fa-sign-in-alt me-2"></i>Se Connecter
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