<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Assignment;

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
        'quantite',
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
public function hasActiveAssignments()
{
    return $this->assignments()->whereNull('end_date')->exists();
}
protected static function boot()
{
    parent::boot();

    // Update status when an equipment is saved
    static::saving(function ($equipment) {
        if ($equipment->hasActiveAssignments()) {
            $equipment->statut = 'affecté';
        } else {
            $equipment->statut = 'en cours';
        }
    });
}
}
