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
        Schema::create('reduces', function (Blueprint $table) {
            $table->id();
            $table->integer('store_item_id');
            $table->string('product_name');
            $table->integer('store_id');
            $table->integer('qty');
            $table->integer('total_price');
            $table->integer('type'); // 0 = normal reduce , 1 = damage reduce
            $table->integer('reduce_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reduces');
    }
};
