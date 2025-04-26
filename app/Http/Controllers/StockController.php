<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipement;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $equipement = Equipement::all();

        // Vérification de sécurité si la table est vide
        $sousCategorie = $equipement->isNotEmpty() ? $equipement->first()->sous_categorie : null;

        // Stock groupé par sous_categorie
        $stocks = Stock::select('sous_categorie', 
            DB::raw('SUM(quantite) as quantite'),
            DB::raw('MAX(seuil_min) as seuil_min')
            )
            ->groupBy('sous_categorie')
            ->get();

        return view('stock.index', compact('stocks', 'equipement', 'sousCategorie'));
    }

    public function getAvailableEquipments()
    {
        $equipements = Equipement::where('statut', 'Disponible')->get();
        return response()->json($equipements);
    }

    public function addToStock(Request $request)
{
    $request->validate([
        'equipements' => 'required|json',
        'seuil_min' => 'required|json',
    ]);

    // Decode the JSON data
    $equipements = json_decode($request->equipements, true);
    $seuilMinValues = json_decode($request->seuil_min, true);

    DB::beginTransaction();
    try {
        // Get the selected available equipment
        $equipements = Equipement::whereIn('numero_de_serie', $equipements)
            ->where('statut', 'Disponible')
            ->get();

        if ($equipements->isEmpty()) {
            return response()->json(['error' => 'Aucun équipement disponible trouvé.'], 400);
        }

        // Group the equipments by their sous_categorie
        $groupedEquipments = [];
        foreach ($equipements as $equipement) {
            $sousCategorie = $equipement->sous_categorie;
            
            if (!isset($groupedEquipments[$sousCategorie])) {
                $groupedEquipments[$sousCategorie] = 0;
            }
            $groupedEquipments[$sousCategorie]++;

            // Update the equipment status and seuil_min
            $equipement->update([
                'statut' => 'En stock',
                'seuil_min' => $seuilMinValues[$equipement->numero_de_serie] ?? 0,
            ]);
        }

        // Update the stock for each sous_categorie
        foreach ($groupedEquipments as $sousCategorie => $quantite) {
            Stock::updateOrCreate(
                [   'article' => $equipements->firstWhere('sous_categorie', $sousCategorie)->article,
                    'sous_categorie' => $sousCategorie],
                [
                    'quantite' => DB::raw("quantite + $quantite"),
                    'seuil_min' => $seuilMinValues[$sousCategorie] ?? 0
                ]
            );
        }

        DB::commit();
        return response()->json(['success' => 'Stock mis à jour avec succès.']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Erreur lors de la mise à jour du stock: ' . $e->getMessage()], 500);
    }
}

    public function updateSeuil(Request $request, $sous_categorie)
    {
        $request->validate([
            'seuil_min' => 'required|integer|min:0',
        ]);
    
        // Mise à jour de tous les stocks de la même sous-catégorie
        $updated = \App\Models\Stock::where('sous_categorie', $sous_categorie)
            ->update(['seuil_min' => $request->seuil_min]);
    
        if ($updated) {
            return redirect()->back()->with('success', 'Seuil minimum mis à jour avec succès pour la sous-catégorie : ' . $sous_categorie);
        } else {
            return redirect()->back()->with('error', 'Aucun équipement trouvé pour cette sous-catégorie.');
        }
    }
    
    public function search(Request $request)
    {
        $query = Stock::query();

        if ($request->filled('sous_categorie')) {
            $query->where('sous_categorie', 'like', '%' . $request->sous_categorie . '%');
        }

        $stocks = $query->get();
        $noResults = $stocks->isEmpty();
        $equipements = Equipement::all();

        return view('stock.index', compact('stocks', 'equipements', 'noResults'));
    }
}
