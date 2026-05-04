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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('store_product_id');
            $table->string('product_name');
            $table->string('category_name');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('profit');
            $table->string('currency');
            $table->string('note')->nullable();
            $table->integer('status')->default(0)->nullable(); // 0=pending,1=cooking,2=finished,3=arrived
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
