<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'address', 
        'country', 
        'email_address', 
        'first_name', 
        'last_name', 
        'phone_number', 
        'products', 
        'user_id',
        'city',
        'termes_conditions',
        'zip_codel',
        'paiement',
        'ref',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
