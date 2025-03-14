@extends('layouts.app')

@section('content') 


<div class="container">
    <div class="search-container">
        <form method="GET" action="{{ route('search') }}" class="search-form">
            <input type="text" name="search" id="search" placeholder="Rechercher par Article,Quantité etc.." >
            <button type="submit"><i class="fas fa-search"></i></button>
       
        
        <!-- Reset Filter Button -->
        <a href="{{ route('equipments.index') }}" class="reset-filter-btn" title="Réinitialiser la recherche">
            <i class="fas fa-sync-alt"></i> 
        </a>
    </form>
    </div>
    
<div class="d-flex justify-content-end mb-3">

    <!-- Importer button with Font Awesome Icon -->
<button class="btn btn-white" 
data-bs-toggle="modal" 
data-bs-target="#importModal"
data-bs-toggle="tooltip" 
data-bs-placement="top" 
title="Importer un fichier CSV">
<i class="fas fa-upload me-2"></i> 
</button>
<!-- In your Blade view (equipments.index.blade.php) -->


<!-- Import Form in Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Importer un fichier CSV</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('equipments.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label>Choisir un fichier CSV</label>
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

<a href="" 
class="btn btn-white me-2" 
data-bs-toggle="modal" 
data-bs-target="#addEquipmentModal"
data-bs-toggle="tooltip" 
data-bs-placement="top" 
title="Ajouter un nouvel équipement">
<i class="fas fa-plus me-2"></i> 
</a>

    <!-- Modal Structure -->
    <div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addEquipmentModalLabel">
                        <i class="fas fa-box me-2"></i>Ajouter un équipement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('equipments.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="row">

                            <!-- Numéro de Série -->
                            <div class="col-md-6 mb-3">
                                <label for="numero_de_serie">Numéro de Série</label>
                                <input type="text" name="numero_de_serie" id="numero_de_serie" class="form-control @error('numero_de_serie') is-invalid @enderror" value="{{ old('numero_de_serie') }}" required>
                                @error('numero_de_serie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Article -->
                            <div class="col-md-6 mb-3">
                                <label for="article">Article</label>
                                <input type="text" name="article" id="article" class="form-control @error('article') is-invalid @enderror" value="{{ old('article') }}">
                                @error('article')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Quantité -->
                            <div class="col-md-6 mb-3">
                                <label for="quantite">Quantité</label>
                                <input type="number" name="quantite" id="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite') }}">
                                @error('quantite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date d'Acquisition -->
                            <div class="col-md-6 mb-3">
                                <label for="date_acquisition">Date d'Acquisition</label>
                                <input type="date" name="date_acquisition" id="date_acquisition" class="form-control @error('date_acquisition') is-invalid @enderror" value="{{ old('date_acquisition') }}">
                                @error('date_acquisition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de Mise en Oeuvre -->
                            <div class="col-md-6 mb-3">
                                <label for="date_de_mise_en_oeuvre">Date de Mise en Oeuvre</label>
                                <input type="date" name="date_de_mise_en_oeuvre" id="date_de_mise_en_oeuvre" class="form-control @error('date_de_mise_en_oeuvre') is-invalid @enderror" value="{{ old('date_de_mise_en_oeuvre') }}">
                                @error('date_de_mise_en_oeuvre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catégorie -->
                            <div class="col-md-6 mb-3">
                                <label for="categorie">Catégorie</label>
                                <input type="text" name="categorie" id="categorie" class="form-control @error('categorie') is-invalid @enderror" value="{{ old('categorie') }}">
                                @error('categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sous Catégorie -->
                            <div class="col-md-6 mb-3">
                                <label for="sous_categorie">Sous Catégorie</label>
                                <input type="text" name="sous_categorie" id="sous_categorie" class="form-control @error('sous_categorie') is-invalid @enderror" value="{{ old('sous_categorie') }}">
                                @error('sous_categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Matricule -->
                            <div class="col-md-6 mb-3">
                                <label for="matricule">Matricule</label>
                                <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}">
                                @error('matricule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Ajouter</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

   

    <!-- Equipment Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Numéro de Série</th>
                <th>Article</th>
                <th>Quantité</th>
                <th>Date d'Acquisition</th>
                <th>Date de Mise en Oeuvre</th>
                <th>Catégorie</th>
                <th>Sous Catégorie</th>
                <th>Matricule</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($equipments->isEmpty())
            <tr>
                <td colspan="9" class="text-center">Aucun équipement trouvé correspondant à votre recherche.</td>
            </tr>
            @else

            @foreach($equipments as $equipment)
                <tr>
                    <td>{{ $equipment->numero_de_serie }}</td>
                    <td>{{ $equipment->article ?? '-' }}</td>
                    <td>{{ $equipment->quantite ?? '-' }}</td>
                    <td>{{ $equipment->date_acquisition ?? '-' }}</td>
                    <td>{{ $equipment->date_de_mise_en_oeuvre ?? '-' }}</td>
                    <td>{{ $equipment->categorie ?? '-' }}</td>
                    <td>{{ $equipment->sous_categorie ?? '-' }}</td>
                    <td>{{ $equipment->matricule ?? '-' }}</td>
                    
                    <td>
                        <!-- Modifier button with icon -->
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editEquipmentModal{{ $equipment->numero_de_serie }}">
                            <i class="fas fa-edit"></i>
                        </button>
                    
                        <!-- Modal Structure -->
                        <div class="modal fade" id="editEquipmentModal{{ $equipment->numero_de_serie }}" tabindex="-1" aria-labelledby="editEquipmentModalLabel{{ $equipment->numero_de_serie }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="editEquipmentModalLabel">
                                            <i class="fas fa-box me-2"></i>Modifier un équipement
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('equipments.update', $equipment->numero_de_serie) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <!-- Numéro de Série -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="numero_de_serie">Numéro de Série</label>
                                                    <input type="text" name="numero_de_serie" id="numero_de_serie" class="form-control @error('numero_de_serie') is-invalid @enderror" value="{{ old('numero_de_serie', $equipment->numero_de_serie) }}" required>
                                                    @error('numero_de_serie')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                    
                                                <!-- Article -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="article">Article</label>
                                                    <input type="text" name="article" id="article" class="form-control @error('article') is-invalid @enderror" value="{{ old('article', $equipment->article) }}">
                                                    @error('article')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                    
                                                <!-- Quantité -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="quantite">Quantité</label>
                                                    <input type="number" name="quantite" id="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite', $equipment->quantite) }}">
                                                    @error('quantite')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                    
                                                <!-- Date d'Acquisition -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="date_acquisition">Date d'Acquisition</label>
                                                    <input type="date" name="date_acquisition" id="date_acquisition" class="form-control @error('date_acquisition') is-invalid @enderror" value="{{ old('date_acquisition', $equipment->date_acquisition) }}">
                                                    @error('date_acquisition')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                    
                                                <!-- Date de Mise en Oeuvre -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="date_de_mise_en_oeuvre">Date de Mise en Oeuvre</label>
                                                    <input type="date" name="date_de_mise_en_oeuvre" id="date_de_mise_en_oeuvre" class="form-control @error('date_de_mise_en_oeuvre') is-invalid @enderror" value="{{ old('date_de_mise_en_oeuvre', $equipment->date_de_mise_en_oeuvre) }}">
                                                    @error('date_de_mise_en_oeuvre')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                    
                                                <!-- Catégorie -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="categorie">Catégorie</label>
                                                    <input type="text" name="categorie" id="categorie" class="form-control @error('categorie') is-invalid @enderror" value="{{ old('categorie', $equipment->categorie) }}">
                                                    @error('categorie')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                    
                                                <!-- Sous Catégorie -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="sous_categorie">Sous Catégorie</label>
                                                    <input type="text" name="sous_categorie" id="sous_categorie" class="form-control @error('sous_categorie') is-invalid @enderror" value="{{ old('sous_categorie', $equipment->sous_categorie) }}">
                                                    @error('sous_categorie')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                    
                                                <!-- Matricule -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="matricule">Matricule</label>
                                                    <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule', $equipment->matricule) }}">
                                                    @error('matricule')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                    
                                                <!-- Submit Button -->
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit me-2"></i>Modifier</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   
                        <!-- Supprimer button with icon -->
                        <form action="{{ route('equipments.destroy', $equipment->numero_de_serie) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                <i class="fas fa-trash-alt"></i> 
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>
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

