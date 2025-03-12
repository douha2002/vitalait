@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajouter un équipement</h2>

    <form action="{{ route('equipments.store') }}" method="POST" novalidate>
        @csrf

        <!-- Numéro de Série -->
        <div class="mb-3">
            <label for="numero_de_serie">Numéro de Série</label>
            <input type="text" name="numero_de_serie" id="numero_de_serie" class="form-control @error('numero_de_serie') is-invalid @enderror" value="{{ old('numero_de_serie') }}" required>
            @error('numero_de_serie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Article -->
        <div class="mb-3">
            <label for="article">Article</label>
            <input type="text" name="article" id="article" class="form-control @error('article') is-invalid @enderror" value="{{ old('article') }}" required>
            @error('article')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Quantité -->
        <div class="mb-3">
            <label for="quantite">Quantité</label>
            <input type="number" name="quantite" id="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite') }}" required>
            @error('quantite')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Date d'Acquisition -->
        <div class="mb-3">
            <label for="date_acquisition">Date d'Acquisition</label>
            <input type="date" name="date_acquisition" id="date_acquisition" class="form-control @error('date_acquisition') is-invalid @enderror" value="{{ old('date_acquisition') }}" required>
            @error('date_acquisition')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Date de Mise en Oeuvre -->
        <div class="mb-3">
            <label for="date_de_mise_en_oeuvre">Date de Mise en Oeuvre</label>
            <input type="date" name="date_de_mise_en_oeuvre" id="date_de_mise_en_oeuvre" class="form-control @error('date_de_mise_en_oeuvre') is-invalid @enderror" value="{{ old('date_de_mise_en_oeuvre') }}">
            @error('date_de_mise_en_oeuvre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Catégorie -->
        <div class="mb-3">
            <label for="categorie">Catégorie</label>
            <input type="text" name="categorie" id="categorie" class="form-control @error('categorie') is-invalid @enderror" value="{{ old('categorie') }}">
            @error('categorie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Sous Catégorie -->
        <div class="mb-3">
            <label for="sous_categorie">Sous Catégorie</label>
            <input type="text" name="sous_categorie" id="sous_categorie" class="form-control @error('sous_categorie') is-invalid @enderror" value="{{ old('sous_categorie') }}">
            @error('sous_categorie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Matricule -->
        <div class="mb-3">
            <label for="matricule">Matricule</label>
            <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}">
            @error('matricule')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success btn-block">Ajouter</button>
    </form>
</div>
@endsection