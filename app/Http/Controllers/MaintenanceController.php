<?php
namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Equipement;
use App\Models\Fournisseur;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Stock;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('equipement', 'fournisseur')->get();
        $equipements = Equipement::all();
        $fournisseurs = Fournisseur::all();
        return view('maintenances.index', compact('maintenances','equipements', 'fournisseurs'));
    }

   

    public function store(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'numero_de_serie' => 'required|exists:equipements,numero_de_serie',
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'date_debut' => 'required|date',
        'date_fin' => 'nullable|date|after:date_debut',
        'commentaires' => 'nullable|string',
    ]);

    // Find the equipment
    $equipement = Equipement::where('numero_de_serie', $validated['numero_de_serie'])->first();

    // Check if the equipment is currently assigned ("Affecté")
    if ($equipement->statut === 'Affecté') {
        return redirect()->route('maintenances.index')->with('error', 'Vous ne pouvez pas affecter cet équipement à une maintenance car il est actuellement affecté.');
    }

    // Create the maintenance record
    $maintenance = Maintenance::create([
        'numero_de_serie' => $validated['numero_de_serie'],
        'fournisseur_id' => $validated['fournisseur_id'] ?? null,
        'date_debut' => $validated['date_debut'],
        'date_fin' => $validated['date_fin'] ?? null,
        'commentaires' => $validated['commentaires'] ?? null,
    ]);

    // Send email to supplier if applicable
    if (!empty($validated['fournisseur_id'])) {
        $fournisseur = Fournisseur::find($validated['fournisseur_id']);
        if ($fournisseur) {
            Mail::raw("Salut, cet équipement {$equipement->numero_de_serie} nécessite une maintenance le {$validated['date_debut']}.",
                function ($message) use ($fournisseur) {
                    $message->to($fournisseur->email)
                        ->subject("Demande de Maintenance");
                });
        }
    }
     // Update the equipment status
    $equipment = Equipement::findOrFail($request->numero_de_serie);
    if ($request->date_fin) {
        $equipment->statut = 'Disponible';
    } else {
        $equipment->statut = 'En panne';
    }
    $equipment->save();

        // ✅ REMOVE from stock (update quantity)
        $stockItem = Stock::where('sous_categorie', $equipment->sous_categorie)->first();

        if ($stockItem) {
            $stockItem->quantite = max($stockItem->quantite - 1, 0);
            $stockItem->save();
        }

    return redirect()->route('maintenances.index')->with('success', 'Maintenance planifiée avec succès.');
}

    
   
    
public function update(Request $request, $id)
{
    // Validate the input
    $validated = $request->validate([
        'fournisseur_id' => 'nullable|exists:fournisseurs,id', 
        'date_debut' => 'required|date',
        'date_fin' => 'nullable|date|after:date_debut',
    ]);
    
    // Find the maintenance record
    $maintenance = Maintenance::findOrFail($id);

    // Find the related equipment
    $equipement = Equipement::where('numero_de_serie', $maintenance->numero_de_serie)->first();



    // Update maintenance record
    $maintenance->update([
        'fournisseur_id' => $validated['fournisseur_id'] ?? $maintenance->fournisseur_id,
        'date_debut' => $validated['date_debut'],
        'date_fin' => $validated['date_fin'] ?? $maintenance->date_fin,
    ]);
    // Update the equipment status
    $equipment = Equipement::findOrFail($maintenance->numero_de_serie);
    if ($request->date_fin) {
        $equipment->statut = 'Disponible';
    } else {
        $equipment->statut = 'En panne';
    }
    $equipment->save();

    return redirect()->route('maintenances.index')->with('success', 'Maintenance mise à jour avec succès.');
}
public function search(Request $request)
{
    $searchTerm = $request->input('search');

    $maintenances = Maintenance::with(['equipement', 'fournisseur'])
        ->when($searchTerm, function ($query) use ($searchTerm) {
            $query->whereHas('fournisseur', function ($q) use ($searchTerm) {
                $q->where('nom', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('equipement', function ($q) use ($searchTerm) {
                $q->where('numero_de_serie', 'like', '%' . $searchTerm . '%');
            });
        })
        ->get();

    $noResults = $maintenances->isEmpty();

    $equipements = Equipement::all();
    $fournisseurs = Fournisseur::all();

    return view('maintenances.index', compact('maintenances', 'equipements', 'fournisseurs', 'noResults'));
}

}