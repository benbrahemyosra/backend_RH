<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class typeconge extends Model
{
    //
    protected $fillable = [
      'name', 'actif','nbJourAn','maxJourPris','anciennete','justifiant_pdf'
    ];
}
