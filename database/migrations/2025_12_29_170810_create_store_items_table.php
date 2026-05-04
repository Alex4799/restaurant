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
        Schema::create('store_items', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('purchase_price');
            $table->integer('selling_price')->nullable();
            $table->string('currency')->default('MMK');
            $table->integer('qty')->default(1);
            $table->integer('profit');
            $table->integer('instock_level');
            $table->integer('store_id');
            $table->integer('instock_type')->default(0); // 1 = No Need Instock , 0 = Need Instock
            $table->integer('type')->default(1); //1 = Not selling type,0 = Selling type
            $table->integer('active')->default(0); // 0 = Not Active , 1 = Active
            $table->string('barcode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_items');
    }
};
