<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'numero_de_serie',
        'fournisseur_id',
        'date_panne',
        'date_affectation',
        'date_reception',
        'commentaires',
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'numero_de_serie', 'numero_de_serie');
    }
    public function fournisseur() {
        return $this->belongsTo(Fournisseur::class);
    }
    


}
