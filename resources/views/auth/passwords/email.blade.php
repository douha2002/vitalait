@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg rounded-lg border-0 p-4 transition-transform hover:scale-105">
            <!-- En-tête de la carte -->
            <div class="card-header bg-white border-0 text-center py-4">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-unlock-alt me-2"></i>Réinitialiser le mot de passe
                </h2>
                <p class="text-muted mb-0">Entrez votre adresse email pour recevoir le lien de réinitialisation.</p>
            </div>

            <!-- Corps de la carte -->
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Champ Email -->
                    <div class="mb-4 form-floating">
                        <input id="email" type="email" name="email" required
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Adresse Email">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2 text-primary"></i>Adresse Email
                        </label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bouton Envoyer -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer le lien
                        </button>
                    </div>

                    <!-- Lien retour connexion -->
                    <p class="text-center text-muted mb-0">
                        <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la connexion
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript pour les labels flottants
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
