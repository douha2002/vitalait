@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Section de recherche -->
    <div class="d-flex justify-content-center align-items-center mb-4">
        <div class="search-container w-50">
            <form method="GET" action="{{ route('fournisseurs.search') }}" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control shadow-sm" placeholder="Rechercher par nom, email, etc.">
                    <button type="submit" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <!-- Bouton de réinitialisation -->
        <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-danger shadow-sm" title="Réinitialiser la recherche">
            <i class="fas fa-sync-alt"></i>
        </a>
    </div>

    <!-- Boutons d'action -->
    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-outline-success me-2 shadow-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#importModal"
                title="Importer un fichier CSV">
            <i class="fas fa-upload me-2"></i>Importer CSV
        </button>

        <button class="btn btn-outline-primary shadow-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#addFournisseurModal"
                title="Ajouter un nouveau fournisseur">
            <i class="fas fa-plus me-2"></i> Ajouter Fournisseur
        </button>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="fas fa-file-csv me-2"></i> Importer un fichier CSV
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('fournisseurs.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file">Choisir un fichier CSV</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Fournisseur -->
    <div class="modal fade" id="addFournisseurModal" tabindex="-1" aria-labelledby="addFournisseurModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="addFournisseurModalLabel">
                        <i class="fas fa-user-plus me-2"></i> Ajouter un Fournisseur
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('fournisseurs.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @foreach (['nom' => 'Nom', 'email' => 'Email','numero_de_telephone' => 'Numéro de téléphone'] as $field => $label)
                                <div class="col-md-6 mb-3">
                                    <label for="{{ $field }}" class="fw-semibold">{{ $label }}</label>
                                    <input type="text" name="{{ $field }}" id="{{ $field }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field) }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between bg-light rounded-bottom">
                        <button type="button" class="btn btn-outline-secondary px-4 rounded-3 shadow-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                            <i class="fas fa-save me-2"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Fournisseurs -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="fournisseursTable" class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center"><i class="fas fa-truck"></i> Nom</th>
                        <th class="text-center"><i class="fas fa-envelope"></i> Email</th>  
                        <th class="text-center"><i class="fas fa-phone"></i> Numéro de téléphone</th>     
                        <th class="text-center"><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($fournisseurs as $fournisseur)
                            <tr>
                                <td class="text-center">{{ $fournisseur->nom }}</td>
                                <td class="text-center">{{ $fournisseur->email }}</td>
                                <td class="text-center">{{ $fournisseur->numero_de_telephone }}</td>
                                <td class="text-center"> 
                                    <div class="d-flex justify-content-center gap-2">                                    <!-- Bouton Modifier -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFournisseurModal{{ $fournisseur->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Modal Modifier -->
                                    <div class="modal fade" id="editFournisseurModal{{ $fournisseur->id }}" tabindex="-1" aria-labelledby="editFournisseurModalLabel{{ $fournisseur->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                                                <div class="modal-header bg-primary text-white rounded-top">
                                                    <h5 class="modal-title fw-bold" id="editFournisseurModalLabel{{ $fournisseur->id }}">
                                                        <i class="fas fa-user-edit me-2"></i> Modifier le Fournisseur
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('fournisseurs.update', $fournisseur->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            @foreach (['nom' => 'Nom', 'email' => 'Email','numero_de_telephone'=>'Numéro de téléphone'] as $field => $label)
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="{{ $field }}" class="fw-semibold">{{ $label }}</label>
                                                                    <input type="text" name="{{ $field }}" id="{{ $field }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field, $fournisseur->$field) }}">
                                                                    @error($field)
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer d-flex justify-content-between bg-light rounded-bottom">
                                                        <button type="button" class="btn btn-outline-secondary px-4 rounded-3 shadow-sm" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i> Annuler
                                                        </button>
                                                        <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                                                            <i class="fas fa-edit me-2"></i> Modifier
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Supprimer -->
                                    <form action="{{ route('fournisseurs.destroy', $fournisseur->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet fournisseur ?')">
                                            <i class="fas fa-trash me-2"></i> 
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
   {{-- Success Message --}}
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
    document.addEventListener('DOMContentLoaded', function () {
        // Fade out success message after error message disappears
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.transition = "opacity 0.5s";
                errorMessage.style.opacity = "0";
                setTimeout(() => {
                    errorMessage.remove();
                    if (successMessage) {
                        successMessage.style.transition = "opacity 0.5s";
                        successMessage.style.opacity = "1";
                        setTimeout(() => successMessage.remove(), 5000); // Remove after 5 seconds
                    }
                }, 500);
            }, 3000); // Wait for 3 seconds before hiding the error message
        }
        else {
            // If no error message, show success directly
            if (successMessage) {
                setTimeout(() => successMessage.remove(), 5000); // Remove success message after 5 seconds
            }
        }
    });
</script>
@endif

{{-- Flash Error Message --}}
@if(session('error'))
<div class="alert alert-danger" id="error-message" style="
    position: fixed;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    width: 50%;
    text-align: center;
    padding: 10px;
    border-radius: 5px;
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
">
    {{ session('error') }}
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fade out success message after error message disappears
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.transition = "opacity 0.5s";
                errorMessage.style.opacity = "0";
                setTimeout(() => {
                    errorMessage.remove();
                    if (successMessage) {
                        successMessage.style.transition = "opacity 0.5s";
                        successMessage.style.opacity = "1";
                        setTimeout(() => successMessage.remove(), 5000); // Remove after 5 seconds
                    }
                }, 500);
            }, 3000); // Wait for 3 seconds before hiding the error message
        }
        else {
            // If no error message, show success directly
            if (successMessage) {
                setTimeout(() => successMessage.remove(), 5000); // Remove success message after 5 seconds
            }
        }
    });
</script>

@endif
<script>
    $(document).ready(function () {
        $('#fournisseursTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
            },
            paging: true,
            searching: false,
            info: true
        });
    });
</script>
@include('layouts.sidebar')
@endsection
