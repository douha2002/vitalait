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
        return view('maintenances.index', compact('maintenances', 'equipements', 'fournisseurs'));
    }

    public function store(Request $request)
{
    

    // Validation
    $validated = $request->validate([
        'numero_de_serie' => 'required|exists:equipements,numero_de_serie',
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'date_panne' => 'required|date',
        'date_affectation' => 'nullable|date|after_or_equal:date_panne',
        'date_reception' => 'nullable|date|after:date_panne',
        'commentaires' => 'nullable|string',
    ]);

    // Récupérer l’équipement
    $equipement = Equipement::where('numero_de_serie', $validated['numero_de_serie'])->firstOrFail();

    // Vérifier que l’équipement n’est pas affecté
    if ($equipement->statut === 'Affecté') {
        return redirect()->route('maintenances.index')->with('error', 'Cet équipement est actuellement affecté.');
    }

    // Vérifier s’il était en stock
    $wasInStock = false;
    $stockItem = Stock::where('sous_categorie', $equipement->sous_categorie)->first();
    if ($equipement->statut === 'En stock' && $stockItem && $stockItem->quantite > 0) {
        $wasInStock = true;
    }

    // Créer la maintenance
    $maintenance = Maintenance::create([
        'numero_de_serie' => $validated['numero_de_serie'],
        'fournisseur_id' => $validated['fournisseur_id'],
        'date_panne' => $validated['date_panne'],
        'date_affectation' => $validated['date_affectation'] ?? null,
        'date_reception' => $validated['date_reception'] ?? null,
        'commentaires' => $validated['commentaires'] ?? null,
    ]);

    // Envoyer un mail au fournisseur
    $fournisseur = Fournisseur::find($validated['fournisseur_id']);
    if ($fournisseur && $fournisseur->email) {
        Mail::raw(
            "Salut, cet équipement {$equipement->numero_de_serie} nécessite une maintenance le {$validated['date_panne']}.",
            function ($message) use ($fournisseur) {
                $message->to($fournisseur->email)
                        ->subject("Demande de maintenance");
            }
        );
    }

    // Mettre à jour le statut de l’équipement
    if (!empty($validated['date_panne']) && empty($validated['date_affectation']) && empty($validated['date_reception'])) {
        $equipement->statut = 'En panne en stock'; // Pas encore expédié
    } elseif (!empty($validated['date_affectation']) && empty($validated['date_reception'])) {
        $equipement->statut = 'En panne'; // En réparation
    } elseif (!empty($validated['date_reception'])) {
        $equipement->statut = 'En stock'; // Maintenance terminée
    }

    $equipement->save();

   

    return redirect()->route('maintenances.index')->with('success', 'Maintenance planifiée avec succès.');
}


public function update(Request $request, $id)
{
    $validated = $request->validate([
        'fournisseur_id' => 'nullable|exists:fournisseurs,id',
        'date_panne' => 'required|date',
        'date_affectation' => 'nullable|date|after_or_equal:date_panne',
        'date_reception' => 'nullable|date|after:date_panne',
    ]);

    $maintenance = Maintenance::findOrFail($id);
    $equipement = Equipement::where('numero_de_serie', $maintenance->numero_de_serie)->firstOrFail();
    $stockItem = Stock::where('sous_categorie', $equipement->sous_categorie)->first();

    // Sauvegarder les anciennes dates pour détecter les changements
    $ancienneDateAffectation = $maintenance->date_affectation;
    $ancienneDateReception = $maintenance->date_reception;

    // Mise à jour de la maintenance
    $maintenance->update([
        'fournisseur_id' => $validated['fournisseur_id'] ?? $maintenance->fournisseur_id,
        'date_panne' => $validated['date_panne'],
        'date_affectation' => $validated['date_affectation'] ?? $maintenance->date_affectation,
        'date_reception' => $validated['date_reception'] ?? $maintenance->date_reception,
    ]);

    // Gestion du statut et des quantités
    if ($maintenance->date_panne && !$maintenance->date_affectation && !$maintenance->date_reception) {
        $equipement->statut = 'En panne en stock';
        // Pas de changement de quantité
    }

    if ($maintenance->date_affectation && !$ancienneDateAffectation && !$maintenance->date_reception) {
        $equipement->statut = 'En panne';
        // Diminuer la quantité du stock de 1
        if ($stockItem && $stockItem->quantite > 0) {
            $stockItem->quantite -= 1;
            $stockItem->save();
        }
    }

    if ($maintenance->date_reception && !$ancienneDateReception) {
        $equipement->statut = 'En stock';
        // Ajouter 1 à la quantité
        if ($stockItem) {
            $stockItem->quantite += 1;
            $stockItem->save();
        }
    }

    $equipement->save();

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
