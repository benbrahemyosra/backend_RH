<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class infoprive extends Model
{
    protected $fillable = [
    'id_employee','adress', 'phone', 'num_bank','genre','civil','nationality','cin', 'date_birth','place_birth', 'country_birth' 
    ];
}
