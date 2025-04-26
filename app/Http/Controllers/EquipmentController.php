<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Equipement;
use App\Models\Category;
use App\Models\Stock;
use App\Imports\EquipementsImport;
use Carbon\Carbon;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipement::all();
        return view('equipments.index', compact('equipments'));
    }

    public function store(Request $request)
    {
        // Convert dates before validation
        $date_acquisition = Carbon::createFromFormat('Y-m-d', $request->input('date_acquisition'))->format('Y-m-d');
        $date_de_mise_en_oeuvre = Carbon::createFromFormat('Y-m-d', $request->input('date_de_mise_en_oeuvre'))->format('Y-m-d');

        $request->merge([
            'date_acquisition' => $date_acquisition,
            'date_de_mise_en_oeuvre' => $date_de_mise_en_oeuvre,
        ]);

        // Check for duplicate
        if (Equipement::where('numero_de_serie', $request->numero_de_serie)->exists()) {
            return redirect()->route('equipments.index')->with('error', 'Cet équipement existe déjà.');
        }

        $request->validate([
            'numero_de_serie' => 'required|string|max:255|unique:equipements,numero_de_serie',
            'article' => 'required|string|max:255',
            'date_acquisition' => 'required|date',
            'date_de_mise_en_oeuvre' => 'nullable|date',
            'categorie' => 'nullable|string|max:255',
            'sous_categorie' => 'required|string|max:255',
            'matricule' => 'nullable|string|max:255',
        ]);

        Equipement::create($request->all());

        return redirect()->route('equipments.index')->with('success', 'Équipement ajouté avec succès.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls,txt'
        ]);

        $import = new EquipementsImport();
        Excel::import($import, $request->file('file'));

        $errors = $import->getErrors();
        $successCount = $import->getSuccessCount();

        $successMessage = "Importation réussie pour $successCount équipements.";

        if (!empty($errors)) {
            return redirect()->route('equipments.index')->withErrors($errors)->with('success', $successMessage);
        }

        return redirect()->route('equipments.index')->with('success', $successMessage);
    }

    public function update(Request $request, $numero_de_serie)
    {

        $equipment = Equipement::where('numero_de_serie', $numero_de_serie)->firstOrFail();

        // Validate the date format
    $validatedData = $request->validate([
        'date_acquisition' => 'required|date_format:Y-m-d',
        'date_de_mise_en_oeuvre' => 'required|date_format:Y-m-d',
    ]);
     // Convert date fields to Carbon instances if needed
    $equipment->date_acquisition = Carbon::parse($request->date_acquisition)->toDateString();
    $equipment->date_de_mise_en_oeuvre = Carbon::parse($request->date_de_mise_en_oeuvre)->toDateString();

    // Other fields
    $equipment->numero_de_serie = $request->numero_de_serie;
    $equipment->article = $request->article;
    $equipment->categorie = $request->categorie;
    $equipment->sous_categorie = $request->sous_categorie;
    $equipment->matricule = $request->matricule;


        $equipment->update($validatedData);

        return redirect()->route('equipments.index')->with('success', 'Équipement modifié avec succès.');
    }

    public function destroy($numero_de_serie)
    {
        \Log::info('Attempting to delete equipment with serial number: ' . $numero_de_serie);

        $equipment = Equipement::where('numero_de_serie', $numero_de_serie)->firstOrFail();
        \Log::info('Equipment found: ' . $equipment->numero_de_serie);

        // Block deletion if statut is "Affecté" or "En panne"
        if (in_array($equipment->statut, ['Affecté', 'En panne'])) {
            \Log::warning('Cannot delete equipment with statut: ' . $equipment->statut);
            return redirect()->route('equipments.index')
                ->with('error', 'Impossible de supprimer cet équipement car il est "' . $equipment->statut . '".');
        }

        // Update stock if "En stock"
        if ($equipment->statut === 'En stock' && $equipment->sous_categorie) {
            $stock = Stock::where('sous_categorie', $equipment->sous_categorie)->first();

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
        $searchTerm = $request->input('search');

        $query = Equipement::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('numero_de_serie', 'like', '%' . $searchTerm . '%')
                  ->orWhere('article', 'like', '%' . $searchTerm . '%')
                  ->orWhere('date_acquisition', 'like', '%' . $searchTerm . '%')
                  ->orWhere('date_de_mise_en_oeuvre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('categorie', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sous_categorie', 'like', '%' . $searchTerm . '%')
                  ->orWhere('matricule', 'like', '%' . $searchTerm . '%');
            });
        }

        $equipments = $query->get();
        $noResults = $equipments->isEmpty();

        return view('equipments.index', compact('equipments', 'noResults'));
    }
}
