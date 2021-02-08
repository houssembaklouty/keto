<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'ref', 
        'amount', 
        'state', 
        'country', 
        'currency', 
        'details', 
    ];
}
