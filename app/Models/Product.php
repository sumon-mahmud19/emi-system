<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',

    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }


    // Define relationship to Purchase model
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // BelongTo Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function models()
    {
        return $this->hasMany(ProductModel::class);
    }
}
