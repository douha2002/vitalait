@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Search Section -->
    <div class="d-flex justify-content-center align-items-center mb-4">
        <div class="search-container w-50">
            <form method="GET" action="{{ route('maintenances.search') }}" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control shadow-sm" placeholder="Rechercher par numéro de série, Fournisseur, etc...">
                    <button type="submit" class="btn btn-outline-secondary shadow-sm"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <a href="{{ route('maintenances.index') }}" class="btn btn-outline-danger shadow-sm ms-2" title="Réinitialiser la recherche">
            <i class="fas fa-sync-alt"></i>
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal" title="Ajouter une nouvelle maintenance">
            <i class="fas fa-plus me-2"></i> Ajouter Maintenance
        </button>
    </div>

    <!-- Add Maintenance Modal -->
    <div class="modal fade" id="addMaintenanceModal" tabindex="-1" aria-labelledby="addMaintenanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="addMaintenanceModalLabel">
                        <i class="fas fa-tools me-2"></i> Ajouter une maintenance
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contract-alert" class="alert alert-warning d-none" role="alert"></div>

                    <form action="{{ route('maintenances.store') }}" method="POST">
                        @csrf
                        <!-- Équipement -->
                        <div class="mb-3">
                            <label for="equipement" class="fw-semibold">Équipement :</label>
                            <select name="numero_de_serie" id="equipement" class="form-select rounded-3 shadow-sm" required>
                                <option value="" selected disabled>-- Sélectionnez un équipement --</option>
                                @foreach($equipements as $equipement)
                                    <option value="{{ $equipement->numero_de_serie }}"
                                            data-fournisseur="{{ optional($equipement->contrat)->fournisseur_id }}"
<<<<<<< HEAD
                                            data-statut="{{ $equipement->statut }}">
=======
                                            data-statut="{{ $equipement->statut }}"
                                            data-date-fin="{{ optional($equipement->contrat)->date_fin }}">

>>>>>>> ff707a9a02ca0702f2af4c2d1d1bc29cb5e1649c
                                        {{ $equipement->numero_de_serie }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="affecte-alert" class="mt-2 text-danger fw-bold d-none">
                                 Attention : Vous ne pouvez pas planifier cet équipement car il est affecté.
                            </div>
                        </div>

                        <!-- Fournisseur -->
                        <div class="mb-3">
                            <label class="fw-semibold">Fournisseur :</label>
                            <select name="fournisseur_id" id="fournisseur" class="form-select rounded-3 shadow-sm" required>
                                <option value="" selected disabled>-- Sélectionnez un fournisseur --</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date de début -->
                        <div class="mb-3">
                            <label class="fw-semibold">Date de début :</label>
                            <input type="date" name="date_debut" class="form-control rounded-3 shadow-sm" value="{{ old('date_debut') }}" required>
                        </div>

                        <!-- Commentaires -->
                        <div class="mb-3">
                            <label class="fw-semibold">Commentaires :</label>
<<<<<<< HEAD
                            <textarea name="commentaire" class="form-control rounded-3 shadow-sm">{{ old('commentaire') }}</textarea>
=======
                            <textarea name="commentaires" class="form-control rounded-3 shadow-sm">{{ old('commentaires') }}</textarea>
>>>>>>> ff707a9a02ca0702f2af4c2d1d1bc29cb5e1649c
                        </div>

                        <div class="modal-footer d-flex justify-content-between bg-light rounded-bottom">
                            <button type="button" class="btn btn-outline-secondary px-4 rounded-3 shadow-sm" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                                <i class="fas fa-save me-2"></i> Planifier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th><i class="fas fa-laptop"></i> Équipement</th>
                        <th><i class="fas fa-truck"></i> Fournisseur</th>
                        <th><i class="fas fa-calendar-alt"></i> Date de Début</th>
                        <th><i class="fas fa-calendar-check"></i> Date de Fin</th>
                        <th><i class="fas fa-comment"></i> Commentaires</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $maintenance)
                        <tr>
                            <td class="text-center">{{ $maintenance->numero_de_serie }}</td>
                            <td class="text-center">{{ $maintenance->fournisseur->nom }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($maintenance->date_debut)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $maintenance->date_fin ? \Carbon\Carbon::parse($maintenance->date_fin)->format('d-m-Y') : 'En cours' }}</td>
                            <td class="text-center">
                                @empty($maintenance->commentaires)
                                    Aucun commentaire
                                @else
                                    {{ $maintenance->commentaires }}
                                @endempty
                            </td>
                            <td class="d-flex justify-content-center">
                                <!-- Edit Button -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMaintenanceModal{{ $maintenance->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editMaintenanceModal{{ $maintenance->id }}" tabindex="-1" aria-labelledby="editMaintenanceModalLabel{{ $maintenance->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                                            <div class="modal-header bg-primary text-white rounded-top">
                                                <h5 class="modal-title fw-bold" id="editMaintenanceModalLabel{{ $maintenance->id }}">
                                                    <i class="fas fa-tools me-2"></i> Modifier la maintenance
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('maintenances.update', $maintenance->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="mb-3">
                                                        <label class="fw-semibold">Équipement :</label>
                                                        <select name="numero_de_serie" id="equipement_edit_{{ $maintenance->id }}" class="form-select rounded-3 shadow-sm" required>
                                                            <option value="" disabled>-- Sélectionnez un équipement --</option>
                                                            @foreach($equipements as $equipement)
                                                                <option value="{{ $equipement->numero_de_serie }}"
                                                                    {{ $equipement->numero_de_serie == old('numero_de_serie', $maintenance->numero_de_serie) ? 'selected' : '' }}>
                                                                    {{ $equipement->numero_de_serie }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="fw-semibold">Fournisseur :</label>
                                                        <select name="fournisseur_id" class="form-select rounded-3 shadow-sm" required>
                                                            <option value="" disabled>-- Sélectionnez un fournisseur --</option>
                                                            @foreach($fournisseurs as $fournisseur)
                                                                <option value="{{ $fournisseur->id }}"
                                                                    {{ $fournisseur->id == old('fournisseur_id', $maintenance->fournisseur_id) ? 'selected' : '' }}>
                                                                    {{ $fournisseur->nom }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="fw-semibold">Date de début :</label>
                                                        <input type="date" name="date_debut" class="form-control rounded-3 shadow-sm"
                                                               value="{{ old('date_debut', $maintenance->date_debut) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="fw-semibold">Date de fin :</label>
                                                        <input type="date" name="date_fin" class="form-control rounded-3 shadow-sm"
                                                               value="{{ old('date_fin', $maintenance->date_fin) }}" {{ $maintenance->date_fin ? 'disabled' : '' }}>
                                                        @if($maintenance->date_fin)
                                                            <small class="form-text text-muted">Cette maintenance est déjà terminée.</small>
                                                        @endif
                                                    </div>

                                                    <div class="modal-footer d-flex justify-content-between bg-light rounded-bottom">
                                                        <button type="button" class="btn btn-outline-secondary px-4 rounded-3 shadow-sm" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i> Annuler
                                                        </button>
                                                        <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                                                            <i class="fas fa-save me-2"></i> Modifier
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucune maintenance trouvée.</td>
                        </tr>
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
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = "opacity 0.5s";
                    successMessage.style.opacity = "0";
                    setTimeout(() => successMessage.remove(), 500);
                }, 5000); // Remove after 5 seconds
            }
        });
    </script>
@endif


<!-- Errors -->
@if($errors->any())
    <div class="alert alert-danger" id="error-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 50%;">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => successMessage.remove(), 5000);
        }

        const equipementSelect = document.getElementById("equipement");
        if (equipementSelect) {
            equipementSelect.addEventListener("change", function () {
<<<<<<< HEAD
                const selected = this.options[this.selectedIndex];
                const fournisseurId = selected.getAttribute("data-fournisseur");
                const statut = selected.getAttribute("data-statut");
                const alertBox = document.getElementById("contract-alert");
                const affecteAlert = document.getElementById("affecte-alert");

                alertBox.classList.add("d-none");
                affecteAlert.classList.add("d-none");

                if (statut === "Affecté") {
                    affecteAlert.classList.remove("d-none");
                } else {
                    if (fournisseurId) {
                        document.getElementById("fournisseur").value = fournisseurId;
                        alertBox.innerHTML = "⚠️ Attention : Cet équipement a un contrat avec le fournisseur sélectionné automatiquement.";
                    } else {
                        document.getElementById("fournisseur").value = "";
                        alertBox.innerHTML = "⚠️ Aucun contrat trouvé pour cet équipement. Veuillez sélectionner un fournisseur.";
                    }
                    alertBox.classList.remove("d-none");
                }
            });
=======
    const selected = this.options[this.selectedIndex];
    const fournisseurId = selected.getAttribute("data-fournisseur");
    const statut = selected.getAttribute("data-statut");
    const dateFin = selected.getAttribute("data-date-fin");
    const alertBox = document.getElementById("contract-alert");
    const affecteAlert = document.getElementById("affecte-alert");

    alertBox.classList.add("d-none");
    affecteAlert.classList.add("d-none");

    if (statut === "Affecté") {
        affecteAlert.classList.remove("d-none");
    } else {
        const today = new Date().toISOString().split('T')[0];

        if (fournisseurId && dateFin && dateFin >= today) {
            // Contrat valide
            document.getElementById("fournisseur").value = fournisseurId;
            alertBox.innerHTML = "⚠️ Attention : Cet équipement a un contrat avec le fournisseur sélectionné automatiquement.";
        } else {
            // Contrat expiré ou inexistant
            document.getElementById("fournisseur").value = "";
            alertBox.innerHTML = "⚠️ Aucun contrat trouvé pour cet équipement. Veuillez sélectionner un fournisseur.";
        }

        alertBox.classList.remove("d-none");
    }
});

>>>>>>> ff707a9a02ca0702f2af4c2d1d1bc29cb5e1649c
        }
    });
</script>

@include('layouts.sidebar')
@endsection
