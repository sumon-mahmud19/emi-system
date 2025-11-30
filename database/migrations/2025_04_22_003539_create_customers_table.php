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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->integer('customer_id');
            $table->string('customer_phone')->unique();
            $table->string('customer_image')->nullable();
            $table->string('landlord_name')->nullable();
            // $table->string('landlord_phone')->unique();
            $table->unsignedBigInteger('location_id');
            $table->string('location_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
