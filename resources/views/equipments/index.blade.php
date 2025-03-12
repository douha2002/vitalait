@extends('layouts.app')

@section('content')

@include('partials.search')

<div class="container">
    <!-- Buttons for adding and importing equipment -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-white me-2" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-2"></i>
        </button>
        <button class="btn btn-white" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-upload me-2"></i>
        </button>
    </div>

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
                            <label for="file" class="form-label">Choisir un fichier CSV</label>
                            <input type="file" name="file" class="form-control" id="file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Equipment Form in Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Ajouter un nouvel équipement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('equipments.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Numéro de Série -->
                            <div class="col-md-6 mb-3">
                                <label for="numero_de_serie" class="form-label">Numéro de Série</label>
                                <input type="text" name="numero_de_serie" id="numero_de_serie" class="form-control @error('numero_de_serie') is-invalid @enderror" value="{{ old('numero_de_serie') }}" required>
                                @error('numero_de_serie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Article -->
                            <div class="col-md-6 mb-3">
                                <label for="article" class="form-label">Article</label>
                                <input type="text" name="article" id="article" class="form-control @error('article') is-invalid @enderror" value="{{ old('article') }}">
                                @error('article')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Quantité -->
                            <div class="col-md-6 mb-3">
                                <label for="quantite" class="form-label">Quantité</label>
                                <input type="number" name="quantite" id="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite') }}">
                                @error('quantite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date d'Acquisition -->
                            <div class="col-md-6 mb-3">
                                <label for="date_acquisition" class="form-label">Date d'Acquisition</label>
                                <input type="date" name="date_acquisition" id="date_acquisition" class="form-control @error('date_acquisition') is-invalid @enderror" value="{{ old('date_acquisition') }}">
                                @error('date_acquisition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de Mise en Oeuvre -->
                            <div class="col-md-6 mb-3">
                                <label for="date_de_mise_en_oeuvre" class="form-label">Date de Mise en Oeuvre</label>
                                <input type="date" name="date_de_mise_en_oeuvre" id="date_de_mise_en_oeuvre" class="form-control @error('date_de_mise_en_oeuvre') is-invalid @enderror" value="{{ old('date_de_mise_en_oeuvre') }}">
                                @error('date_de_mise_en_oeuvre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catégorie -->
                            <div class="col-md-6 mb-3">
                                <label for="categorie" class="form-label">Catégorie</label>
                                <input type="text" name="categorie" id="categorie" class="form-control @error('categorie') is-invalid @enderror" value="{{ old('categorie') }}">
                                @error('categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sous Catégorie -->
                            <div class="col-md-6 mb-3">
                                <label for="sous_categorie" class="form-label">Sous Catégorie</label>
                                <input type="text" name="sous_categorie" id="sous_categorie" class="form-control @error('sous_categorie') is-invalid @enderror" value="{{ old('sous_categorie') }}">
                                @error('sous_categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Matricule -->
                            <div class="col-md-6 mb-3">
                                <label for="matricule" class="form-label">Matricule</label>
                                <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}">
                                @error('matricule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Equipment Table -->
    <table class="table table-striped">
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
                        <a href="{{ route('equipments.edit', $equipment->numero_de_serie) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> 
                        </a>
                        <form action="{{ route('equipments.destroy', $equipment->numero_de_serie) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
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