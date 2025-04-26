<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the plural form of the model name
    protected $table = 'employees'; 

    // Define the primary key if it's not the default 'id'
    protected $primaryKey = 'matricule'; 
    public $incrementing = false;// or the relevant field name

    // Disable timestamps if not used
    public $timestamps = false;

    // Define the fillable attributes
    protected $fillable = ['matricule', 'nom', 'prenom','poste','service','email'];

    
     // Define the relationship to the Equipement model
     public function equipments()
     {
         return $this->hasMany(Assignment::class, 'employees_id', 'matricule')
                     ->join('equipements', 'assignments.numero_de_serie', '=', 'equipements.numero_de_serie')
                     ->select('equipements.*');
     }
}
