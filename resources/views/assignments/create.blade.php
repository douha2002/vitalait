@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Nouvelle Affectation</h2>

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

    <form method="POST" action="{{ route('assignments.store') }}">
        @csrf
        <div class="mb-3">
            <label for="equipment" >Équipement :</label>
            <select name="numero_de_serie" id="equipment" class="form-control" required>
                <option value="">-- Sélectionner un équipement --</option>
                @foreach($equipments as $equipment)
                    <option value="{{ $equipment->numero_de_serie }}" {{ old('numero_de_serie') == $equipment->numero_de_serie ? 'selected' : '' }}>
                        {{ $equipment->matricule }} ({{ $equipment->article ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="employee" >Employé :</label>
            <select name="employees_id" id="employee" class="form-control" required>
                <option value="">-- Sélectionner un employé --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employees_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" >Date de début :</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Affecter</button>
    </form>
</div>
@endsection