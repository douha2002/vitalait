@extends('layouts.app')

@section('content')

<div class="container">
    <div class="search-container">
        <form method="GET" action="{{ route('assignments.index') }}" class="search-form">
            <input type="text" name="search" id="search" placeholder="Rechercher par Article, Quantité, etc..." value="{{ request()->search }}">
            <button type="submit"><i class="fas fa-search"></i></button>
        
            <!-- Reset Filter Button -->
            <a href="{{ route('assignments.index') }}" class="reset-filter-btn" title="Réinitialiser la recherche">
                <i class="fas fa-sync-alt"></i> 
            </a>
        </form>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="#" 
            class="btn btn-white me-2" 
            data-bs-toggle="modal" 
            data-bs-target="#addAssignmentModal"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="Ajouter une nouvelle affectation">
            <i class="fas fa-plus me-2"></i> 
        </a>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="addAssignmentModal" tabindex="-1" aria-labelledby="addAssignmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addAssignmentModalLabel">
                        <i class="fas fa-box me-2"></i>Ajouter un affectation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('assignments.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="numero_de_serie">Équipement :</label>
                            <select name="numero_de_serie" id="numero_de_serie" class="form-control" required>
                                <option value="">-- Sélectionner un équipement --</option>
                                @foreach($equipments as $equipment)
                                    <option value="{{ $equipment->numero_de_serie }}">{{ $equipment->numero_de_serie }}</option>
                                @endforeach
                            </select>
                        </div>
            
                        <div class="mb-3">
                            <label for="employees_id">Employee</label>
                            <select name="employees_id" id="employees_id" class="form-control" required>
                                <option value="">--Sélectionner Employee--</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->matricule }}">{{ $employee->nom }} {{ $employee->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
            
                        <div class="mb-3">
                            <label for="start_date">Date de début :</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        </div>
            
                        <button type="submit" class="btn btn-success">Affecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Équipement</th>
                <th>Employé</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->equipment->numero_de_serie ?? 'N/A' }}</td>
                    <td>{{ $assignment->employee->nom ?? 'N/A' }} {{ $assignment->employee->prenom ?? 'N/A' }}</td>
                    <td>{{ $assignment->start_date }}</td>
                    <td>{{ $assignment->end_date ?? 'En cours' }}</td>
                    <td>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAssignmentModal{{ $assignment->id }}">
                                <i class="fas fa-edit"></i>
                            </button>   
                         
                            <!-- Modal Structure -->
                            <div class="modal fade" id="editAssignmentModal{{ $assignment->id }}" tabindex="-1" aria-labelledby="editAssignmentModalLabel{{ $assignment->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="editAssignmentModalLabel{{ $assignment->id }}">
                                                <i class="fas fa-box me-2"></i>Modifier l'affectation
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('assignments.update', $assignment->id) }}">
                                                @csrf
                                                @method('PUT')
                        
                                                <div class="mb-3">
                                                    <label for="equipement">Équipement :</label>
                                                    <select name="numero_de_serie" id="equipment" class="form-control" required>
                                                        <option value="">-- Sélectionner un équipement --</option>
                                                        @foreach($equipments as $equip)
                                                            <option value="{{ $equip->numero_de_serie }}" {{ $assignment->numero_de_serie == $equip->numero_de_serie ? 'selected' : '' }}>
                                                                {{ $equip->numero_de_serie }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                        
                                                <div class="mb-3">
                                                    <label for="employee">Employé :</label>
                                                    <select name="employees_id" id="employee" class="form-control" required>
                                                        <option value="">-- Sélectionner un employé --</option>
                                                        @foreach($employees as $employee)
                                                            <option value="{{ $employee->matricule }}" {{ $assignment->employees_id == $employee->matricule ? 'selected' : '' }}>
                                                                {{ $employee->nom }} {{ $employee->prenom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                        
                                                <div class="mb-3">
                                                    <label for="start_date">Date de début :</label>
                                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $assignment->start_date ?? '') }}" required>
                                                </div>
                        
                                                <div class="mb-3">
                                                    <label for="end_date">Date de fin :</label>
                                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $assignment->end_date ?? '') }}">
                                                    <small class="text-muted">Laissez vide si l'affectation est toujours en cours.</small>
                                                </div>
                        
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                                <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Annuler</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>                        

                            <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>

                           <!-- Bouton pour afficher le modal -->
                                      <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#historyAssignmentModal{{ $assignment->id }}">
                                     <i class="fas fa-history"></i>
                                     </button>
                                     
                                <!-- Modal Structure -->
                                <div class="modal fade" id="historyAssignmentModal{{ $assignment->id }}" tabindex="-1" aria-labelledby="historyAssignmentModalLabel{{ $assignment->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="historyAssignmentModalLabel{{ $assignment->id }}">
                                <i class="fas fa-box me-2"></i>Historique des Affectations pour l'Équipement : {{ $equipment->numero_de_serie }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                            <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Employé</th>
                                            <th>Date de Début</th>
                                            <th>Date de Fin</th>
                                        </tr>
                                    </thead>
                        <tbody>
                            @foreach($assignment->equipment->assignments as $history)
                                <tr>
                                    <td>
                                        @if ($history->employee)
                                          {{ $history->employee->nom }} {{ $history->employee->prenom }}
                                        @else
                                           <span class="text-danger">Employé inconnu</span>
                                        @endif
                                    </td>
                                    <td>{{ $history->start_date }}</td>
                                    <td>{{ $history->end_date ?? 'En cours' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                           </table>
                        </div>
                    </div>
                </div>
            </div>
                    </td>
        
    </tr>

            @endforeach
           
        </tbody>
    </table>
</div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success" id="success-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1000; width: 50%; text-align: center; padding: 10px; border-radius: 5px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
        {{ session('success') }}
    </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Success message
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => successMessage.remove(), 5000); // Remove success message after 5 seconds
        }

        // Error message
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.transition = "opacity 0.5s";
                errorMessage.style.opacity = "0";
                setTimeout(() => {
                    errorMessage.remove();
                }, 500); // Remove after fade-out animation
            }, 3000); // Wait for 3 seconds before starting to hide the error message
        }
    });
</script>

@include('layouts.sidebar')

@endsection
