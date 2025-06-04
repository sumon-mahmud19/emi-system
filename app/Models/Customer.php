<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_id',
        'customer_phone',
        'customer_phone2',
        'customer_image',
        'landlord_name',
        'location_id',
        'location_details',
    ];


    // BelongsTo Location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function installments()
    {
        return $this->hasManyThrough(Installment::class, Purchase::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
