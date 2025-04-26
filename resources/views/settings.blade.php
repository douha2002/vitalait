@extends('layouts.app')

@section('content')



<!-- Navbar for Notifications (Top Right) -->
<nav class="navbar navbar-expand-lg navbar-light bg-light position-absolute top-0 end-0 m-3">
    <ul class="navbar-nav">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i> 
                @if(auth()->user()->unreadNotifications->count())
                    <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                @forelse(auth()->user()->unreadNotifications as $notification)
                    @php $notifUserId = $notification->data['user_id']; @endphp
                    @if($notifUserId != auth()->id()) <!-- 💡 Empêche l'auto-validation -->
                        <li class="dropdown-item d-flex justify-content-between align-items-center">
                            <span>{{ $notification->data['message'] }}</span>
                            <div class="ms-2 d-flex gap-1">
                                <!-- Approve Form -->
                                <form action="{{ route('settings.approve', ['id' => $notifUserId]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm" title="Approuver">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>

                                <!-- Reject Form -->
                                <form action="{{ route('settings.reject', ['id' => $notifUserId]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Rejeter">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endif
                @empty
                    <li class="dropdown-item text-muted text-center">
                        {{ __('Aucune notification') }}
                    </li>
                @endforelse
            </ul>
        </li>
    </ul>
</nav>


<!-- Main Content -->
<div class="container py-2 mt-1">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4 fw-bold text-primary">
            <i class="bi bi-gear-fill"></i> {{ __('Paramètres') }}
        </h1>
    </div>

    
<!-- Mon Compte Card -->
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-10"> <!-- Adjust width -->

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="bi bi-person-circle"></i> {{ __('Mon Compte') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.updateAccount') }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control border-primary" value="{{ Auth::user()->name }}" placeholder="Nom" required>
                        <label for="name" class="fw-semibold"><i class="bi bi-person"></i> {{ __('Nom') }}</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control border-primary" value="{{ Auth::user()->email }}" placeholder="Email" required>
                        <label for="email" class="fw-semibold"><i class="bi bi-envelope"></i> {{ __('Email') }}</label>
                    </div>
                    <!-- Password Update Section -->
                    <div class="form-floating mb-3">
                        <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
                        <label for="current_password" class="fw-semibold"><i class="bi bi-lock"></i> {{ __('Mot de passe actuel') }}</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="new_password" class="form-control " placeholder="New Password" required>
                        <label for="new_password" class="fw-semibold"><i class="bi bi-lock"></i> {{ __('Nouveau mot de passe') }}</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm New Password" required>
                        <label for="new_password_confirmation" class="fw-semibold"><i class="bi bi-lock"></i> {{ __('Confirmer le nouveau mot de passe') }}</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> {{ __('Mettre à jour mon compte') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- Gérer les Utilisateurs Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-people"></i> {{ __('Gérer les Utilisateurs') }}</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th><i class="bi bi-person"></i> {{ __('Nom') }}</th>
                        <th><i class="bi bi-envelope"></i> {{ __('Email') }}</th>
                        <th><i class="bi bi-shield-lock"></i> {{ __('Statut') }}</th>
                        <th class="text-end"><i class="bi bi-tools"></i> {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">
                                @if ($user->status === 'En attente')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> {{ __('En attente') }}</span>
                                @elseif ($user->status === 'Approuvé')
                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i> {{ __('Approuvé') }}</span>
                                @elseif ($user->status === 'Rejeté')
                                    <span class="badge bg-danger"><i class="bi bi-x-lg"></i> {{ __('Rejeté') }}</span>
                                @endif
                            </td>
                            <td class="text-end align-middle">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                    <i class="bi bi-pencil"></i> 
                                </button>
                                <form action="{{ route('settings.delete', $user) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cet utilisateur ?') }}')" title="{{ __('Supprimer') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                                  <!-- Edit User Modal -->
                                  <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">{{ __("Modifier L'utilisateur") }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('settings.update', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                            
                                                    <!-- Name Input -->
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label fw-bold">{{ __('') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Email Input -->
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label fw-bold">{{ __('') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Buttons -->
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="bi bi-arrow-left"></i> {{ __('Annuler') }}
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-save"></i> {{ __('Modifier') }}
                                                        </button>
                                                    </div>
                                                </form>      
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach                
                </tbody>
            </table>
        </div>
    </div>
</div>


@if(session('success'))
    <div class="alert alert-success" id="success-message" style="
        position: fixed;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        width: 50%;
        text-align: center;
        padding: 10px;
        border-radius: 5px;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    ">
        {{ session('success') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = "opacity 0.5s";
                    successMessage.style.opacity = "0";
                    setTimeout(() => successMessage.remove(), 500);
                }, 3000); 
            }
        });
    </script>

@endif
@include('layouts.sidebar')

@endsection