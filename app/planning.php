<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class planning extends Model
{
    protected $fillable = [
        'type_planning','titre','description','date_debut','date_fin', 'liste_employees', 'etat', 'MinPersonne', 'dateProd'
    ];
}
