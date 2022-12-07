<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class parametre extends Model
{
    protected $fillable = [
        'NbJourSemaine', 'NbCongeSolde','NbHeureJour','MinNbHeure'
    ];
}
