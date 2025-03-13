<?php
namespace App\Imports;

use App\Models\Equipement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class EquipementsImport implements ToModel, WithHeadingRow
{
   
    public function model(array $row)
    {

        \Log::info('Processing Row:', $row); // Debugging log

        return new Equipement([
            'numero_de_serie' => trim($row['numero_de_serie']),
            'article' => trim($row['article'] ?? ''),
            'quantite' => $row['quantite'] ?? 0,
            'date_acquisition' => $this->convertExcelDate($row['date_acquisition'] ?? null),
            'date_de_mise_en_oeuvre' => $this->convertExcelDate($row['date_de_mise_en_oeuvre'] ?? null),
            'categorie' => trim($row['categorie'] ?? ''),
            'sous_categorie' => trim($row['sous_categorie'] ?? ''),
            'matricule' => trim($row['matricule'] ?? ''),
        ]);
    }
    private function convertExcelDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
        }
        return $excelDate; // If already in YYYY-MM-DD, return as is
    }
    }
