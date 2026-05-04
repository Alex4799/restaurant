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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->longText('image');
            $table->string('promo_code');
            $table->integer('shop_id')->nullable();
            $table->integer('percentage')->nullable();
            $table->integer('amount')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('limit')->nullable();
            $table->integer('active')->default(0)->nullable();
            $table->integer('default')->default(0)->nullable();
            $table->integer('feature')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
