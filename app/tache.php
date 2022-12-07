<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tache extends Model
{
    protected $fillable = [
        'titre', 'description', 'date_debut','date_fin','id_planning' ,'id_employee' 
      ]; 
}
