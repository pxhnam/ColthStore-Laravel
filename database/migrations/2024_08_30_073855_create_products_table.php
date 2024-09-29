<?php

use App\Enums\ProductState;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug');
            $table->uuid('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->text('desc');
            $table->enum('state', ProductState::getValues())->default(ProductState::HIDDEN->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
