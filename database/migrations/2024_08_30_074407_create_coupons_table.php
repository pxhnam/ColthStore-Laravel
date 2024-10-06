<?php

use App\Enums\CouponType;
use Illuminate\Support\Facades\DB;
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
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->unsignedInteger('value');
            $table->enum('type', CouponType::getValues());
            $table->unsignedInteger('min')->default(0);
            $table->unsignedInteger('max')->nullable();
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('limit')->nullable();
            $table->text('desc');
            $table->dateTime('start_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
