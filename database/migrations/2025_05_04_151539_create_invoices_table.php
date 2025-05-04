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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('header_name');
            $table->string('bill_to');
            $table->string('name');
            $table->string('phone');
            $table->string('location');
            $table->string('product_name');
            $table->string('product_model');
            $table->string('total_price');
            $table->string('down_payment');
            $table->string('emi_month');
            $table->string('next_emi_amount');
            $table->string('footer_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
