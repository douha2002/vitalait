<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipement;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Equipement::query();

        // Dynamically filter based on the request input
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($query) use ($search) {
                $query->where('numero_de_serie', 'like', '%' . $search . '%')
                      ->orWhere('article', 'like', '%' . $search . '%')
                      ->orWhere('categorie', 'like', '%' . $search . '%')
                      ->orWhere('sous_categorie', 'like', '%' . $search . '%')
                      ->orWhere('matricule', 'like', '%' . $search . '%');
            });
        }

        // Execute the query and get results
        $equipments = $query->get();

        // Check if no equipment is found
        $noResults = $equipments->isEmpty();

        // Return the results to the view
        return view('equipments.index', compact('equipments', 'noResults'));
    }
}
