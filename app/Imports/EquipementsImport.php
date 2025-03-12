<?php
namespace App\Imports;

use App\Models\Equipement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EquipementsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        \Log::info('CSV Row:', $row); // Log the row data for debugging

        return new Equipement([
            'numero_de_serie' => $row['numero_de_serie'] ,
            'article' => $row['article'] ,
            'quantite' => $row['quantite'],
            'date_acquisition' => $row['date_acquisition'],
            'date_de_mise_en_oeuvre' => $row['date_de_mise_en_oeuvre'] ,
            'categorie' => $row['categorie'],
            'sous_categorie' => $row['sous_categorie'],
            'matricule' => $row['matricule'],
        ]);
    }

    }
