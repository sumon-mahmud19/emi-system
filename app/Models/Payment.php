<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{


    protected $fillable = ['installment_id', 'amount', 'paid_at'];

    // Ensure that paid_at is cast to Carbon instance
    public function installment() {
        return $this->belongsTo(Installment::class);
    }
    
}
