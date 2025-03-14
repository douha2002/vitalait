@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier l'Affectation</h2>

    {{-- Affichage des erreurs de validation --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('assignments.update', $assignment->id) }}">
        @csrf
        @method('PUT') {{-- Utilisation de la méthode PUT pour la mise à jour --}}

        <div class="mb-3">
            <label for="equipement" >Équipement :</label>
            <select name="numero_de_serie" id="equipment" class="form-control" required>
                <option value="">-- Sélectionner un équipement --</option>
                @foreach($equipments as $equipment)
                    <option value="{{ $equipment->numero_de_serie }}" {{ $assignment->numero_de_serie == $equipment->numero_de_serie ? 'selected' : '' }}>
                        {{ $equipment->matricule }} ({{ $equipment->numero_de_serie ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="employee" >Employé :</label>
            <select name="employees_id" id="employee" class="form-control" required>
                <option value="">-- Sélectionner un employé --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $assignment->employees_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" >Date de début :</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $assignment->start_date }}" required>
        </div>

        <div class="mb-3">
            <label for="end_date" >Date de fin :</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $assignment->end_date }}">
            <small class="text-muted">Laissez vide si l'affectation est toujours en cours.</small>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection