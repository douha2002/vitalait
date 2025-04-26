<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Equipement;
use App\Models\Employee;
use Illuminate\Database\Eloquent\SoftDeletes;


class Assignment extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'numero_de_serie',
        'employees_id',
        'date_debut',
        'date_fin',
    ];
    protected $dates = ['deleted_at']; // Permet d'utiliser les soft deletes


   public function equipment()
   {
       return $this->belongsTo(Equipement::class, 'numero_de_serie', 'numero_de_serie');
   }


   public function employee()
{
    return $this->belongsTo(Employee::class, 'employees_id', 'matricule');
}

   

}
