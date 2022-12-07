<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pointage extends Model
{
    protected $fillable = [
        'id_employee','date_debut','date_debut_pause','date_fin_pause','date_fin','position','NB_heures','NB_heures_supplementaires'
   ];
}
