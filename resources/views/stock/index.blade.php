@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Search Section -->
    <div class="d-flex justify-content-center align-items-center mb-4">
        <div class="search-container w-50">
            <form method="GET" action="{{ route('stock.search') }}" class="search-form">
                <div class="input-group">
                    <input type="text" name="sous_categorie" id="search" class="form-control shadow-sm" placeholder="Rechercher par sous catégorie">
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
                        <th class="text-center">Article</th>
                        <th class="text-center">Sous-catégorie</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-center">Seuil Minimum</th>
                        <th class="text-center">Modifier Seuil Minimum</th>
                    </tr>
                </thead>
                <tbody>
                    @if($stocks->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">Aucun équipement dans le stock.</td>
                        </tr>
                    @else
                        @foreach($stocks as $stock)
                            @php
                                // Get the first equipment of this category to display its article name
                                $equipment = App\Models\Equipement::where('sous_categorie', $stock->sous_categorie)->first();
                            @endphp
                            <tr>
                                <td class="text-center">{{ $equipment->article ?? $stock->sous_categorie }}</td>
                                <td class="text-center">{{ $stock->sous_categorie }}</td>
                                <td class="text-center">{{ $stock->quantite ?? 0 }}</td>
                                <td class="text-center">{{ $stock->seuil_min ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('stock.updateSeuil', ['sous_categorie' => $stock->sous_categorie]) }}" method="POST" class="d-flex">
                                        @csrf
                                        <input type="number" name="seuil_min" value="{{ $stock->seuil_min }}" class="form-control me-3" required>
                                        <button type="submit" class="btn btn-sm btn-warning"> <i class="fas fa-edit"></i> Modifier</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
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
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body with Table -->
                <div class="modal-body">
                    <form id="equipementForm">
                        @csrf
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Numéro de Série</th>
                                    <th class="text-center">Article</th>
                                    <th class="text-center">Sous-catégorie</th>
                                    <th class="text-center">Seuil Minimum</th>
                                    <th class="text-center">
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

        // Function to fetch available equipment
        function fetchAvailableEquipments() {
            fetch("{{ route('stock.available') }}")
                .then(response => response.json())
                .then(data => {
                    let rows = "";
                    data.forEach(equip => {
                        rows += `
                            <tr data-sous-categorie="${equip.sous_categorie}">
                                <td>${equip.numero_de_serie}</td>
                                <td>${equip.article}</td>
                                <td>${equip.sous_categorie}</td>
                                <td>
                                    <input type="number" name="seuil_min" 
                                           class="form-control seuil-min" 
                                           data-sous-categorie="${equip.sous_categorie}"
                                           data-numero-de-serie="${equip.numero_de_serie}"
                                           required>
                                </td>
                                <td><input type="checkbox" name="equipements[]" value="${equip.numero_de_serie}" data-sous-categorie="${equip.sous_categorie}" data-numero-de-serie="${equip.numero_de_serie}"></td>
                            </tr>`;
                    });
                    document.getElementById("equipementTable").innerHTML = rows;
                    $('#equipementModal').modal('show');
                })
                .catch(error => console.error("Erreur:", error));
        }

        // Automatically update the seuil_min for all items in the same sous_categorie
        document.getElementById('equipementTable').addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('seuil-min')) {
                let seuilMinValue = e.target.value;
                let sousCategorie = e.target.dataset.sousCategorie;
                
                // Find all other seuil_min inputs with the same sous_categorie and update them
                let allSeuils = document.querySelectorAll(`.seuil-min[data-sous-categorie="${sousCategorie}"]`);
                allSeuils.forEach(input => {
                    input.value = seuilMinValue; // Set the same seuil_min for all items of the same sous_categorie
                });
            }
        });

        // Submit equipment data (Add to stock)
        function submitEquipement() {
            // Collect all selected equipment and their seuil_min values
            let formData = new FormData();
            let selectedEquipments = [];
            let seuilMinValues = {};
            
            document.querySelectorAll("#equipementTable input[type='checkbox']:checked").forEach(checkbox => {
                let row = checkbox.closest('tr');
                let numeroSerie = checkbox.value;
                let sousCategorie = row.dataset.sousCategorie;
                let seuilMin = row.querySelector('.seuil-min').value;
                
                selectedEquipments.push(numeroSerie);
                seuilMinValues[numeroSerie] = seuilMin;
                // Also store by sous_categorie for the stock update
                seuilMinValues[sousCategorie] = seuilMin;
            });

            formData.append('equipements', JSON.stringify(selectedEquipments));
            formData.append('seuil_min', JSON.stringify(seuilMinValues));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch("{{ route('stock.add') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    location.reload();
                } else {
                    alert("Erreur: " + (data.error || 'Une erreur est survenue'));
                }
            })
            .catch(error => console.error("Erreur:", error));
        }
    </script>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert alert-success" id="success-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1000; width: 50%; text-align: center; padding: 10px; border-radius: 5px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
        {{ session('success') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => successMessage.remove(), 5000); // Remove success message after 5 seconds
            }
        });
    </script>
    @endif

    {{-- Flash Error Message --}}
    @if(session('error'))
    <div class="alert alert-danger" id="error-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1000; width: 50%; text-align: center; padding: 10px; border-radius: 5px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
        {{ session('error') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transition = "opacity 0.5s";
                    errorMessage.style.opacity = "0";
                    setTimeout(() => {
                        errorMessage.remove();
                        if (successMessage) {
                            successMessage.style.transition = "opacity 0.5s";
                            successMessage.style.opacity = "1";
                            setTimeout(() => successMessage.remove(), 5000); // Remove after 5 seconds
                        }
                    }, 500);
                }, 3000); // Wait for 3 seconds before hiding the error message
            }
            else {
                if (successMessage) {
                    setTimeout(() => successMessage.remove(), 5000); // Remove success message after 5 seconds
                }
            }
        });
    </script>
    @endif

    @include('layouts.sidebar')
@endsection