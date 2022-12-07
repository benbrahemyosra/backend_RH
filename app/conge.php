<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class conge extends Model
{
    protected $fillable = [
        'id_employee', 'Code_typeC', 'date_start','date_end','nbJourPris','status','date_creation','commentUpdate','certificat','description', 'comment_response' 
    ];
    
    public function conge()
    {
        return $this->belongsTo(conge::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }

}
