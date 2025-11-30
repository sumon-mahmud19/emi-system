<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{


   protected $fillable = [
        'customer_id',
        'purchase_id',
        'product_id',
        'amount',
        'status',
        'paid_at',
    ];

    // Ensure that paid_at is cast to Carbon instance
    public function installment() {
        return $this->belongsTo(Installment::class, 'installment_id');
    }
    

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }
}
