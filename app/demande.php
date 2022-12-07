<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class demande extends Model
{
    //    
    protected $fillable = [
        'id_employee', 'type_demande', 'date_demande','date_response','comment_reponse' ,'objet', 'status', 'status_demand'
    ];
}
