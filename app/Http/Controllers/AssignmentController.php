<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Equipement;
use App\Models\Employee;

class AssignmentController extends Controller
{
    public function index()
{
    $assignments = Assignment::with(['equipment', 'employee'])->get();
     // Recherche par équipement (matricule ou article)
     if ($request->has('search')) {
        $search = $request->input('search');
        $query->whereHas('equipment', function ($q) use ($search) {
            $q->where('numero_de_serie', 'like', "%$search%")
              ->orWhere('matricule', 'like', "%$search%");
        });
    }

    $assignments = $query->get();

    return view('assignments.index', compact('assignments'));
}
    

    public function create()
{
    // Fetch all equipment to see if they exist
    $equipments = Equipement::all();

    // Fetch all employees
    $employees = Employee::all();

    

    // Pass to view
    return view('assignments.create', compact('equipments', 'employees'));
}


    

public function store(Request $request)
{
    $request->validate([
        'numero_de_serie' => 'required|exists:equipements,numero_de_serie',
        'employees_id' => 'required|exists:employees,id',
    ]);

    Assignment::create([
        'numero_de_serie' => $request->numero_de_serie,
        'employees_id' => $request->employees_id,
        'start_date' => now(),
    ]);

    return redirect()->route('assignments.index')->with('success', 'Equipement affecté avec succès.');
}

public function edit(Assignment $assignment)
{
    // Récupérer tous les équipements disponibles
    $equipments = Equipement::all();

    // Récupérer tous les employés disponibles
    $employees = Employee::all();

    // Passer les variables à la vue
    return view('assignments.edit', compact('assignment', 'equipments', 'employees'));
}

public function update(Request $request, Assignment $assignment)
{
    // Validation des données
    $request->validate([
        'numero_de_serie' => 'required|exists:equipements,numero_de_serie',
        'employees_id' => 'required|exists:employees,id',
        'start_date' => 'required|date',
    ]);

    // Vérifier si l'employé a changé
    if ($assignment->employees_id != $request->employees_id) {
        // Mettre à jour la date de fin de l'ancienne affectation
        $assignment->update(['end_date' => $request->start_date]);

        // Créer une nouvelle affectation pour le nouvel employé
        Assignment::create([
            'numero_de_serie' => $request->numero_de_serie,
            'employees_id' => $request->employees_id,
            'start_date' => $request->start_date,
        ]);
    } else {
        // Si l'employé n'a pas changé, mettre à jour simplement la date de début
        $assignment->update(['start_date' => $request->start_date]);
    }

    return redirect()->route('assignments.index')->with('success', 'Affectation mise à jour avec succès.');
}
    
    public function destroy(Assignment $assignment)
    {
        $assignment->update(['end_date' => now()]);
        return redirect()->route('assignments.index')->with('success', 'Affectation supprimée avec succès.');
    }

    public function show($numero_de_serie)
    {
        // Récupérer l'équipement avec ses affectations
        $equipment = Equipement::with('assignments.employee')->findOrFail($numero_de_serie);
    
        // Passer les données à la vue
        return view('assignments.history', compact('equipment'));
    }
    
}

