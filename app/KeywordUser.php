<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordUser extends Model
{
    protected $fillable = [
    	'user_id', 'keyword_id'
    ];
}
