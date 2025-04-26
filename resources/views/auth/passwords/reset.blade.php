@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg rounded-lg border-0 p-4 transition-transform hover:scale-105">
            <!-- Card Header -->
            <div class="card-header bg-white border-0 text-center py-4">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-unlock-alt me-2"></i>Réinitialiser le mot de passe
                </h2>
                <p class="text-muted mb-0">Définissez un nouveau mot de passe pour votre compte.</p>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Field -->
                    <div class="mb-4 form-floating">
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Adresse Email" required autofocus>
                        <label for="email">
                            <i class="fas fa-envelope me-2 text-primary"></i>Adresse Email
                        </label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4 form-floating">
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Nouveau mot de passe" required>
                        <label for="password">
                            <i class="fas fa-lock me-2 text-primary"></i>Nouveau mot de passe
                        </label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4 form-floating">
                        <input id="password-confirm" type="password" name="password_confirmation"
                            class="form-control" placeholder="Confirmer le mot de passe" required>
                        <label for="password-confirm">
                            <i class="fas fa-lock me-2 text-primary"></i>Confirmer le mot de passe
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-sync-alt me-2"></i>Réinitialiser le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript pour les floating labels
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
