<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Equipement; // Correctly importing Equipement model
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{ 
    // List all assignments with optional search
    public function index(Request $request)
    {
        $assignments = Assignment::with(['equipment', 'employee'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('nom', 'like', '%' . $request->search . '%');
                })
                ->orWhere('numero_de_serie', 'like', '%' . $request->search . '%');
            })
            ->get();
    
        $equipments = Equipement::all();
        $employees = Employee::all();
    
        return view('assignments.index', compact('assignments', 'equipments', 'employees'));
    }
    
    

    // Store the new assignment
    public function store(Request $request)
    {
        $request->validate([
            'numero_de_serie' => 'required|exists:equipements,numero_de_serie',
            'employees_id' => 'required|exists:employees,matricule',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
        ]);
          // Update the equipment status
          $equipment = Equipement::findOrFail($request->numero_de_serie);

    $existingAssignment = Assignment::where('numero_de_serie', $equipment->numero_de_serie)
                                    ->whereNull('date_fin')  // Check if the assignment is still ongoing
                                    ->first();

    // If there is an active assignment, return with an error message
    if ($existingAssignment) {
        return redirect()->route('assignments.index')->withErrors([
            'error' => 'Cet équipement est déjà affecté.',
        ]);
    }


          // Prevent assignment if equipment is "En panne"
      if ($equipment->statut === 'En panne') {
          return redirect()->route('assignments.index')->withErrors([
              'error' => 'Vous ne pouvez pas affecter cet équipement car il est en panne.',
          ]);
      }
    
        // Create the assignment
        Assignment::create([
            'numero_de_serie' => $request->numero_de_serie,
            'employees_id' => $request->employees_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin ?? null, // Null if empty
        ]);
    
      
        if ($request->date_fin) {
            $equipment->statut = 'Disponible';
        } else {
            $equipment->statut = 'Affecté';
        }
        $equipment->save();

    // ✅ REMOVE from stock (update quantity)
    $stockItem = Stock::where('sous_categorie', $equipment->sous_categorie)->first();

    if ($stockItem) {
        $stockItem->quantite = max($stockItem->quantite - 1, 0);
        $stockItem->save();
    }
    
    
        return redirect()->route('assignments.index')->with('success', 'Équipement affecté avec succès.');
    }
  
    public function update(Request $request, Assignment $assignment)
{
    $request->validate([
        'employees_id' => 'required|exists:employees,matricule',
        'date_debut' => 'required|date|before_or_equal:date_fin',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
    ]);

    // Get the equipment
    $equipment = Equipement::findOrFail($assignment->numero_de_serie);

    

    // Check if employee is being changed
    $employeeChanged = $assignment->employees_id !== $request->employees_id;

    if ($employeeChanged) {
        // End the current assignment with the provided end_date
        $assignment->update(['date_fin' => $request->date_fin]);

        // Create a new assignment for the new employee
        Assignment::create([
            'numero_de_serie' => $assignment->numero_de_serie,
            'employees_id' => $request->employees_id,
            'date_debut' => $request->date_fin, // New start date = old assignment's end date
            'date_fin' => null, // Open-ended new assignment
        ]);
    } else {
        // Simply update the end date if the employee remains the same
        $assignment->update(['date_fin' => $request->date_fin]);
    }

    // Update the equipment status
    if ($request->date_fin) {
        $equipment->statut = 'Disponible';
    } else {
        $equipment->statut = 'Affecté';
    }
    $equipment->save();

    return redirect()->route('assignments.index')->with('success', 'Affectation modifiée avec succès.');
}

    

public function destroy(Assignment $assignment)
{
    // Check if the assignment is still ongoing
    if ($assignment->date_fin === null) {
        return redirect()->route('assignments.index')
                         ->withErrors('Impossible de supprimer cette affectation, elle est toujours en cours.');
    }

    // Get the equipment associated with the assignment
    $equipment = Equipement::findOrFail($assignment->numero_de_serie);

    // Soft delete the assignment
    $assignment->delete();

    // Check if the equipment has any other active assignments
    $hasActiveAssignments = Assignment::where('numero_de_serie', $equipment->numero_de_serie)
                                      ->whereNull('date_fin') // Check for open-ended assignments
                                      ->exists();

    // If the equipment has no active assignments, update its status to "Disponible"
    if (!$hasActiveAssignments) {
        $equipment->statut = 'Disponible';
        $equipment->save();
    }

    return redirect()->route('assignments.index')
                     ->with('success', 'Affectation archivée avec succès.');
}


public function affecter(Request $request)
{
    // Find the equipment by its unique 'numero_de_serie'
    $equipment = Equipement::findOrFail($request->numero_de_serie);

    // Check if the equipment status is 'En panne'
    if ($equipment->statut === 'En panne') {
        // If the status is 'En panne', return with a warning message
        return redirect()->route('assignments.index')->with('error', 'Attention: Vous ne pouvez pas affecter cet équipement car il est en panne.');
    }

    // If not 'En panne', proceed with the assignment logic
    $assignment = new Assignment();
    $assignment->numero_de_serie = $equipment->numero_de_serie;
    $assignment->employees_id = $request->employees_id;
    $assignment->date_debut = now(); // Set the current date as the start date
    $assignment->date_fin = null; // The assignment doesn't have an end date yet
    $assignment->save();

    // Update the equipment status to 'Affecté' since it has been assigned
    $equipment->statut = 'Affecté';
    $equipment->save();

    return redirect()->route('assignments.index')->with('success', 'L\'équipement a été affecté avec succès.');
}
public function search(Request $request)
{
    $query = Assignment::query();

    // Filter by Numéro de Série
    if ($request->filled('search')) {
        $query->whereHas('equipment', function ($q) use ($request) {
            $q->where('numero_de_serie', 'like', '%' . $request->search . '%');
        })
        ->orWhereHas('employee', function ($q) use ($request) {
            $q->where('nom', 'like', '%' . $request->search . '%');
        });
    }

    // Get the results
    $assignments = $query->get();
    $noResults = $assignments->isEmpty();

    // Retrieve all equipments to ensure they're available in the view
    $equipments = Equipement::all();  // Corrected variable name here
    $employees = Employee::all();

    return view('assignments.index', compact('assignments', 'equipments', 'employees', 'noResults'));  // Corrected variable name here
}
public function softDelete($id)
{
    $assignment = Assignment::findOrFail($id);

     // Check if the assignment is still ongoing
     if ($assignment->date_fin === null) {
        return redirect()->route('assignments.index')
                         ->withErrors('Impossible de supprimer cette affectation, elle est toujours en cours.');
    }
    $assignment->delete(); // Soft delete
    return back()->with('success', 'Affectation supprimée avec succès.');
}

public function restore($id)
{
    $assignment = Assignment::onlyTrashed()->findOrFail($id);
    $assignment->restore(); // Restore the soft deleted record
    return back()->with('success', 'Affectation restaurée avec succès.');
}


public function getAssignmentsByMonth(Request $request)
{
    $year = $request->input('year', date('Y')); // Default to the current year if not specified

    $data = DB::table('assignments')
        ->join('equipements', 'assignments.numero_de_serie', '=', 'equipements.numero_de_serie')
        ->whereYear('assignments.date_debut', $year)
        ->selectRaw('
            sous_categorie,
            MONTH(date_debut) as mois,
            COUNT(*) as total
        ')
        ->groupBy('sous_categorie', 'mois')
        ->get();

    // Initialiser tableau par mois de 1 à 12
    $labels = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    $sousCategories = $data->pluck('sous_categorie')->unique();
    $datasets = [];

    foreach ($sousCategories as $categorie) {
        $dataset = [
            'label' => $categorie,
            'data' => array_fill(0, 12, 0),
            'backgroundColor' => '#' . substr(md5($categorie), 0, 6),
        ];

        foreach ($data as $entry) {
            if ($entry->sous_categorie === $categorie) {
                $dataset['data'][$entry->mois - 1] = $entry->total;
            }
        }

        $datasets[] = $dataset;
    }

    return response()->json([
        'labels' => $labels,
        'datasets' => $datasets,
    ]);
}

public function employeeAssignmentPercentage()
{
    $totalEmployees = Employee::count(); // Get the total number of employees
    $assignedEmployees = Employee::whereHas('equipments')->count(); // Get the number of employees with assigned equipment

    $percentage = $totalEmployees ? ($assignedEmployees / $totalEmployees) * 100 : 0;

    return response()->json([
        'assigned' => $assignedEmployees,
        'total' => $totalEmployees,
        'percentage' => $percentage
    ]);
}








}
