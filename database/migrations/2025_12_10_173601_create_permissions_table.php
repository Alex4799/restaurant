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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('report')->default(1); // 0 = Allow , 1 = Not Allow
            $table->integer('product_update')->default(1); // 0 = Allow , 1 = Not Allow
            $table->integer('store_item_update')->default(1); // 0 = Allow , 1 = Not Allow
            $table->integer('purchase')->default(1); // 0 = Allow , 1 = Not Allow
            $table->integer('transfer')->default(1); // 0 = Allow , 1 = Not Allow
            $table->integer('order_reject')->default(1); // 0 = Allow , 1 = Not Allow
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
