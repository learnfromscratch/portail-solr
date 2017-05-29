<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cin', 'name', 'email', 'password','tel', 'address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The keywords that belong to the user.
     */
    public function keywords()
    {
        return $this->belongsToMany('App\Keyword');
    }

    public function abonnement()
    {
        return $this->hasOne('App\Abonnement');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity');
    }

}
