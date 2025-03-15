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
    
        Assignment::create([
            'numero_de_serie' => $request->numero_de_serie,
            'employees_id' => $request->employees_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? null, // Null if empty
        ]);
    
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
    
        return redirect()->route('assignments.index')->with('success', 'Affectation modifiée avec succès.');
    }
    
     // In AssignmentController.php

     public function destroy(Assignment $assignment)
     {
         // Check if the end_date is null (the assignment is still ongoing)
         if ($assignment->end_date === null) {
             // Return with an error message if the assignment is still ongoing
             return redirect()->route('assignments.index')
                              ->withErrors('Impossible de supprimer cette affectation, elle est toujours en cours.');
         }
     
         // Mark the assignment as ended by setting the end_date to the current date
         $assignment->update(['end_date' => now()]);
     
         // Redirect to the assignment index page with a success message
         return redirect()->route('assignments.index')
                          ->with('success', 'Affectation supprimée avec succès.');
     }
     
     
     public function show($numero_de_serie)
     {
         // Get the equipment by numero_de_serie and eager load its related assignments (including soft-deleted ones)
         $equipment = Equipement::with(['assignments' => function($query) {
             $query->withTrashed(); // Include soft-deleted assignments
         }])->where('numero_de_serie', $numero_de_serie)->first();
     
         // Handle the case when no equipment is found
         if (!$equipment) {
             return redirect()->route('assignments.index')->withErrors('Équipement introuvable.');
         }
     
         // Return the view with the equipment and its assignments
         return view('assignments.history', compact('equipment'));
     }
     


    
}
