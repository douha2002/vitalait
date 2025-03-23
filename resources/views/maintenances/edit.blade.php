@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier la Maintenance</h1>
    
    <form action="{{ route('maintenances.update', $maintenance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Équipement Selection -->
        <div class="mb-3">
            <label>Équipement:</label>
            <select name="numero_de_serie" id="equipement" class="form-select" required>
                <option value="">-- Sélectionnez un équipement --</option>
                @foreach($equipements as $equipement)
                    <option value="{{ $equipement->numero_de_serie }}" 
                        {{ $equipement->numero_de_serie == old('numero_de_serie', $maintenance->numero_de_serie) ? 'selected' : '' }}>
                        {{ $equipement->numero_de_serie }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Fournisseur Selection -->
        <div class="mb-3">
            <label>Fournisseur:</label>
            <select name="fournisseur_id" id="fournisseur" class="form-select">
                <option value="">-- Sélectionnez un fournisseur --</option>
                @foreach($fournisseurs as $fournisseur)
                    <option value="{{ $fournisseur->id }}" 
                        {{ $fournisseur->id == old('fournisseur_id', $maintenance->fournisseur_id) ? 'selected' : '' }}>
                        {{ $fournisseur->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Date de début -->
        <div class="mb-3">
            <label>Date de début:</label>
            <input type="date" name="date_debut" class="form-control" 
                value="{{ old('date_debut', $maintenance->date_debut) }}" required>
        </div>
    
        <!-- Date de fin -->
        <div class="mb-3">
            <label for="date_fin">Date de Fin</label>
            <input 
                type="date" 
                name="date_fin" 
                id="date_fin" 
                class="form-control" 
                value="{{ old('date_fin', $maintenance->date_fin) }}"
                @if($maintenance->date_fin) disabled @endif  <!-- Disable if already set -->
            >
            @if($maintenance->date_fin)
                <small class="form-text text-muted">Cette maintenance est déjà terminée.</small>
            @endif
        </div>
    
        <button type="submit" class="btn btn-primary">Mettre à jour</button>    
        <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
