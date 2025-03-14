<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee; // Import the Employee model


class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        {
            Employee::insert([
                ['name' => 'Meriem Moujahed'],
                ['name' => 'Tesnim Hmida'],
                ['name' => 'Houssem Eddine'],
                ['name' => 'Mohamed Ali'],
                ['name' => 'Mohamed Amine'],
                ['name' => 'Mohamed Firas'],
                ['name' => 'Mohamed Hedi'],
                ['name' => 'Mohamed Nizar'],
                ['name' => 'Mohamed Oussama'],
                ['name' => 'Mohamed Yassine'],
              
            ]);
        }
    }
}
