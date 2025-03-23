@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <!-- Search Section -->
    <div class="d-flex justify-content-center align-items-center mb-4">
        <div class="search-container w-50">
            <form method="GET" action="{{ route('search') }}" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control shadow-sm" placeholder="Rechercher par Article, Quantité, etc..">
                    <button type="submit" class="btn btn-outline-secondary shadow-sm"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <!-- Reset Filter Button -->
        <a href="{{ route('equipments.index') }}" class="btn btn-outline-danger shadow-sm" title="Réinitialiser la recherche">
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
                data-bs-target="#addEquipmentModal"
                title="Ajouter un nouvel équipement">
            <i class="fas fa-plus me-2"></i> Ajouter Équipement
        </button>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-content shadow-lg">
 <!-- Modal Header -->
 <div class="modal-header bg-primary text-white">
    <h5 class="modal-title" id="importModalLabel">
        <i class="fas fa-file-csv me-2"></i> <!-- Add an icon for better visual appeal -->
        Importer un fichier CSV
    </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
                    <form action="{{ route('equipments.import') }}" method="POST" enctype="multipart/form-data">
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
    </div>

    <!-- Add Equipment Modal -->
    <div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="addEquipmentModalLabel">
                        <i class="fas fa-box me-2"></i> Ajouter un équipement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('equipments.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @foreach (['numero_de_serie' => 'Numéro de Série', 'article' => 'Article', 'date_acquisition' => 'Date d\'Acquisition', 'date_de_mise_en_oeuvre' => 'Date de Mise en Oeuvre', 'categorie' => 'Catégorie', 'sous_categorie' => 'Sous Catégorie', 'matricule' => 'Matricule'] as $field => $label)
                                <div class="col-md-6 mb-3">
                                    <label for="{{ $field }}" class="fw-semibold">{{ $label }}</label>
                                    <input type="{{ $field === 'date_acquisition' || $field === 'date_de_mise_en_oeuvre' ? 'date' : 'text' }}" 
                                           name="{{ $field }}" 
                                           id="{{ $field }}" 
                                           class="form-control rounded-3 shadow-sm @error($field) is-invalid @enderror" 
                                           value="{{ old($field) }}" 
                                           required>
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
        

    <!-- Equipment Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">Numéro de Série</th>
                        <th class="text-center">Article</th>
                        <th class="text-center">Date d'Acquisition</th>
                        <th class="text-center">Date de Mise en Oeuvre</th>
                        <th class="text-center">Catégorie</th>
                        <th class="text-center">Sous Catégorie</th>
                        <th class="text-center">Matricule</th>
                        <th class="text-center"><i class="bi bi-shield-lock"></i> Statut</th>
                        <th class="text-center"><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipments as $equipment)
                        <tr>
                            <td class="text-center">{{ $equipment->numero_de_serie }}</td>
                            <td class="text-center">{{ $equipment->article ?: '-' }}</td>
                            <td class="text-center">{{ $equipment->date_acquisition ? \Carbon\Carbon::parse($equipment->date_acquisition)->format('d-m-Y') : '-' }}</td>
                            <td class="text-center">{{ $equipment->date_de_mise_en_oeuvre ? \Carbon\Carbon::parse($equipment->date_de_mise_en_oeuvre)->format('d-m-Y') : '-' }}</td>
                            <td class="text-center">{{ $equipment->categorie ?: '-' }}</td>
                            <td class="text-center">{{ $equipment->sous_categorie ?: '-' }}</td>
                            <td class="text-center">{{ $equipment->matricule ?: '-' }}</td>
                            <td class="text-center">
                                @if ($equipment->statut === 'Disponible')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-thumbs-up"></i> {{ __('Disponible') }}</span>
                                @elseif ($equipment->statut === 'Affecté')
                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i> {{ __('Affecté') }}</span>
                                @elseif ($equipment->statut === 'En panne')
                                    <span class="badge bg-danger"><i class="bi bi-exclamation-circle"></i> {{ __('En panne') }}</span>
                                @endif
                            </td>                            
                            <td class="d-flex justify-content-center justify-content-between ">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editEquipmentModal{{ $equipment->numero_de_serie }}">
    <i class="fas fa-edit"></i>
</button>

<!-- Edit Equipment Modal -->
<div class="modal fade" id="editEquipmentModal{{ $equipment->numero_de_serie }}" tabindex="-1" aria-labelledby="editEquipmentModalLabel{{ $equipment->numero_de_serie }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="modal-title fw-bold" id="editEquipmentModalLabel{{ $equipment->numero_de_serie }}">
                    <i class="fas fa-box me-2"></i> Modifier un équipement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form action="{{ route('equipments.update', $equipment->numero_de_serie) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row">
                        @foreach (['numero_de_serie' => 'Numéro de Série', 'article' => 'Article', 'date_acquisition' => 'Date d\'Acquisition', 'date_de_mise_en_oeuvre' => 'Date de Mise en Oeuvre', 'categorie' => 'Catégorie', 'sous_categorie' => 'Sous Catégorie', 'matricule' => 'Matricule'] as $field => $label)
                            <div class="col-md-6 mb-3">
                                <label for="{{ $field }}" class="fw-semibold">{{ $label }}</label>
                                <input type="{{ in_array($field, ['date_acquisition', 'date_de_mise_en_oeuvre']) ? 'date' : 'text' }}" 
                                       name="{{ $field }}" 
                                       id="{{ $field.$equipment->numero_de_serie }}" 
                                       class="form-control rounded-3 shadow-sm @error($field) is-invalid @enderror" 
                                       value="{{ old($field, $equipment->$field) }}" 
                                       required>
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
            </form> <!-- Form End -->
        </div>
    </div>
</div>

                                <!-- Delete Button -->
                                <form action="{{ route('equipments.destroy', $equipment->numero_de_serie) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center">Aucun équipement trouvé.</td></tr>
                    @endforelse
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

{{-- Error Messages --}}
@if($errors->any())
    <div class="alert alert-danger" id="error-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 50%; text-align: center; padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@include('layouts.sidebar')

@endsection