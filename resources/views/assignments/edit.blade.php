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
                @foreach($equipments as $equip) {{-- Renamed loop variable --}}
                <option value="{{ $equip->numero_de_serie }}"{{ $assignment->numero_de_serie == $equip->numero_de_serie ? 'selected' : '' }}>{{ $equip->numero_de_serie }}</option>
                        {{ $equip->numero_de_serie }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="employee" >Employé :</label>
            <select name="employees_id" id="employee" class="form-control" required>
                <option value="">-- Sélectionner un employé --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->matricule }}" {{ $assignment->employees_id == $employee->matricule ? 'selected' : '' }}> {{-- Matricule used here --}}
                        {{ $employee->nom }} {{ $employee->prenom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" >Date de début :</label>
            <input type="date" name="start_date" id="start_date" class="form-control"  value="{{ old('start_date', $assignment->start_date ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="end_date" >Date de fin :</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $assignment->end_date ?? '') }}">
            <small class="text-muted">Laissez vide si l'affectation est toujours en cours.</small>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
