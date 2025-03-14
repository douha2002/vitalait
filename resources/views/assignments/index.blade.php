@extends('layouts.app')

@section('content')

<div class="container">
    <div class="search-container">
        <form method="GET" action="{{ route('assignments.index') }}" class="search-form"> <!-- Adjust action route -->
            <input type="text" name="search" id="search" placeholder="Rechercher par Article, Quantité, etc..." value="{{ request()->search }}">
            <button type="submit"><i class="fas fa-search"></i></button>
        
        <!-- Reset Filter Button -->
        <a href="{{ route('assignments.index') }}" class="reset-filter-btn" title="Réinitialiser la recherche">
            <i class="fas fa-sync-alt"></i> 
        </a>
    </div>
</form>

    

    <h2>Gestion des Affectations</h2>

    <a href="{{ route('assignments.create') }}" class="btn btn-primary">Nouvelle Affectation</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Équipement</th>
                <th>Employé</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
            <tr>
                <td>{{ $assignment->equipment->numero_de_serie ?? 'N/A' }}</td>
                <td>{{ $assignment->employee->nom ?? 'N/A' }} {{ $assignment->employee->prenom ?? 'N/A' }}</td>
            </td>
                <td>{{ $assignment->start_date }}</td>
                <td>{{ $assignment->end_date ?? 'En cours' }}</td>

                <td>
                    <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> </a>
                    <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> </button>
                    </form>
                    <a href="{{ route('equipments.history', $assignment->equipment->numero_de_serie) }}" class="btn btn-info"><i class="fas fa-history"></i></a> <!-- Corrected history link -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success" id="success-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1000; width: 50%; text-align: center; padding: 10px; border-radius: 5px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
        {{ session('success') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fade out success message after error message disappears
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => successMessage.remove(), 5000); // Remove success message after 5 seconds
            }
        });
    </script>
@endif

{{-- Error Messages --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@include('layouts.sidebar')

@endsection
