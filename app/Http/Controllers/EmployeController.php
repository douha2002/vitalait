<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Validation\Rule;
use App\Imports\EmployesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;


class EmployeController extends Controller
{
    public function index()
    {
        $employes = Employee::all();
        return view('employes.index', compact('employes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricule' => 'required|unique:employees,matricule',
            'nom' => 'required',
            'prenom' => 'required',
            'poste' => 'required',
            'service' => 'required',
            'email' => 'required|email',
        ]);

        Employee::create($request->all());
        return redirect()->route('employes.index')->with('success', 'Employé ajouté avec succès.');
    }

    public function update(Request $request, $matricule)
    {
        $request->validate([
            'matricule' => Rule::unique('employees', 'matricule')->ignore($matricule, 'matricule'),
            'nom' => 'required',
            'prenom' => 'required',
            'poste' => 'required',
            'service' => 'required',
            'email' => 'required|email',
        ]);
    
        $employe = Employee::where('matricule', $matricule)->firstOrFail();
        $employe->update($request->all());
    
        return redirect()->route('employes.index')->with('success', 'Employé modifié avec succès.');
    }
    

public function destroy($id)
{
    try {
        $employe = Employee::findOrFail($id);
        $employe->delete();

        return redirect()->route('employes.index')->with('success', 'Employé supprimé avec succès.');
    } catch (QueryException $e) {
        if ($e->getCode() == '23000') { // Foreign key constraint violation
            return redirect()->route('employes.index')->with('error', 'Impossible de supprimer cet employé car il est encore affecté à un équipement.');
        }

        return redirect()->route('employes.index')->with('error', 'Une erreur est survenue lors de la suppression.');
    }
}


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls,txt'
        ]);

        $import = new EmployesImport();
        Excel::import($import, $request->file('file'));

        $errors = $import->getErrors();
        $successCount = $import->getSuccessCount();
        $successMessage = "Importation réussie pour $successCount employés.";

        if (!empty($errors)) {
            return redirect()->route('employes.index')->withErrors($errors)->with('success', $successMessage);
        }

        return redirect()->route('employes.index')->with('success', $successMessage);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
    
        $query = Employee::query();
    
        if ($request->filled('search')) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('matricule', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('prenom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('poste', 'like', '%' . $searchTerm . '%')
                  ->orWhere('service', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%'); // ne pas oublier le point-virgule ici
            });
        }
    
        $employes = $query->get();
        $noResults = $employes->isEmpty();
    
        return view('employes.index', compact('employes', 'noResults'));
    }
 
}
