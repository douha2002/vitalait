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
    protected $fillable = ['nom', 'email', 'poste']; // Adjust fields accordingly
}
