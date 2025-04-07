<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Equipement; // Ensure consistent model name
use App\Imports\EquipementsImport;
use App\Models\Category; // Import the Category model if needed

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipement::all(); // Get all equipment
        return view('equipments.index', compact('equipments')); // Pass data to view
    }

    
    public function store(Request $request)
{
    // Validate input data
    $request->validate([
        'numero_de_serie' => 'required|string|max:255|unique:equipements,numero_de_serie',
        'article' => 'required|string|max:255',
        'date_acquisition' => 'required|date',
        'date_de_mise_en_oeuvre' => 'nullable|date',
        'categorie' => 'nullable|string|max:255',
        'sous_categorie' => 'nullable|string|max:255',
        'matricule' => 'nullable|string|max:255',
    ]);
    // Store data in database
    Equipement::create($request->all());

    // Redirect back with success message
    return redirect()->route('equipments.index')->with('success', 'Équipement ajouté avec succès.');
}

    

public function import(Request $request)
{
    // Validate the uploaded file
    $request->validate([
        'file' => 'required|mimes:csv,xlsx,xls,txt'
    ]);

    // Create an instance of the import class
    $import = new EquipementsImport();

    // Perform the import
    Excel::import($import, $request->file('file'));

    // Get errors and success count from the import class
    $errors = $import->getErrors();
    $successCount = $import->getSuccessCount();  // Get the number of successful imports

    // Store the success message
    $successMessage = "Importation réussie pour $successCount équipements.";

    // Store the error messages (if any)
    if (!empty($errors)) {
        // Pass errors as a validation error bag
        return redirect()->route('equipments.index')->withErrors($errors)->with('success', $successMessage);
    }

    // Flash the success message if there are no errors
    return redirect()->route('equipments.index')->with('success', $successMessage);
}


    public function update(Request $request, $numero_de_serie)
    {
        $equipment = Equipement::where('numero_de_serie', $numero_de_serie)->firstOrFail();
    
        $validatedData = $request->validate([
            'numero_de_serie' => 'required|string|unique:equipements,numero_de_serie,' . $equipment->numero_de_serie . ',numero_de_serie',
            'article' => 'nullable|string|max:255',
            'date_acquisition' => 'nullable|date',
            'date_de_mise_en_oeuvre' => 'nullable|date',
            'categorie' => 'nullable|string|max:255',
            'sous_categorie' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:255',
        ]);
    
        $equipment->update($validatedData);
    
        return redirect()->route('equipments.index')->with('success', 'Équipement modifié avec succès.');
    }
    
    

    public function destroy($numero_de_serie)
{
    \Log::info('Attempting to delete equipment with serial number: ' . $numero_de_serie);

    $equipment = Equipement::where('numero_de_serie', $numero_de_serie)->firstOrFail();
    \Log::info('Equipment found: ' . $equipment->numero_de_serie);

    // ❌ Block deletion if statut is "Affecté" or "En panne"
    if (in_array($equipment->statut, ['Affecté', 'En panne'])) {
        \Log::warning('Cannot delete equipment with statut: ' . $equipment->statut);
        return redirect()->route('equipments.index')
            ->with('error', 'Impossible de supprimer cet équipement car il est "' . $equipment->statut . '".');
    }

    // ✅ Update stock if statut is "En stock"
    if ($equipment->statut === 'En stock' && $equipment->sous_categorie) {
        $stock = \App\Models\Stock::where('sous_categorie', $equipment->sous_categorie)->first();

        if ($stock) {
            $stock->quantite = max(0, $stock->quantite - 1);
            if ($stock->quantite === 0) {
                $stock->delete();
            } else {
                $stock->save();
            }
        }
    }

    $equipment->delete();
    \Log::info('Equipment deleted successfully.');

    return redirect()->route('equipments.index')->with('success', 'Équipement supprimé.');
}



    public function search(Request $request)
    {
        $query = Equipement::query();

        // Filter by Numéro de Série
        if ($request->filled('numero_de_serie')) {
            $query->where('numero_de_serie', 'like', '%' . $request->numero_de_serie . '%');
        }

        // Filter by Article
        if ($request->filled('article')) {
            $query->where('article', 'like', '%' . $request->article . '%');
        }



        // Filter by Date d'Acquisition
        if ($request->filled('date_acquisition')) {
            $query->whereDate('date_acquisition', $request->date_acquisition);
        }

        // Filter by Date de Mise en Oeuvre
        if ($request->filled('date_de_mise_en_oeuvre')) {
            $query->whereDate('date_de_mise_en_oeuvre', $request->date_de_mise_en_oeuvre);
        }

        // Filter by Catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Filter by Sous Catégorie
        if ($request->filled('sous_categorie')) {
            $query->where('sous_categorie', 'like', '%' . $request->sous_categorie . '%');
        }
         // Filter by Sous Catégorie
         if ($request->filled('categorie')) {
            $query->where('categorie', 'like', '%' . $request->sous_categorie . '%');
        }

        // Filter by Matricule
        if ($request->filled('matricule')) {
            $query->where('matricule', 'like', '%' . $request->matricule . '%');
        }

        // Get the results
        $equipments = $query->get();
        $noResults = $equipments->isEmpty();

        // Get categories for the dropdown (if needed)
        $categories = Category::all();

        return view('equipments.index', compact('equipments', 'categories', 'noResults'));
    }
    
}