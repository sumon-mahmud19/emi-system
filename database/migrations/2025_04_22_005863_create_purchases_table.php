<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('model_id'); // Foreign key for model
            $table->decimal('sales_price', 10, 2);  // Sales price column
            $table->decimal('down_price', 10, 2)->default(0);  // Using decimal for monetary values
            $table->decimal('net_price', 10, 2);  // Using decimal for monetary values
            $table->integer('emi_plan');  // EMI plan months
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
