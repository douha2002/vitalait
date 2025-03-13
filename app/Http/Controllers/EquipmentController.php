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
        'quantite' => 'required|integer|min:1',
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

    

    public function import(Request $request) // Removed unnecessary $numero_de_serie parameter
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls,txt'
        ]);


        
        Excel::import(new EquipementsImport, $request->file('file'));
        

        return redirect()->route('equipments.index')->with('success', 'Importation réussie!');
    }

    public function edit($numero_de_serie)
    {
        $equipment = Equipement::where('numero_de_serie', $numero_de_serie)->firstOrFail();
        return view('equipments.edit', compact('equipment'));
    }

    public function update(Request $request, $numero_de_serie)
    {
        $request->validate([
            'numero_de_serie' => 'required|string|unique:equipements,numero_de_serie,' . $numero_de_serie . ',numero_de_serie',
            'article' => 'nullable|string',
            'quantite' => 'nullable|integer',
            'date_acquisition' => 'nullable|date',
            'date_de_mise_en_oeuvre' => 'nullable|date',
            'categorie' => 'nullable|string',
            'sous_categorie' => 'nullable|string',
            'matricule' => 'nullable|string',
        ]);

        $equipment = Equipement::where('numero_de_serie', $numero_de_serie)->firstOrFail();

        // Debugging: Log request and equipment before update
        \Log::info('Request data:', $request->all());
        \Log::info('Equipment before update:', $equipment->toArray());

        // Update the equipment
        $equipment->update($request->all());

        // Debugging: Log after update
        \Log::info('Equipment after update:', $equipment->fresh()->toArray());

        return redirect()->route('equipments.index')->with('success', 'Équipement modifié avec succès.');
    }

    public function destroy($numero_de_serie)
    {
        $equipment = Equipement::where('numero_de_serie', $numero_de_serie)->firstOrFail();
        $equipment->delete();


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

        // Filter by Quantité
        if ($request->filled('quantite')) {
            $query->where('quantite', $request->quantite);
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

        // Filter by Matricule
        if ($request->filled('matricule')) {
            $query->where('matricule', 'like', '%' . $request->matricule . '%');
        }

        // Get the results
        $equipments = $query->get();
        $noResults = $equipments->isEmpty();

        // Get categories for the dropdown (if needed)
        $categories = Category::all();

        return view('equipments.search', compact('equipments', 'categories', 'noResults'));
    }
}