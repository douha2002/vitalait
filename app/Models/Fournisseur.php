<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model {
    use HasFactory;
    protected $fillable = ['nom','email','numero_de_telephone'];
    
    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }
    public function maintenances() {
        return $this->hasMany(Maintenance::class);
    }
}
