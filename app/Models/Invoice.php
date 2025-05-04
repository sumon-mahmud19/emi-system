<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'header_name',
        'bill_to',
        'name',
        'phone',
        'location',
        'product_name',
        'product_model',
        'total_price',
        'down_payment',
        'emi_month',
        'next_emi_amount',
        'footer_name',
    ];
}
