<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Equipement; // Correctly importing Equipement model
use App\Models\Employee;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{ 
    // List all assignments with optional search
    public function index(Request $request)
    {
        // Get assignments, eager load equipment and employee relations
        $assignments = Assignment::with(['equipment', 'employee'])
            ->when($request->search, function ($query) use ($request) {
                return $query->where('numero_de_serie', 'like', '%' . $request->search . '%')
                             ->orWhere('employees_id', 'like', '%' . $request->search . '%');
            })
            ->get();
    
        // Fetch all equipment and employees
        $equipments = Equipement::all();
        $employees = Employee::all();
    
        // Pass assignments, equipments, and employees to the view
        return view('assignments.index', compact('assignments', 'equipments', 'employees'));
    }
    
    // Show the form to create a new assignment
    public function create()
    {
        // Fetch all equipment and employees
        $equipments = Equipement::all();
        $employees = Employee::all();

        // Pass them to the view
        return view('assignments.create', compact('equipments', 'employees'));
    }

    // Store the new assignment
    public function store(Request $request)
    {
        $request->validate([
            'numero_de_serie' => 'required|exists:equipements,numero_de_serie',
            'employees_id' => 'required|exists:employees,matricule',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);
    
        // Create the assignment
        Assignment::create([
            'numero_de_serie' => $request->numero_de_serie,
            'employees_id' => $request->employees_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? null, // Null if empty
        ]);
    
        // Update the equipment status
        $equipment = Equipement::findOrFail($request->numero_de_serie);
        if ($request->end_date) {
            $equipment->statut = 'En cours';
        } else {
            $equipment->statut = 'Affecté';
        }
        $equipment->save();
    
        return redirect()->route('assignments.index')->with('success', 'Équipement affecté avec succès.');
    }
    

    // Show the form to edit an assignment
    public function edit($id)
    {
        // Get the assignment you want to edit
        $assignment = Assignment::findOrFail($id);
    
        // Get all the equipments and employees
        $equipments = Equipement::all();
        $employees = Employee::all(); 
    
        // Return the view with the assignment data
        return view('assignments.edit', compact('assignment', 'equipments', 'employees'));
    }
    

    // Update the assignment
    public function update(Request $request, Assignment $assignment)
{
    $request->validate([
        'employees_id' => 'required|exists:employees,matricule',
        'start_date' => 'required|date|before_or_equal:end_date',
        'end_date' => 'nullable|date|after_or_equal:start_date', // Ensure end date is valid
    ]);

    // Check if employee is being changed
    $employeeChanged = $assignment->employees_id !== $request->employees_id;

    if ($employeeChanged) {
        // End the current assignment with the provided end_date
        $assignment->update(['end_date' => $request->end_date]);

        // Create a new assignment for the new employee
        Assignment::create([
            'numero_de_serie' => $assignment->numero_de_serie,
            'employees_id' => $request->employees_id,
            'start_date' => $request->end_date, // New start date = old assignment's end date
            'end_date' => null, // Open-ended new assignment
        ]);
    } else {
        // Simply update the end date if the employee remains the same
        $assignment->update(['end_date' => $request->end_date]);
    }

    // Update the equipment status
    $equipment = Equipement::findOrFail($assignment->numero_de_serie);
    if ($request->end_date) {
        $equipment->statut = 'En cours';
    } else {
        $equipment->statut = 'Affecté';
    }
    $equipment->save();

    return redirect()->route('assignments.index')->with('success', 'Affectation modifiée avec succès.');
}
    
     // In AssignmentController.php

     public function destroy(Assignment $assignment)
{
    if ($assignment->end_date === null) {
        return redirect()->route('assignments.index')
                         ->withErrors('Impossible de supprimer cette affectation, elle est toujours en cours.');
    }

    $assignment->delete(); // Soft delete

    // Check if the equipment has any other active assignments
    $hasActiveAssignments = Assignment::where('numero_de_serie', $equipement->numero_de_serie)
        ->whereNull('end_date') // Check for open-ended assignments
        ->exists();

    // If the equipment has no active assignments, update its status to "En cours"
    if (!$hasActiveAssignments) {
        $equipment->statut = 'En cours';
        $equipment->save();
    }

    return redirect()->route('assignments.index')
                     ->with('success', 'Affectation archivée avec succès.');
}

     
public function showHistory($numero_de_serie)
{
    // Retrieve the equipment by its serial number and its assignment history
    $equipment = Equipement::with(['assignments' => function($query) {
        $query->withTrashed(); // Include soft-deleted assignments
    }])->where('numero_de_serie', $numero_de_serie)->first();

    // If equipment not found, return error
    if (!$equipment) {
        return response()->json(['error' => 'Équipement introuvable.'], 404);
    }

    // Return the partial view for the assignment history
    return view('assignments.history', compact('equipment'));
}


     


    
}
