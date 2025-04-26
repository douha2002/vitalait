<?php

namespace App\Imports;

use App\Models\Fournisseur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FournisseursImport implements ToModel, WithHeadingRow
{
    public $errors = [];
    public $successCount = 0;

    public function model(array $row)
    {
        $nom = trim($row['nom'] ?? '');
        $email = trim($row['email'] ?? '');
        $numero_de_telephone = trim($row['numero_de_telephone'] ?? '');

        if (empty($nom) || empty($email)) {
            $this->errors[] = "Nom ou email manquant.";
            return null;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Email invalide : <strong>$email</strong>";
            return null;
        }

        if (Fournisseur::where('email', $email)->exists()) {
            $this->errors[] = "Le fournisseur avec l'email <strong>$email</strong> existe déjà.";
            return null;
        }

        if (Fournisseur::where('nom', $nom)->exists()) {
            $this->errors[] = "Le fournisseur avec le nom <strong>$nom</strong> existe déjà.";
            return null;
        }

        try {
            Fournisseur::create([
                'nom' => $nom,
                'email' => $email,
                'numero_de_telephone' => $numero_de_telephone,
            ]);
            $this->successCount++;
        } catch (\Exception $e) {
            $this->errors[] = "Erreur lors de l'import de <strong>$nom</strong> ($email): " . $e->getMessage();
        }

        return null;
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
