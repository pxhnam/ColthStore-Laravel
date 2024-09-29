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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->uuid('color_id');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->uuid('size_id');
            $table->foreign('size_id')->references('id')->on('sizes');
            $table->unsignedInteger('num');
            $table->unsignedInteger('cost');
            $table->timestamps();
            $table->unique(['product_id', 'color_id', 'size_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
