<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    protected $fillable = ['numero_de_serie', 'fournisseur_id', 'date_debut', 'date_fin'];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}