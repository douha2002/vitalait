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
        // Get grouped stock items
        $stocks = Stock::select('sous_categorie', DB::raw('SUM(quantite) as quantite'))
            ->groupBy('sous_categorie')
            ->get();

        return view('stock.index', compact('stocks'));
    }

    public function getAvailableEquipments()
    {
        // Fetch all "Disponible" equipment
        $equipements = Equipement::where('statut', 'Disponible')->get();
        return response()->json($equipements);
    }

    public function addToStock(Request $request)
{
    $request->validate([
        'equipements' => 'required|array',
    ]);

    DB::beginTransaction();
    try {
        // Fetch all valid equipements in one query
        $equipements = Equipement::whereIn('numero_de_serie', $request->equipements)
            ->where('statut', 'Disponible')
            ->get();

        if ($equipements->isEmpty()) {
            return response()->json(['error' => 'Aucun équipement disponible trouvé.'], 400);
        }

        $groupedEquipments = [];

        foreach ($equipements as $equipement) {
            $sousCategorie = $equipement->sous_categorie;

            // Count selected equipment by sous_categorie
            if (!isset($groupedEquipments[$sousCategorie])) {
                $groupedEquipments[$sousCategorie] = 0;
            }
            $groupedEquipments[$sousCategorie]++;

            // Update status in equipements table
            $equipement->update(['statut' => 'En stock']);
        }

        // Update stock quantities efficiently
        foreach ($groupedEquipments as $sousCategorie => $quantite) {
            Stock::updateOrCreate(
                ['sous_categorie' => $sousCategorie],
                ['quantite' => DB::raw("quantite + $quantite")] // Alternative: Use increment
            );
        }

        DB::commit();
        return response()->json(['success' => 'Stock mis à jour avec succès.']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Erreur lors de la mise à jour du stock.'], 500);
    }
}



public function search(Request $request)
{
    $query = Stock::query();

    // Filter by Sous Catégorie
    if ($request->filled('sous_categorie')) {
        $query->where('sous_categorie', 'like', '%' . $request->sous_categorie . '%');
    }

    // Get the results
    $stocks = $query->get();
    $noResults = $stocks->isEmpty();

    // Retrieve all équipements to ensure they're available in the view
    $equipements = Equipement::all();

    return view('stock.index', compact('stocks', 'equipements', 'noResults'));
}

}