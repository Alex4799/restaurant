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
        Schema::create('selling_products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('purchase_price');
            $table->integer('selling_price')->nullable(0);
            $table->string('currency')->default('MMK');
            $table->integer('qty');
            $table->integer('profit');
            $table->integer('instock_level');
            $table->integer('store_id');
            $table->integer('instock_type')->default(1); // 0 = No Need Instock , 1 = Need Instock
            $table->integer('type')->default(1); //0 = Not selling type,1 = Selling type
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
        Schema::dropIfExists('selling_products');
    }
};
