<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\InvoicePaid;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'email', 'password','first_name', 'last_name','departement' ,'role', 'adress', 'city','birth_date', 'phoneHome', 'phonePro', 'poste_id', 'type_employee','date_arrive'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     *
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function poste()
    {
        return $this->belongsTo('App\poste');
    }

    public function conge()
    {
        return $this->HasMany(conge::class, 'id_user');
    }

    public function typeemployee()
    {
        return $this->belongsTo('App\typeemployee');
    }
}
