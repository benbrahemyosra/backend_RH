<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class poste extends Model
{
    protected $fillable = [
         'name_post','salary_post'
    ];
}
