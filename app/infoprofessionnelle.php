<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class infoprofessionnelle extends Model
{
      protected $fillable = [
        'id_employee', 'email', 'email_pro','phone_pro','type_employee','post','code_point', 'code_pin'
    ];
}
