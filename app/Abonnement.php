<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    protected $fillable = [
        'start_date', 'end_date',
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
