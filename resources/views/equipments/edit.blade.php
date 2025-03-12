@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier l'équipement</h2>

    <form action="{{ route('equipments.update', $equipment->numero_de_serie) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT method for updates -->
    
        <!-- Numéro de Série -->
        <div class="mb-3">
            <label>Numéro de Série</label>
            <input type="text" name="numero_de_serie" class="form-control @error('numero_de_serie') is-invalid @enderror" value="{{ old('numero_de_serie', $equipment->numero_de_serie) }}" readonly>
            @error('numero_de_serie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Article -->
        <div class="mb-3">
            <label>Article</label>
            <input type="text" name="article" class="form-control @error('article') is-invalid @enderror" value="{{ old('article', $equipment->article) }}">
            @error('article')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Quantité -->
        <div class="mb-3">
            <label>Quantité</label>
            <input type="number" name="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite', $equipment->quantite) }}">
            @error('quantite')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Date d'Acquisition -->
        <div class="mb-3">
            <label>Date d'Acquisition</label>
            <input type="date" name="date_acquisition" class="form-control @error('date_acquisition') is-invalid @enderror" value="{{ old('date_acquisition', $equipment->date_acquisition) }}">
            @error('date_acquisition')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Date de Mise en Oeuvre -->
        <div class="mb-3">
            <label>Date de Mise en Oeuvre</label>
            <input type="date" name="date_de_mise_en_oeuvre" class="form-control @error('date_de_mise_en_oeuvre') is-invalid @enderror" value="{{ old('date_de_mise_en_oeuvre', $equipment->date_de_mise_en_oeuvre) }}">
            @error('date_de_mise_en_oeuvre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Catégorie -->
        <div class="mb-3">
            <label>Catégorie</label>
            <input type="text" name="categorie" class="form-control @error('categorie') is-invalid @enderror" value="{{ old('categorie', $equipment->categorie) }}">
            @error('categorie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Sous Catégorie -->
        <div class="mb-3">
            <label>Sous Catégorie</label>
            <input type="text" name="sous_categorie" class="form-control @error('sous_categorie') is-invalid @enderror" value="{{ old('sous_categorie', $equipment->sous_categorie) }}">
            @error('sous_categorie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Matricule -->
        <div class="mb-3">
            <label>Matricule</label>
            <input type="text" name="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule', $equipment->matricule) }}">
            @error('matricule')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
