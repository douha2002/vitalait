<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Assignment;
use App\Models\maintenance;

class Equipement extends Model
{
    use HasFactory;

    protected $table = 'equipements'; // Ensure the table is named "equipements" (not "equipment")
    protected $primaryKey = 'numero_de_serie'; // The primary key is 'numero_de_serie' (not 'id')
    public $incrementing = false; // The primary key is not auto-incrementing
    protected $keyType = 'string'; // The primary key is a string (not an integer)

    // Combine both $fillable arrays into one
    protected $fillable = [
        'numero_de_serie',
        'article',
        'date_acquisition',
        'date_de_mise_en_oeuvre', // Ensure this is the correct column name in the database
        'categorie',
        'sous_categorie',
        'matricule',
        'statut',
    ];

    // Optionally, you can define date attributes to auto-convert to Carbon instances
    protected $dates = [
        'date_acquisition',
        'date_de_mise_en_oeuvre',
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}


public function assignments()
{
    return $this->hasMany(Assignment::class, 'numero_de_serie', 'numero_de_serie');

}
public function contrat()
{
    return $this->hasOne(Contrat::class, 'numero_de_serie', 'numero_de_serie');
}
public function hasActiveAssignments()
{
    return $this->assignments()->whereNull('date_fin')->exists();
}

public function hasActiveMaintenances()
{
    return $this->maintenances()->whereNull('date_fin')->exists();
}

public function maintenances()
{
    return $this->hasMany(Maintenance::class, 'numero_de_serie', 'numero_de_serie');
}
public function markAsAssigned()
{
    if ($this->statut !== 'Affecté') {
        $this->statut = 'Affecté';
        $this->save();

        // Decrease the stock quantity
        Stock::where('sous_categorie', $this->sous_categorie)
            ->where('quantite', '>', 0)
            ->decrement('quantite', 1);
    }
}



protected static function boot()
{
    parent::boot();

    static::saving(function ($equipment) {
        // Allow 'En stock' updates
        if ($equipment->statut === 'En stock') {
            return;
        }

        // Vérifie si l'équipement est en maintenance
        if ($equipment->hasActiveMaintenances()) {
            $equipment->statut = 'En panne';
        } 
        // Sinon, vérifie s'il est affecté
        elseif ($equipment->hasActiveAssignments()) {
            $equipment->statut = 'Affecté';
        } 
        // Sinon, il est disponible
        else {
            $equipment->statut = 'Disponible';
        }
    });
}
}