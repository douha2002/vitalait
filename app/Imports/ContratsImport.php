<?php
namespace App\Imports;

use App\Models\Contrat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ContratsImport implements ToModel, WithHeadingRow
{
    public $errors = [];
    public $successCount = 0;

    public function model(array $row)
    {
        \Log::debug('Processing row: ', $row);

        $numero_de_serie = trim($row['numero_de_serie'] ?? '');
        $fournisseur_nom = trim($row['fournisseur'] ?? ''); // This contains the fournisseur's name from Excel
        $date_debut_raw = trim($row['date_debut'] ?? '');
        $date_fin_raw = trim($row['date_fin'] ?? '');

        // Convert dates (now handling multiple formats)
        try {
            $date_debut = $this->convertDate($date_debut_raw);
            $date_fin = $this->convertDate($date_fin_raw);
        } catch (\Exception $e) {
            $this->errors[] = "Format de date invalide pour le contrat $numero_de_serie.";
            return null;
        }

        // Convert fournisseur name to ID
        $fournisseur = \App\Models\Fournisseur::where('nom', $fournisseur_nom)->first();
        if (!$fournisseur) {
            $this->errors[] = "Fournisseur '$fournisseur_nom' introuvable pour le contrat $numero_de_serie.";
            return null;
        }
        $fournisseur_id = $fournisseur->id;

        // Check if exactly same contract already exists (same numero_de_serie + date_debut + date_fin)
if (Contrat::where('numero_de_serie', $numero_de_serie)
->where('date_debut', $date_debut)
->where('date_fin', $date_fin)
->exists()) {
$this->errors[] = "Le contrat pour <strong>$numero_de_serie</strong> avec les mêmes dates existe déjà.";
return null;
}


        try {
            Contrat::create([
                'numero_de_serie' => $numero_de_serie,
                'fournisseur_id' => $fournisseur_id,
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
            ]);

            $this->successCount++;

        } catch (\Exception $e) {
            $this->errors[] = "Erreur lors de l'import de <strong>$numero_de_serie</strong>: " . $e->getMessage();
        }

        return null;
    }

    // Enhanced date conversion to handle different formats
    private function convertDate($date)
{
    $formats = ['m/d/Y', 'd/m/Y', 'd-n-Y', 'd-m-Y', 'j/n/Y', 'j/n/y', 'Y-m-d'];

    foreach ($formats as $format) {
        try {
            return Carbon::createFromFormat($format, $date)->format('Y-m-d');
        } catch (\Exception $e) {
            // Continue if the date doesn't match the format
        }
    }

    // Final attempt: try parsing the date with Carbon's flexible parser
    try {
        return Carbon::parse($date)->format('Y-m-d');
    } catch (\Exception $e) {
        throw new \Exception("Date format is invalid: '$date'");
    }
}

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}
