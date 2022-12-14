<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class typeconge extends Model
{
    //
    protected $fillable = [
      'name', 'actif','nbJourAn','MaxJourPris','anciennete','maxHeureAuto','nbFoisMois'
    ];
}
