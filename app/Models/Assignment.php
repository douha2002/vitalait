<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Equipement;
use App\Models\Employee;


class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_de_serie',
        'employees_id',
        'start_date',
        'end_date',
    ];

   public function equipment()
   {
       return $this->belongsTo(Equipement::class, 'numero_de_serie', 'numero_de_serie');
   }


   public function employee()
{
    return $this->belongsTo(Employee::class, 'employees_id', 'matricule'); 
}

}
