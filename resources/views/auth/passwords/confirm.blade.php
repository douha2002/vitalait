@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg rounded-lg border-0 p-4 transition-transform hover:scale-105">
            <!-- Card Header -->
            <div class="card-header bg-white border-0 text-center py-4">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
                </h2>
                <p class="text-muted mb-0">Veuillez confirmer votre mot de passe avant de continuer.</p>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password Field -->
                    <div class="mb-4 form-floating">
                        <input id="password" type="password" name="password" required
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Mot de passe actuel" autocomplete="current-password">
                        <label for="password">
                            <i class="fas fa-lock me-2 text-primary"></i>Mot de passe
                        </label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-check-circle me-2"></i>Confirmer le mot de passe
                        </button>
                    </div>

                    <!-- Forgot Password -->
                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="text-primary text-decoration-none" href="{{ route('password.request') }}">
                                <i class="fas fa-key me-2"></i>Mot de passe oublié ?
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript pour floating labels
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
