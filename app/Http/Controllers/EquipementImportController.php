<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EquipementsImport;
use Illuminate\Support\Facades\Log;

class EquipementImportController extends Controller
{
    // Show the import form
    public function showImportForm()
    {
        return view('import');
    }

    // Handle the CSV import
    public function importCSV(Request $request)
    {
        // Validate file
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        // Import the data from the file
        try {
            // Import the file using EquipementsImport
            Excel::import(new EquipementsImport, $request->file('file'));

            // Set success message
            return redirect()->route('equipments.index')->with('success', 'Équipements importés avec succès.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error importing equipment: ' . $e->getMessage());

            // Return error message to the view
            return redirect()->route('equipments.index')->with('error', 'Erreur lors de l\'importation des équipements.');
        }
    }
}
