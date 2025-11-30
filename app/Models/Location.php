<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name'
    ];

    // multiple customer
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    
}
