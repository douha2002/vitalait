@extends('layouts.app')

@section('content')

@include('partials.search')
<div class="container">
    <h2>Gestion des Affectations</h2>
    
    {{-- Formulaire de recherche --}}
    <form method="GET" action="{{ route('assignments.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher par matricule ou article..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>
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
                <td>{{ $assignment->employee->name ?? 'N/A' }}</td>
                <td>{{ $assignment->start_date }}</td>
                <td>{{ $assignment->end_date ?? 'En cours' }}</td>
                <td>
                    <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> </a>
                    <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> </button>
                        <a href="{{ route('equipments.history', $assignment->numero_de_serie) }}" class="btn btn-info"><i class="fas fa-history"></i></a>

                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success" id="success-message" style="
        position: fixed;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        width: 50%;
        text-align: center;
        padding: 10px;
        border-radius: 5px;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    ">
        {{ session('success') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fade out success message after error message disappears
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
                // If no error message, show success directly
                if (successMessage) {
                    setTimeout(() => successMessage.remove(), 5000); // Remove success message after 5 seconds
                }
            }
        });
    </script>
@endif

{{-- Error Messages --}}
@if($errors->any())
    <div class="alert alert-danger" id="error-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 50%; text-align: center; padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@include('layouts.sidebar')
@endsection