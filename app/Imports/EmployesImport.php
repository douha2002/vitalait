<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployesImport implements ToModel, WithHeadingRow
{
    public $errors = []; // Store error messages
    public $successCount = 0; // Store successful imports

    public function model(array $row)
    {
        \Log::info('Processing Employee Row:', $row); // Debugging log

        // Check if matricule already exists
        if (Employee::where('matricule', trim($row['matricule']))->exists()) {
            $this->errors[] = "Le matricule {$row['matricule']} existe déjà dans la base de données.";
            return null; // Skip this row
        }

        // Create new employee
        Employee::create([
            'matricule' => trim($row['matricule']),
            'nom' => trim($row['nom'] ?? ''),
            'prenom' => trim($row['prenom'] ?? ''),
            'poste' => trim($row['poste'] ?? ''),
            'service' => trim($row['service'] ?? ''),
            'email' => trim($row['email'] ?? ''),

        ]);

        $this->successCount++; // Increment only if insert succeeds

        return null; // Return null to skip default handling
    }

    // Return collected errors
    public function getErrors()
    {
        return $this->errors;
    }

    // Return success count
    public function getSuccessCount()
    {
        return $this->successCount;
    }
}

