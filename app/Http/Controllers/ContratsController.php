<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrat;
use App\Models\Equipement;
use App\Models\Fournisseur;
use App\Imports\ContratsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ContratsController extends Controller
{
    public function index()
    {
        $contrats = Contrat::with('fournisseur')->get(); // très important !
        $equipements = Equipement::all();
        $fournisseurs = Fournisseur::all();
    
        return view('contrats.index', compact('contrats', 'equipements', 'fournisseurs'));
    }
    


public function store(Request $request)
{
    $request->validate([
        'numero_de_serie' => 'required|exists:equipements,numero_de_serie',
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',
    ]);

    // Check if there's already an active contract for this equipment
    $existingContrat = Contrat::where('numero_de_serie', $request->numero_de_serie)
        ->orderByDesc('date_fin') // Optional: get the latest one if many
        ->first();

    if ($existingContrat && Carbon::parse($existingContrat->date_fin)->isFuture()) {
        return redirect()->route('contrats.index')->with('error', 'Cet équipement a déjà un contrat actif.');
    }

    // Otherwise, create the new contract
    Contrat::create($request->all());

    return redirect()->route('contrats.index')->with('success', 'Contrat ajouté avec succès.');
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'numero_de_serie' => 'required|exists:equipements,numero_de_serie',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',

        ]);

        $contrat = Contrat::findOrFail($id);
        $contrat->update($request->all());

        return redirect()->route('contrats.index')->with('success', 'Contrat modifié avec succès.');
    }
    public function destroy($id)
    {
        $contrat = Contrat::findOrFail($id);
        $contrat->delete();

        return redirect()->route('contrats.index')->with('success', 'Contrat supprimé avec succès.');
    }
   
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls,txt'
        ]);
    
        $import = new ContratsImport();
        Excel::import($import, $request->file('file'));
    
        $errors = $import->getErrors();
        $successCount = $import->getSuccessCount();
        $successMessage = "Importation réussie pour $successCount contrat(s).";
    
        return redirect()->route('contrats.index')
            ->withErrors($errors)
            ->with('success', $successMessage);
    }
    
    public function search(Request $request)
{
    $searchTerm = $request->input('search');

    $query = Contrat::with('fournisseur'); // Eager load the relation

    if (!empty($searchTerm)) {
        $query->where(function ($q) use ($searchTerm) {
            $q->where('numero_de_serie', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('fournisseur', function ($subQuery) use ($searchTerm) {
                  $subQuery->where('nom', 'like', '%' . $searchTerm . '%');
              });
        });
    }

    $contrats = $query->get();
    $equipements = Equipement::all(); // Required for the view
    $fournisseurs = Fournisseur::all(); // Required for the view
    $noResults = $contrats->isEmpty();

    return view('contrats.index', compact('contrats', 'equipements', 'fournisseurs', 'noResults'));
}


   
}
