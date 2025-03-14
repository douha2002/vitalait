@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Historique des Affectations pour l'Équipement : {{ $equipment->matricule }} ({{ $equipment->numero_de_serie }})</h2>

    <a href="{{ route('assignments.index') }}" class="btn btn-secondary mb-3">Retour à la liste des affectations</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employé</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipment->assignments as $assignment)
                <tr>
                    <td>
                        @if ($assignment->employee)
                            {{ $assignment->employee->nom }} {{ $assignment->employee->prenom }}
                        @else
                            <span class="text-danger">Employé inconnu</span>
                        @endif
                    </td>
                    <td>{{ $assignment->start_date }}</td>
                    <td>{{ $assignment->end_date ?? 'En cours' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
