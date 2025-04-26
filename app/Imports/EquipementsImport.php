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
    try {
        $date_acquisition = $this->convertExcelDate($row['date_acquisition'] ?? null);
        $date_mise_en_oeuvre = $this->convertExcelDate($row['date_de_mise_en_oeuvre'] ?? null);
    } catch (\Exception $e) {
        $this->errors[] = "Erreur lors de la conversion de date pour le numéro de série {$row['numero_de_serie']}: " . $e->getMessage();
        return null;
    }

    \Log::info('Processing Row:', $row);

    if (Equipement::where('numero_de_serie', trim($row['numero_de_serie']))->exists()) {
        $this->errors[] = "Le numéro de série {$row['numero_de_serie']} existe déjà.";
        return null;
    }

    Equipement::create([
        'numero_de_serie' => trim($row['numero_de_serie']),
        'article' => trim($row['article'] ?? ''),
        'date_acquisition' => $date_acquisition,
        'date_de_mise_en_oeuvre' => $date_mise_en_oeuvre,
        'categorie' => trim($row['categorie'] ?? ''),
        'sous_categorie' => trim($row['sous_categorie'] ?? ''),
        'matricule' => trim($row['matricule'] ?? ''),
    ]);

    $this->successCount++;
    return null;
}

    // Convert Excel date to Carbon instance
    private function convertExcelDate($value)
{
    if (is_numeric($value)) {
        // Format date Excel brut
        return Carbon::createFromDate(1900, 1, 1)->addDays($value - 2)->format('Y-m-d');
    }

    try {
        // Essaye le format texte : 12/04/2024 ou autre
        return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    } catch (\Exception $e) {
        // En dernier recours, tente format standard
        return Carbon::parse($value)->format('Y-m-d');
    }
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
