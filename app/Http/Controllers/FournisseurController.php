<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseur;
use Illuminate\Validation\Rule;
use App\Imports\FournisseursImport;
use Maatwebsite\Excel\Facades\Excel;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::paginate(10);
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email',
            'numero_de_telephone' => 'required|string|max:20',
        ]);

        Fournisseur::create($request->all());
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseurs ajouté avec succès.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email',
            'numero_de_telephone' => 'nullable|string|max:20',
            ]);

        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->update($request->all());

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseurs modifié avec succès.');
    }
    public function destroy($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseurs supprimé avec succès.');
    }
   
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls,txt'
        ]);
    
        $import = new \App\Imports\FournisseursImport();
        Excel::import($import, $request->file('file'));
    
        $errors = $import->getErrors();
        $successCount = $import->getSuccessCount();
        $successMessage = "Importation réussie pour $successCount fournisseur(s).";
    
        return redirect()->route('fournisseurs.index')
            ->withErrors($errors)
            ->with('success', $successMessage);
    }
    
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $query = Fournisseur::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        $fournisseurs = $query->get();
        $noResults = $fournisseurs->isEmpty();

        return view('fournisseurs.index', compact('fournisseurs', 'noResults'));
    }


}
