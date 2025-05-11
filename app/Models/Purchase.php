<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'model_id',
        'total_price',
        'sales_price',
        'down_payment',
        'emi_plan',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Purchase.php
    public function model()
    {
        return $this->belongsTo(ProductModel::class, 'model_id');
    }
}
