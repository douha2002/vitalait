<?php
namespace App\Imports;

use App\Models\Equipement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class EquipementsImport implements ToModel, WithHeadingRow
{
    public $errors = [];  // Store error messages
    public $successCount = 0;  // Store successful imports

    public function model(array $row)
    {
        \Log::info('Processing Row:', $row); // Debugging log

        // Check if numero_de_serie already exists in the database
        if (Equipement::where('numero_de_serie', trim($row['numero_de_serie']))->exists()) {
            // If it exists, add to errors array
            $this->errors[] = "Le numéro de série {$row['numero_de_serie']} existe déjà dans la base de données.";
            return null;  // Skip this row (don't insert into the database)
        }

        // Otherwise, create the new equipment
        Equipement::create([
            'numero_de_serie' => trim($row['numero_de_serie']),
            'article' => trim($row['article'] ?? ''),
            'date_acquisition' => $this->convertExcelDate($row['date_acquisition'] ?? null),
            'date_de_mise_en_oeuvre' => $this->convertExcelDate($row['date_de_mise_en_oeuvre'] ?? null),
            'categorie' => trim($row['categorie'] ?? ''),
            'sous_categorie' => trim($row['sous_categorie'] ?? ''),
            'matricule' => trim($row['matricule'] ?? ''),
        ]);

        // Increment success count after inserting an equipment
        $this->successCount++;

        return null;  // Don't need to return anything since we're handling the insert directly
    }

    // Convert Excel date to Carbon instance
    private function convertExcelDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
        }
        return $excelDate;  // If already in YYYY-MM-DD, return as is
    }

    // Get error messages
    public function getErrors()
    {
        return $this->errors;
    }

    // Get success count
    public function getSuccessCount()
    {
        return $this->successCount;
    }
}
