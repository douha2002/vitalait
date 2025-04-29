@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Search Section -->
<div class="d-flex justify-content-center align-items-center mb-4">
    <div class="search-container w-50">
        <form method="GET" action="{{ route('contrats.search') }}" class="search-form">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control shadow-sm" placeholder="Rechercher par equipement,fournisseur, etc..">
                <button type="submit" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    <!-- Reset Filter Button -->
    <a href="{{ route('contrats.index') }}" class="btn btn-outline-danger shadow-sm" title="Réinitialiser la recherche">
        <i class="fas fa-sync-alt"></i> 
    </a>
</div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-outline-success me-2 shadow-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#importModal"
                title="Importer un fichier CSV">
            <i class="fas fa-upload me-2"></i>Importer CSV
        </button>

        <button class="btn btn-outline-primary shadow-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#addContratModal"
                title="Ajouter un nouvel contrat">
            <i class="fas fa-plus me-2"></i> Ajouter un Contrat
        </button>
    </div>
 <!-- Import Modal -->
 <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-content shadow-lg">
<!-- Modal Header -->
<div class="modal-header bg-success text-white">
<h5 class="modal-title" id="importModalLabel">
    <i class="fas fa-file-csv me-2"></i> 
    Importer un fichier CSV
</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
                <form action="{{ route('contrats.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file">Choisir un fichier CSV</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-2"></i>Importer

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
    <!-- Add Employé Modal -->
    <div class="modal fade" id="addContratModal" tabindex="-1" aria-labelledby="addContratModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="addContratModalLabel">
                        <i class="fas fa-file-contract me-2"></i> Ajouter un Contrat
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('contrats.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="equipement" class="fw-semibold">Équipement :</label>
                            <select name="numero_de_serie" id="numero_de_serie" class="form-control">
                                <option value="" selected disabled>-- Sélectionner un équipement --</option>
                                @foreach($equipements as $equipment)
                                    <option value="{{ $equipment->numero_de_serie }}">{{ $equipment->numero_de_serie }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="contrat" class="fw-semibold">Fournisseur :</label>
                            <select name="fournisseur_id" id="fournisseur_id" class="form-control">
                                <option value="" selected disabled>-- Sélectionner un fournisseur --</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date_debut" class="fw-semibold">Date de début :</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control rounded-3 shadow-sm" value="{{ old('date_debut') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="date_fin" class="fw-semibold">Date de fin :</label>
                            <input type="date" name="date_fin" id="date_fin" class="form-control rounded-3 shadow-sm" value="{{ old('date_fin') }}" required>
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

    <!-- Employee Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="contratsTable" class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">
                            <i class="fas fa-laptop"></i> Équipement
                        </th>
                        <th  class="text-center">
                            <i class="fas fa-truck"></i> Fournisseur
                        </th>
                        <th  class="text-center">
                            <i class="fas fa-calendar-alt"></i> Date Début
                        </th>
                        <th  class="text-center">
                            <i class="fas fa-calendar-check"></i> Date Fin
                        </th>        
                    <th class="text-center"><i class="fas fa-cogs"></i> Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contrats as $contrat)
                    <tr>
                        <td class="text-center">{{ $contrat->numero_de_serie }}</td>
                        <td class="text-center">{{ $contrat->fournisseur->nom }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($contrat->date_debut)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($contrat->date_fin)->format('d-m-Y') }}</td>
                        <td class="text-center"> 
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editContratModal{{ $contrat->id }}">
                               <i class="fas fa-edit"></i>
                            </button>
                            <!-- Edit Contrat Modal -->
                            <div class="modal fade" id="editContratModal{{ $contrat->id }}" tabindex="-1" aria-labelledby="editContratModalLabel{{ $contrat->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                                        <div class="modal-header bg-primary text-white rounded-top">
                                            <h5 class="modal-title fw-bold" id="editContratModalLabel{{ $contrat->id }}">
                                                <i class="fas fa-file-contract me-2"></i> Modifier un Contrat
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('contrats.update', $contrat->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="equipement" class="fw-semibold">Équipement :</label>
                                                    <select name="numero_de_serie" id="numero_de_serie" class="form-control rounded-3 shadow-sm" required>
                                                        <option value="" selected disabled>-- Sélectionner un équipement --</option>
                                                        @foreach($equipements as $equipment)
                                                            <option value="{{ $equipment->numero_de_serie }}" 
                                                                {{ $contrat->numero_de_serie == $equipment->numero_de_serie ? 'selected' : '' }}>
                                                                {{ $equipment->numero_de_serie }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                  
                                                </div>
                                                    

                                                <div class="mb-3">
                                                    <label for="contrat" class="fw-semibold">Fournisseur :</label>
                                                    <select name="fournisseur_id" id="fournisseur_id" class="form-control rounded-3 shadow-sm" required>
                                                        <option value="" selected disabled>-- Sélectionner un fournisseur --</option>
                                                        @foreach($fournisseurs as $fournisseur)
                                                            <option value="{{ $fournisseur->id }}" 
                                                                {{ $fournisseur->id == old('fournisseur_id', $contrat->fournisseur_id) ? 'selected' : '' }}>
                                                                {{ $fournisseur->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    
                                                </div>

                                                <div class="mb-3">
                                                    <label for="date_debut" class="fw-semibold">Date de début :</label>
                                                    <input type="date" name="date_debut" id="date_debut" class="form-control rounded-3 shadow-sm" value="{{ old('date_debut', $contrat->date_debut ?? '') }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="date_fin" class="fw-semibold">Date de fin :</label>
                                                    <input type="date" name="date_fin" id="date_fin" class="form-control rounded-3 shadow-sm" value="{{ old('date_fin', $contrat->date_fin ?? '') }}">
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

                            <!-- Delete Employee Form -->

                            <form action="{{ route('contrats.destroy', $contrat->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet contrat ?')">
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
                            setTimeout(() => successMessage.remove(), 3000); // Remove after 5 seconds
                        }
                    }, 500);
                }, 3000); // Wait for 3 seconds before hiding the error message
            }
            else {
                // If no error message, show success directly
                if (successMessage) {
                    setTimeout(() => successMessage.remove(), 3000); // Remove success message after 5 seconds
                }
            }
        });
    </script>
@endif
@if(session('importErrors'))
    <div class="alert alert-danger" id="error-import" style="
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
        <ul>
            @foreach(session('importErrors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fade out success message after error message disappears
            const errorMessage = document.getElementById('error-import');
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
                            setTimeout(() => successMessage.remove(), 3000); // Remove after 5 seconds
                        }
                    }, 500);
                }, 3000); // Wait for 3 seconds before hiding the error message
            }
            else {
                // If no error message, show success directly
                if (successMessage) {
                    setTimeout(() => successMessage.remove(), 3000); // Remove success message after 5 seconds
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
                            setTimeout(() => successMessage.remove(), 3000); // Remove after 5 seconds
                        }
                    }, 500);
                }, 3000); // Wait for 3 seconds before hiding the error message
            }
            else {
                // If no error message, show success directly
                if (successMessage) {
                    setTimeout(() => successMessage.remove(), 3000); // Remove success message after 5 seconds
                }
            }
        });
    </script>

@endif
<script>
    $(document).ready(function () {
        $('#contratsTable').DataTable({
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
