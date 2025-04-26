@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <!-- Search Section -->
    <div class="d-flex justify-content-center align-items-center mb-4">
        <div class="search-container w-50">
            <form method="GET" action="{{ route('assignments.search') }}" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control shadow-sm" placeholder="Rechercher par numéro de série, Employé, etc...">
                    <button type="submit" class="btn btn-outline-secondary shadow-sm"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <!-- Reset Filter Button -->
        <a href="{{ route('assignments.index') }}" class="btn btn-outline-danger shadow-sm ms-2" title="Réinitialiser la recherche">
            <i class="fas fa-sync-alt"></i>
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-outline-primary shadow-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#addAssignmentModal"
                title="Ajouter une nouvelle affectation">
            <i class="fas fa-plus me-2"></i> Ajouter Affectation
        </button>
    </div>

    <!-- Add Assignment Modal -->
    <div class="modal fade" id="addAssignmentModal" tabindex="-1" aria-labelledby="addAssignmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="addAssignmentModalLabel">
                        <i class="fas fa-box me-2"></i> Ajouter une affectation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('assignments.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sous_categorie" class="fw-semibold">Sous catégorie :</label>
                            <select name="sous_categorie" id="sous_categorie" class="form-control rounded-3 shadow-sm" required>
                                <option value="" selected disabled>-- Sélectionner un équipement par sous catégorie --</option>
                                @foreach($equipments->pluck('sous_categorie')->unique() as $sousCategorie)
                                    <option value="{{ $sousCategorie }}">{{ $sousCategorie }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="numero_de_serie" class="fw-semibold">Équipement :</label>
                            <select name="numero_de_serie" id="numero_de_serie" class="form-control rounded-3 shadow-sm" required>
                                <option value="" selected disabled>-- Sélectionner un équipement --</option>
                                @foreach($equipments as $equipment)
                                    <option value="{{ $equipment->numero_de_serie }}" data-statut="{{ $equipment->statut }}">{{ $equipment->numero_de_serie }}</option>
                                @endforeach
                            </select>
                            <div id="alert-message-panne" class="mt-2 text-danger fw-bold" style="display: none;">
                                Attention: Vous ne pouvez pas affecter cet équipement car il est en panne.
                            </div>
                            
                            <div id="alert-message-affecte" class="mt-2 text-danger fw-bold" style="display: none;">
                                Attention: Vous ne pouvez pas affecter cet équipement car il est déjà affecté.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="employees_id" class="fw-semibold">Employé :</label>
                            <select name="employees_id" id="employees_id" class="form-control rounded-3 shadow-sm" required>
                                <option value="" selected disabled>-- Sélectionner un employé --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->matricule }}">{{ $employee->nom }} {{ $employee->prenom }} - {{ $employee->matricule }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date_debut" class="fw-semibold">Date de début :</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control rounded-3 shadow-sm" value="{{ old('date_debut') }}" required>
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

    <!-- Assignments Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="assignmentsTable" class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">
                            <i class="fas fa-laptop"></i> Équipement
                        </th>
                        
                        <th  class="text-center">
                            <i class="fas fa-users"></i> Employé
                        </th>
                        <th  class="text-center">
                            <i class="fas fa-calendar-alt"></i> Date Début
                        </th>
                        <th  class="text-center">
                            <i class="fas fa-calendar-check"></i> Date Fin
                        </th>
                        <th  class="text-center">
                            <i class="fas fa-cogs"></i> Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                        @forelse($assignments as $assignment)
                            <tr>
                                <td class="text-center">{{ $assignment->equipment->numero_de_serie ?? 'N/A' }}</td>
                                <td class="text-center">{{ $assignment->employee->nom ?? 'N/A' }} {{ $assignment->employee->prenom ?? 'N/A' }} - {{ $assignment->employee->matricule ?? 'N/A' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($assignment->date_debut)->format('d-m-Y') }}</td>
                                <td class="text-center">{{ $assignment->date_fin ? \Carbon\Carbon::parse($assignment->date_fin)->format('d-m-Y') : 'En cours' }}</td>
                                <td class="d-flex justify-content-center justify-content-between justify-content-md-around">
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAssignmentModal{{ $assignment->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Edit Assignment Modal -->
                                    <div class="modal fade" id="editAssignmentModal{{ $assignment->id }}" tabindex="-1" aria-labelledby="editAssignmentModalLabel{{ $assignment->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                                                <div class="modal-header bg-primary text-white rounded-top">
                                                    <h5 class="modal-title fw-bold" id="editAssignmentModalLabel{{ $assignment->id }}">
                                                        <i class="fas fa-box me-2"></i> Modifier l'affectation
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('assignments.update', $assignment->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="equipement" class="fw-semibold">Équipement :</label>
                                                            <select name="numero_de_serie" id="numero_de_serie" class="form-control rounded-3 shadow-sm" required>
                                                                <option value="" selected disabled>-- Sélectionner un équipement --</option>
                                                                @foreach($equipments as $equipment)
                                                                    <option value="{{ $equipment->numero_de_serie }}" 
                                                                            {{ isset($assignment) && $assignment->numero_de_serie == $equipment->numero_de_serie ? 'selected' : '' }} 
                                                                            data-statut="{{ $equipment->statut }}">
                                                                        {{ $equipment->numero_de_serie }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            
                                                            <div id="alert-message-panne" class="mt-2 text-danger fw-bold" style="display: none;">
                                                                Attention: Vous ne pouvez pas affecter cet équipement car il est en panne.
                                                            </div>
                                                            
                                                            <div id="alert-message-affecte" class="mt-2 text-danger fw-bold" style="display: none;">
                                                                Attention: Vous ne pouvez pas affecter cet équipement car il est déjà affecté.
                                                            </div>
                                                        </div>
                                                            

                                                        <div class="mb-3">
                                                            <label for="employee" class="fw-semibold">Employé :</label>
                                                            <select name="employees_id" id="employee" class="form-control rounded-3 shadow-sm" required>
                                                                <option value="" selected disabled>-- Sélectionner un employé --</option>
                                                                @foreach($employees as $employee)
                                                                    <option value="{{ $employee->matricule }}" {{ $assignment->employees_id == $employee->matricule ? 'selected' : '' }}>
                                                                        {{ $employee->nom }} {{ $employee->prenom }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="date_debut" class="fw-semibold">Date de début :</label>
                                                            <input type="date" name="date_debut" id="date_debut" class="form-control rounded-3 shadow-sm" value="{{ old('date_debut', $assignment->date_debut ?? '') }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="date_fin" class="fw-semibold">Date de fin :</label>
                                                            <input type="date" name="date_fin" id="date_fin" class="form-control rounded-3 shadow-sm" value="{{ old('date_fin', $assignment->date_fin ?? '') }}">
                                                        </div>
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
                                    

                                    <!-- Delete Button -->
                                    <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet affectation ?');">
                                            <i class="fas fa-trash me-2"></i>
                                        </button>
                                    </form>

                                    <!-- History Button -->
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#historyModal{{ $assignment->equipment->numero_de_serie }}">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    @foreach ($assignments as $assignment)
                                    <!-- Modal -->
                                    
    <div class="modal fade" id="historyModal{{ $assignment->equipment->numero_de_serie }}" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="historyModalLabel">
                        <i class="fas fa-history me-2"></i> Historique des Affectations
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Équipement : <span class="fw-bold">{{ $assignment->equipment->numero_de_serie }}</span></p>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th><i class="fas fa-user"></i> Employé</th>
                                    <th><i class="fas fa-calendar-alt"></i> Date de Début</th>
                                    <th><i class="fas fa-calendar-check"></i> Date de Fin</th>
                                    <th><i class="fas fa-trash-alt"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignment->equipment->assignments as $history)
                                    <tr>
                                        <td class="text-center">
                                            @if ($history->employee)
                                                <span class="fw-semibold">{{ $history->employee->nom }} {{ $history->employee->prenom }}</span>
                                            @else
                                                <span class="text-warning">Employé inconnu</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($history->date_debut)->format('d-m-Y') }}</td>
                                        <td class="text-center">
                                            @if ($history->date_fin)
                                                <span class="badge bg-success">{{ \Carbon\Carbon::parse($history->date_fin)->format('d-m-Y') }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">En cours</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('assignments.softDelete', $history->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="fas fa-exclamation-circle me-2"></i> Aucune affectation trouvée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Section for Soft Deleted Assignments -->
                    <hr>
                    <h5 class="text-danger text-center"><i class="fas fa-undo-alt"></i> Affectations Supprimées</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-danger">
                                <tr>
                                    <th class="text-center">Employé</th>
                                    <th class="text-center">Date de Début</th>
                                    <th class="text-center">Date de Fin</th>
                                    <th class="text-center">Restaurer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignment->equipment->assignments()->onlyTrashed()->get() as $deleted)
                                    <tr>
                                        <td class="text-center">{{ $deleted->employee->nom ?? 'Employé inconnu' }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($deleted->date_debut)->format('d-m-Y') }}</td>
                                        <td class="text-center">{{ $deleted->date_fin ? \Carbon\Carbon::parse($deleted->date_fin)->format('d-m-Y') : 'En cours' }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('assignments.restore', $deleted->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-undo-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Aucune affectation supprimée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
                        </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Aucun équipement trouvé.</td>
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

{{-- Error Messages --}}
@if($errors->any())
    <div class="alert alert-danger" id="error-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 50%; text-align: center; padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transition = "opacity 0.5s";
                    errorMessage.style.opacity = "0";
                    setTimeout(() => errorMessage.remove(), 500);
                }, 5000); // Remove after 5 seconds
            }
        });
    </script>
@endif

{{-- Equipement Select JS --}}
<script>
    $(document).ready(function() {
        // Lorsque la sélection change
        $('#numero_de_serie').change(function() {
            var selectedOption = $(this).find('option:selected');
            var statut = selectedOption.data('statut'); // Récupérer l'attribut 'statut' de l'équipement sélectionné

            // Si le statut est 'En panne', afficher le message d'avertissement
            if (statut === 'En panne') {
                $('#alert-message-panne').show(); // Afficher le message d'alerte
                $('#alert-message-affecte').hide(); // Masquer l'alerte "Affecté"
                $('#assign-button').prop('disabled', true); // Désactiver le bouton d'affectation
            } 
            // Si le statut est 'Affecté', afficher l'alerte correspondante
            else if (statut === 'Affecté') {
                $('#alert-message-affecte').show(); // Afficher l'alerte "Affecté"
                $('#alert-message-panne').hide(); // Masquer l'alerte "En panne"
                $('#assign-button').prop('disabled', true); // Désactiver le bouton d'affectation
            }
            // Si aucun des deux statuts, activer l'affectation
            else {
                $('#alert-message-panne').hide(); // Masquer l'alerte "En panne"
                $('#alert-message-affecte').hide(); // Masquer l'alerte "Affecté"
                $('#assign-button').prop('disabled', false); // Activer le bouton d'affectation
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#assignmentsTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
            },
            paging: true,
            searching: false,
            info: true
        });
    });
</script>
<script>
    // Regrouper les équipements par sous catégorie
    const allEquipments = @json($equipments);

    const sousCategorieSelect = document.getElementById('sous_categorie');
    const numeroDeSerieSelect = document.getElementById('numero_de_serie');

    sousCategorieSelect.addEventListener('change', function () {
        const selectedSousCategorie = this.value;

        // Nettoyer la liste des numéros de série
        numeroDeSerieSelect.innerHTML = '<option value="" selected disabled>-- Sélectionner un équipement --</option>';

        // Filtrer et ajouter les équipements correspondants
        allEquipments.forEach(equipment => {
            if (equipment.sous_categorie === selectedSousCategorie) {
                const option = document.createElement('option');
                option.value = equipment.numero_de_serie;
                option.textContent = equipment.numero_de_serie;
                numeroDeSerieSelect.appendChild(option);
            }
        });
    });
</script>



@include('layouts.sidebar')

@endsection