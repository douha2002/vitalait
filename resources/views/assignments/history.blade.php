@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4 text-center text-primary font-weight-bold" style="font-size: 2rem;">
        Historique des Affectations pour l'Équipement : 
        <span class="text-secondary">{{ $equipment->numero_de_serie }}</span>
    </h2>

    <a href="{{ route('assignments.index') }}" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left me-2"></i>Retour à la liste des affectations
    </a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Employé</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipment->assignments as $assignment)
                    <tr>
                        <td>
                            @if ($assignment->employee)
                                {{ $assignment->employee->nom }} {{ $assignment->employee->prenom }}
                            @else
                                <span class="text-warning">Employé inconnu</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($assignment->start_date)->format('d-m-Y') }}</td>
                        <td>{{ $assignment->end_date ? \Carbon\Carbon::parse($assignment->end_date)->format('d-m-Y') : 'En cours' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Aucune affectation trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
