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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('user_address')->nullable();
            $table->json('payment_method');
            $table->string('payment_slip')->nullable();
            $table->integer('promotion_id')->nullable();
            $table->integer('promotion_price')->nullable();
            $table->integer('total_price')->default(0);
            $table->integer('subtotal')->default(0);
            $table->integer('pay_amount')->nullable();
            $table->integer('pay_left')->default(0);
            $table->integer('change')->default(0);
            $table->integer('tax_id')->nullable();
            $table->integer('tax_price')->nullable();
            $table->integer('profit')->default(0);
            $table->string('currency')->default('MMK');
            $table->string('table')->default('Take Away');
            $table->integer('shop_id');
            $table->string('shop_name');
            $table->string('seller_name')->nullable();
            $table->integer('delivery')->nullable();
            $table->integer('delivery_fees')->nullable();
            $table->integer('status')->default(0);
            $table->integer('slip_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
