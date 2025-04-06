@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
 <!-- Search Section -->
    <div class="d-flex justify-content-center align-items-center mb-4">
        <div class="search-container w-50">
            <form method="GET" action="{{ route('stock.search') }}" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control shadow-sm" placeholder="Rechercher par sous categorie">
                    <button type="submit" class="btn btn-outline-secondary shadow-sm"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <!-- Reset Filter Button -->
        <a href="{{ route('stock.index') }}" class="btn btn-outline-danger shadow-sm ms-2" title="Réinitialiser la recherche">
            <i class="fas fa-sync-alt"></i>
        </a>
    </div>

    <div class="d-flex justify-content-end mb-4">
    <button class="btn btn-outline-primary shadow-sm" onclick="fetchAvailableEquipments()">📦 Ajouter des équipements au stock</button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
            <tr>
                <th class="text-center">Sous-catégorie</th>
                <th class="text-center">Quantité</th>
            </tr>
        </thead>
        <tbody>
        
            @foreach($stocks as $stock)
            <tr>
                <td>{{ $stock->sous_categorie }}</td>
                <td>{{ $stock->quantite }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

<!-- Modal -->
<div id="equipementModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sélectionnez les équipements à ajouter au stock</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="equipementForm">
                    @csrf
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Numéro de Série</th>
                                <th>Article</th>
                                <th>Sous-catégorie</th>
                                <th>
                                    <input type="checkbox" id="selectAll"> Sélectionner tout
                                </th>
                            </tr>
                        </thead>
                        <tbody id="equipementTable">
                            <!-- Data will be inserted here via JS -->
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success" onclick="submitEquipement()">Ajouter au Stock</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Sélectionner/désélectionner toutes les cases à cocher
    document.getElementById("selectAll").addEventListener("change", function () {
        let checkboxes = document.querySelectorAll("#equipementTable input[type='checkbox']");
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});
</script>

<script>
function fetchAvailableEquipments() {
    fetch("{{ route('stock.available') }}")
    .then(response => response.json())
    .then(data => {
        let rows = "";
        data.forEach(equip => {
            rows += `
                <tr>
                    <td>${equip.numero_de_serie}</td>
                    <td>${equip.article}</td>
                    <td>${equip.sous_categorie}</td>
                    <td><input type="checkbox" name="equipements[]" value="${equip.numero_de_serie}"></td>
                </tr>`;
        });
        document.getElementById("equipementTable").innerHTML = rows;
        $('#equipementModal').modal('show');
    })
    .catch(error => console.error("Erreur:", error));
}

function submitEquipement() {
    let formData = new FormData(document.getElementById("equipementForm"));
    fetch("{{ route('stock.add') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            location.reload();
        } else {
            alert("Erreur: " + data.error);
        }
    })
    .catch(error => console.error("Erreur:", error));
}
</script>
@include('layouts.sidebar')
@endsection
