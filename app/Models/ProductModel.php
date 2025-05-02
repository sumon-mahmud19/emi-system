<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
     // Explicitly define table name to avoid 'models' error
     protected $table = 'product_models';

     protected $fillable = [
         'product_id',
         'model_name',
     ];
 
     public function product()
     {
         return $this->belongsTo(Product::class);
     }
}
