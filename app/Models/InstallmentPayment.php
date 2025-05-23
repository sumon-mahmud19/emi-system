<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{
    protected $fillable = ['installment_id', 'amount', 'paid_at'];

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}
